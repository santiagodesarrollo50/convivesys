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

$Idregistro=$_SESSION['s_idalumno'];

$ArrayCampos=['Alumno','FechaNac','Direccion','CP','Localidad',
'Provincia','CorreoAlumno','CorreoAlumnoCorp','Tutor1','TelefonoTutor1',
'CorreoTutor1','Tutor2','TelefonoTutor2','CorreoTutor2','Repetidor',
'Custodia'];
$ArrayCamposFor=['FechaNac','Direccion','CP','Localidad',
'Provincia','CorreoAlumno','CorreoAlumnoCorp','Tutor1','TelefonoTutor1',
'CorreoTutor1','Tutor2','TelefonoTutor2','CorreoTutor2','Repetidor',
'Custodia','SituacionFamiliar','ObservacionesAl'];
    $ArrayTituloCampos=['Fecha de Nacimiento','Dirección',
    'Código Postal','Localidad','Provincia','Correo del Alumno','Correo Corporativo del Alumno',
    'Tutor Legal 1','Teléfono del Tutor Legal 1','Correo del Tutor Legal 1','Tutor Legal 2',
    'Teléfono del Tutor Legal 2','Correo del Tutor Legal 2','Alumno Repetidor','Custodia',
    'Situación Familiar','Observaciones de Jefatura de Estudios'];
    $ArrayTipoCampos=['date','text','text','text','text','email','email',
    'text','text','email','text','text','email','text','text','text','text','text'];
    $ArrayCampoRequerido=[' ',' ',' ',' ',' ',' ',' ',' ',' ',
    ' ',' ',' ',' ',' ',' ',' ',' ',' ',' '];
    $ArrayCampoSelect=['no','no','no',
    'no','no','no','no','no','no','no','no','no','no','no','no','no','no'];
    
//En caso de pulsar actualizar parte
if(isset($_POST['update']))
{ 
    $Idregistro2=$_POST['idregistro'];
    foreach($ArrayCampos as $campo)
    {
        $$campo=$_POST[$campo];      
        $conn->query("UPDATE talumnos set $campo='{${$campo}}' where IdAlumno='$Idregistro2'");
    }
    $IdUnidad=$_POST['IdUnidad'];
    $conn->query("UPDATE talumnounidad set UnidadIdAlUn=$IdUnidad where AlumnoIdAlUn='$Idregistro2' and 
    CursoAlUn='$curso'");
    $SituacionFamiliar = $_POST['SituacionFamiliar'];
    $ObservacionesAl = $_POST['ObservacionesAl'];
    $conn->query("INSERT INTO tnotasalumno (AlumnoIdNo, SituacionFamiliar, ObservacionesAl)
        VALUES ('$Idregistro2', '$SituacionFamiliar', '$ObservacionesAl')
        ON DUPLICATE KEY UPDATE SituacionFamiliar='$SituacionFamiliar', ObservacionesAl='$ObservacionesAl'");
  $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}

// extrae los datos a modificar
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno= '$Idregistro'"));
extract(consultaBDunica("SELECT * FROM tnotasalumno WHERE AlumnoIdNo= '$Idregistro'"));
extract(consultaBDunica("SELECT Unidad as Unidads, IdUnidad as IdUnidads FROM talumnounidad inner join tunidades
    on IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn= '$Idregistro' and CursoUn='$curso'")); 
   
  
       
?>

<div class="container"> <!-- centra el contenido -->
    <div class="row mt-4">
      <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h5 class="text-center font-weight-bold">Modificar Datos Personales del Alumno/a</h5>
        <hr class="my-1">
        
   
   
   <form  method="post" class="p-3"> 
   
   
   <div>
  <h5 class="text-secundary"> Nombre del Alumno/a*</h5>
    </div>
    <div class="input-group">
         <input autofocus type="text"
            name="Alumno" value="<?= $Alumno ?>" 
            class="form-control form-control-lg rounded-0 border-info" autocomplete="off" required ><br>
        </div>


   <div>
        <h5 class="text-secundary"> Unidad*</h5>
        </div>
        <div class="input-group">
            
<?php     
        $resSelect3 = $conn->prepare("SELECT Unidad, IdUnidad from tunidades where CursoUn='$curso' order by Orden");
        $resSelect3->execute(); 
?>
        <select name="IdUnidad" required class="form-control form-control-lg rounded-0 border-info">
            <?php
            while($row3 = $resSelect3->fetch()) 
            { extract($row3);
            
            if ($Unidad==$Unidads)
            { $seleccionado="selected"; } else {$seleccionado ="";}
            ?>
            <option <?php echo $seleccionado ?> value="<?php echo $IdUnidad;?>"><?php echo $Unidad;?></option>
            <?php 
            }
            ?>
            </select>
        </div>
<?php 

    foreach($ArrayCamposFor as $n => $campo)
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
            name="<?= $campo ?>" value="<?= ${$campo} ?>" 
            class="form-control form-control-lg rounded-0 border-info" autocomplete="off" 
            <?= $ArrayCampoRequerido[$n]?> ><br>
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

