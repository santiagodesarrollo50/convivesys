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
$_SESSION['Procedencia']="02absentismopendiente.php";
$curso=$_SESSION['PCurso'];  
  
//Aviso cuando no se ha podido realizar la acción seleccionada
if ($_SESSION['aviso']!=0)
{ $textoaviso=$ArrayAvisos[$_SESSION['aviso']];
    echo '<script> alert("'.$textoaviso.'") </script>';
    $_SESSION['aviso']=0;
}
?>
 
 
  <center>
   <div class="container">
    <a href="02disciplinapendiente.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="02absentismopendiente.php"><button type="button" class="btn btn-info btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br><br><br><br>

<!-- MOSTRAR LOS ANOTACIONES ABSENTISMO    -->
    
    <div class="col-md-12 mx-auto bg-light rounded p-4">    
    <div>
        <h4 class="text-center font-weight-bold">ANOTACIONES DE ABSENTISMO PENDIENTES DE TRAMITAR:</h4>
     </div>     
    <form method="POST" >        
    <div id="menuopciones">
    <input type="submit" name="opcionanotaciones" value="Modificar Anotación" class="btn btn-primary" 
    formaction="03modificaranotacionabs.php">
    <input type="submit" name="opcionanotaciones" value="Enviar Anotación al Tutor" 
    class="btn btn-primary" formaction="10enviocorreos.php">
    <input type="submit" name="opcionanotaciones" value="Borrar Anotación" class="btn btn-danger" formaction="04borraranotacionabs.php">
    </div>

    <table  class="table table-sm align-middle middle table-hover table-striped"  id="tablaregistros">
        <thead>
            <tr>
            <th scope="col"></th>
            <th scope="col">Alumno</th>
            <th scope="col">Unidad</th>
            <th scope="col">Seguimiento Abs.</th>
            <th scope="col">Edad</th>
            <th scope="col">Estado</th>
            <th scope="col">Fecha Anotación</th>
            <th scope="col">Tipo Anotación</th>
            <th scope="col">Anotación</th>
            <th scope="col">Obs.</th>
            <th scope="col">Correo Tutor</th>
            <th scope="col">Reg. Séneca</th>
            </tr>
        </thead>        
        <tbody> 
    
    <?php    
    // Busca todas las anotaciones de absentismo pendientes
    $consulta6 = "SELECT * FROM tanotacionesabs WHERE EstadoAn = 'P' ORDER BY FechaAn DESC";
    $resSelect6 = $conn->prepare($consulta6);
    $resSelect6->execute();    
    $a=0;
    while($row6 = $resSelect6->fetch()) {        
        unset($ABS_Seguimiento,$Unidad);
        extract($row6);     
        extract(consultaBDunica("SELECT * from talumnos where IdAlumno='$AlumnoIdAn'"));
        extract(consultaBDunica("SELECT ABS_Seguimiento from tnotasalumno where AlumnoIdNo='$AlumnoIdAn'"));
        extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
		    ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));
    ?>
       
       <div> <a name="<?php echo "marcadorAbs".$IdAnotacion; ?>"></a> </div>
        
        <tr class="<?php echo $color ?>" >
        
        <th scope="row"><input type="radio" name="anotacionid[]" value="<?php echo $IdAnotacion; ?>" ></th>
        <td><a href="01absentismoalumno.php?g_idalumno=<?= $AlumnoIdAn ?>" ><?= $Alumno ?></a></td>
        <td><?= $Unidad ?></td>
        
        <td><?php echo inputcambiarestado("ABS_Seguimiento","TNo",$color,"SI","NO"); ?> </td>
        <td> <?php echo calcularedadformato($FechaNac,16) ?> </td>
        <td> <?php echo inputcambiarestado("EstadoAn",'TAn',$color); ?>    </td>
        <td><?= darfechaesp($FechaAn) ?>   </td>
        
        <td><?= substr($TipoAn,3) ?></td>
        <td>
          <?php
          if (strlen($FechaCita)>0)
          {$Anotacionplus=$Anotacion."\n La familia ha sido citada el día ".darfechaesp($FechaCita).
          " a las ".$HoraCita." horas. ";}
          else
          {$Anotacionplus=$Anotacion;}
          botonpopover("Anotación", $Anotacionplus, $color,50) ?>
        </td>
        
        <td>
            <?php botonpopover("Observaciones", $ObservacionesAn, $color, 20) ?>
        </td>
        <td>
            <?php
            if ($CorreoTutorAn=="S")
            {echo $icono_mail_enviado;}
            else {echo '<span class="badge bg-warning"> P </span>';}
            ?>
        </td>
        <td>  
        <?php if (substr($TipoAn,0,2)<7) 
        {echo inputcambiarestado("SenecaAn",'TAn',$color);}
        else 
        {echo $icono_mail_nodisponible;}
        ?>  </td>
        </tr>
        
        <?php
        $a=$a+1;  
    }
    
    ?>
    
    
    </tbody>  
    </table>
    </form>
    </div>
    
    
    </center>
    <br>
    <br>
    <br>
    
    </body>
    </html>







    
    