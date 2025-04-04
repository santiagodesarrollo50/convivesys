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
include '00funciones.php';
$conn = conectarBaseDeDatos();
$curso=$_SESSION['PCurso'];
$s_idalumno=$_SESSION['s_idalumno'];
extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
extract(consultaBDunica("SELECT Unidad, Nivel from talumnounidad inner join tunidades 
    on IdUnidad=UnidadIdAlUn where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));
  
if (isset($_POST['generar'])) {
    require ('00apoyopdf.php');
    $f_FechaAn=$_POST['f_FechaAn'];
    $Fechaderivacion=darfechaesp($f_FechaAn);
    $f_CentroSS=$_POST['f_CentroSS'];
    $f_NumeroHermanos=$_POST['f_NumeroHermanos'];
    $f_ProblemasHermanos=$_POST['f_ProblemasHermanos'];
    $f_Antecedentes=$_POST['f_Antecedentes'];
    $f_Actuaciones=$_POST['f_Actuaciones'];
    $f_OtraInformacion=$_POST['f_OtraInformacion'];
    $f_IdAnotacion=$_POST['idanotacion'];
    
    
    //Registro y actualizacion de los datos de la anotación anexo II
    $edit = $conn->query("UPDATE tanotacionesabs set FechaAn='$f_FechaAn', 
    CentroSS='$f_CentroSS', NumeroHermanos='$f_NumeroHermanos', ProblemasHermanos='$f_ProblemasHermanos',
    Antecedentes='$f_Antecedentes', Actuaciones='$f_Actuaciones', OtraInformacion='$f_OtraInformacion'
    where IdAnotacion='$f_IdAnotacion'");
   
    
    //Datos necesarios para componer el documento
    
    extract(consultaBDunica("SELECT valor1 as Director FROM tconfiguracion where id='profesordirector'"));
    
    
    
    
    extract(consultaBDunica("SELECT * FROM tconfiguracion WHERE orden='$f_CentroSS'"));
    extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));
    extract(consultaBDunica("SELECT valor1 as provincia FROM tconfiguracion WHERE id='provinciaies'"));
    extract(consultaBDunica("SELECT valor1 as nombreies FROM tconfiguracion WHERE id='nombreies'"));
    extract(consultaBDunica("SELECT valor1 as codigocentro FROM tconfiguracion WHERE id='codigocentro'"));
    extract(consultaBDunica("SELECT valor1 as direccioncentro FROM tconfiguracion WHERE id='direccioncentro'"));
    
    $FechaNacEsp=darfechaesp($FechaNac);
    $Edad=calcularedad($FechaNac);
    $Fechaenvio=utf8_decode($localidad.', a '.fechaformatogranada($f_FechaAn));
    
    
    
    
    
    
    //Inicia pdf Cabeceras y pie de pagina del documento PDF
    defineclasepdf ();
    $pdf=new PDF();


    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();
    
    $pdf->AddPage();
    $pdf->Ln(2);

    //Titulo del documento
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,7,utf8_decode('ANEXO II'),0,1,'C');
    $pdf->SetFont('Arial','B',12);
    $pdf->MultiCell(0,6,utf8_decode('PROTOCOLO DE DERIVACIÓN DE LOS CENTROS EDUCATIVOS A LOS SERVICIOS SOCIALES COMUNITARIOS/MUNICIPALES EN CASO DE ABSENTISMO ESCOLAR')
    ,0,'C');
    $pdf->Ln(7);



    $text='EMITIDO POR:';
    textoM($text,12,'B');
    
    $text='NOMBRE DEL CENTRO: '.$nombreies.'                  CÓDIGO: '.$codigocentro.'
DIRECCIÓN: '.$direccioncentro.'
LOCALIDAD: '.$localidad.'                            		    		PROVINCIA: '.$provincia.'
FECHA DE DERIVACIÓN: <<Fechaderivacion>>';
    textoG($text,10);
    $pdf->Ln(5);
    
    
    
    $text='DIRIGIDO A:';
    textoM($text,12,'B');
    $text='SERVICIOS SOCIALES COMUNITARIOS/ MUNICIPALES DE :   <<etiqueta>>
DIRECCIÓN: <<valor1>>
LOCALIDAD: <<valor2>>                                     PROVINCIA: '.$provincia;
    textoG($text,10);
    $pdf->Ln(5);


    $text='MOTIVO DEL INFORME:';
    textoM($text,12,'B');
    $text='Absentismo';
    textoG($text,10);
    $y=$pdf->GetY();
    $pdf->Line(15,$y,185,$y);
    $pdf->Ln(5);
    
    
    
    $text='DATOS DE IDENTIFICACIÓN DEL MENOR:';
    textoM($text,12,'B');
    $text='NOMBRE: <<Alumno>>
NOMBRE DEL 1º TUTOR LEGAL:  <<Tutor1>>        TELF:  <<TelefonoTutor1>>     
NOMBRE DEL 2º TUTOR LEGAL:  <<Tutor2>>       TELF:  <<TelefonoTutor2>>
EDAD:  <<Edad>> años.                    FECHA DE NACIMIENTO:  <<FechaNacEsp>>';
    textoG($text,10);
    $pdf->Ln(5);

    $text='CURSO EN EL QUE SE ENCUENTRA MATRICULADO/A:  <<Nivel>>  (<<Unidad>>)
DOMICILIO:  <<Direccion>>
LOCALIDAD:  <<CP>>-<<Localidad>>  (<<Provincia>>)					
Nº DE HERMANOS/AS MATRICULADOS/AS EN EL CENTRO:  <<f_NumeroHermanos>>
¿PRESENTAN TAMBIÉN PROBLEMAS DE ABSENTISMO?';
textoG($text,10);
textoM($f_ProblemasHermanos,10);

    $pdf->Ln(5);




    $pdf->AddPage();
    $pdf->Ln(13);
    
    $text='RESUMEN DE LOS ANTECEDENTES DE ABSENTISMO DEL MENOR:';
    textoM($text,12,'B');
    $pdf->SetFont('Times','',10);
    $pdf->MultiCell(0,7,utf8_decode($f_Antecedentes),1);
    $pdf->Ln(5);
    
    $text='ACTUACIONES REALIZADAS DESDE EL CENTRO EDUCATIVO RELATIVAS AL CASO:';
    textoM($text,12,'B');
    $pdf->SetFont('Times','',10);
    $pdf->MultiCell(0,7,utf8_decode($f_Actuaciones),1);
    $pdf->Ln(5);
    
    $text='OTRA INFORMACIÓN RELEVANTE (POSIBLES INDICADORES DE MALTRATO/ ABANDONO) RESPECTO DEL MENOR Y LA FAMILIA:';
    textoM($text,12,'B');
    $pdf->SetFont('Times','',10);
    $pdf->MultiCell(0,7,utf8_decode($f_OtraInformacion),1);
    $pdf->Ln(10);
    
    
    $text='FECHA, SELLO Y FIRMA DE LA DIRECCIÓN DEL CENTRO  EDUCATIVO';
    textoM($text,10);
    $pdf->Ln(10);

    $y=$pdf->GetY();
    $text='<<Fechaenvio>>
    

Fdo: D. <<Director>>.
Director del '.$nombreies.'.';
    textoM($text,10,'','R');
    
    


    $inicialessinpuntos=str_replace('.',' ',$Iniciales);
    //Genera el fichero PDF
    $namefile='AnexoIIABS -'.$inicialessinpuntos.'('.$Unidad.').pdf';
    $pdf->Output('D',utf8_decode($namefile));

    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}





if (!isset($_POST['generar']))
{ 
    include ('13cabecera.php');
    
    
    $s_idalumno=$_SESSION['s_idalumno'];
    extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$s_idalumno'"));
    extract(consultaBDunica("SELECT * from tanotacionesabs WHERE
    AlumnoIdAn='$s_idalumno' AND substring(TipoAn,1,2)='05' order by FechaAn DESC"));
    $FechaInicioCurso=iniciocursoacutal();
    
    //Composicion de los Antecedentes
    if (is_null($Antecedentes))
    {   
        $consulta1="SELECT FechaAn as FechaAn2, TipoAn as TipoAn2 from tanotacionesabs where AlumnoIdAn='$IdAlumno' and 
        FechaAn<'$FechaInicioCurso' order by FechaAn";
        $resSelect1 = $conn->prepare($consulta1);
        $resSelect1->execute();
        while($row1 = $resSelect1->fetch()) 
            { 
            extract($row1);
            if (substr($TipoAn2,0,2)<5)
            { 
            extract(consultaBDunica("SELECT F_TipoAnotacion from tdatos where F_IdTipoAnotacion='$TipoAn2'"));
            $Antecedentes=$Antecedentes."\n (".darfechaesp($FechaAn2).') '.$F_TipoAnotacion;}
            }
        if ($Antecedentes=="")
        {$Antecedentes='No constan antecedentes de absentismo anteriores a este curso escolar, en este centro educativo.';}
    }
  
  
    //Composicion de los Actuaciones
    if (is_null($Actuaciones))
    {   
        $consulta1="SELECT FechaAn as FechaAn2, TipoAn as TipoAn2 from tanotacionesabs where AlumnoIdAn='$IdAlumno' and 
        FechaAn>'$FechaInicioCurso' and FechaAn<='$FechaAn' order by FechaAn";
        $resSelect1 = $conn->prepare($consulta1);
        $resSelect1->execute();
        $Actuaciones='SUGERENCIA. BORRAR LO QUE NO PROCEDA:
- Se ha ayudado a al familia a gestionar la credencial de adquisición de los libros escolares para este curso. 
- Se le ha facilitado a la familia las credenciales de acceso a la aplicación PASEN para el seguimiento de la asistencia de su hijo/a.';
        while($row1 = $resSelect1->fetch()) 
            { 
            extract($row1);
            if (substr($TipoAn2,0,2)<5)
            { 
            extract(consultaBDunica("select F_TipoAnotacion from tdatos where F_IdTipoAnotacion='$TipoAn2'"));
            $Actuaciones=$Actuaciones."\n (".darfechaesp($FechaAn2).') '.$F_TipoAnotacion;}
            }
    }

  
    //Composición de Otra Información
    if (is_null($OtraInformacion))
    { $OtraInformacion='No constan.'; }
   
   
    
?>


  <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">GENERANDO PDF ANEXO II</h5>
        <hr class="my-1">
        
        <form  method="post" class="p-3"> 
       
          <div>
            <h5 class="text-secundary">Alumno: <?= $Alumno." (".$Unidad.")" ?> </h5>
            <br>
            <h6 class="text-secundary">Sugerencia de contenidos de Anexo II</h6>
          </div>
          
      
          <div>
            <h5 class="text-secondary">Fecha de derivación*</h5>
          </div>
          <div class="input-group">
            <input type="date" name="f_FechaAn" class="form-control form-control-lg rounded-0 border-info"
            value="<?= $FechaAn ?>" autocomplete="off" required>
           </div>
         
         <div>
            <h5 class="text-secondary">Centro de Servicios Sociales del alumno*</h5>
          </div>
          <div class="input-group">
            <?php         
            // Cargamos opciones CentrosSS
            $consulta = "SELECT etiqueta, id, orden FROM tconfiguracion where tipo='centrosSS'";
            $resSelect3 = $conn->prepare($consulta);
            $resSelect3->execute();
            ?>
            <select name="f_CentroSS" required class="form-control form-control-lg rounded-0 border-info">
               <?php 
                while($row3 = $resSelect3->fetch()) {
                    if ($row3['orden']==$CentroSS) {
                        $seleccionado="selected"; 
                    } else {
                        $seleccionado ="";
                    }
                    ?>
                    <option <?php echo $seleccionado ?> value="<?php echo $row3['orden'];?>">
                        <?php echo $row3['etiqueta'];?>
                    </option>
                <?php 
                }
                ?>
            </select>
           </div>
           
           <div>
            <h5 class="text-secondary">Número de hermanos en el centro*</h5>
          </div>
          <div class="input-group">
            <input type="number" name="f_NumeroHermanos" class="form-control form-control-lg rounded-0 border-info"
             autocomplete="off" value="<?php
             if(is_null($NumeroHermanos))
             {echo '0';}
             else 
             {echo $NumeroHermanos;} ?>" required>
           </div>
         
         <div>
            <h5 class="text-secondary">Problemas de Absentismo de los hermanos matriculados en el centro</h5>
          </div>
          <div class="input-group">
            <textarea name="f_ProblemasHermanos" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"><?= $ProblemasHermanos?></textarea>
            <br>
        </div>
          
           <div>
            <h5 class="text-secondary">Antecedentes de Absentismo*</h5>
          </div>
          <div class="input-group">
            
            <textarea name="f_Antecedentes" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off" required><?= $Antecedentes?></textarea>
            <br>
           </div>
           
           <div>
            <h5 class="text-secondary">Actuaciones realizadas en el centro*</h5>
          </div>
          <div class="input-group">
            
            <textarea name="f_Actuaciones" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off" required><?= $Actuaciones?></textarea>
            <br>
           </div>
           
           <div>
            <h5 class="text-secondary">Otra información relevante (Maltrato, abandono):*</h5>
          </div>
          <div class="input-group">
            
            <textarea name="f_OtraInformacion" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off" required><?= $OtraInformacion ?></textarea>
            <br>
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