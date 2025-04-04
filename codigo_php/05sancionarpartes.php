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

  // Comprueba que no se ha seleccionado más de una sanción
  if  (isset($_POST['checkboxsancion'])) {
    $contarsanciones=count($_POST['checkboxsancion']);
  } else {
    $contarsanciones=0;
  }
  if ($contarsanciones>1 && !isset($_POST['submit5'])) {
    $_SESSION['aviso']=4; //indicará error de selección
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
  }

  // CUANDO SE PULSA GRABAR NUEVA SANCIÓN EN EL FORMULARIO
  if (isset($_POST['submit5'])) {
    $ffechasa = $_POST['f_fechasa'];
    $fhechos = $_POST['f_hechos'];
    $ftiposancion = $_POST['f_tiposancion'];
    $ffechain = $_POST['f_fechain'];
    $ffechafin = $_POST['f_fechafin'];
    $fcomfamilia = $_POST['f_comfamilia'];
    $fobservaciones = $_POST['f_observaciones'];
    $AlumnoIdPa = $_POST['AlumnoIdPa'];
    $ArrayIdPartes = explode(",",$_POST['ArrayIdPartes']);
    
    //Comprobar que fechain<=fechafin
    if (strtotime($ffechain)>strtotime($ffechafin)) {
      $_SESSION['aviso']=7;
      $Procedencia=$_SESSION['Procedencia'];
      header('location:'.$Procedencia);
    }
    
    //Calcula el Id y letra de la nueva sanción x alumno
    extract(consultaBDunica("SELECT max(IdSancionxAlumno) AS id FROM tsancionparte WHERE AlumnoIdSaPa='$AlumnoIdPa'")); 
    if (is_null($id)) {
      $NuevoIdSancionxAlumno=1;
    } else {
      $NuevoIdSancionxAlumno=$id+1;
    }
    $NuevaLetraSancionxAlumno=chr($NuevoIdSancionxAlumno+64);
      
    //Calcula el Id de la nueva sanción
    extract(consultaBDunica("SELECT max(SancionIdSaPa) AS id FROM tsancionparte")); 
    if (is_null($id)) {
      $IdSancion=1;
    } else {
      $IdSancion=$id+1;
    }
    
    // insert nueva sanción
    $insert = "INSERT INTO tsanciones (EstadoSa, IdSancion, AlumnoIdSa, FechaSa, HechosSa, TipoSa, ObservacionesSa, ComFamiliaSa,
      PedirTar, DadasTar, ReSeneca, DadasCom, ComPasen, ArchiCom)
      VALUES ('P','$IdSancion', '$AlumnoIdPa' , '$ffechasa', '$fhechos', '$ftiposancion', '$fobservaciones', '$fcomfamilia',
      'P','P','P','P','P','P')";
    $resSelect = $conn->prepare($insert);
    $resSelect->execute(); 

    // terminamos de insertar la nueva sanción (fechas no obligatorias de rellenar) Problema de null
    if ($ffechain != "" && $ffechafin != "") {
      $Diassancion=contardiaslectivos($ffechain,$ffechafin);
      $fechas = "UPDATE tsanciones set FechaInicio='$ffechain', FechaFin='$ffechafin', DiasSancion='$Diassancion' where IdSancion='$IdSancion'";
      $resSelect3 = $conn->prepare($fechas);
      $resSelect3->execute(); 
    }
      
    // Registra las relaciones sanciones-partes en la tabla tsancionparte
    $n=0;
    while ($n < count($ArrayIdPartes) ) {
      $conn->query("INSERT INTO tsancionparte set SancionIdSaPa='$IdSancion', ParteIdSaPa='$ArrayIdPartes[$n]', 
      AlumnoIdSaPa='$AlumnoIdPa', IdSancionxAlumno='$NuevoIdSancionxAlumno', LetraSancionxAlumno='$NuevaLetraSancionxAlumno'"); 
      $n++;
    }
        
    //Pasa datos para envio por correo al ac
      
    if ($_POST['correoac']=="si" && substr($ftiposancion,0,1)=="A") {
      unset ($_SESSION['ArrayCorreosEnviar']);
      $_SESSION['ArrayCorreosEnviar'][]=['CorreoSancionAC',$IdSancion]; 
      header('location:10enviocorreos.php');
    } else {
      //Redireccionar a la web mostrar por alumno
      $Procedencia=$_SESSION['Procedencia'];
      header('location:'.$Procedencia); 
    }
  }
  
    

    
    
  //ANTES DE PULSAR NUEVA SANCIÓN EN EL FORMULARIO
  if (!(isset($_POST['submit5']))) {
    $ArrayIdPartes =$_POST['checkboxid'];
    $IdSancion = $_POST['checkboxsancion'][0];
    
    //Comprobar que hay algún parte seleccionado
    if (count($ArrayIdPartes)==0) {
      $Procedencia=$_SESSION['Procedencia'];
      header('location:'.$Procedencia);
    }

    //Comprobar que los partes pertenecen al mismo alumno y obtiene datos del alumno(extract)
    $anterior="";
    $n=0;
    $pgraves=0;
    $pcontrarios=0;
    while ($n < count($ArrayIdPartes) ) {
      extract(consultaBDunica("SELECT * FROM tpartes WHERE IdParte = '$ArrayIdPartes[$n]'")); 
      if ($n>0 && $anterior!=$AlumnoIdPa) {
        $_SESSION['aviso']=5; //indicará error de selección
        $Procedencia=$_SESSION['Procedencia'];
        header('location:'.$Procedencia);
      }
      $anterior = $AlumnoIdPa;
      if (substr($TipoPa,0,1)=="G") {
        $pgraves++;
      } else {
        $pcontrarios++;
      }
      $n++;
    }
    
    //Obtiene Alumno y unidad
    extract(consultaBDunica("SELECT Alumno FROM talumnos WHERE IdAlumno = '$AlumnoIdPa'")); 
    extract(consultaBDunica("SELECT UnidadIdAlUn as Unidad from talumnounidad where AlumnoIdAlUn='$AlumnoIdPa'"));
    
    //Comprueba si hay sanción seleccionada: Caso Si hay sanción seleccionada (este caso no es necesario rellenar una nueva sanción)
    //registra la relación entre sanción y partes seleccionados y vuelve a disciplinaalumno
    if (!(is_null($IdSancion))) {  
      //Busca $IdSancionxAlumno y $LetraSancionxAlumno correspondiente a $IdSancion
      extract(consultaBDunica("SELECT * FROM tsancionparte WHERE SancionIdSaPa='$IdSancion'"));
      
      //Comprobar que los partes seleccionados (ya se ha comprobado que pertenecen al mismo alumno) pertenecen al mismo alumno que la sanción marcada
      if ($AlumnoIdSaPa==$AlumnoIdPa) { //Si coincide alumno parte con alumno sancion
        // Registra las relaciones sanciones-partes en la tabla tsancionparte
        $n=0;
        while ($n < count($ArrayIdPartes) ) {
          // Comprueba si la relación sanción-parte ya existe, para no registrarla
          extract(consultaBDunica("SELECT count(SancionIdSaPa) AS cuentasapa FROM tsancionparte 
          WHERE (ParteIdSaPa=$ArrayIdPartes[$n]  && SancionIdSaPa=$IdSancion)"));
          if ($cuentasapa==0)     {
            $conn->query("INSERT INTO tsancionparte set SancionIdSaPa='$IdSancion', ParteIdSaPa='$ArrayIdPartes[$n]', 
            AlumnoIdSaPa='$AlumnoIdPa', IdSancionxAlumno='$IdSancionxAlumno', LetraSancionxAlumno='$LetraSancionxAlumno'");      
          }
          $n++;
        }
      } else { //Si no coincide alumno parte con alumno sancion
        $_SESSION['aviso']=5; //indicará error de selección para cuando redireccione a la página de partida
      }
    
      //Redireccionar a la web mostrar por alumno
      $Procedencia=$_SESSION['Procedencia'];
      header('location:'.$Procedencia);
    }
  }

  if ($pcontrarios==0) {
    $f_hechosdefecto="Acumulación de ".$pgraves." partes graves.";
  } elseif ($pgraves==0) {
    $f_hechosdefecto="Acumulación de ".$pcontrarios." partes contrarios.";
  } else {
    $f_hechosdefecto="Acumulación de ".$pgraves." partes graves y ".$pcontrarios." partes contrarios.";
  }
?>
   
   
  
  
  
  

  
  <div class="container">
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">NUEVA SANCIÓN DEL ALUMNO: <?php echo $Alumno.' ('.$Unidad.')'; ?></h5>
        <hr class="my-1">
        
        <form  method="post" class="p-3"> 
          <div>
            <h5 class="text-secondary">Fecha Acuerdo Sanción*</h5>
          </div>
          <div class="input-group">
            <input autofocus type="date" name="f_fechasa" class="form-control form-control-lg rounded-0 border-info"
            value= <?php  echo date("Y-m-d"); ?> autocomplete="off" required><br>
          </div>
          <div class="col-md-5" style="position: relative;margin-top: -0px;margin-left: 215px;">
        </div>
      
          <div>
            <h5 class="text-secondary">Descripción de hechos que dan lugar a la sanción*</h5>
          </div>
                    
          <div class="input-group">
           <textarea name="f_hechos" rows="5" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="on" required> <?= $f_hechosdefecto ?> </textarea>
            <br>
           </div>
           
           
           
           <div>
            <h5 class="text-secondary">Tipo Sanción</h5>
          </div>
          
          <div class="input-group">
          <?php         
    // Cargamos opciones TiposParte
    $consulta = "SELECT id as B_IdTipoSancion FROM tconfiguracion WHERE tipo='tiposancion'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select class="form-control form-control-lg rounded-0 border-info" name="f_tiposancion" >
     <option value=""></option>
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
    ?>
        <option value="<?php echo $row3['B_IdTipoSancion'];?>"><?php echo $row3['B_IdTipoSancion'];?></option>
    <?php 
        }
    ?>
    </select>
          
         </div>
         
           <div>
            <h5 class="text-secondary">Fecha Inicio Sanción</h5>
          </div>
           <div class="input-group">
            <input type="date" name="f_fechain"  class="form-control form-control-lg rounded-0 border-info" 
            placeholder="Fecha inicio..." autocomplete="off" value="NULL"><br>
           </div>
           <div>
            <h5 class="text-secondary">Fecha Finalización Sanción</h5>
          </div>
           <div class="input-group">
            <input type="date" name="f_fechafin"  class="form-control form-control-lg rounded-0 border-info"
            autocomplete="off"><br>
           </div>
           
           <div>
               <h5 class="text-secondary">Comunicacion a la Familia de la sanción</h5> <h7>(Quién lo comunica, fecha, hora, y modo de comunicación)</h7>
          </div>
           <div class="input-group">
            <textarea name="f_comfamilia"  rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="on"></textarea>
            <br>
           </div>
           
           
           <div>
               <h5 class="text-secondary">Observaciones de Jefatura de Estudios</h5>
          </div>
           <div class="input-group">
            <textarea name="f_observaciones" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="off"></textarea>
            <br>
           </div>
           
           <!-- 
           <div class="form-check">
         <input class="form-check-input" type="checkbox" name="correoac" value="si" checked> 
            
         <label class="form-check-label" for="flexCheckDefault">
          Si la sanción es de Aula de Convivencia, comunicarla por correo-e a los responsables del Aula 
        </label>
        </div>  
          -->
           
           
           <div class="input-group-append">
              <input type="submit" name="submit5" value="Grabar Nueva Sanción" class="btn btn-info btn-lg rounded-0">
            </div>
            <input type="hidden" name="AlumnoIdPa" value="<?php echo $AlumnoIdPa; ?>" >
            
            <input type="hidden" name="ArrayIdPartes" value="<?php echo implode(",",$ArrayIdPartes); ?>" >
            </form>
      </div>
      </div>
  </div>
  
</body>
</html>
  
  
