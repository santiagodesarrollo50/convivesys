<?php
/*
 * Copyright (C) [2025] [Santigo Morales Domingo]
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN NINGUNA GARANTÍA.
 * 
 * El software ha sido diseñado por un programador novato. Por la seguridad 
 * de sus datos es recomendable instalarlo y usarlo en un servidor web local 
 * desconectado de internet. La seguridad de los datos es su responsabilidad.
 */
include '00funciones.php';
$conn = conectarBaseDeDatos();
session_start();
$curso=$_SESSION['PCurso'];

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

//Tipo de correo según base de datos


if (isset($_POST['checkboxid'])) { 
  $ArrayCorreosEnviar=Array(); //sirve para partes y seguimientoabs
  foreach($_POST['checkboxid'] as $Id) {
      array_push($ArrayCorreosEnviar,[$_GET['tipoenviog'],$Id,$_GET['OtroDato']]);
  }
} elseif (isset($_POST['anotacionid'])) { 
   $ArrayCorreosEnviar=Array();
   foreach($_POST['anotacionid'] as $IdAnotacion) {
       array_push($ArrayCorreosEnviar,['CorreoAnotacionATutor',$IdAnotacion]);
   } 
} elseif (isset($_POST['TipoEnvio'])) {
    $ArrayCorreosEnviar[0]=[$_POST['TipoEnvio'],$_POST['IdEnvio'],$_POST['OtroDato']];
} else {  
   $ArrayCorreosEnviar=$_SESSION['ArrayCorreosEnviar'];
}

//print_r($ArrayCorreosEnviar);

// Configuración genérica del correo 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
include "class.phpmailer.php";
include "class.smtp.php";

extract(consultaBDunica("SELECT valor1 as nombreies FROM tconfiguracion WHERE id='nombreies'"));
extract(consultaBDunica("SELECT valor1 as correoenvios FROM tconfiguracion WHERE id='correoenvios'"));
extract(consultaBDunica("SELECT valor1 as passcorreoenvios FROM tconfiguracion WHERE id='passcorreoenvios'"));
extract(consultaBDunica("SELECT valor1 as correoclaustro FROM tconfiguracion WHERE id='correoclaustro'"));
extract(consultaBDunica("SELECT valor1 as hostcorreoenvio FROM tconfiguracion WHERE id='hostcorreoenvios'"));
extract(consultaBDunica("SELECT valor1 as puertohostenvio FROM tconfiguracion WHERE id='puertohostenvio'"));
extract(consultaBDunica("SELECT valor1 as correojefaturaestudios FROM tconfiguracion WHERE id='correojefaturaestudios'"));

$email_user = $correoenvios;
$email_password = $passcorreoenvios;
$from_name = utf8_decode("Jefatura de Estudios - ".$nombreies);

$phpmailer = new PHPMailer();
// ———- datos de la cuenta de correo de envíos ——————————-
$phpmailer->Username = $email_user;
$phpmailer->Password = $email_password; 
// $phpmailer->SMTPDebug = 1; //Quitado de antes
//$phpmailer->SMTPSecure = 'ssl';
//$phpmailer->SMTPSecure = 'tls';
$phpmailer->Host = $hostcorreoenvio; // GMail
$phpmailer->Port = $puertohostenvio;
$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;
$phpmailer->setFrom($phpmailer->Username,$from_name);
$phpmailer->addReplyTo($correojefaturaestudios);
$phpmailer->IsHTML(true);
$phpmailer->ClearAddresses ();  


//Bucle para enviar tantos correos como partes seleccionados
foreach ($ArrayCorreosEnviar as $envio) {    
    //Carga los datos del tipo de correo 
    extract(consultaBDunica('SELECT * from tdatos where C_IdComunicacion="'.$envio[0].'"'));    
    $Id=$envio[1];
    $OtroDato=$envio[2];
    
    //Extrae los datos necesarios de BD para componer el correo, según el tipo de correo
    if ($C_ConsultasBD != "") {
        foreach(explode("--",$C_ConsultasBD) as $consult) { 
            $consult=componeretiquetas($consult);
            //echo $consult;
            extract(consultaBDunica($consult));
        }
    }

    //Compone titulo y cuerpo del correo
    $Titulo = componeretiquetas($C_TituloComunicacion);
    $Cuerpo = componeretiquetas($C_CuerpoComunicacion);
    $phpmailer->Subject = utf8_decode($Titulo);
    $phpmailer->Body = utf8_decode($Cuerpo);

    //Añadir fichero adjunto
    $phpmailer->ClearAttachments();
    if ($C_FicheroAdjunto == "si") {
        $phpmailer->AddStringAttachment(
            $_SESSION['FAdjunto'],
            $_SESSION['NAdjunto'],
            'base64',
            'application/pdf'
        );
    }

    //Carga de los correos destino
    $phpmailer->ClearAddresses ();
    $phpmailer->ClearBCCs ();
    
    if ($C_CorreosDestino=="Claustro") {
        $phpmailer->AddBCC($correoclaustro);
    } elseif ($C_CorreosDestino=="Aula Convivencia") {
        $consulta = "SELECT CorreoProfesor FROM tprofesores where IdProfesor in
            (SELECT valor1 as M_IdProfesor FROM tconfiguracion where id='profesorabsentismo')";
        $resSelect = $conn->prepare($consulta);
        $resSelect->execute();
        while($row = $resSelect->fetch()) {    
            extract($row);
            $phpmailer->AddBCC($CorreoProfesor);  
        }
    } elseif ($envio[0]!="CorreoParteAFamilia") {
        //En algunos servicios de correo no se puede agregar más de email con addAdress.
        //Por eso para familias lo hacemos más adelante de otra forma.
        foreach(explode(",",$C_CorreosDestino) as $correo) {
            $phpmailer->AddBCC(${$correo});
        }
    }


    if ($envio[0] == "CorreoParteAFamilia") { //Caso hace tantos envíos de correos individuales 
        foreach (explode(",", $C_CorreosDestino) as $correo) {
            $phpmailer->AddAddress(${$correo});
            if ($phpmailer->Send()) {
                $edit = $conn->query("UPDATE tpartes set CorreoFamPa='S' where IdParte='$IdParte'");
            }
        }
    } else { //envía todos los correos juntos en addbcc
        //print_r($phpmailer->getAllRecipientAddresses()); //imprime las direcciones de envio 
        // Envia y Registra en la base de datos si se ha enviado el correo
        //0 ->al  tutor 1-> a la familia
        if ($phpmailer->Send()) {
            //echo "envia";
            if ($envio[0] == "CorreoParteATutor") {
                $edit = $conn->query("UPDATE tpartes set CorreoTutorPa='S' where IdParte='$IdParte'");
            } elseif ($envio[0] == "CorreoParteAFamilia") {
                $edit = $conn->query("UPDATE tpartes set CorreoFamPa='S' where IdParte='$IdParte'");
            } elseif ($envio[0] == "CorreoAnotacionATutor") {
                $edit = $conn->query("UPDATE tanotacionesabs set CorreoTutorAn='S' where IdAnotacion='$IdAnotacion'");
            } elseif ($envio[0] == "CorreoTutorJustificaFaltas") {
                $today = date('Y-m-d');
                $anotaciondelmes = componeretiquetas($C_Cuerpo2Comunicacion);

                //Calcula el Id de la nueva anotacion de justificacion (necesario por problema autoincrement de la tabla)
                unset($idanota);
                extract(consultaBDunica("SELECT max(IdAnotacion) AS idanota FROM tanotacionesabs"));
                if (is_null($idanota)) {
                    $nIdAnotacion = 1;
                } else {
                    $nIdAnotacion = $idanota + 1;
                }

                $edit = $conn->query("INSERT INTO tanotacionesabs (IdAnotacion, Anotacion, AlumnoIdAn, FechaAn, EstadoAn,
                TipoAn, CorreoTutorAn, OtraInformacion) values 
                ('$nIdAnotacion','Petición de las faltas de este mes a fecha de hoy', '$Id', '$today', 'P',
                '11-Petición Tutor Justificación faltas', 'S', '$OtroDato')");
            }
        }
    }


}
   
unset($_SESSION['FAdjunto']);
unset($_SESSION['NAdjunto']);
unset($_SESSION['ArrayCorreosEnviar']); 
$Procedencia=$_SESSION['Procedencia'];
header('location:'.$Procedencia);
?>