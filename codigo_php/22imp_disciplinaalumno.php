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
$curso=$_SESSION['PCurso'];

//Composición del informe pdf



$s_idalumno=$_GET['g_idalumno'];   
    
// Busca en la tabla talumnos los datos del alumno seleccionado
extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
extract(consultaBDunica("SELECT Unidad from talumnounidad, tunidades where IdUnidad=UnidadIdAlUn and CursoAlUn='$curso' and AlumnoIdAlUn='$IdAlumno'"));
$Iniciales=$Nombre.' '.substr($Apellido1,0,1).'. '.substr($Apellido2,0,1).'.';

defineclasepdf ();
$pdf=new PDF();

$pdf->SetMargins(15,15);
$pdf->AliasNbPages();

$pdf->AddPage('P');

$text="REGISTROS DE DISCIPLINA DEL ALUMNO/A: ".$Alumno." (".$Unidad.")";
$pdf->SetTitulo(utf8_decode($text));    
   
   //necesario ya que no lo imprime en la primera pagina con header
   $pdf->SetY(30);
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(0,10,$pdf->titulo,0,1,'C');
    
   
    
    
    $pdf->SetFillColor(224,235,255);


    
   // TABLA DE PARTES 

  


    //configuración de la tabla
    $Largocol=[20,20,10,30,80,20];
$alineado=['C','C','C','C','C','C'];
$pdf->SetWidths($Largocol);
$pdf->SetAligns($alineado);
$cabeceratabla=['Fecha','Hora',utf8_decode('Sanción'),'Profesor Implicado','Hechos','Tipo Parte'];
$pdf->SetCabeceras($cabeceratabla);
$pdf->SetColorRellenoCabeceras('165, 105, 189');





    

    $text='Fecha del informe: '.date("d-m-y");
    textoM($text,14);
    $pdf->ln(4);
    
    
    $text="PARTES DE DISCIPLINA DEL ALUMNO/A:";
    
     //Titulo segundo
     $pdf->SetFont('Arial','B',12);
     $pdf->Cell(0,16,utf8_decode($text),0,1,'C');
     $pdf->Ln(6);
 
     
    
     $pdf->SetFont('Arial','B',10);
     $pdf->Cabecera(); //cabecera tabla partes
 
       // Busca en la tabla tpartes todos los partes del alumno seleccionado
    $consulta2 = "SELECT * FROM tpartes WHERE AlumnoIdPa = '$IdAlumno' ORDER BY FechaPa DESC";
    $resSelect2 = $conn->prepare($consulta2);
    $resSelect2->execute();
     
    $pdf->SetFont('Arial','',10);
  //Imprime en pantalla los partes del alumno seleccionado
    $a=0;
    while($row2 = $resSelect2->fetch())
    {
        extract($row2);
     
        // Busca el nombre del profesor
        extract(consultaBDunica("SELECT * FROM tprofesores WHERE IdProfesor = '$ProfesorIdPa'"));
        
    
        //busca las letras de las sanciones
        $consulta4 = "SELECT * FROM tsancionparte WHERE ParteIdSaPa = '$IdParte'";
        $resSelect4 = $conn->prepare($consulta4);
        $resSelect4->execute();
    
        $LetrasSanciones="";
        while($row4 = $resSelect4->fetch()) 
        { 
            extract($row4);
            if ($LetrasSanciones == "")
            {$LetrasSanciones=$LetraSancionxAlumno;} 
            else
            {$LetrasSanciones=$LetrasSanciones." - ". $LetraSancionxAlumno;}
        }
        
        $Datosfila=[darfechaesp($FechaPa),$HoraPa,$LetrasSanciones,utf8_decode($Profesor),
        utf8_decode($HechosPa),utf8_decode($TipoPa)];

        

        if ($a % 2 == 0)
        { $pdf->Row($Datosfila,'D','235, 237, 239'); }
        else 
        { $pdf->Row($Datosfila,'DF','235, 237, 239'); }
        
        
        
       
        $a=$a+1;  
        }
    
    
    
   // TABLA DE SANCIONES

   $pdf->AddPage('P');

   
    //configuración de la tabla
    $Largocol=[5,15,90,30,10,10,10];
    $alineado=['C','C','C','C','C','C','C'];
    $pdf->SetWidths($Largocol);
    $pdf->SetAligns($alineado);
    $cabeceratabla=['Id','F. Acuerdo','Hechos',utf8_decode('Tipo Sanción'),'F. Inicio','F. Fin',
    utf8_decode('Nº Días')];
    $pdf->SetCabeceras($cabeceratabla);
    $pdf->SetColorRellenoCabeceras('165, 105, 189');
    
    
    
    
        // Busca en la tabla tsanciones todos las sanciones del alumno seleccionado
    $consulta5 = "SELECT * FROM tsanciones WHERE AlumnoIdSa = '$IdAlumno' ORDER BY FechaSa DESC";
    $resSelect5 = $conn->prepare($consulta5);
    $resSelect5->execute(); 
    

        
        
        $text="SANCIONES DEL ALUMNO/A:";
        
         //Titulo segundo
         $pdf->SetFont('Arial','B',10);
         $pdf->Cell(0,16,utf8_decode($text),0,1,'C');
         $pdf->Ln(6);
     
         
        
        
         $pdf->Cabecera(); //cabecera tabla partes
     
        
         $pdf->SetFont('Arial','',10);
      //Imprime en pantalla los sanciones del alumno seleccionado
      $b=0;
      while($row5 = $resSelect5->fetch())
      {
          extract($row5);
       
       
       //Busca letra de la sanción por alumno
      extract(consultaBDunica("SELECT * FROM tsancionparte WHERE SancionIdSaPa = '$IdSancion'"));
      
            
            $Datosfila=[$LetraSancionxAlumno,darfechaesp($FechaSa),utf8_decode($HechosSa),
            utf8_decode($TipoSa),darfechaesp($FechaInicio),darfechaesp($FechaFin),$DiasSancion];
    
    
            if ($b % 2 == 0)
            { $pdf->Row($Datosfila,'D','235, 237, 239'); }
            else 
            { $pdf->Row($Datosfila,'DF','235, 237, 239'); }
            
                      
            $b=$b+1;  
            }
        
      
   
 
   $pdf->Output('D',utf8_decode('Registros de disciplina del alumno '.$Iniciales.' ('.$Unidad.').pdf'));
   header('location:01disciplinaalumno.php'); 
    
       
   
    
   
?>