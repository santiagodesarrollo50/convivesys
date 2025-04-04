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

$curso=$_SESSION['PCurso'];

    
    $_SESSION['Procedencia']="21unidadabsentismo.php";
   
   
    
  if(isset($_POST['cambiarunidad']))
    {
        $IdUnidads=$_POST['funidad'];  
        extract(consultaBDunica("SELECT Unidad as Unidads FROM tunidades WHERE IdUnidad='$IdUnidads'"));
    }
    $messeguimiento=date("n");
    $messeguimientoAnterior = $messeguimiento-1;
    if ($messeguimientoAnterior == 0) {
        $messeguimientoAnterior = 12;
    }
    
    //Numero de faltas tope para resaltar
    $TopeI=$_SESSION['TopeI'];
    $TopeJ=$_SESSION['TopeJ'];
    $TopeR=$_SESSION['TopeR'];
    
  
  
    //Aviso cuando no se ha podido realizar la acción seleccionada
    if ($_SESSION['aviso']!=0)
    { $textoaviso=$ArrayAvisos[$_SESSION['aviso']];
        echo '<script> alert("'.$textoaviso.'") </script>';
        $_SESSION['aviso']=0;
    }
    
    ?>
 
 
    <center>
   <div class="container">
    <a href="21unidaddisciplina.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="21unidadabsentismo.php"><button type="button" class="btn btn-info btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br>

<br>

    
    <br>
    <br>

      
      <div class="container">
    <h3> <b>ABSENTISMO DE: <?= $Unidads ?> </b></h3>
    </div>
    
    <form method="POST" >
    
    <input type="submit" name="cambiarunidad" value="Cambiar Unidad" 
    class="btn btn-primary" formaction="21unidadabsentismo.php">
    
    <?php 
    // Cargamos opciones Unidades
   $consulta = "SELECT Unidad, IdUnidad FROM tunidades where CursoUn='$curso' and
   (substring(Unidad,2,3)='FPB' or substring(Unidad,2,3)='ESO')";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>

    <select  name="funidad" >
     <option value=""></option>
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
    if ($row3['IdUnidad']==$IdUnidads)
    { $seleccionado="selected"; } else {$seleccionado ="";}
    
    ?>
        <option value="<?php echo $row3['IdUnidad'];?>" 
        <?php echo $seleccionado ?>><?php echo $row3['Unidad'];?></option>
    
        <?php 
        }
        ?>
       </select> 
    
    

 

    <table  class="table table-hover" id="tablaregistros">
      
       
   
<?php
   

    //lista al alumnado seleccionado para el seguimieto mensual 
    $consulta3 = "SELECT * FROM talumnos inner join talumnounidad on idalumno=alumnoidalUn
    WHERE UnidadIdAlUn='$IdUnidads' ORDER BY Alumno";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    
    $a=0;
    while($row3 = $resSelect3->fetch())
    {
        unset($HFI, $HFJ, $HFR, $HFIa, $HFJa, $HFRa,  $HFITotal, $HFJTotal, $HFRTotal,
         $NProtocolo, $PProtocolo, $Frevision, $ABS_Seguimiento);
        
        extract($row3);
    
        if ($a % 7 == 0) 
        { ?>
            <thead>
            <tbody>
          <tr>
    <th scope="col">Alumno</th>
    <th scope="col">Seguimiento Abs.</th>
    <th scope="col">Estado Abs.</th>
    <th scope="col">Faltas I/J/R <?= nombremes($messeguimiento)?> </th>
    <th scope="col">Faltas I/J/R <?= nombremes($messeguimientoAnterior)?> </th>
    <th scope="col">Faltas Totales </th>
    <th scope="col">Edad </th>
    <th scope="col">F. Nacimiento </th>
    <th scope="col">F. Revisión (<?= nombremes($messeguimiento)?>)</th>
        </tr>
        </thead>
        
    <?php }

    extract(consultaBDunica("SELECT ABS_Seguimiento FROM tnotasalumno 
    WHERE AlumnoIdNo='$IdAlumno'"));

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
    

        if ($a % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
        
       



        ?>
       
        
        <tr class="<?php echo $colorb ?>" >
        
        <td><a href="01absentismoalumno.php?g_idalumno=<?= $IdAlumno ?>" ><?= $Alumno ?></a></td>
        <td><?= inputcambiarestado("ABS_Seguimiento",'TNo',"table-primary","SI","NO"); ?> </td>
        <td> <?= $PProtocolo ?>    </td>
        <td> <?= resaltarnumero($HFI,$TopeI).' / '.resaltarnumero($HFJ,$TopeJ).' / '.resaltarnumero($HFR,$TopeR) ?>    </td>
        <td> <?= resaltarnumero($HFIa,$TopeI).' / '.resaltarnumero($HFJa,$TopeJ).' / '.resaltarnumero($HFRa,$TopeR) ?>    </td>
        <td> <?= resaltarnumero($HFITotal,$TopeI*mesesdecurso()).' / '.resaltarnumero($HFJTotal,$TopeJ*mesesdecurso()).' / '.resaltarnumero($HFRTotal,$TopeR*mesesdecurso()) ?>    </td>
        <td> <?= calcularedadformato($FechaNac,16) ?> </td>
        <td> <?= darfechaesp($FechaNac) ?> </td>
        <td> <?= darfechaesp($Frevision) ?> </td>
        </tr>
        
        <?php
        $a=$a+1;  
        }
    
    ?>
    </tbody>  
    </table>

  
  
  </form>
 
  </center>
  <br>  <br> 
  

    
    </body>
    </html>







    
    