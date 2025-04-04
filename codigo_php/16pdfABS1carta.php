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
    
    

    $f_FechaAn=$_POST['fechaenvio'];
    $f_HoraCita=$_POST['horacita'];
    $f_JefeEstudios=$_POST['jefeestudios'];
    $f_IdAnotacion=$_POST['idanotacion'];
    
    if ($_POST['fechacita']!='')
    {
      $f_FechaCita=$_POST['fechacita'];
      $FechaCitaF=utf8_decode(fechaformatogranada($f_FechaCita));
    }
    else
    {
      $FechaCitaF='';
    }
    
    
    //Datos necesarios para componer el documento

    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
	extract(consultaBDunica("SELECT Unidad, ProfesorIdUn from talumnounidad inner join tunidades 
  on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));
    extract(consultaBDunica("SELECT Profesor from tprofesores where IdProfesor='$ProfesorIdUn'"));
    extract(consultaBDunica("SELECT * from tanotacionesabs where IdAnotacion='$f_IdAnotacion'"));
    extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));

    $Fechaenvio=utf8_decode($localidad.', a '.fechaformatogranada($f_FechaAn));
    
    
    //Registro y actualizacion de los datos de la anotación 1ªcarta
    $edit = $conn->query("UPDATE tanotacionesabs set 
    FechaAn='$f_FechaAn', FechaCita='$f_FechaCita', HoraCita='$f_HoraCita',
    JefeEstudios='$f_JefeEstudios' where IdAnotacion='$f_IdAnotacion'");
   
    
    
    
    


    //Inicia pdf Cabeceras y pie de pagina del documento PDF
    defineclasepdf ();
    $pdf=new PDF();


    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();
    
    //Página de Instrucciones al tutor
    $pdf->AddPage();
    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,7,utf8_decode('INSTRUCCIONES AL TUTOR/A PARA GESTIONAR'),0,1,'C');
    $pdf->Cell(0,7,utf8_decode('LA 1ª CARTA DEL PROTOCOLO DE ABSENTISMO'),0,1,'C');
    $pdf->Ln(5);
    
    $text='Alumno/a: <<Alumno>> - (<<Unidad>>)
Tutor/a: <<Profesor>>';
    textoM($text,14,'B');
    $pdf->Ln(5);
    
$text='1º PASO:';
textoM($text,12,'B');
$text='El tutor concertará telefónicamente una reunión presencial entre la familia y él para tratar la asistencia del alumno al centro. Y comunicará dicha fecha y hora al jefe de estudios responsable de absentismo.';
textoM($text,12);
    $pdf->Ln(5);


   
$pdf->SetFont('Arial','B',12);
$pdf->SetX(20);
$pdf->Cell(80,9,utf8_decode('Fecha de la reunión: '.darfechaesp($FechaCita)),1);
$pdf->Cell(80,9,utf8_decode('Hora de la reunión: '.$HoraCita),1);
$pdf->Ln(15);  

$text='2º PASO:';
textoM($text,12,'B');
$text='Desde Jefatura de Estudios se enviará carta certificada a la familia con la citación para dicha reunión. Jefatura de Estudios entregará al tutor dos documentos:
    - Copia de la citación a la reunión. 
    - Modelo de contrato de mejora del absentismo.
El tutor comunicará al jefe de estudios encargado de absentismo, cualquier cambio en la fecha de la reunión.';
    textoM($text,12);
    $pdf->Ln(5);

$text='3º PASO:';
textoM($text,12,'B');
$text='Realización de la reunión entre tutor y familia del alumno. En dicha reunión se deberán tocar al menos los siguientes puntos:';
textoM($text,12);
$pdf->Ln(5);

$text='Punto 1:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='El tutor informará a la familia de las faltas justificadas e injustificadas del alumno mes a mes desde principio de curso.';
textoM($text,12);
$pdf->Ln(5);

$text='Punto 2:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='El tutor preguntará sobre los motivos de las faltas de asistencia (justificadas o no).';
textoM($text,12);
    
$text='Motivos:';
$pdf->SetX(35);
textoM($text,12,'');
$y=$pdf->GetY();
$pdf->Rect(30,$y,160,20);
$pdf->Ln(25);


$text='Punto 3:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='El tutor indicará a la familia que pueden hacer el seguimiento de dichas faltas por iPasen. (Si es necesario, Jefatura de Estudios puede proporcionar las credenciales de tutor legal a dicha plataforma).';
textoM($text,12);
$pdf->Ln(5);

$text='Punto 4:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='El tutor indicará a la familia que en adelante es necesario que justifique las faltas con la documentación adecuada (Documento médico, o en su caso cualquier otro documento oficial. No es válida una declaración por escrito de la familia).';
textoM($text,12);
$pdf->Ln(5);

$text='Punto 5:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='La familia firmará el "recibí" de la citación, rellenando fecha y nombre del padre o madre.  El tutor conservará dicho documento y deberá entregarlo al jefe de estudios responsable de absentiso. Realizará una copia para la familia si ésta lo solicita.'; 
textoM($text,12);
$pdf->Ln(5);

$text='Punto 6:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='Se leerá y firmará (rellenar fecha y nombre del padre o madre) el contrato de mejora del absentismo. El tutor conservará dicho documento y deberá entregarlo al jefe de estudios responsable del absentismo. Realizará una copia para la familia si ésta lo solicita.';
textoM($text,12);
$pdf->Ln(5);

$text='Punto 7:';
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,6,$text);
$text='Otras observaciones sobre la reunión:';
textoM($text,12);
    $pdf->Ln(5);

$y=$pdf->GetY();
$pdf->Rect(30,$y,160,20);
$pdf->Ln(30);



$text='4º PASO:';
textoM($text,12,'B');
  $text='Se entregará al jefe de estudios responsable de absentismo los tres documentos:
    - "Citación a la reunión" con recibí firmado,
    - "Contrato de mejora" firmado,
    - Este documento relleno (motivos de ausencia y observaciones si son necesarias).';
    textoM($text,12);
    $pdf->Ln(5);

    
    $pdf->AddPage();

    $pdf->Ln(13);

    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,17,utf8_decode('CITACIÓN PARA REUNIÓN SOBRE ABSENTISMO DEL ALUMNO/A'),0,1,'C');
    $pdf->Ln(7);



    $text='Sr/Sra. padre, madre o tutor legal del alumno/a:';
    textoM($text,14);
    $pdf->Ln(5);

    $text='<<Alumno>> - (<<Unidad>>)';
    textoM($text,14,'B','C');
    $pdf->Ln(5);


    $text='Por medio de la presente, pongo en su conocimiento que la Delegación de Educación  considera absentistas  a los alumnos que faltan al centro escolar sin justificar.
    
En este sentido le informamos que su hijo/a ha faltado a clase las horas que figuran en la siguiente página. Puede obtener información más detallada de las faltas de asistencia en la aplicación iPasen.

Con esta notificación damos cumplimiento a su derecho como padre/madre a estar informado de las faltas de asistencia de su hijo/a y le recordamos que deberá usted pasarse por el centro para tratar del tema en una reunión con el tutor/a del grupo de su hijo/a (<<Profesor>>), en el siguiente horario:';
    textoM($text,14);
    $pdf->Ln(5);

    $text='Fecha: <<FechaCitaF>>                                Hora: <<f_HoraCita>>';
    textoM($text,14,'B','C');
    $pdf->Ln(5);

    $text='Sin otro particular, reciba un cordial saludo.';
    textoM($text,14);
    $pdf->Ln(5);

    $y=$pdf->GetY();
    $text='<<Fechaenvio>>
    

Fdo: <<f_JefeEstudios>>
Jefatura de Estudios.';
    textoM($text,12);
    
    
    $text='RECIBÍ:
Fecha:_________________
Firma:

Fdo:___________________________
Padre, Madre o Tutor legal del alumno/a';
    $pdf->SetXY(120,$y);
    $pdf->SetFont('Times','',12);
    $pdf->MultiCell(75,8,utf8_decode($text),1);
    
    





$pdf->AddPage();



$pdf->Ln(20);
 $text='Anexo: Faltas de asistencia del alumno '. $Alumno.' ('.$Unidad.') durante el curso '.cursoescolar();
    textoM($text,14,'B','C');
    $pdf->Ln(10);

//tabla de faltas mensuales
  //configuración de la tabla
    $Largocol=[15,30,30,25,70];
$alineado=['C','C','C','C','C'];
$pdf->SetWidths($Largocol);
$pdf->SetAligns($alineado);
$pdf->SetCabeceras(['Mes','Horas Injustificadas','Horas Justificadas','Retrasos',utf8_decode('Días del mes faltados injustificadamente')]);
$pdf->SetColorRellenoCabeceras('165, 105, 189');



$pdf->SetX(20);
 $pdf->SetFont('Arial','B',12);
$pdf->Cabecera(); //cabecera


    $numeromes=array_slice([9,10,11,12,1,2,3,4,5,6],0,mesesdecurso());
    $Meses=array_slice(Array(9 => 'Sep',10 => 'Oct',11 => 'Nov',12 => 'Dic',1 => 'Ene',
    2 => 'Feb',3 => 'Mar',4 => 'Abr',5 => 'May',6 => 'Jun'),0,mesesdecurso());
    
    $pdf->SetFont('Arial','',12);
    foreach($Meses as $mm => $mes)
    {
    $m=$numeromes[$mm];
    extract(consultaBDunica("Select sum(HorasI) as HFI FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select sum(HorasJ) as HFJ FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select sum(HorasR) as HR FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select max(FechaActualizacion) as Frevision FROM
    tfaltas WHERE MONTH(FechaFa)=$m")); 
    
     //busca los dias faltados en el mes
        $consulta4 = "SELECT * FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'";
        $resSelect4 = $conn->prepare($consulta4);
        $resSelect4->execute();
        $diasfaltados="";
        
        while($row4 = $resSelect4->fetch()) 
        { 
        extract($row4);
        if ($HorasI!=0)
        {$diasfaltados=$diasfaltados.'día '.date('j',strtotime($FechaFa)).', ';}
        }
        $diasfaltadosd=utf8_decode($diasfaltados);
        $pdf->SetX(20);
        $pdf->Row([$mes,$HFI,$HFJ,$HR,$diasfaltadosd],'D','235, 237, 239');
    }

    $inicialessinpuntos=str_replace('.',' ',$Iniciales);
    //Genera el fichero PDF
    $namefile='1CartaABS -'.$inicialessinpuntos.'('.$Unidad.').pdf';
    $pdf->Output('D',utf8_decode($namefile));




    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}





if (!isset($_POST['generar']))
{ 
    include ('13cabecera.php');
    
    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT * from tanotacionesabs where
    AlumnoIdAn='$s_idalumno' AND substring(TipoAn,1,2)='01' order by FechaAn DESC"));
    extract(consultaBDunica("SELECT valor1 as M_IdProfesor from tconfiguracion where id='profesorabsentismo'"));
    extract(consultaBDunica("SELECT Profesor as JefeEstudiosAbs from tprofesores where IdProfesor='$M_IdProfesor'"));
?>


  <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">GENERANDO PDF 1ª CARTA</h5>
        <hr class="my-1">
        
        <form  method="post" class="p-3"> 
       
          <div>
            <h5 class="text-secundary">Alumno: <?= $Alumno." (".$Unidad.")" ?> </h5>
          </div>
          
      
          <div>
            <h5 class="text-secondary">Fecha del envio*</h5>
          </div>
          <div class="input-group">
            <input type="date" name="fechaenvio" class="form-control form-control-lg rounded-0 border-info"
            value="<?= $FechaAn ?>" autocomplete="off" required>
           </div>
         
         
           <div>
            <h5 class="text-secondary">Fecha de la citación a la reunión</h5>
          </div>
          <div class="input-group">
            <input type="date" name="fechacita" class="form-control form-control-lg rounded-0 border-info"
             autocomplete="off" value="<?= $FechaCita ?>">
           </div>
           
           <div>
            <h5 class="text-secondary">Hora de la citación a la reunión</h5>
          </div>
          <div class="input-group">
            <input type="text" name="horacita" class="form-control form-control-lg rounded-0 border-info"
             autocomplete="off" value="<?= $HoraCita?>" >
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