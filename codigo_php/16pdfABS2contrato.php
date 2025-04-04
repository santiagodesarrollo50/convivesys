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

session_start();
$curso=$_SESSION['PCurso'];
    
   

if (isset($_POST['generar']))
{
    
  include '00funciones.php';
  $conn = conectarBaseDeDatos();
    require ('00apoyopdf.php');
    
    $f_JefeEstudios=$_POST['jefeestudios'];
    $f_IdAnotacion=$_POST['idanotacion'];
    
    //Registro y actualizacion de los datos de la anotación 1ªcarta
    $edit = $conn->query("UPDATE tanotacionesabs set JefeEstudios='$f_JefeEstudios' where IdAnotacion='$f_IdAnotacion'");

    //Datos necesarios para componer el documento
    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad from talumnounidad inner join tunidades 
      on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));
    extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));  
    extract(consultaBDunica("SELECT valor1 as nombreies FROM tconfiguracion WHERE id='nombreies'"));  
    
   
    defineclasepdf ();
    $pdf=new PDF();


    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();
    //Primera página
    $pdf->AddPage();

    $pdf->Ln(8);
    
    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,16,utf8_decode('SEGUNDO CONTRATO DE MEJORA DEL ABSENTISMO'),0,1,'C');
    $pdf->Ln(6);

    $text='D./Dª';
    textoM($text,14);
    
    $text='<<Tutor1>>';
    textoM($text,14);
    
    $text='<<Tutor2>>';
    textoM($text,14);
   
    $text='como padre, madre o tutor legal del alumno/a';
    textoM($text,14);
    $pdf->Ln(5);

    $text='<<Alumno>> - (<<Unidad>>)';
    textoM($text,14,'B','C');
    $pdf->Ln(5);
    
    $text='y <<f_JefeEstudios>> por parte de la Jefatura de Estudios del '.$nombreies.', después de la reunión mantenida en este Centro en el día de la fecha, acuerdan lo siguiente en relación con el comportamiento del alumno/a.';
    textoM($text,14);
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

    $text='Fdo: <<f_JefeEstudios>>
Jefatura de Estudios'; 
    $y=$pdf->GetY();
    textoM($text,12);



    $text='Fdo: ________________________
El padre, madre o tutor legal del alumno';
    $pdf->SetXY(110,$y);
    textoM($text,12);

 
   


    $inicialessinpuntos=str_replace('.',' ',$Iniciales);
    //Genera el fichero PDF
    $namefile='2ContratoAbs-'.$inicialessinpuntos.'('.$Unidad.').pdf'; 
    $pdf->Output('D',utf8_decode($namefile));




    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}







if (!isset($_POST['generar']))
{ 
    include ('13cabecera.php');
    
    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad from talumnounidad inner join tunidades 
  on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));  
    extract(consultaBDunica("SELECT * from tanotacionesabs where
    AlumnoIdAn='$s_idalumno' AND substring(TipoAn,1,2)='04' order by FechaAn DESC"));
    extract(consultaBDunica("SELECT valor1 as M_IdProfesor from tconfiguracion where id='profesorabsentismo'"));
    extract(consultaBDunica("SELECT Profesor as JefeEstudiosAbs from tprofesores where IdProfesor='$M_IdProfesor'"));


?>


  <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">GENERANDO PDF 2º CONTRATO DE MEJORA DEL ABSENTISMO</h5>
        <hr class="my-1">
        
        <form  method="post" class="p-3"> 
       
          <div>
            <h5 class="text-secundary">Alumno: <?= $Alumno." (".$Unidad.")" ?> </h5>
          </div>
          
      
          <div>
            <h5 class="text-secondary">Jefe de Estudios que firma la carta</h5>
          </div>
           <div class="input-group">
            <input type="text" name="jefeestudios" class="form-control form-control-lg rounded-0 border-info"
             autocomplete="off" 
             <?php if (is_null($JefeEstudios)) {echo 'value="'.$JefeEstudiosAbs.'"';}
             else {echo 'value="'.$JefeEstudios.'"';} ?> >
           </div>
         
         
             <input type="hidden" name="idanotacion" value="<?= $IdAnotacion ?>">  
           <div class="input-group-append">
              <input type="submit" name="generar" value="Generar documento PDF" class="btn btn-info btn-lg rounded-1">
            </div>

        </form>
      </div>
      
       
    </div>
  </div>
  
  
  
  
</body>
</html>


<?php

}

?>



  
  