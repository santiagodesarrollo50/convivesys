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


// Comprueba que solo ha sido seleccionado un parte
if (count($_POST['checkboxid'])!=1)
{
$_SESSION['aviso']=1; //indicará que para esta opcion solo hay que elegir 1 parte
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}




$Idparte = $_POST['checkboxid'][0];




error_reporting(E_ALL);
ini_set('display_errors', 1);


$consulta1 = "Select count(SancionIdSaPa) FROM tsancionparte WHERE ParteIdSaPa=$Idparte";
    $resSelect1 = $conn->prepare($consulta1);
$resSelect1->execute(); 
$row1 = $resSelect1->fetch();
    extract($row1);
    
     
    
    if ($row1['count(SancionIdSaPa)']==0) {

$consulta = "DELETE FROM tpartes WHERE IdParte=$Idparte";
    $resSelect = $conn->prepare($consulta);
 try {   $resSelect->execute();  }
catch ( PDOException $e){
   echo $e->getMessage(); }
    
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    
    }
    else
    {
    $_SESSION['aviso']=3; //indica que no es posible borrar parte con sanciones asociadas
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    }

     
     
?>
   
