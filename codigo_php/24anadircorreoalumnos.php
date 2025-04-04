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

// Correspondencia entre los campos de nuestra base de datos y los campos de Séneca

extract(consultaBDunica("SELECT * from tdatos where L_IdRelacionCampos='ImpAlumnoCorreo'"));

$ArrayNuestroCampos=explode("--",$L_NuestroCampo);
$ArraySenecaCampos=explode("--",$L_SenecaCampo);
  
  // Cuando pulsa cargar datos
    if (isset($_POST['grabar']))
    {
    
$datos= $_POST['datos'];

$_SESSION['datos2']=$datos;
$tablaalumnos=capturatablascsv($datos); 
$CamposSeneca=$tablaalumnos[0];

    }

   

?>




<div class="container">
    <h3> <b>IMPORTAR CORREO-CORPORATIVO A LOS DATOS DEL ALUMNADO </b></h3>
    </div>
       
    
    
    <!-- Formulario de opciones por partes-->
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Datos" class="btn btn-primary" formaction="">
   </div>
   
   
   <div>
            <h5 class="text-secondary">Pegar texto exportado csv de una tabla con los nie del alumno y su correo (construirlad con los datos de GAdmin cruzado con nie alumnos - cuidado nombres duplicados)
                (ctrl+A y ctrl+C)</h5>
          </div>
           <div class="input-group">
               <textarea autofocus name="datos" rows="2" cols="2" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            </div>
        <br><br><br>
        <center>




  <div class="container"> <!-- centra el contenido -->

<div class="row mt-4">
  <div class="col-md-8 mx-auto bg-light rounded p-4">
    <h5 class="text-center font-weight-bold">RELACIÓN CON LOS CAMPOS DE TABLA CSV PEGADA</h5>
    <hr class="my-1">
    

<?php

foreach ($ArrayNuestroCampos as $key => $value)
{
    echo "Asigna un campo de Séneca para el campo ".$value;
?>

<select name="<?php echo $value; ?>" required class="form-control form-control-lg rounded-0 border-info">
<option value="sin asignar" >sin asignar</option>

<?php
foreach ($CamposSeneca as $value2) 
    {
if ($value2==$ArraySenecaCampos[$key]) {$selecccionado="selected";} else {$selecccionado="";}
    
    ?>
    <option value="<?php echo $value2;?>" <?php echo $selecccionado; ?> ><?php echo $value2;?></option>
<?php 
    }

?>
</select>

<?php
}
?>  


<div id="menuopciones">
    <input type="submit" name="anadiralumno" value="Importar Correo Corporativo al Alumnado en la base de datos" class="btn btn-primary" formaction="">
   </div>

    </form>

    
<?php    
    // Cuando pulsa añadir alumnado
    if (isset($_POST['anadiralumno']))
    {
    $datos2= $_SESSION['datos2'];
    
    $tablaalumnos=capturatablascsv($datos2);  
    $CamposSeneca=$tablaalumnos[0];
    
    $CSeneca="";
    foreach ($ArrayNuestroCampos as $key => $value)
    {
        $CSeneca=$CSeneca.$_POST["$value"]."--";
    }   

    $CSeneca=substr($CSeneca,0,strlen($CSeneca)-2);
    $edit = $conn->query("UPDATE tdatos set L_SenecaCampo='$CSeneca' where L_IdRelacionCampos='ImpAlumnoCorreo'");
    
    foreach(range(1,count($tablaalumnos)-1) as $n)
{ 
    foreach($tablaalumnos[$n] as $columna=>$vcampo)
    {  
        $valor[$tablaalumnos[0][$columna]]=$vcampo;
    }
    
    //ajustar las variables al csv que importamos 
    unset($vidalumno,$vcorreo);
    $vidalumno=$valor[$_POST['IdAlumno']];
    
    $vcorreo=$valor[$_POST['CorreoAlumnoCorp']];
    

    // Si es necesario
    //extract(consultaBDunica("SELECT IdAlumno, count(IdAlumno) as cuantos FROM talumnos 
    //WHERE Alumno='$valumno'")); 

    //if($cuantos > 0)
    //{
        $conn->query("UPDATE talumnos set CorreoAlumnoCorp='$vcorreo'
        where IdAlumno='$vidalumno'"); //IdAlumno='$IdAlumno'
    //}

}
$_SESSION['datos2']="";
    }
       
?>

    <div class="container">
    <h4> <b>Tabla csv pegada: </b> </h4><br> <br>
    </div>
    <?php
    echo imprimetablaarray($tablaalumnos);
    ?>  
    
    
    </center>
    <br>
    <br>
    <br>
    </html>