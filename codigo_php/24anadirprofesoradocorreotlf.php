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

extract(consultaBDunica("SELECT * from tdatos where L_IdRelacionCampos='ImpProfesCorreo'"));

$ArrayNuestroCampos=explode("--",$L_NuestroCampo);
$ArraySenecaCampos=explode("--",$L_SenecaCampo);
  
  // Cuando pulsa cargar datos
    if (isset($_POST['grabar']))
    {
    
$datos= $_POST['datos'];

$_SESSION['datos2']=$datos;
$tablaprofes=capturatablascsv($datos); 
$CamposSeneca=$tablaprofes[0];

    }

   

?>




<div class="container">
    <h3> <b>IMPORTAR CORREO-E Y TELÉFONO A LOS DATOS DEL PROFESORADO </b></h3>
    </div>
       
    
    
    <!-- Formulario de opciones por partes-->
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Datos" class="btn btn-primary" formaction="">
   </div>
   
   
   <div>
            <h5 class="text-secondary">Pegar texto exportado csv del fichero de exportación de Google Contact aquí (ctrl+A y ctrl+C)</h5>
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
    <h5 class="text-center font-weight-bold">RELACIÓN CON LOS CAMPOS DE GOOGLE-CONTACT</h5>
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
    <input type="submit" name="anadirprofes" value="Importar Correo y Teléfono al Profesorado en la base de datos" class="btn btn-primary" formaction="">
   </div>

    </form>

    
<?php    
    // Cuando pulsa añadir profesores
    if (isset($_POST['anadirprofes']))
    {
    $datos2= $_SESSION['datos2'];
    
    $tablaprofes=capturatablascsv($datos2);  
    $CamposSeneca=$tablaprofes[0];
    
    $CSeneca="";
    foreach ($ArrayNuestroCampos as $key => $value)
    {
        $CSeneca=$CSeneca.$_POST["$value"]."--";
    }   

    $CSeneca=substr($CSeneca,0,strlen($CSeneca)-2);
    $edit = $conn->query("UPDATE tdatos set L_SenecaCampo='$CSeneca' where L_IdRelacionCampos='ImpProfesCorreo'");
    
    foreach(range(1,count($tablaprofes)-1) as $n)
{ 
    foreach($tablaprofes[$n] as $columna=>$vcampo)
    {  
        $valor[$tablaprofes[0][$columna]]=$vcampo;
    }
    
    unset($vprofesor,$vcorreo,$vtelefonoprofesor);
    $vprofesor=$valor[$_POST['Profesor']];
    $vcorreo=$valor[$_POST['CorreoProfesor']];
    $vtelefonoprofesor=$valor[$_POST['TelefonoProfesor']];

    extract(consultaBDunica("SELECT IdProfesor, count(IdProfesor) as cuantos FROM tprofesores 
    WHERE Profesor='$vprofesor'")); 

    if($cuantos > 0)
    {
        $conn->query("UPDATE tprofesores set TelefonoProfesor='$vtelefonoprofesor',
        CorreoProfesor='$vcorreo'
        where IdProfesor='$IdProfesor'");
    }

}
$_SESSION['datos2']="";
    }
    
    
    
?>


    
    <div class="container">
    <h4> <b>Relación de contactos del profesorado en Google - Contact: </b> </h4><br> <br>
    </div>
    <?php
    echo imprimetablaarray($tablaprofes);
    ?>  
    
    
    </center>
    <br>
    <br>
    <br>
    </html>