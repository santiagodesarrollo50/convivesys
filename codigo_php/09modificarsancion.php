<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

?>



  
  
  

<?php
// Comprueba que solo ha sido seleccionado una sancion
if  (isset($_POST['checkboxsancion']))
  {$contarsanciones=count($_POST['checkboxsancion']);}
  else
  {$contarsanciones=0;}

if ($contarsanciones!=1 && !isset($_POST['update']))
{

$_SESSION['aviso']=1; //indicará que para esta opcion solo hay que elegir 1 parte
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}

$IdSancion = $_POST['checkboxsancion'][0];


// Comprueba que se haya seleccionado una sanción
if ($IdSancion =="")
{
   $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}



// Obtenemos los datos de la sanción a modificar
    extract(consultaBDunica("SELECT * FROM tsanciones WHERE IdSancion = '$IdSancion'")); 
    
if(isset($_POST['update'])) // when click on Update button
{ 
    $ffechasa = $_POST['f_fechasa'];
    $fhechos = $_POST['f_hechos'];
    $ftiposancion = $_POST['f_tiposancion'];
    $ffechain = $_POST['f_fechain'];
    $ffechafin = $_POST['f_fechafin'];
    $fobservaciones = $_POST['f_observaciones'];
    $IdSancion= $_POST['IdSancion'];
    $fComFamiliaSa= $_POST['f_comfamiliasa'];
    
    
    //Comprobar que fechain<=fechafin
  if (strtotime($ffechain)>strtotime($ffechafin))
  {
      $_SESSION['aviso']=7;
  $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    }
    
    
    
	$edit = $conn->query("update tsanciones set FechaSa='$ffechasa', HechosSa='$fhechos', TipoSa='$ftiposancion', 
    ComFamiliaSa='$fComFamiliaSa', ObservacionesSa='$fobservaciones' where IdSancion='$IdSancion'"); 
	
	 // terminamos de insertar la nueva sanción (fechas no obligatorias de rellenar) Problema de null
    if ($ffechain != "" && $ffechafin != "")
    { 
    
    $Diassancion=contardiaslectivos($ffechain,$ffechafin);
    
    $edit = $conn->query("UPDATE tsanciones SET FechaInicio='$ffechain', FechaFin='$ffechafin', 
    DiasSancion='$Diassancion' where IdSancion='$IdSancion'");
    } 
    else
	{ 
	    $edit = $conn->query("UPDATE tsanciones SET FechaInicio=NULL, FechaFin=NULL WHERE IdSancion='$IdSancion'");
    } 

    //$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    
      	
}

 
?>


 <div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">ACTUALIZAR SANCIÓN</h5>
        <hr class="my-1">

<form method="POST">
    <div>
            <h5 class="text-secondary">Fecha acuerdo sanción*</h5>
          </div>
<div class="input-group">
            <input autofocus type="date" name="f_fechasa" class="form-control form-control-lg rounded-0 border-info" 
            autocomplete="off" required value="<?php echo $FechaSa; ?>"><br>
          </div>
          <div class="col-md-5" style="position: relative;margin-top: -0px;margin-left: 215px;">
        </div>
      
          <div>
            <h5 class="text-secondary">Descripción de los hechos que dan lugar a la sanción*</h5>
          </div>
          <div class="input-group">
            <textarea name="f_hechos" rows="4" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="off" required><?php echo $HechosSa; ?></textarea>
               <br>
           </div>
           <div>
            <h5 class="text-secondary">Tipo Sanción</h5>
          </div>
          
          <?php         
    // Cargamos opciones Tipos Sanción
    $consulta = "SELECT id as B_IdTipoSancion FROM tconfiguracion WHERE tipo='tiposancion'";
    $resSelect3 = $conn->prepare($consulta);
    $resSelect3->execute(); 
    ?>
    <select class="form-control form-control-lg rounded-0 border-info" name="f_tiposancion" >
     <option value=""></option>
    <?php
    while($row3 = $resSelect3->fetch()) 
        {
    if ($row3['B_IdTipoSancion']==$TipoSa)
    { $seleccionado="selected"; } else {$seleccionado ="";}
    
    ?>
        <option value="<?php echo $row3['B_IdTipoSancion'];?>" <?php echo $seleccionado ?>
        ><?php echo $row3['B_IdTipoSancion'];?></option>
    <?php 
        }
    ?>
    </select>
          
          
         
           <div>
            <h5 class="text-secondary">Fecha Inicio Sanción</h5>
          </div>
           <div class="input-group">
            <input type="date" name="f_fechain"  class="form-control form-control-lg rounded-0 border-info" 
            placeholder="Fecha inicio..." autocomplete="off" value="<?php echo $FechaInicio; ?>"><br>
           </div>
           <div>
            <h5 class="text-secondary">Fecha Finalización Sanción</h5>
          </div>
           <div class="input-group">
            <input type="date" name="f_fechafin"  class="form-control form-control-lg rounded-0 border-info"
            placeholder="Fecha fin..." autocomplete="off" value="<?php echo $FechaFin; ?>"><br>
           </div>
           
           <div>
               <h5 class="text-secondary">Comunicación a la familia</h5> <h7>(Quién lo comunica, fecha, hora, y modo de comunicación)</h7>
          </div>
           <div class="input-group">
               <textarea name="f_comfamiliasa" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="off"><?php echo $ComFamiliaSa; ?></textarea>
            <br>
           </div>
           
           <div>
               <h5 class="text-secondary">Observaciones para Jefatura de Estudios</h5>
          </div>
           <div class="input-group">
               <textarea name="f_observaciones" rows="3" cols="40" 
               class="form-control form-control-lg rounded-0 border-info"
               autocomplete="off"><?php echo $ObservacionesSa; ?></textarea>
            <br>
           </div>
           
           <div class="input-group-append">
              <input type="submit" name="update" value="Actualizar Sanción" class="btn btn-info btn-lg rounded-0">
            </div>
            <input type="hidden" name="IdSancion" value="<?php echo $IdSancion; ?>" >    
</form>

</div>
</div>
</div>
</body>
</html>
