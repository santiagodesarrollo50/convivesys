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

  
  // Cuando pulsa grabar nuevo anotación
    if (isset($_POST['submit']))
    {
    
    $alumnounidad_f = $_POST['alumno_unidad'];
    $fecha_f = $_POST['fecha'];
    $anotacion_f = $_POST['anotacion'];
   $tipoanotacion_f = $_POST['tipoanotacion'];
   $observaciones_f = $_POST['observaciones'];
   $correotutor_f = $_POST['correotutor'];
   $seguimiento=$_POST['seguimiento'];
   

   // buscamos Id alumno
   extract(consultaBDunica("SELECT IdAlumno FROM talumnos, talumnounidad, tunidades WHERE
   AlumnoIdAlUn=IdAlumno and IdUnidad=UnidadIdAlUn and CursoUn='$curso' and
   concat(Alumno, ' (', Unidad ,')') = '$alumnounidad_f'"));
 
  
  
    //Calcula el Id de la nueva anotacion (necesario para mandarlos al tutor 10enviocorreos)
   extract(consultaBDunica("SELECT max(IdAnotacion) AS id FROM tanotacionesabs")); 
    if (is_null($id)) {$nIdAnotacion=1;} else {$nIdAnotacion=$id+1;}
    
    
    // Insert nueva anotación
   $conn->query("INSERT INTO tanotacionesabs (IdAnotacion, AlumnoIdAn, FechaAn, Anotacion, TipoAn, ObservacionesAn, EstadoAn,
   SenecaAn, CorreoTutorAn)
   VALUES ('$nIdAnotacion', '$IdAlumno' , '$fecha_f', '$anotacion_f', '$tipoanotacion_f',
   '$observaciones_f', 'P', 'P', 'P' )");
   
   
    //Inserta el estado seguimiento del absentismo en la tabla de alumnos
    if($seguimiento=="SI")
    {
      extract(consultaBDunica("SELECT count(*) as numero FROM tnotasalumno WHERE AlumnoIdNo='$IdAlumno'"));
      if ($numero==0)
      {
          $conn->query("INSERT INTO tnotasalumno (AlumnoIdNo, ABS_Seguimiento) VALUES ('$IdAlumno','SI')");
      }   
      else
      {
        $conn->query("UPDATE tnotasalumno set ABS_Seguimiento='SI' where AlumnoIdNo='$IdAlumno'");
      }     
    }
   
    //Pasa datos para envio por correo
    unset ($_SESSION['ArrayCorreosEnviar']);
   if ($_POST['correotutor']=="si")
   {   $_SESSION['ArrayCorreosEnviar'][]=['CorreoAnotacionATutor',$nIdAnotacion]; }
   
   
    header('location:10enviocorreos.php');
   } 
   
   
   
   
  ?>

  
  <center>
  <div class="container">
    <a href="02absentismodisciplina.php"><button type="button" class="btn btn-outline-info btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-info btn-lg" >
    ABSENTISMO
    </button>
    </div>
    <br>
  </div>
  </center>
  
  <div class="container"> <!-- centra el contenido -->

    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">NUEVA ANOTACIÓN DE ABSENTISMO DE UN ALUMNO</h5>
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
          </div>
      
          <div>
            <h5 class="text-secondary">Fecha de la anotación*</h5>
          </div>
          <div class="input-group">
            <input type="date" name="fecha" id="fecha" class="form-control form-control-lg rounded-0 border-info"
            value= <?php  echo date("Y-m-d"); ?> autocomplete="off" required>
           </div>
         
         
           
           
           <div>
            <h5 class="text-secondary">Anotación</h5>
          </div>
           <div class="input-group">
               <textarea name="anotacion" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            <br>
           </div>
           <div>
            <h5 class="text-secondary">Tipo Anotación*</h5>
          </div>
          
    <?php         
    // Cargamos opciones TiposAnotación
   $consulta = "SELECT F_IdTipoAnotacion FROM tdatos where F_IdTipoAnotacion IS NOT NULL";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select name="tipoanotacion" required class="form-control form-control-lg rounded-0 border-info">
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
        ?>
        <option value="<?php echo $row3['F_IdTipoAnotacion'];?>"><?php echo $row3['F_IdTipoAnotacion'];?></option>
    <?php 
        }
    ?>
    </select>
           

           <div>
            <h5 class="text-secondary">Observaciones</h5>
          </div>
           <div class="input-group">
               <textarea name="observaciones" id="observaciones" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            <br>
            
           </div>
           <br>
           
        <div>
            <h5 class="text-secondary">Realizar seguimiento mensual del Absentismo</h5>
          </div>
           
           <?php
           $sidalumno=$_SESSION['s_idalumno'];
           extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$sidalumno'"));
        ?>
    
    
         <div class="form-check">
         <input class="form-check-input" type="checkbox" name="seguimiento" value="SI"> 
            <label class="form-check-label" for="flexCheckDefault">
          Realizar seguimiento mensual del Absentismo
        </label>
        </div>       
    
    
    
    
          
        <div>
            <h5 class="text-secondary">Comunicar anotación por correo-e</h5>
          </div>
           
           
           <?php
           $sidalumno=$_SESSION['s_idalumno'];
           extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$sidalumno'"));
        ?>
    
    
         <div class="form-check">
         <input class="form-check-input" type="checkbox" name="correotutor" value="si" checked> 
            <label class="form-check-label" for="flexCheckDefault">
          Al tutor del grupo
        </label>
        </div>       
               
           <div class="input-group-append">
              <input type="submit" name="submit" value="Grabar Anotación" class="btn btn-info btn-lg rounded-1">
            </div>

        </form>
      </div>
      
       
    </div>
  </div>
  
  
  
  
</body>
</html>