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
    require '00apoyopdf.php';
    

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
    
    
    
    //Registro y actualizacion de los datos de la anotación 2ªcarta
    $edit = $conn->query("UPDATE tanotacionesabs set FechaAn='$f_FechaAn', 
    FechaCita='$f_FechaCita', HoraCita='$f_HoraCita', JefeEstudios='$f_JefeEstudios'
    where IdAnotacion='$f_IdAnotacion'");
   
    
    //Datos necesarios para componer el documento

    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad from talumnounidad inner join tunidades 
      on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));
    extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));
   

    $Fechaenvio=utf8_decode($localidad.', a '.fechaformatogranada($f_FechaAn));
    
    extract(consultaBDunica("SELECT Profesor from tprofesores where IdProfesor in
    (SELECT valor1 as M_IdProfesor FROM tconfiguracion where id='profesordirector')"));
    $Director=nombreprimero($Profesor);
    


    //Inicia pdf Cabeceras y pie de pagina del documento PDF
    defineclasepdf ();
    $pdf=new PDF();


    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();
    
    //Página de Instrucciones al tutor
    $pdf->AddPage();
    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,7,utf8_decode('COMUNICACIÓN DE ABSENTISMO'),0,1,'C');
    $pdf->Ln(5);
    
    
    
  $text='Sr. D./Sra Dña.: 
<<Tutor1>>,
<<Tutor2>>,
como padre, madre o tutor legal del alumno/a:';
    textoM($text,10,'');
    $pdf->Ln(5);  

    
    $text='<<Alumno>> - (<<Unidad>>)';
    textoM($text,12,'B','C');
    $pdf->Ln(5);
    

$text='como consecuencia de la constancia en el centro de faltas reiteradas de asistencia de su hijo/ hija, que no han sido justificadas mediante documento oficial, nos vemos en la obligación de  informarle de lo siguiente:
    
    1.-  Como padres y tutores tienen la obligación legal de garantizar la escolarización de su hijo/a hasta cumplidos los dieciséis años, según establece la normativa vigente.
    
    2.-Como tutores legales pueden estar incurriendo en falta o delito, por negligencia o descuido en sus responsabilidades como padres al amparar que esta situación se continúe dando, sobre todo después de ser informados de este hecho.
    
    3.-Desde la responsabilidad que nos compete, tenemos la obligación de trasladar la información sobre dichas faltas al Equipo  de Absentismo, así como a otros entes competentes en esta materia en base a la siguiente normativa:';
textoM($text,10);
$pdf->Ln(2);

    $text='Constitución Española de 1978:';
    textoM($text,10,'B');
    $text='     - Artículo 27.4: Indica que la Enseñanza básica es obligatoria y gratuita.
    - Artículo 39: Recoge la protección a la familia y la infancia, obligando a los poderes públicos a asegurar la protección integral a los niños.';
    textoM($text,10);
    $pdf->Ln(5);
    $text='Ley Orgánica 9/1995 de 20 de noviembre, de Participación Evaluación y Gobierno de los Centros  Docentes:';
textoM($text,10,'B');
$text='     -Artículo 21: Hace a los directores de los centros responsables de velar por el cumplimiento de la legalidad';
   textoM($text,10);
    $pdf->Ln(5); 
    $text='Ley 1/1998 de 20 de abril, de Derechos y la Atención al Menor:';
textoM($text,10,'B');
$text='     -Artículo 11: Los titulares de los centros educativos y el personal de los mismos están especialmente obligados a poner en conocimiento, ya sea del Servicio de Atención al Niño, de la Autoridad Judicial o del Ministerio Fiscal aquellos hechos que puedan suponer la existencia de situaciones de desprotección o  riesgo, o indicio de maltrato y, a colaborar con los mismos para evitar y resolver tales situaciones. Deberán poner expresamente en conocimiento de tales organismos el absentismo escolar de los niños/as
    -Artículo 23: Desamparo y tutela. Se considera situación de desamparo la ausencia de escolarización y/o absentismo habitual.';
textoM($text,10);
    $pdf->Ln(5);

$y=$pdf->GetY();
    $text='<<Fechaenvio>>
    
    
    
VºBº
Fdo.: D. <<Director>>
Director del Centro.';
    textoM($text,10);
    
    
    $text='RECIBÍ:
Fecha:_________________
Firma:

Fdo:___________________________
Padre, Madre o Tutor legal del alumno/a';
    $pdf->SetXY(120,$y);
    $pdf->SetFont('Times','',10);
    $pdf->MultiCell(75,8,utf8_decode($text),1);

    
    
    
    
    
    
    
    
    $pdf->AddPage();

    $pdf->Ln(13);

    //Titulo del documento
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,17,utf8_decode('CITACIÓN PARA REUNIÓN SOBRE ABSENTISMO DEL ALUMNO/A'),0,1,'C');
    $pdf->Ln(7);



 


    $text='Estimados padres y madres (tutores legales):
Ante el comportamiento absentista de su hijo/a, el/la alumno/a:';
    textoM($text,12);
    $pdf->Ln(5);

    $text='<<Alumno>> - (<<Unidad>>)';
    textoM($text,14,'B','C');
    $pdf->Ln(5);


    $text='con las horas faltadas sin justificar durante el presente curso escolar que figuran en el informe anexo, y dada la persistencia de dicho absentismo, les citamos a una reunión a celebrar';
    textoM($text,12);
    $pdf->Ln(5);

    
    $text='el día <<FechaCitaF>>,                          a las <<f_HoraCita>> horas,';
    textoM($text,14,'B','C');
    $pdf->Ln(5);

    $text='en el centro escolar para hablar de este  asunto y buscar conjuntamente una solución.

Con respecto a este tema, queremos recordarles  la obligatoriedad que tienen los padres o tutores legales de los menores en las etapas de educación obligatoria (entre 6 y 16 años), de procurar la asistencia continuada de los mismos al centro escolar y en buenas condiciones sanitarias, de higiene y alimenticias.

Por último, les informamos que en caso de no tener respuesta a esta citación, yo como director y el responsable del Programa de Compensación del EOE en este Centro, pondremos el asunto en conocimiento de los Servicios Sociales de esta localidad y de la Delegación de  Educación de la Junta de Andalucía, para que dichas instituciones tomen las medidas oportunas.

Sin otro particular, atentamente.';
    textoM($text,12);
    $pdf->Ln(5);

    $y=$pdf->GetY();
    $text='<<Fechaenvio>>
    

Fdo: D. <<Director>>
Director del Centro.';
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
 $text='Anexo: Faltas de asistencia del alumno '.$Alumno.' ('.$Unidad.') durante el curso '.cursoescolar();
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
    extract(consultaBDunica("SELECT sum(HorasI) as HFI FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT sum(HorasJ) as HFJ FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT sum(HorasR) as HR FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT max(FechaActualizacion) as Frevision FROM
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
    $namefile='2CartaABS -'.$inicialessinpuntos.'('.$Unidad.').pdf';
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
    AlumnoIdAn='$s_idalumno' AND substring(TipoAn,1,2)='03' order by FechaAn DESC"));
    extract(consultaBDunica("SELECT valor1 as M_IdProfesor from tconfiguracion where id='profesorabsentismo'"));
    extract(consultaBDunica("SELECT valor1 as M_IdProfesor from tconfiguracion where id='profesorabsentismo'"));
    extract(consultaBDunica("SELECT Profesor as JefeEstudiosAbs from tprofesores where IdProfesor='$M_IdProfesor'"));


?>


  <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">GENERANDO PDF 2ª CARTA</h5>
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