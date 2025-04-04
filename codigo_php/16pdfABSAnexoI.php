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
include '00apoyopdf.php';
session_start();

extract(consultaBDunica("SELECT valor1 as nombreies FROM tconfiguracion WHERE id='nombreies'"));
extract(consultaBDunica("SELECT valor1 as provincia FROM tconfiguracion WHERE id='provinciaies'"));
extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));


//Composición del informe pdf



$fechainf=$_POST['fechainf'];

defineclasepdf ();
$pdf=new PDF();

$pdf->SetMargins(15,15);
$pdf->AliasNbPages();

$pdf->AddPage('L');

// Carga el título y cuerpo modelo de la comunicación
 //borrar   extract(consultaBDunica("select * from tdatos where C_IdComunicacion='PDFSancionesSemanal'"));
    
$pdf->SetTitulo(utf8_decode("ANEXO I
(a fecha de ".darfechaesp($fechainf).")"));    
   
   //necesario ya que no lo imprime en la primera pagina con header
   
   $pdf->SetXY(30,30);
   $pdf->SetFont('Arial','B',12);
   $pdf->MultiCell(0,10,$pdf->titulo,0,'');
    
    
    $texto='SEGUIMIENTO DEL ABSENTISMO ESCOLAR. 

CENTRO: '.$nombreies.'.
'.$localidad.' ('.$provincia.')
Curso: '.cursoescolar();

     $pdf->SetXY(150,30);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(80,5,utf8_decode($texto),1);
    
    
    
    $pdf->SetFillColor(224,235,255);

 
    $pdf->SetFont('Arial','',8);

//Table

 foreach(range(1,count($_SESSION['TablaSa'])-1) as $fila)
  {   
    $motivo=$_POST['Motivo'.$fila];
    $devolucion=$_POST['Devolucion'.$fila];
    
    $alumnoid=$_POST['Alumnoid'.$fila];
      $_SESSION['TablaSa'][$fila][14]=substr($motivo,0,3);
      $_SESSION['TablaSa'][$fila][20]=$devolucion;
      //registro del motivo de ausencia en la tablaAlumnos
    
    extract(consultaBDunica("SELECT count(*) as numero FROM tnotasalumno WHERE AlumnoIdNo='$alumnoid'"));
      if ($numero==0)
      {
          $conn->query("INSERT INTO tnotasalumno (AlumnoIdNo, ABS_Motivo) VALUES ('$alumnoid','$motivo')");
      }     
      else
      {
        $edit = $conn->query("UPDATE tnotasalumno set ABS_Motivo='$motivo' 
        where AlumnoIdNo='$alumnoid'");
      }
  }
    


$pdf->SetY(60);
unset ($TablaSaEncode);

foreach($_SESSION['TablaSa'] as $f => $fila )
    { 
        foreach(range(1,count($fila)-1) as $c)
        {
            $TablaSaEncode[$f][$c-1]=utf8_decode($fila[$c]);
        }
    }
    
   
    //configuración de la tabla
    $Largocol=[40,12,18,9,9,9,9,9,9,9,9,9,9,11,17,17,17,17,17,17];
$alineado=['C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C'];
$pdf->SetWidths($Largocol);
$pdf->SetAligns($alineado);
$pdf->SetCabeceras($TablaSaEncode[0]);
$pdf->SetColorRellenoCabeceras('165, 105, 189');


//escribimos la tabla

$pdf->Cabecera(); //cabecera
$tipofila=1;   
foreach($_POST['checkboxid'] as $f )
    {        
        $tipofila++;
        if ($tipofila % 2 == 0)
        { $pdf->Row($TablaSaEncode[$f],'D','235, 237, 239'); }
        else 
        { $pdf->Row($TablaSaEncode[$f],'DF','235, 237, 239');}
    }
   
   $pdf->Ln(8);
   
    $texto='Motivos de absentismo: (1) Problema familiar, (2) Desinterés, (3) Problemática laboral, (4) Continuos desplazamientos (5) No se sabe (6) Otros';
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell(0,5,utf8_decode($texto),0,'C');
   
   //comprobamos si hay que enviar el pdf por correo-e
   
   
   
   if (isset($_POST['pdf'])) {
    $pdf->Output('D',utf8_decode('Anexo I Absentismo ('.darfechaesp($fechainf).').pdf'));
    header('location:18AnexoI.php'); 
    }
       
   
   
   
    

?>