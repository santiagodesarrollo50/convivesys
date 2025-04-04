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
    
    $_SESSION['Procedencia']="21unidaddisciplina.php";
   
   
    
  if(isset($_POST['cambiarunidad']))
    {
        $IdUnidads=$_POST['funidad'];  
        extract(consultaBDunica("SELECT Unidad as Unidads FROM tunidades WHERE IdUnidad='$IdUnidads'"));
    }
    
    
   
    
  
  
    //Aviso cuando no se ha podido realizar la acción seleccionada
    if ($_SESSION['aviso']!=0)
    { $textoaviso=$ArrayAvisos[$_SESSION['aviso']];
        echo '<script> alert("'.$textoaviso.'") </script>';
        $_SESSION['aviso']=0;
    }
    
    ?>
 
 
    <center>
   <div class="container">
    <a href="21unidaddisciplina.php"><button type="button" class="btn btn-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="21unidadabsentismo.php"><button type="button" class="btn btn-outline-info btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br>

<br>

    
    <br>
    <br>

      
      <div class="container">
    <h3> <b>PARTES DE DISCIPLINA DE: <?= $Unidads ?> </b></h3>
    </div>
    
    <form method="POST" >
    
    <input type="submit" name="cambiarunidad" value="Cambiar Unidad" 
    class="btn btn-primary" formaction="21unidaddisciplina.php">
    
    <?php 
    // Cargamos opciones Unidades
   $consulta = "SELECT IdUnidad, Unidad FROM tunidades where CursoUn='$curso' ORDER BY Orden";
    $resSelect3 = $conn->prepare($consulta); 
    $resSelect3->execute(); 
    ?>

    <select  name="funidad">
        <option value=""></option>
        <?php
            while($row3 = $resSelect3->fetch()) {
                if ($row3['IdUnidad']==$IdUnidads) {
                    $seleccionado="selected"; 
                } else {
                    $seleccionado ="";
                }            
        ?>
                    <option value="<?php echo $row3['IdUnidad'];?>" 
                    <?php echo $seleccionado ?>><?php echo $row3['Unidad'];?></option>
            
        <?php 
            }
        ?>
    </select>    

 

    <table  class="table table-hover" id="tablaregistros">
      
       
   
<?php
   

    //lista al alumnado 
    $consulta3 = "SELECT * FROM tpartes WHERE AlumnoIdPa in
    (select IdAlumno from talumnos inner join talumnounidad on idalumno=alumnoidalun 
    where UnidadIdAlUn='$IdUnidads')
     order by FechaPa desc";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    
    $a=0;
    while($row3 = $resSelect3->fetch()) {
                
        extract($row3);    
        extract(consultaBDunica("SELECT Alumno, IdAlumno, CorreoTutor1, CorreoTutor2 from talumnos where IdAlumno='$AlumnoIdPa'"));    
        // Busca el nombre del profesor
        extract(consultaBDunica("SELECT Profesor FROM tprofesores WHERE IdProfesor = '$ProfesorIdPa'"));        
        unset($LetraSancionxAlumno);
        extract(consultaBDunica("SELECT * FROM tsancionparte WHERE ParteIdSaPa = '$IdParte'"));
        if(isset($LetraSancionxAlumno)) {
            $LetrasSanciones="SI";
        } else {
            $LetrasSanciones="";
        }
    
        if ($a % 7 == 0) {
        ?>
        <thead>
        <tbody>
        <tr>
        <th scope="col">Alumno</th>
        <th scope="col">Fecha</th>
        <th scope="col">Hora</th>
        <th scope="col">Sanción</th>
        <th scope="col">Profesor Implicado</th>
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



    

    if ($a % 2 == 0) {
        $color="table-primary";     
    } else {
        $color="table-secondary";
    }
    ?>


<tr class="<?php echo $color ?>" >

<td><a href="01disciplinaalumno.php?g_idalumno=<?= $IdAlumno ?>" ><?= $Alumno ?></a></td>
<td><?= darfechaesp($FechaPa) ?> <div> <a name="<?php echo "marcadorP".$row2['IdParte']; ?>"></a> </div>  </td>
<td><?= $HoraPa ?></td>

<td>
<?php
    if (strlen($LetrasSanciones)==0)
    {echo $icono_sancion_pendiente;
    }
    else
    {echo '<span class="badge bg-info"><H6>'.$LetrasSanciones.'</H6></span>';}
    ?>
</td>

<td><?= $Profesor ?></td>
<td> <?php botonpopover("Hechos del Parte", $HechosPa, $color,50) ?> </td>
<td><?= $TipoPa ?></td>
<td>
    <?php botonpopover("Observaciones", $ObservacionesPa, $color,20) ?>
</td>
<td>
    <?php
    if(strlen($ComFamiliaPa)<=0)
    { echo '<span class="badge bg-warning"> P </span>'; }
    else
    { botonpopover("Comunicación con la familia", $ComFamiliaPa, $color,20); } 
    ?>
</td>
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
    //         MUESTRA LAS SANCIONES DE la unidad 

    // Busca en la tabla tsanciones todos las sanciones 
    $consulta5 = "SELECT * FROM tsanciones WHERE AlumnoIdSa in
    (select IdAlumno from talumnos inner join talumnounidad on idalumno=alumnoidalun 
    where UnidadIdAlUn='$IdUnidads') ORDER BY FechaSa DESC";
    $resSelect5 = $conn->prepare($consulta5);
    $resSelect5->execute(); 
    ?>
    
    <br><br><br>
    
     <div class="container">
     <h3> <b>SANCIONES EN: <?= $Unidads ?> </b> </h3>
    </div>
    
    
     
	
     <!-- Cabecera tabla de Sanciones-->
    
    <table id="tablaregistros" class="table table-hover">
   
  
    
    
<?php
   
  
    //Imprime en pantalla las filas de sanciones del alumno seleccionado
    $b=0;
    while($row5 = $resSelect5->fetch())
    {
        extract($row5);
     
     // Busca en la tabla talumnos los datos del alumno 
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$AlumnoIdSa'"));
    
     
    
    
    
    
    
    if ($b % 6 == 0)//Cabeceras cada 4 filas
        {
        ?>
          <thead>

          <tbody>

          <tr>
    <th scope="col">Alumno</th>
    <th scope="col">F. acuerdo</th>
    <th scope="col">Hechos</th>
    <th scope="col">Tipo Sanción</th>
    <th scope="col">F. Inicio</th>
     <th scope="col">F. Fin</th>
     <th scope="col">Dias de Sancion</th>
     <th scope="col">Obs.</th>
     <th scope="col">Com. Familia</th>
    </tr>       
       
            
       </thead>     
       
        <?php
        }
        
        if ($b % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
        ?>
        
        
        
    <tr class="<?php echo $colorb?>">
        <td><a href="01disciplinaalumno.php?g_idalumno=<?= $IdAlumno ?>" ><?= $Alumno ?></a></td>
        <td><?= darfechaesp($FechaSa) ?> <div> <a name="<?php echo "marcadorS".$row5['IdSancion']; ?>"></a> </div>    </td>
        <td>  <?php botonpopover("Hechos de la Sanción", $HechosSa, $colorb,50) ?>  </td>
        <td><?= $TipoSa ?></td>
        <td><?= darfechaesp($FechaInicio) ?></td>
        <td><?= darfechaesp($FechaFin) ?></td>
        <td><?= $DiasSancion ?></td>
        <td>  <?php botonpopover("Observaciones", $ObservacionesSa, $colorb,20) ?>  </td>
        <td>  <?php
            if(strlen($ComFamiliaSa)<=0)
            { echo '<span class="badge bg-warning"> P </span>'; }
            else
            { botonpopover("Comunicación con la familia", $ComFamiliaSa, $colorb,20); } 
        ?>  </td>
        </tr>
  
      <?php
        
  
    $b=$b+1;  }
    
    
    //Fin imprimir partes del alumno seleccionado
  
  

  
      
  ?>




    </tbody>
    </table>
  
  
  </form>
 
  </center>
  <br>  <br> 
  <br>  <br>
  

    
    </body>
    </html>







    
    