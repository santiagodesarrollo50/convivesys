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
?>
  

<?php

    //En caso de pulsar actualizar parte
    if(isset($_POST['update']))
    { 
        $f_alumno_unidad = $_POST['alumno_unidad'];
        $f_fecha = $_POST['fecha'];
	    $f_profesor = $_POST['profesor'];
	    $f_hechos = $_POST['hechos'];
	    $f_tipoparte = $_POST['tipoparte'];
	    $hora_f = $_POST['hora'];
        $comfamilia_f = $_POST['comfamilia'];
        $observaciones_f = $_POST['observaciones'];
	    $f_IdParte= $_POST['hidparte'];
	
	    //Conseguir IdAlumno de $f_alumno_unidad
	    extract(consultaBDunica("SELECT IdAlumno from talumnos, talumnounidad, tunidades where
      AlumnoIdAlUn=IdAlumno and IdUnidad=UnidadIdAlUn and 
      concat(Alumno,' (',Unidad ,')')='$f_alumno_unidad'"));
    
	    //Conseguir IdProfesor de $f_profesor
	    extract(consultaBDunica("SELECT IdProfesor from tprofesores where Profesor='$f_profesor'"));
    
        // Actualiza el parte en la base de datos
        $edit = $conn->query("UPDATE tpartes set AlumnoIdPa='$IdAlumno', FechaPa='$f_fecha', 
        ProfesorIdPa='$IdProfesor', HechosPa='$f_hechos', TipoPa='$f_tipoparte', 
        HoraPa='$hora_f', ComFamiliaPa='$comfamilia_f', ObservacionesPa='$observaciones_f'
        where IdParte='$f_IdParte'"); 
	 echo $Procedencia;
        $Procedencia=$_SESSION['Procedencia'];
        header('location:'.$Procedencia);
   
    }
    
    // Comprueba que solo ha sido seleccionado un parte
    if (count($_POST['checkboxid'])!=1 && !isset($_POST['update']))
    {
        $_SESSION['aviso']=1; //indicará que para esta opcion solo hay que elegir 1 parte
        $Procedencia=$_SESSION['Procedencia'];
        header('location:'.$Procedencia);
    }

    // Busca en la tabla tpartes el primer parte seleccionado
    $f_IdParte = $_POST['checkboxid'][0];
    extract(consultaBDunica("SELECT * FROM tpartes WHERE IdParte = '$f_IdParte'")); 
    
    // Conseguir Profesor de ProfesorIdPa
    extract(consultaBDunica("SELECT Profesor from tprofesores where IdProfesor='$ProfesorIdPa'"));
    
    // Conseguir Alumno_Unidad de AlumnoIdPa
    extract(consultaBDunica("SELECT concat(Alumno,' (', Unidad,')') as wAlumno_Unidad 
    from talumnos, talumnounidad, tunidades where AlumnoIdAlUn=IdAlumno and IdUnidad=UnidadIdAlUn and 
    CursoAlUn='$curso' and IdAlumno='$AlumnoIdPa'"));
    
    
?>

    <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
    <div class="col-md-8 mx-auto bg-light rounded p-4">
    <h5 class="text-center font-weight-bold">MODIFICAR PARTE DISCIPLINARIO DE UN ALUMNO</h5>
    <hr class="my-1">
    
    <form  method="post" class="p-3"> 
    
    <div>
            <h5 class="text-secundary">Alumno*</h5>
          </div><?php echo $wAlumno_Unidad ?>
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
                  if ($row['sAlumno_Unidad']==$wAlumno_Unidad) {
                    echo '<option selected>'.$row['sAlumno_Unidad'].'</option>';
                  } else {
                    echo '<option>'.$row['sAlumno_Unidad'].'</option>';
                  }
                }             
                ?>
        </select>
          </div>
          
      
          <div>
            <h5 class="text-secondary">Fecha del incidente*</h5>
          </div>
          <div class="input-group">
            <input type="date" name="fecha" id="fecha" value="<?php echo $FechaPa; ?>" 
            class="form-control form-control-lg rounded-0 border-info" autocomplete="off" required>
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
    
    if ($row3['D_Horas']==$HoraPa)
    { $seleccionado="selected"; } else {$seleccionado ="";}
?>
        <option <?php echo $seleccionado ?> ><?php echo $row3['D_Horas'];?></option>
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
            <?php
                $sqlp = "SELECT Profesor FROM tprofesores";
                $stmtp = $conn->prepare($sqlp);
                $stmtp->execute();
                $resultadosp = $stmtp->fetchAll();
                foreach ($resultadosp as $rowp) {
                  if ($rowp['Profesor']==$Profesor) {
                    echo '<option selected>'.$rowp['Profesor'].'</option>'; 
                  } else {
                    echo '<option>'.$rowp['Profesor'].'</option>';    
                  }
                }
            ?>
        </select>
           </div>
            <div>
            <h5 class="text-secondary">Descripción de los hechos*</h5>
          </div>
           <div class="input-group">
               <textarea name="hechos" id="hechos"  rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off" required><?php echo $HechosPa; ?></textarea>
            <br>
           </div>
           <div>
            <h5 class="text-secondary">Tipo Parte*</h5>
          </div>
          
    <?php         
    // Cargamos opciones TiposParte
    
   $consulta = "SELECT id as A_IdTipoParte FROM tconfiguracion WHERE tipo='tipoparte'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select name="tipoparte" class="form-control form-control-lg rounded-0 border-info">
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
           if ($row3['A_IdTipoParte']==$TipoPa)
    { $seleccionado="selected"; } else {$seleccionado ="";}
    
    ?>
        <option <?php echo $seleccionado ?> ><?php echo $row3['A_IdTipoParte'];?></option>
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
               autocomplete="on"><?php echo $ComFamiliaPa; ?></textarea>
            <br>
           </div>
           
           <div>
            <h5 class="text-secondary">Observaciones para Jefatura de Estudios</h5>
          </div>
           <div class="input-group">
             
               <textarea name="observaciones" id="observaciones" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="off"><?php echo $ObservacionesPa; ?></textarea>
            <br>
            <br>
           </div>
           
           <input type="hidden" name="hidparte" value="<?php echo $IdParte; ?>" > 
      
           <div class="input-group-append">
              <input type="submit" name="update" value="Actualizar Parte" class="btn btn-info btn-lg rounded-1">
            </div>

        </form>
      </div>
      
       
    </div>
  </div>
   
   

        


 



</body>
</html>

