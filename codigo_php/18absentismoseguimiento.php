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
include ('13cabecera.php');

$Curso=$_SESSION['PCurso'];

      

$_SESSION['Procedencia']="18absentismoseguimiento.php";
    // calcular mes de seguimiento, por defecto el actual (correspondiente al curso actual)
    if (isset($_POST['actualizarS']))
    {
        $messeguimiento= numeromes($_POST['messeguimiento']);
        $TopeI= $_POST['topeI'];
        $TopeJ= $_POST['topeJ'];
        $TopeR= $_POST['topeR']; 
        $_SESSION['messeguimiento']=$messeguimiento;   
        $_SESSION['topeI']=$TopeI;
        $_SESSION['topeJ']=$TopeJ;
        $_SESSION['topeR']=$TopeR; 
    }
    elseif (isset($_SESSION['topeI']))
    {
        $messeguimiento=$_SESSION['messeguimiento'];
        $TopeI= $_SESSION['topeI'];
        $TopeJ= $_SESSION['topeJ'];
        $TopeR= $_SESSION['topeR'];
    }
    else
    { 
        $messeguimiento=date("n");
        $TopeI=20;
        $TopeJ=300;
        $TopeR=300;
    }

    $messeguimientoAnterior = $messeguimiento-1;
    if ($messeguimientoAnterior == 0) {
        $messeguimientoAnterior = 12;
    }
    
    
?>
    
    <center>
    <div class="container">
    <div class="col-md-12 mx-auto bg-light rounded p-4">
    <h3> <b>SEGUIMIENTO MENSUAL DEL ABSENTISMO
    <br> Curso: <?= $Curso ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mes: <?= nombremes ($messeguimiento) ?> </b></h3>
    <br> <h7> En esta pantalla se muestra: <br>
        1. Alumnado menor de 16 años que cumple alguno de los criterios de faltas y retrasos indicados.
        <br>
        2. Alumnado menor de 16 años con algún paso del protocolo de absentismo realizado durante este curso escolar. 
        <br>
        3. Alumnado marcado para seguimiento de cualquier edad.
    </h7> <br>
    <br>
    <form method="POST" >
    
    
    <!-- Opciones del seguimiento-->
    <div id="menuopciones">
    <h5>
    Mes: <select name="messeguimiento">
            <?php
            foreach([9,10,11,12,1,2,3,4,5,6] as $Opcion) 
            {
            if ($Opcion==$messeguimiento)
            { $seleccionado="selected"; } else {$seleccionado ="";}
            ?>
            <option <?php echo $seleccionado ?>><?php echo nombremes($Opcion);?></option>
            <?php 
            }
            ?>
    </select>
    <br>
    Tope horas faltas injustificadas: 
    <input type="text" name="topeI" value="<?= $TopeI ?>" size="3">
    Tope horas faltas justificadas: 
    <input type="text" name="topeJ" value="<?= $TopeJ ?>" size="3">
    Tope horas retrasos: 
    <input type="text" name="topeR" value="<?= $TopeR ?>" size="3">
    </h5>
    <input type="submit" name="actualizarS" value="Actualizar Seguimiento" class="btn btn-primary"
    formaction="18absentismoseguimiento.php">
    <input type="submit" name="PTutor" value="Enviar al tutor petición de justificación de faltas del alumno" class="btn btn-secondary" 
    formaction="10enviocorreos.php?tipoenviog=CorreoTutorJustificaFaltas&OtroDato=<?=$messeguimiento?>">
    </div>
   
   

    <table  class="table table-hover" id="tablaregistros">
      
     
       
   
<?php
   
    //lista al alumnado seleccionado para el seguimieto mensual 
    $finicioc=iniciocursoacutal ( );
    $sql = "SELECT DISTINCT Alumno, IdAlumno, Unidad, FechaNac,
    (SELECT ABS_Seguimiento FROM tnotasalumno WHERE AlumnoIdNo=IdAlumno) as ABS_Seguimiento
    FROM talumnos, tnotasalumno, talumnounidad, tunidades
WHERE idalumno=alumnoidalun and idunidad=unidadidalun and CursoAlUn='$Curso' and
(
    (idalumno=alumnoidno and ABS_Seguimiento='SI') OR
    (
        Timestampdiff(YEAR, FechaNac, curdate())<16 AND
        (
            idalumno in
            (SELECT AlumnoIdFa FROM tfaltas where month(FechaFa)=$messeguimiento
            GROUP BY AlumnoIdFa HAVING (sum(HorasI)>=$TopeI OR sum(HorasJ)>=$TopeJ OR sum(HorasR)>=$TopeR))
            OR
            idalumno in
            (SELECT DISTINCT AlumnoIdAn FROM tanotacionesabs WHERE substring(TipoAn,1,2)<7 
            AND FechaAn > '$finicioc')
        )
    )    
) 
ORDER BY orden, Alumno";
    $resSelect3 = $conn->prepare($sql);
    $resSelect3->execute();
    
    $a=0;
    while($row3 = $resSelect3->fetch())
    {
        unset($HFI, $HFJ, $HFR, $HFIa, $HFJa, $HFRa,  $HFITotal, $HFJTotal, $HFRTotal,
        $PProtocolo, $NProtocolo, $Frevision);
        
        extract($row3);
    
        if ($a % 7 == 0) 
        { ?>
            <thead>
            <tbody> 
            <tr>
        <th scope="col"></th>
        <th scope="col">Alumno</th>
        <th scope="col">Unidad</th>
        <th scope="col">Seguimiento Abs.</th>
        <th scope="col">P. Just. Tutor (<?= nombremes($messeguimiento)?>)</th>
        <th scope="col">Estado Abs.</th>
        <th scope="col">Faltas (I/J/R) <?= nombremes($messeguimiento)?> </th>
        <th scope="col">Faltas (I/J/R) <?= nombremes($messeguimientoAnterior)?> </th>
        <th scope="col">Faltas Totales </th>
        <th scope="col">Edad </th>
        <th scope="col">F. Revisión (<?= nombremes($messeguimiento)?>)</th>
            </tr>
            </thead>
        
        <?php }





    extract(consultaBDunica("SELECT max(substring(TipoAn,1,2)) AS NProtocolo FROM tanotacionesabs 
    WHERE substring(TipoAn,1,2)<7 AND AlumnoIdAn='$IdAlumno'")); 
    extract(consultaBDunica("SELECT TipoAn AS PProtocolo FROM tanotacionesabs 
    WHERE substring(TipoAn,1,2)='$NProtocolo' AND AlumnoIdAn='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasI) as HFI FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimiento and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasJ) as HFJ FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimiento and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasR) as HFR FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimiento and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasI) as HFIa FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimientoAnterior and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasJ) as HFJa FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimientoAnterior and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasR) as HFRa FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimientoAnterior and AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasI) as HFITotal FROM tfaltas 
    WHERE AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasJ) as HFJTotal FROM tfaltas 
    WHERE AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT sum(HorasR) as HFRTotal FROM tfaltas 
    WHERE AlumnoIdFa='$IdAlumno'"));
    extract(consultaBDunica("SELECT max(FechaActualizacion) as Frevision FROM tfaltas 
    WHERE MONTH(FechaFa)=$messeguimiento")); 
    unset($CorreoTutorAn); 
    extract(consultaBDunica("SELECT CorreoTutorAn FROM tanotacionesabs 
    WHERE OtraInformacion='$messeguimiento' and AlumnoIdAn='$IdAlumno'"));

        if ($a % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
        ?>
       
        
        <tr class="<?php echo $colorb ?>" >
        
        <th scope="row"><input type="checkbox" name="checkboxid[]" value="<?= $IdAlumno ?>" ></th>
        <td><a href="01absentismoalumno.php?g_idalumno=<?= $IdAlumno ?>" target="_blank" ><?= $Alumno ?></a></td>
        <td><?= $Unidad ?></td>
        <td><?= inputcambiarestado("ABS_Seguimiento",'TNo',"table-primary","SI","NO"); ?> </td>
        <td>
            <?php
            if ($CorreoTutorAn=="S")
            {echo $icono_mail_enviado;}
            else {echo '<span class="badge bg-warning"> P </span>';}
            ?>
        </td>
        <td> <?= $PProtocolo ?>    </td>
        <td> <?= resaltarnumero($HFI,$TopeI).' / '.resaltarnumero($HFJ,$TopeJ).' / '.resaltarnumero($HFR,$TopeR) ?>    </td>
        <td> <?= resaltarnumero($HFIa,$TopeI).' / '.resaltarnumero($HFJa,$TopeJ).' / '.resaltarnumero($HFRa,$TopeR) ?>    </td>
        <td> <?= resaltarnumero($HFITotal,$TopeI*mesesdecurso()).' / '.resaltarnumero($HFJTotal,$TopeJ*mesesdecurso()).' / '.resaltarnumero($HFRTotal,$TopeR*mesesdecurso()) ?>    </td>
        <td> <?= calcularedadformato($FechaNac,16) ?> </td>
        <td> <?= darfechaesp($Frevision) ?> </td>
        </tr>
        
        <?php
        $a=$a+1;  
        }
    
    ?>
    </tbody>  
    </table>
</div>
  
  
  </form>
  </div>
  </div>
  </center>
  <br>  <br> 
  
  </body>
  </html>



