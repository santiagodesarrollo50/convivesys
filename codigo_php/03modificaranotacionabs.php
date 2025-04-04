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



    $Idregistro = $_POST['anotacionid'][0];
    

$ArrayCampos=['FechaAn','TipoAn','Anotacion','ObservacionesAn'];
    $ArrayTituloCampos=['Fecha de la Anotación','Tipo de Anotación','Anotación','Observaciones'];
    $ArrayTipoCampos=['date','text','text-text'];
    $ArrayCampoRequerido=['required','required',' ',' ',' '];
    $ArrayCampoSelect=['no','SELECT	F_IdTipoAnotacion as Opcion from tdatos where F_IdTipoAnotacion IS NOT NULL',
    'no','no'];
    
//En caso de pulsar actualizar anotacion
if(isset($_POST['update']))
{ 
    $Idregistro2=$_POST['idregistro'];
    foreach($ArrayCampos as $campo)
    {
        $$campo=$_POST[$campo];      
        $edit = $conn->query("UPDATE tanotacionesabs set $campo='${$campo}' where IdAnotacion='$Idregistro2'");
    }
  $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}

// extrae los datos a modificar
    extract(consultaBDunica("SELECT * FROM tanotacionesabs WHERE IdAnotacion = '$Idregistro'")); 
    
    //nombre del alumno para el titulo
    extract(consultaBDunica("SELECT Alumno FROM talumnos WHERE IdAlumno = $AlumnoIdAn")); 

?>

<div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">Modificar Anotación de Absentismo de:<br><?= $Alumno ?></h5>
        <hr class="my-1">
        
   
   
   <form  method="post" class="p-3"> 
    
<?php 
    foreach($ArrayCampos as $n => $campo)
    {
?>
        <div>
        <h5 class="text-secundary"><?php  echo $ArrayTituloCampos[$n];
        if($ArrayCampoRequerido[$n]=="required") {echo '*';} ?></h5>
        </div>
        <div class="input-group">
            
<?php        
        if ($ArrayCampoSelect[$n]!="no")
        {
            $resSelect3 = $conn->prepare($ArrayCampoSelect[$n]);
            $resSelect3->execute(); 
            ?>
            <select name="<?= $campo ?>" <?= $ArrayCampoRequerido[$n]?> class="form-control form-control-lg rounded-0 border-info">
            <?php
            while($row3 = $resSelect3->fetch()) 
            { extract($row3);
            
            if ($Opcion==${$campo})
            { $seleccionado="selected"; } else {$seleccionado ="";}
            ?>
            <option <?php echo $seleccionado ?> ><?php echo $Opcion;?></option>
            <?php 
            }
            ?>
            </select>
            <?php
        }
        else
        {
            ?>
            <input <?php if ($n==0) {echo "autofocus";} ?> type="<?= $ArrayTipoCampos[$n] ?>"
            name="<?= $campo ?>"   
            class="form-control form-control-lg rounded-0 border-info" autocomplete="off" 
            <?= $ArrayCampoRequerido[$n]?> value="<?= ${$campo} ?>"><br>
            <?php 
        }
        ?>
        </div>
<?php
    }
?>
         <input type="hidden" name="idregistro" value="<?= $Idregistro?>" >
        <div class="input-group-append">
              <input type="submit" name="update" value="Actualizar Datos" class="btn btn-info btn-lg rounded-1">
            </div>
        </form>
      </div>
    </div>
  </div>
   
   

        


 



</body>
</html>

