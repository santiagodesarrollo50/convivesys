<?php

  include ('13cabecera.php');
  $_SESSION['Procedencia']="00buscaralumno.php";
  $curso=$_SESSION['PCurso'];

  //En caso de pulsar actualizar parte
  if(isset($_POST['submite'])) { 
    $f_alumno_unidad = $_POST['alumno'];    
    
    //Conseguir IdAlumno de $f_alumno_unidad
    $consulta = "SELECT IdAlumno FROM talumnos WHERE concat(Alumno, ' (', (SELECT Unidad from tunidades inner join talumnounidad 
      on UnidadIdAlUn=IdUnidad WHERE  AlumnoIdAlUn=IdAlumno AND CursoAlUn='$curso'),')')='$f_alumno_unidad'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    extract($resSelect3->fetch());
    $_SESSION["s_idalumno"]=$IdAlumno;
    header("location:01disciplinaalumno.php");    
  }
?>
      
<section>  
  <div class="container">
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h4 class="text-center font-weight-bold">BUSCAR UN ALUMNO/A:</h4>
        <hr class="my-1">
           <form id="buscaralumno"  method="post" class="p-3">
            <div class="input-group">
            <select autofocus name="alumno" id="buscar" 
            class="form-control form-control-lg rounded-0 border-info selectpicker" 
            data-live-search="true" data-live-search-normalize="true" title="Ingrese nombre del alumno/a..." 
            required>
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
                  echo '<option>'.$row['sAlumno_Unidad'].'</option>';
                }             
              ?>
          </select>
            <div class="input-group-append">
              <input type="submit" name="submite" value="Buscar" class="btn btn-info btn-lg rounded-0">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
 
 
  </body>
</html>