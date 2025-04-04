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
include('00apoyopdf.php');
session_start();

//Composición del informe pdf
 

$fsemanai=$_POST['semanai'];

defineclasepdf ();
$pdf=new PDF();

$pdf->SetMargins(15,15);
$pdf->AliasNbPages();

$pdf->AddPage('L');

// Carga el título y cuerpo modelo de la comunicación
    extract(consultaBDunica("select * FROM tdatos WHERE C_IdComunicacion='PDFSancionesSemanal'"));
    
$pdf->SetTitulo(utf8_decode($C_TituloComunicacion." (Semana del ".darfechaesp($fsemanai).")"));    
   
   //necesario ya que no lo imprime en la primera pagina con header
   $pdf->SetY(30);
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(0,10,$pdf->titulo,0,1,'C');
    
     $pdf->SetY(40);
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell(0,5,utf8_decode($C_CuerpoComunicacion),0);
    
    
    
    $pdf->SetFillColor(224,235,255);

 


//Table

 foreach(range(1,count($_SESSION['TablaSa'])-1) as $fila)
  {  
    if (isset($_POST['Obs'.$fila]))
    { $_SESSION['TablaSa'][$fila][9]=$_POST['Obs'.$fila];}
    else
    {$_SESSION['TablaSa'][$fila][9]="-";}
  }


$pdf->SetY(75);
unset ($TablaSaEncode);
//utf8 decode para tildes
foreach($_SESSION['TablaSa'] as $f => $fila )
    { 
        foreach($fila as $c => $element)
        {
            $TablaSaEncode[$f][$c]=utf8_decode($element);
        }
    }
    
   
    //configuración de la tabla
    $Largocol=[35,35,45,13,13,13,13,13,55,40];
$alineado=['C','C','C','C','C','C','C','C','C','C'];
$pdf->SetWidths($Largocol);
$pdf->SetAligns($alineado);
$pdf->SetCabeceras($TablaSaEncode[0]);
$pdf->SetColorRellenoCabeceras('165, 105, 189');


//escribimos la tabla

$pdf->Cabecera(); //cabecera
$tipofila=1;   
foreach(range(1,count($TablaSaEncode)-1) as $f )
    {       if ($TablaSaEncode[$f][1]!=$TablaSaEncode[$f-1][1])
            { $tipofila++;}
            if ($tipofila % 2 == 0)
            { $pdf->Row($TablaSaEncode[$f],'D','235, 237, 239'); }
            else 
            { $pdf->Row($TablaSaEncode[$f],'DF','235, 237, 239'); }
    }
   
   
   
   
   //comprobamos si hay que enviar el pdf por correo-e
   
   
   
   if (isset($_POST['pdf'])) {
        $pdf->Output('D',utf8_decode('Medidas de Convivencia (Semana del '.darfechaesp($fsemanai).').pdf'));
        header('location:18informesancionessemanal.php');
   } elseif (isset($_POST['enviarpdf']))  {
        $_SESSION['FAdjunto'] = $pdf->Output('S',utf8_decode('Medidas de Convivencia (Semana del '.darfechaesp($fsemanai).').pdf'));
        $_SESSION['NAdjunto'] = utf8_decode('Medidas de Convivencia (Semana del '.darfechaesp($fsemanai).').pdf');
        $_SESSION['ArrayCorreosEnviar']=[['CorreoSancionesSemanalAClaustro',darfechaesp($fsemanai)]];
        header('location:10enviocorreos.php');
    }
       
   
   
   
    

?>