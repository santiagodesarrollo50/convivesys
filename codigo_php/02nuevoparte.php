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


session_start();
$curso=$_SESSION['PCurso'];


  
  // Cuando pulsa grabar nuevo parte
    if (isset($_POST['submit']))
    {
    
    $alumnounidad_f = $_POST['alumno_unidad'];
    $fecha_f = $_POST['fecha'];
    $profesor_f = $_POST['profesor'];
    $asignatura_f = $_POST['asignatura'];
    $hechos_f = $_POST['hechos'];
  $hora_f = $_POST['hora'];
  $tipoparte_f = $_POST['tipoparte'];
  $comfamilia_f = $_POST['comfamilia'];
  $observaciones_f = $_POST['observaciones'];
   
   
   extract(consultaBDunica("SELECT IdAlumno FROM talumnounidad, talumnos, tunidades where IdUnidad=UnidadIdAlUn
   and IdAlumno=AlumnoIdAlUn and concat(Alumno, ' (', Unidad ,')') ='$alumnounidad_f'"));
    
    // buscamos Id del profesor $IdProfesor_b
   extract(consultaBDunica("SELECT * FROM tprofesores WHERE Profesor = '$profesor_f'"));
    
    $IdProfesor_b=$IdProfesor;
    
    
    //Calcula el Id del nuevo parte (necesario para mandarlos a 10enviocorreos)
   extract(consultaBDunica("SELECT max(IdParte) AS id FROM tpartes")); 
    if (is_null($id)) {$nIdParte=1;} else {$nIdParte=$id+1;}


     
    // Insert nuevo parte 
   $insert = "INSERT INTO tpartes (IdParte, AlumnoIdPa, FechaPa, ProfesorIdPa, Asignatura, HechosPa, TipoPa, HoraPa,
    ComFamiliaPa, ObservacionesPa, EstadoPa, Origen)
   VALUES ('$nIdParte', '$IdAlumno' , '$fecha_f', '$IdProfesor_b', '$asignatura_f', '$hechos_f', '$tipoparte_f', '$hora_f',
    '$comfamilia_f', '$observaciones_f', 'P', 'appdis' )";
    $resSelect = $conn->prepare($insert);
    $resSelect->execute(); 
   
    //Pasa datos para envio por correo
    unset ($_SESSION['ArrayCorreosEnviar']);
   if ($_POST['correotutor']=="si")
   {   $_SESSION['ArrayCorreosEnviar'][]=['CorreoParteATutor',$nIdParte]; }
   if ($_POST['correofamilia']=="si")
   {   $_SESSION['ArrayCorreosEnviar'][]=['CorreoParteAFamilia',$nIdParte]; }
   
   header('location:10enviocorreos.php');
    
  } 
  
?>

 

  <center>
  <div class="container">
    <button type="button" class="btn btn-info btn-lg" >
    DISCIPLINA
    </button>
    &nbsp;&nbsp;&nbsp;
    <a href="02absentismodisciplina.php"><button type="button" class="btn btn-outline-info btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br>
    </div>
  </center>

  
  <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">NUEVO PARTE DISCIPLINARIO DE UN ALUMNO</h5>
        <hr class="my-1">
        
        <form  method="post" class="p-3"> 
       
          <div>
            <h5 class="text-secundary">Alumno*</h5>
          </div>
          <div class="input-group">
          <select autofocus name="alumno_unidad" id="buscar" 
            class="form-control form-control-lg rounded-0 border-info selectpicker" 
            data-live-search="true" data-live-search-normalize="true" title="Ingrese nombre del alumno/a..." required>
              <?php
                $sql = "SELECT concat(Alumno,' (',
                  (SELECT u.unidad from tunidades u inner join talumnounidad a 
                  ON u.IdUnidad=a.UnidadIdAlUn
                  WHERE a.AlumnoIdAlUn=IdAlumno AND a.CursoAlUn='$curso'),')') as sAlumno_Unidad
                  FROM talumnos, tunidades, talumnounidad
                  WHERE Idalumno=AlumnoIdalun
                  AND	idunidad=unidadidalun
                  AND cursoalun='$curso'
                  ORDER BY orden";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $resultados = $stmt->fetchAll();
                foreach ($resultados as $row) {
                  if ($row['sAlumno_Unidad']==$_SESSION['s_alumno_unidad']) {
                    echo '<option selected>'.$row['sAlumno_Unidad'].'</option>'; 
                  } else {
                  echo '<option>'.$row['sAlumno_Unidad'].'</option>';
                  }
                }             
              ?>
          </select>
          <br>
        </div>
      
          <div>
            <h5 class="text-secondary">Fecha del incidente*</h5>
          </div>
          <div class="input-group">
            <input type="date" name="fecha" id="fecha" class="form-control form-control-lg rounded-0 border-info"
            value= <?php  echo date("Y-m-d"); ?> autocomplete="off" required>
           </div>
           <div>
            <h5 class="text-secondary">Hora del incidente*</h5>
            <div class="input-group">
         
             
    <?php         
    // Cargamos opciones Hora
    $consulta = "SELECT valor1 as D_Horas FROM tconfiguracion WHERE tipo='tramohorario'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select name="hora" class="form-control form-control-lg rounded-0 border-info">
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
        ?>
        <option value="<?php echo $row3['D_Horas'];?>"><?php echo $row3['D_Horas'];?></option>
    <?php 
        }
    ?>
    </select>
    
 
           </div>
          </div>
           <div>
            <h5 class="text-secondary">Profesor que comunica el parte*</h5>
          </div>
          <div class="input-group">
          <select autofocus name="profesor" id="buscarp" class="form-control form-control-lg rounded-0 border-info selectpicker" 
          data-live-search="true" data-live-search-normalize="true" title="Ingrese nombre del alumno/a..." required>
            <br>
            <?php
             $sqlp = "SELECT Profesor FROM tprofesores";
             $stmtp = $conn->prepare($sqlp);
             $stmtp->execute();
             $resultadosp = $stmtp->fetchAll();
             foreach ($resultadosp as $rowp) {
              echo '<option>'.$rowp['Profesor'].'</option>';    
               }
          ?>
          </select>
           </div>
            
         <div>
            <h5 class="text-secondary">Asignatura/Módulo/Guardia/Otros*</h5>
          </div>
           <div class="input-group">
               <input type="text" name="asignatura" id="asignatura" max="30" 
               class="form-control form-control-lg rounded-0 border-info"
               require>
            <br>
           </div>


           <div>
            <h5 class="text-secondary">Descripción de los hechos*</h5>
          </div>
           <div class="input-group">
               <textarea name="hechos" id="hechos" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off" required></textarea>
            <br>
           </div>
           <div>
            <h5 class="text-secondary">Tipo Parte*</h5>
          </div>
          
    <?php         
    // Cargamos opciones TiposParte
   $consulta = "SELECT id as A_IdTipoParte, valor2 as A_TipoPartexDefecto FROM tconfiguracion WHERE tipo='tipoparte'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select name="tipoparte" required class="form-control form-control-lg rounded-0 border-info">
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
          if ($row3['A_TipoPartexDefecto']=='si')
    { $seleccionado="selected"; } else {$seleccionado ="";}
        ?>
        <option <?php echo $seleccionado ?> value="<?php echo $row3['A_IdTipoParte'];?>"><?php echo $row3['A_IdTipoParte'];?></option>
    <?php 
        }
    ?>
    </select>
           
          <div>
            <h5 class="text-secondary">Comunicación a la familia</h5> <h7>(Quién lo comunica, fecha, hora, y modo de comunicación)</h7>
          </div>
           <div class="input-group">
               <textarea name="comfamilia" id="comfamilia" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="on"></textarea>
            <br>
           </div>
           
           <div>
            <h5 class="text-secondary">Observaciones de Jefatura de Estudios</h5>
          </div>
           <div class="input-group">
               <textarea name="observaciones" id="observaciones" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            <br>
            
           </div>
           <br>
          
        <div>
            <h5 class="text-secondary">Comunicar parte por correo-e</h5>
          </div>
           
           
          
               <div class="form-check">
                   
             <input class="form-check-input" type="checkbox" name="correofamilia" value="si" checked> 
             
            <label class="form-check-label" for="flexCheckDefault">
          A tutores legales
        </label>
        
        </div>
         <div class="form-check">
         <input class="form-check-input" type="checkbox" name="correotutor" value="si" checked> 
            <label class="form-check-label" for="flexCheckDefault">
          Al tutor del grupo
        </label>
        </div>       
               
           <div class="input-group-append">
              <input type="submit" name="submit" value="Grabar parte" class="btn btn-info btn-lg rounded-1">
            </div>

        </form>
      </div>
      
       
    </div>
  </div>
  
  
 
  
</body>
</html>