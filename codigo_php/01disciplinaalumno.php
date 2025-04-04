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


      
      
      //Carga idalumno según procedencia    
    if ($_SESSION['Procedencia']=="02disciplinapendiente.php" || 
        $_SESSION['Procedencia']=="21unidaddisciplina.php"    ||
        $_SESSION['Procedencia']=="18sancionesaseneca.php" ||
        $_SESSION['Procedencia']=="01disciplinaalumno.php")     {
            $s_idalumno=$_GET['g_idalumno'];   
            $_SESSION['s_idalumno']=$s_idalumno;
    } else {
        $s_idalumno=$_SESSION['s_idalumno'];}
    
    
    $_SESSION['Procedencia']="01disciplinaalumno.php?g_idalumno=".$s_idalumno;

    $curso=$_SESSION['PCurso'];

    // Busca en la tabla talumnos los datos del alumno seleccionado
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
    
    extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
		ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));

    //esta variable de sesión solo se usa para la opción nuevo parte
    $_SESSION["s_alumno_unidad"]=$Alumno.' ('.$Unidad.')';
  
    // Busca en la tabla tpartes todos los partes del alumno seleccionado
    $consulta2 = "SELECT * FROM tpartes WHERE AlumnoIdPa = '$IdAlumno' ORDER BY FechaPa DESC";
    $resSelect2 = $conn->prepare($consulta2);
    $resSelect2->execute();

  
  
    //Aviso cuando no se ha podido realizar la acción seleccionada
    if ($_SESSION['aviso']!=0)
    { $textoaviso=$ArrayAvisos[$_SESSION['aviso']];
        echo '<script> alert("'.$textoaviso.'") </script>';
        $_SESSION['aviso']=0;
    }
    
    
    
    
    
  ?>
      <center>
    <div class="container">
    <a href="01datosalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DATOS DEL ALUMNO
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01disciplinaalumno.php"><button type="button" class="btn btn-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01absentismoalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br>





    
    
    <!-- MOSTRAR LOS PARTES    -->
    <div class="container">
    <h3> <b>PARTES DE DISCIPLINA DEL ALUMNO: <br> </b><?= $Alumno ?> - (<?= $Unidad ?>) </h3>
    </div>
    
    
    
    
    <!-- Formulario de opciones por partes-->
    <form name "modificarparte" id="modificarparte" method="POST" >
    <div id="menuopciones">
    <input type="submit" name="opcionparte" value="Modificar Parte" class="btn btn-primary" formaction="03modificarparte.php"
      title="Modificar el parte que se haya seleccionado. Solo se puede seleccionar uno para esta función">
    <input type="submit" name="opcionparte" value="Nuevo Parte" class="btn btn-secondary" formaction="02nuevoparte.php"
    title="Rellenar un nuevo parte para este alumno">
    <input type="submit" name="opcionparte" value="Sancionar Partes" class="btn btn-primary" formaction="05sancionarpartes.php"
    title="Seleccione uno o varios partes y pulse este botón para crear una sanción para esos partes.
      Otra opción es seleccionar un parte y una sanción (asociado a otro parte), y pulsar este botón para asociar el parte seleccionado también a la sanción">
    <input type="submit" name="opcionparte" value="Enviar Partes al Tutor" class="btn btn-secondary" formaction='10enviocorreos.php?tipoenviog=CorreoParteATutor'
    title="Envía un correo electrónico al tutor del grupo informando del parte">
    <input type="submit" name="opcionparte" value="Enviar Partes a la Familia" class="btn btn-primary" 
           formaction='10enviocorreos.php?tipoenviog=CorreoParteAFamilia'
    title="Envía un correo electrónico a los familiares (padre y madre) informando del parte">
    <input type="submit" name="opcionparte" value="Imprimir" class="btn btn-secondary" formaction="22imp_disciplinaalumno.php?g_idalumno=<?=$IdAlumno ?>"
    title="Descarga un fichero PDF con la información de esta pantalla">
    <input type="submit" name="opcionparte" value="Borrar Parte" class="btn btn-danger" formaction="04borrarparte.php"
    title="Borra el parte que se haya seleccionado. Solo se puede seleccionar uno para esta función">
    </div>
   
    <!-- Cabecera tabla de partes-->
    <table  class="table table-hover" id="tablaregistros">
      
     
        
   
<?php
   
  //Imprime en pantalla los partes del alumno seleccionado
    $a=0;
    while($row2 = $resSelect2->fetch())
    {
        extract($row2);
        
        // Busca el nombre del profesor
        extract(consultaBDunica("SELECT Profesor FROM tprofesores WHERE IdProfesor = '$ProfesorIdPa'"));
        
    
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
        
        
        if ($a % 6 == 0)//Cabeceras cada 4 filas
        {
        ?>
          <thead>
    <tbody> 
          <tr>
    <th scope="col"></th>
    <th scope="col">Fecha</th>
    <th scope="col">Hora</th>
    <th scope="col">Estado</th>
    <th scope="col">Sanción</th>
    <th scope="col">Profesor implicado <br><small>(Asignatura)</small></th>
    <th scope="col">Hechos</th>
    <th scope="col">Tipo Parte</th>
    <th scope="col">Obs.</th>
    <th scope="col">Com. Familia</th>
    <th scope="col">Correo Tutor</th>
    <th scope="col">Correo Familia</th>
        </tr>
        </thead>
        
        
       
        <?php
        }
        
        if ($a % 2 == 0) 
        { $color="table-primary";     }//color para las filas pares
        else {$color="table-secondary";}//color para las filas impares
        ?>
       
        
        <tr class="<?php echo $color ?>" >
        
        <th scope="row"><input type="checkbox" name="checkboxid[]" value="<?php echo $row2['IdParte']; ?>" ></th>
        <td><?= darfechaesp($FechaPa) ?> <div> <a name="<?php echo "marcadorP".$row2['IdParte']; ?>"></a> </div>  </td>
        <td><?= $HoraPa ?></td>
       <td>  <?php echo inputcambiarestado("EstadoPa",'TPa',$color); ?> </td>
        <td>
          <?php              
            if (strlen($LetrasSanciones)==0) {
              echo $icono_sancion_pendiente;
            } else {
              echo '<span class="badge bg-info"><H6>'.$LetrasSanciones.'</H6></span>';
            }
          ?>
            
            
        </td>
        <td><?= $Profesor ?> <br><small>(<?= $Asignatura ?>)</small></td>
        <td>
          <?php botonpopover("Hechos del Parte", $HechosPa, $color,50) ?>
        </td>
        <td><?= $TipoPa ?></td>
        <td>
            <?php botonpopover("Observaciones", $ObservacionesPa, $color, 20) ?>
        </td>
        <td>  <?php
            if(strlen($ComFamiliaPa)<=0)
            { echo '<span class="badge bg-warning"> P </span>'; }
            else
            { botonpopover("Comunicación con la familia", $ComFamiliaPa, $color,20); } 
        ?>  </td>
        
        <td>
            <?php
            if ($CorreoTutorPa=="S")
            {echo $icono_mail_enviado;}
            else {echo '<span class="badge bg-warning"> P </span>';}
            ?>
        </td>
        <td>
            <?php
            if(!filter_var($CorreoTutor1, FILTER_VALIDATE_EMAIL) && !filter_var($CorreoTutor2, FILTER_VALIDATE_EMAIL))
            { echo $icono_mail_nodisponible;}
            else {
                if ($CorreoFamPa=="S")
                {echo $icono_mail_enviado;}
                else 
                { echo '<span class="badge bg-warning"> P </span>';}
            }
            ?>
            </td>
        </tr>
        
        <?php
        $a=$a+1;  
        }
    
    ?>
    
    
    </tbody>  
    </table>

 

<?php
    //         MUESTRA LAS SANCIONES DEL ALUMNO 

    // Busca en la tabla tsanciones todos las sanciones del alumno seleccionado
    $consulta5 = "SELECT * FROM tsanciones WHERE AlumnoIdSa = '$IdAlumno' ORDER BY FechaSa DESC";
    $resSelect5 = $conn->prepare($consulta5);
    $resSelect5->execute(); 
    ?>
    
    <br><br><br>
    
     <div class="container">
     <h3> <b>SANCIONES DEL ALUMNO: </b> <br> <?= $Alumno ?> - (<?= $Unidad ?>) </h3>
    </div>
    
    
    <!-- Formulario de opciones por sanción -->
   
    <div id="menuopciones">
    <input type="submit" name="opcionsancion" value="Modificar Sanción" class="btn btn-success" formaction="09modificarsancion.php"
    title="Modifica la sanción que se haya seleccionado. Solo se puede seleccionar una sanción para esta función.">
    <input type="submit" name="opcionsancion" value="Desvincular Sanción de partes" class="btn btn-secondary" formaction="08desvincularsancion.php"
    title="Seleccione una sanción y un parte que estén vinculados, al pulsar este botón se desvincularán.">
    <input type="submit" name="opcionsancion" value="Generar PDF Comunicación" class="btn btn-success" formaction="16pdfcomunicacionsancion.php"
    title="Descarga un fichero PDF con la comunicación a la familia de la sanción seleccionada.">
    <input type="submit" name="opcionsancion" value="Generar PDF Seguimiento Aula Convivencia" class="btn btn-secondary" formaction="16pdf_seguimientoaulaconvivencia.php"
    title="Descarga un fichero PDF con el seguimiento para el Aula de Convivencia de la sanción seleccionada.">    
    <input type="submit" name="opcionsancion" value="Borrar Sanción" class="btn btn-danger" formaction="07borrarsancion.php"
    title="Borra la sanción seleccionada. Solo se puede seleccionar una sanción para esta función.">
    </div>
   
   
	
     <!-- Cabecera tabla de Sanciones-->
    
    <table id="tablaregistros" class="table table-hover">
   
  
    
    
<?php
   
  
    //Imprime en pantalla las filas de sanciones del alumno seleccionado
    $b=0;
    while($row5 = $resSelect5->fetch())
    {
        extract($row5);
     
     
     //Busca letra de la sanción por alumno
    extract(consultaBDunica("SELECT * FROM tsancionparte WHERE SancionIdSaPa = '$IdSancion'"));
    
    
    
    if ($b % 6 == 0)//Cabeceras cada 4 filas
        {
        ?>
          <thead>
		  <tbody> 
          <tr>
    <th scope="col"></th>
    <th scope="col">Id</th>
    <th scope="col">F. acuerdo</th>
    <th scope="col">Estado</th>
    <th scope="col">Hechos</th>
    <th scope="col">Tipo Sanción</th>
    <th scope="col">F. Inicio</th>
     <th scope="col">F. Fin</th>
     <th scope="col">Días de Sanción</th>
     <th scope="col">Obs.</th>
     <th scope="col">Obs. en la comunicación de Sanción</th>
     <th scope="col">Com. Familia</th>
    <th scope="col">Tareas Pedidas</th>
    <th scope="col">Tareas Dadas</th>
    <th scope="col">Registro Séneca</th>
    <th scope="col">Com. Dada</th>
    <th scope="col">Com. Pasen</th>
    <th scope="col">Com. Archivada</th>        
     </tr>       
       
            
       </thead>     
       
        <?php
        }
        
        if ($b % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
        ?>
        
        
        
      <tr class="<?php echo $colorb?>">
      
    
    <td align="center" scope="row"> 
    <input  type="checkbox" name="checkboxsancion[]" value="<?php echo $IdSancion; ?>" ></td>
    <td><span class="badge bg-info"><H6><?= $LetraSancionxAlumno ?></H6></span> </td>
    <td><?= darfechaesp($FechaSa) ?> <div> <a name="<?php echo "marcadorS".$IdSancion; ?>"></a> </div>    </td>
    
     <td>  <?php echo inputcambiarestado("EstadoSa",'TSa',$colorb); ?> </td>
    <td>
      <?php botonpopover("Hechos de la Sanción", $HechosSa, $colorb,50) ?>
    </td>
    
    <td><?= substr($TipoSa,3)  ?></td>
    
    <td><?= darfechaesp($FechaInicio) ?></td>
    <td><?= darfechaesp($FechaFin) ?></td>
    <td><?= $DiasSancion ?></td>
    <td>
        <?php botonpopover("Observaciones", $ObservacionesSa, $colorb,20) ?>
     </td>
     <td>
        <?php botonpopover("Observaciones", $observacionescomsancion, $colorb,20) ?>
     </td>
     <td>  <?php
            if(strlen($ComFamiliaSa)<=0)
            { echo '<span class="badge bg-warning"> P </span>'; }
            else
            { botonpopover("Comunicación con la familia", $ComFamiliaSa, $colorb,20); } 
    ?>  </td>
    <td>  <?php echo inputcambiarestado("PedirTar",'TSa',$colorb); ?> </td>
    <td>  <?php echo inputcambiarestado("DadasTar",'TSa',$colorb); ?> </td>
    <td>  <?php echo inputcambiarestado("ReSeneca",'TSa',$colorb); ?> </td>
    <td>  <?php echo inputcambiarestado("DadasCom",'TSa',$colorb); ?> </td>  
    <td>  <?php echo inputcambiarestado("ComPasen",'TSa',$colorb); ?> </td>
    <td>  <?php echo inputcambiarestado("ArchiCom",'TSa',$colorb); ?> </td>
    </tr>
  
      <?php
        
  
    $b=$b+1;  }
    
    
    //Fin imprimir partes del alumno seleccionado
  
  ?>


  
   
  </tbody>  
</table>
 </form>


    </center>
  
  <br>  <br> <br>  <br>
</body>
</html>