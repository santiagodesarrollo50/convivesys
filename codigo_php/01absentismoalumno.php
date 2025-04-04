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
      
      
      //Carga idalumno    
    if (isset($_GET['g_idalumno'])) {
        $s_idalumno=$_GET['g_idalumno'];   
        $_SESSION['s_idalumno']=$s_idalumno;
    } else {
        $s_idalumno=$_SESSION['s_idalumno'];
    }    
    
    $_SESSION['Procedencia']="01absentismoalumno.php";
    $curso=$_SESSION['PCurso'];    
	
    // Busca en la tabla talumnos los datos del alumno seleccionado
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
    ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));
    extract(consultaBDunica("SELECT ABS_Seguimiento FROM tnotasalumno 
    WHERE AlumnoIdNo='$s_idalumno'"));
    $_SESSION['s_alumno_unidad']=$Alumno.' ('.$Unidad.')';

 //Aviso cuando no se ha podido realizar la acción seleccionada
    if ($_SESSION['aviso']!=0)    {
        $textoaviso=$ArrayAvisos[$_SESSION['aviso']];
        echo '<script> alert("'.$textoaviso.'") </script>';
        $_SESSION['aviso']=0;
    }


    
 
  if (isset($_POST['generapdfabs'])) {
        switch (substr($_POST['pasoprotocolo'],0,2)) {
            case 1:
                header('location:16pdfABS1carta.php');
                break;
            case 2:
                header('location:16pdfABS1contrato.php');
                break;
            case 3:
                header('location:16pdfABS2carta.php');
                break;
            case 4:
                header('location:16pdfABS2contrato.php');
                break;
            case 5:
                header('location:16pdfABSanexo2.php');
                break;
            case 6:
                //header('location:16pdfABSanexo3.php');
                break;    
        }
  }

?>
    <div class="container">
    <form method="POST" >    
    
<center>    
    <a href="01datosalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DATOS DEL ALUMNO
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01disciplinaalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01absentismoalumno.php"><button type="button" class="btn btn-info btn-lg" >
    ABSENTISMO
    </button></a>
    <br> <br> <a href="18absentismoseguimiento.php"><button 
    type="button" class="btn btn-dark" >
    Seguimiento mensual del absentismo
    </button></a>
    
    <br>    <br>    <br>


    <h3> <?= $Alumno ?> - (<?= $Unidad ?>) </h3><br>
    <h5> Edad <?= calcularedadformato($FechaNac,16); ?> años
    &nbsp;&nbsp;&nbsp; Fecha Nac: <?= darfechaesp($FechaNac)?>
    </h5>
    <br>
        <h4> 
    Seguimiento mensual del Absentismo de este alumno: <?php echo inputcambiarestado("ABS_Seguimiento","TNo","table-primary","SI","NO"); ?>
    <a name="<?php echo "marcadorABS".$IdAnotacion; ?>"></a>
    </h4>
    
    
    
    <br>    <br>

<!-- MOSTRAR LOS ANOTACIONES ABSENTISMO    -->
    
    <div class="col-md-12 mx-auto bg-light rounded p-4">
    
    <h3> <b>ANOTACIONES DE ABSENTISMO: <br>  </b><?= $Alumno ?> - (<?= $Unidad ?>) </h3>
    
    
    
    
    
    <!-- Formulario de opciones por partes-->
    
    <div id="menuopciones">
    <input type="submit" name="opcionanotaciones" value="Modificar Anotación" class="btn btn-primary" 
    formaction="03modificaranotacionabs.php">
    <input type="submit" name="opcionanotaciones" value="Nueva Anotación" class="btn btn-secondary" 
    formaction="02nuevaanotacionabs.php">
    <input type="submit" name="opcionanotaciones" value="Enviar Anotación al Tutor" 
    class="btn btn-primary" formaction="10enviocorreos.php">
    <input type="submit" name="opcionparte" value="Imprimir" class="btn btn-secondary" formaction="22imp_absentismoalumno.php?g_idalumno=<?=$IdAlumno ?>">
    <input type="submit" name="opcionanotaciones" value="Borrar Anotación" class="btn btn-danger" formaction="04borraranotacionabs.php">
    </div>

    <table  class="table table-hover" id="tablaregistros">

<?php
   
  //Imprime en pantalla las anotaciones de absentismo de un alumnno    
    // Busca todas las anotaciones de absentismo del alumno seleccionado
    $consulta6 = "SELECT * FROM tanotacionesabs WHERE AlumnoIdAn = '$IdAlumno' ORDER BY FechaAn DESC";
    $resSelect6 = $conn->prepare($consulta6);
    $resSelect6->execute();
    
    $a=0;
    while($row6 = $resSelect6->fetch())
    {
        extract($row6);
     
        
        
        if ($a % 6 == 0) {//Cabeceras cada 4 filas
        ?>
            <thead>
                <tbody> 
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Tipo Anotación</th>
                        <th scope="col">Anotación</th>
                        <th scope="col">Obs.</th>
                        <th scope="col">Correo Tutor</th>
                        <th scope="col">Reg. Séneca</th>
                    </tr>
            </thead>       
        <?php
        }
        
        if ($a % 2 == 0) 
        { $color="table-primary";     }//color para las filas pares
        else {$color="table-secondary";}//color para las filas impares
        ?>
       
        
        <tr class="<?php echo $color ?>">
        
        <th scope="row"><input type="radio" name="anotacionid[]" value="<?php echo $IdAnotacion; ?>" ></th>
        <td><?= darfechaesp($FechaAn) ?> <div> <a name="<?php echo "marcadorA".$IdAnotacion; ?>"></a> </div>  </td>
        <td> <?php echo inputcambiarestado("EstadoAn",'TAn',$color); ?>    </td>
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
    
    </form>
    </tbody>  
    </table>
    </div>
    <br>
    <br>
    <br>










<!-- MOSTRAR LOS RESUMEN FALTAS MENSUALES    -->
    <div class="col-md-9 mx-auto bg-light rounded p-4">    
    <h3> <b>RESUMEN MENSUAL DE FALTAS DE ASISTENCIA: <br> </b><?= $Alumno ?> - (<?= $Unidad ?>) </h3>
    
    
    
    <!-- Formulario de opciones resumen mensual faltas-->
    <div id="menuopciones">
    <input type="submit"  value="Enviar petición de justificación al tutor" class="btn btn-primary" 
    formaction="10enviocorreos.php" title="Envía un correo-e al tutor del grupo pidiendole información sobre el motivo de las faltas del alumno en el mes seleccionado.">
    <input type="hidden" name="TipoEnvio" value="CorreoTutorJustificaFaltas" >
    <input type="hidden" name="IdEnvio" value="<?=$IdAlumno?>">
    <input type="submit" value="Ver detalle de faltas del mes seleccionado" class="btn btn-secondary" 
    formaction="20faltasmesalumno.php" title="Seleccionar abajo el mes. Al pulsar el botón se verá el detalle de las faltas por cada día del mes seleccionado.">
    </div>
   

    <table  class="table table-hover" id="tablaregistros">
      
     <thead>
     
          <tr>
    <th scope="col"></th>
    <th scope="col">Mes</th>
    <th scope="col">F. Injustificada</th>
    <th scope="col">F. Justificada</th>
    <th scope="col">Retraso</th>
    <th scope="col">Revisado</th>
    <th scope="col">Petición justificación tutor </th>
        </tr>
        </thead>
     <tbody> 
   
<?php
   

    
    $Meses=Array(9 => 'Sep',10 => 'Oct',11 => 'Nov',12 => 'Dic',1 => 'Ene',
    2 => 'Feb',3 => 'Mar',4 => 'Abr',5 => 'May',6 => 'Jun');

    $TopeI=$_SESSION['TopeI'];
    $TopeJ=$_SESSION['TopeJ'];
    $TopeR=$_SESSION['TopeR'];

    $a=0;
	$finicioc=iniciocursoacutal ( );
    foreach($Meses as $m => $mes)
    {
	
    unset($CorreoTutorAn,$Frevision);  
    extract(consultaBDunica("SELECT sum(HorasI) as HFI FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT sum(HorasJ) as HFJ FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT sum(HorasR) as HR FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT max(FechaActualizacion) as Frevision FROM tfaltas
    WHERE MONTH(FechaFa)=$m AND AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("SELECT CorreoTutorAn FROM tanotacionesabs
    WHERE OtraInformacion='$m' and AlumnoIdAn='$IdAlumno' AND FechaAn > '$finicioc'")); 

        if ($a % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
        ?>
       
        
        <tr class="<?php echo $colorb ?>" >
        
        <th scope="row"><input type="radio" name="OtroDato" value="<?php echo $m; ?>" ></th>
        <td><?= $mes ?> </td>
        <td><?= resaltarnumero($HFI,$TopeI) ?> </td>
        <td> <?= resaltarnumero($HFJ,$TopeJ) ?>    </td>
        <td><?= resaltarnumero($HR,$TopeR) ?></td>
        <td> <?= darfechaesp($Frevision) ?> </td>
        <td>
            <?php
            if ($CorreoTutorAn=="S")
            {echo $icono_mail_enviado;}
            else {echo '<span class="badge bg-warning"> P </span>';}
            ?>
        </td>
        
        </tr>
        
        <?php
        $a=$a+1;  
        }
    
    ?>
    </tbody>  
    </table>
</div>
  
  <br>  <br> 



 <!-- MOSTRAR LOS RESUMEN PROTOCOLO ABSENTISMO    -->
    
    
    
    <div class="col-md-8 mx-auto bg-light rounded p-4">
    <h3> <b>PASOS DEL PROTOCOLO DE ABSENTISMO: <br> </b><?= $Alumno ?> - (<?= $Unidad ?>)</h3>
    
    
    <!-- Formulario de opciones resumen mensual faltas-->
    <div id="menuopciones">
        <input type="submit" name="generapdfabs" value="Generar documentación del Protocolo de Absentismo" class="btn btn-primary" 
            title="Seleccionar abajo un paso del protocolo y después pulsar este botón.
Se generará la documentación correspondiente al paso del protocolo seleccionado.">
    </div>
   
    <h7> Para que quede registrado el paso del protocolo de absentismo es necesario
         primero grabar la anotación correspondiente</h7>
   

    <table  class="table table-hover" id="tablaregistros">
      
     <thead>
     
          <tr>
    <th scope="col"></th>
    <th scope="col">Paso del protocolo</th>
    <th scope="col">Fecha</th>
    <th scope="col">Estado</th>
    <th scope="col">Curso Escolar</th>
        </tr>
        </thead>
     <tbody>   
   
<?php
    
    // Busca todas las anotaciones de absentismo del alumno seleccionado
    $consulta16 = "SELECT F_IdTipoAnotacion FROM tdatos WHERE substring(F_IdTipoAnotacion,1,2)<7";
    $resSelect16 = $conn->prepare($consulta16);
    $resSelect16->execute();
    
    $a=0;
    while($row16=$resSelect16->fetch()) {
        extract($row16);    
        $FechaAn="--";
        $EstadoAn="--";
        extract(consultaBDunica("Select * FROM tanotacionesabs WHERE AlumnoIdAn='$IdAlumno' AND
        TipoAn='$F_IdTipoAnotacion' order by FechaAn desc"));         
        if ($a % 2 == 0) { 
            $color="table-primary";
        } else {
            $color="table-secondary";
        }
        ?>     
        <tr class="<?php echo $color ?>" >        
        <th scope="row"><input type="radio" name="pasoprotocolo" value="<?php echo $F_IdTipoAnotacion; ?>" ></th>
        <td><?= $F_IdTipoAnotacion ?> </td>
        <td><?php
            if ($FechaAn=="--") {
                echo $FechaAn ;
            } else {
                echo darfechaesp($FechaAn);
            } ?> </td>
        <td> <?php 
             if ($EstadoAn=="--") {
                echo $icono_mail_nodisponible;
             } else {
                 echo inputcambiarestado("EstadoAn",'TAn',$color);
             }
        ?>    </td>
        <td><?php if ($FechaAn=="--") { echo $FechaAn ;} 
        else {echo cursoescolar($FechaAn);} ?> </td>
        </tr>
        
        <?php
        $a=$a+1;  
    }
    
    ?>
    </tbody>  
    </table>
</div>
  </center>
  </body>
  </html>
  <br>  <br>  <br>  