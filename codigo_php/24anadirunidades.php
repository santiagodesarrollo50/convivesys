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

extract(consultaBDunica("SELECT * from tdatos where L_IdRelacionCampos='ImpUnidades'"));
$ArrayNuestroCampos=explode("--",$L_NuestroCampo);
$ArraySenecaCampos=explode("--",$L_SenecaCampo);  

// Cuando pulsa cargar datos
if (isset($_POST['grabar']))  {    
    $datos= $_POST['datos'];
    $_SESSION['datos2']=$datos;
    $tablaunidades=capturatablascsv($datos);  
    $CamposSeneca=$tablaunidades[0];
}
?>


<div class="container">
    <h3> <b>IMPORTAR UNIDADES </b></h3>
    </div>         
    
    <!-- Formulario de opciones por partes-->
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Datos" class="btn btn-primary" formaction="">
   </div>   
   <div>
            <h5 class="text-secondary">Pegar texto exportado csv de la pantalla Relación de Unidades de Séneca aquí (ctrl+A y ctrl+C)</h5>
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
    <h5 class="text-center font-weight-bold">RELACIÓN CON LOS CAMPOS DE SÉNECA</h5>
    <hr class="my-1">
    

<?php

foreach ($ArrayNuestroCampos as $key => $value) {
    echo "Asigna un campo de Séneca para el campo ".$value;
    ?>
    <select name="<?php echo $value; ?>" required class="form-control form-control-lg rounded-0 border-info">
    <option value="sin asignar" >sin asignar</option>
    <?php
    foreach ($CamposSeneca as $value2) {
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
<br><br>
    <input type="submit" name="anadirunidades" value="Importar Unidades a la base de datos" class="btn btn-primary" formaction="">

<br>
    Curso Escolar:
<select name="cursoescolar" required class="form-control form-control-lg rounded-0 border-info"> 
<option value="<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."- 1 year")))?>">
<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."- 1 year")));?></option>
<option value="<?php echo cursoescolarcorto()?>" selected><?php echo cursoescolarcorto()?></option>
<option value="<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."+ 1 year")))?>">
<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."+ 1 year")));?></option>
</select>
   </div>

    </form>

    
<?php    
    // Cuando pulsa añadir profesores
    $datos2= $_SESSION['datos2'];    
    $tablaunidades=capturatablascsv($datos2);  
    $CamposSeneca=$tablaunidades[0];    
    $CSeneca="";
    foreach ($ArrayNuestroCampos as $key => $value)    {
        $CSeneca=$CSeneca.$_POST["$value"]."--";
    }  
    $CSeneca=substr($CSeneca,0,strlen($CSeneca)-2);
    $edit = $conn->query("UPDATE tdatos set L_SenecaCampo='$CSeneca' where L_IdRelacionCampos='ImpUnidades'");
    $vcursoescolar=$_POST['cursoescolar'];

    if (isset($_POST['anadirunidades'])) {
        extract(consultaBDunica("SELECT count(Unidad) as cuantoss FROM tunidades 
        WHERE Unidad='sin unidad' and CursoUn='$vcursoescolar'"));
        if ($cuantoss==0) {
            $insert = "INSERT INTO tunidades (Unidad, Orden, CursoUn)
            VALUES ('sin unidad', 500, '$vcursoescolar')";  
            $conn->query($insert);
            echo $insert;
        }
    
        foreach(range(1,count($tablaunidades)-1) as $n) { 
            foreach($tablaunidades[$n] as $columna=>$vcampo) {  
                $valor[$tablaunidades[0][$columna]]=$vcampo;
            }    
            unset($vunidad,$vnivel,$vprofesoridun,$IdProfesor,$vbloque,$vturnotarde);
            $vturnotarde=$valor[$_POST['TurnoTarde']];
            $vunidad=$valor[$_POST['Unidad']];
            $vnivel=$valor[$_POST['Nivel']];
            $vprofesoridun=$valor[$_POST['ProfesorIdUn']]; 
            extract(consultaBDunica("SELECT count(Unidad) as cuantos FROM tunidades 
                WHERE Unidad='$vunidad' and CursoUn='$vcursoescolar'")); 

            //busca Idprofesor cuyo nombre esta incluido en el campo de seneca del tutor
            extract(consultaBDunica("SELECT IdProfesor FROM tprofesores 
            WHERE LOCATE (Profesor , '$vprofesoridun')"));
    
            //Determina el bloque al que pertenece la unidad 
            if (strpos($vnivel,"C.F.G.B.")>0)
                {$vbloque="C.F.G.B.";}
            elseif (strpos($vnivel,"E.S.O.")>0)
                {$vbloque="E.S.O.";}    
            elseif (strpos($vnivel,"Bachillerato")>0)
                {$vbloque="Bachillerato";}
            elseif (strpos($vnivel,"Bach.Pers.Adul.")>0)
                {$vbloque="Bach.Pers.Adul.";}
            elseif (strpos($vnivel,"O.C.C.E.G.S.")>0)
                {$vbloque="Curso Especialización";}   
            elseif (strpos($vnivel,"G.M.")>0)
                {$vbloque="Grado Medio";}
            elseif (strpos($vnivel,"G.S.")>0)
                {$vbloque="Grado Superior";}         
            else
                {$vbloque="Otros";}

            if($cuantos == 0) {            
               $insert = "INSERT INTO tunidades (Unidad, ProfesorIdUn, Nivel, Bloque, Orden, TurnoTarde, CursoUn)
               VALUES ('$vunidad','$IdProfesor' ,'$vnivel','$vbloque', $n, '$vturnotarde', '$vcursoescolar')";
                $resSelect = $conn->prepare($insert);
                $resSelect->execute(); 
            } else {        
                $conn->query("UPDATE tunidades set ProfesorIdUn='$IdProfesor',
                    Nivel='$vnivel', Bloque='$vbloque', Orden='$n', TurnoTarde='$vturnotarde'
                    where Unidad='$vunidad' and CursoUn='$vcursoescolar'");
            }
        }
        $_SESSION['datos2']="";
    }
    
    
    
?>


    
    <div class="container">
    <h4> <b>Relación de unidades del centro: </b> </h4><br> <br>
    </div>
    <?php
    echo imprimetablaarray($tablaunidades);
    ?>  
    
    
    </center>
    <br>
    <br>
    <br>
    </html>