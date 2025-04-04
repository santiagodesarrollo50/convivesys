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
require ('00apoyopdf.php');

session_start();

$curso=$_SESSION['PCurso'];
    
    
//Datos necesarios para componer el documento
    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad, ProfesorIdUn from talumnounidad inner join tunidades 
  on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));
    extract(consultaBDunica("SELECT Profesor from tprofesores where IdProfesor='$ProfesorIdUn'"));
    extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));
    

   
    defineclasepdf ();
    $pdf=new PDF();


    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();
    //Primera página
    $pdf->AddPage();

    $pdf->Ln(13);
    
    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,16,utf8_decode('PRIMER CONTRATO DE MEJORA DEL ABSENTISMO'),0,1,'C');
    $pdf->Ln(7);



    $text='Los abajos firmantes, después de la reunión mantenida en el  Centro el día de la fecha, acuerdan lo siguiente en relación con el comportamiento de su hijo:';
    textoM($text,14);
    $pdf->Ln(5);

    $text='<<Alumno>> - (<<Unidad>>)';
    textoM($text,14,'B','C');
    $pdf->Ln(5);
    
    $text='Compromisos:';
    textoM($text,14,'B');
    


    $text='- Asistencia diaria a clase y con puntualidad.
- Justificar las ausencias por enfermedad con el correspondiente certificado  oficial.
- Acudir a hablar con el/la tutor/a periódicamente.

A través de este contrato, que a la fecha de la firma se pone en marcha, se considera que los padres o tutores del alumno se comprometen  a cumplir lo arriba escrito y que haremos revisión del mismo mensualmente, y en caso de no cumplir se derivará a los organismos competentes para que actúen en consecuencia.';
    textoM($text,14);
    $pdf->Ln(15);


    $text='En '.$localidad.', a ____ de ___________________ de _______';
    textoM($text,14,'','C');   
    $pdf->Ln(40);

    $text='Fdo: D. <<Profesor>>
Profesor/a tutor/a de <<Unidad>>'; 
    $y=$pdf->GetY();
    textoM($text,12);



    $text='Fdo: ________________________
El padre, madre o tutor legal del alumno';
    $pdf->SetXY(110,$y);
    textoM($text,12);

 
   


    $inicialessinpuntos=str_replace('.',' ',$Iniciales);
    //Genera el fichero PDF
    $namefile='1ContratoAbs-'.$inicialessinpuntos.'('.$Unidad.').pdf'; 
    $pdf->Output('D',utf8_decode($namefile));




    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);


?>



  
  