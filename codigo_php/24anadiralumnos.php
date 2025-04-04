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
set_time_limit(600);
//Introduce o actualiza el alumno en la tabla TAlumno. Despues introduce, actualiza la unidad del alumno en
// la tabla talumnounidad, o no hace nada si no tiene unidad asignada en séneca.



// Correspondencia entre los campos de nuestra base de datos y los campos de Séneca

extract(consultaBDunica("SELECT * from tdatos where L_IdRelacionCampos='ImpAlumnos'"));

$ArrayNuestroCampos=explode("--",$L_NuestroCampo);
$ArraySenecaCampos=explode("--",$L_SenecaCampo);
// Cuando pulsa cargar datos
if (isset($_POST['grabar'])) {
    $datos= str_replace('\'',"",$_POST['datos']);
    $conn->query("DROP TABLE ttemporal");    
    $AFilas=explode("\n",$datos);
    $CamposSeneca=str_getcsv($AFilas[0]);
    $_SESSION['ncamposseneca']=count($CamposSeneca);
    $sql="CREATE TABLE ttemporal (C1 text";
    $sql3="INSERT INTO ttemporal VALUES (? ";
    foreach (range(2,count($CamposSeneca)) as $n) {
        $sql=$sql.", C".$n." text";
        $sql3=$sql3.", ?";
    }
    $sql=$sql." );";
    $sql3=$sql3." );";
    $conn->query($sql);
    $insertador=$conn->prepare($sql3);
    foreach($AFilas as $nfila => $fila) {
        $insertador->execute(str_getcsv($fila));
    }    
}
	
?>




<div class="container">
    <h3> <b>IMPORTAR ALUMNADO </b></h3>
    No se importará las matriculas de alumnos que ya tienen una matricula registrada, y que su estado sea anulada,
     trasladada, o baja de oficio
    </div>
       
    
    
    <!-- Formulario de opciones por partes-->
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Datos" class="btn btn-primary" formaction="">
   </div>
   
   
   <div>
            <h5 class="text-secondary">Pegar texto exportado csv de la pantalla Alumnado del Centro de Séneca aquí (ctrl+A y ctrl+C)</h5>
          </div>
           <div class="input-group">
               <textarea autofocus name="datos" rows="2" cols="2" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            </div>
        <br><br><br>
        <center>




  <div class="container"> <!-- centra el contenido -->

  <div id="menuopciones" >
<input type="submit" name="anadiralumnos" value="Importar alumnos a la base de datos" class="btn btn-primary" formaction="">
<br><br><br> 

Curso Escolar:
<select name="cursoescolar" required class="form-control form-control-lg rounded-0 border-info"> 
<option value="<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."- 1 year")))?>">
<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."- 1 year")));?></option>
<option value="<?php echo cursoescolarcorto()?>" selected><?php echo cursoescolarcorto()?></option>
<option value="<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."+ 1 year")))?>">
<?php echo cursoescolarcorto(date("Y-m-d",strtotime(date("Y-m-d")."+ 1 year")));?></option>
</select>

</div>


<div class="row mt-4">
  <div class="col-md-8 mx-auto bg-light rounded p-4">
    <h5 class="text-center font-weight-bold">RELACIÓN CON LOS CAMPOS DE SÉNECA</h5>
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

    </form>

    
<?php    
    // Cuando pulsa añadir alumnnos
    if (isset($_POST['anadiralumnos']))
{
    
    $conn->query("ALTER TABLE ttemporal ADD Id INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (Id);");
    
    
    $CSeneca="";
    unset($NuCampo_C);
    extract(consultaBDunica("SELECT * from ttemporal where Id=1"));
    $cantidad=$_SESSION['ncamposseneca'];
    foreach ($ArrayNuestroCampos as $value) {
        $CSeneca=$CSeneca.$_POST["$value"]."--";
        foreach(range(1,$cantidad) as $n) {
            $C="C".$n;
            if ($_POST["$value"]==$$C) {
                $NuCampo_C[$value]=$C;
            }            
        }
    }   
    
	$CursoE=$_POST["cursoescolar"];
    $CSeneca=substr($CSeneca,0,strlen($CSeneca)-2);
    $edit = $conn->query("UPDATE tdatos set L_SenecaCampo='$CSeneca' where L_IdRelacionCampos='ImpAlumnos'");
    
    //Elimina de ttemporal los alumnos con matricula anulada, baja o trasladada
    $sql="DELETE FROM ttemporal 
    where ".$NuCampo_C['EstadoMatricula']."='Anulada' or ".$NuCampo_C['EstadoMatricula']."='Trasladada' 
    or ".$NuCampo_C['EstadoMatricula']."='Baja de oficio'";
    $conn->query($sql);

    //Elimina de ttemporal los alumnos duplicados y se queda con el de Id más alto
    $sql="DELETE FROM ttemporal WHERE ".$NuCampo_C['IdAlumno']." in
    (select ".$NuCampo_C['IdAlumno']." from ttemporal 
    group by ".$NuCampo_C['IdAlumno']." having count(Id)>1)
    and Id not in
    (select max(Id) from ttemporal group by ".$NuCampo_C['IdAlumno'].")";
    $conn->query($sql);

    //Elimina la primera fila con las cabeceras
    $sql="DELETE FROM ttemporal WHERE id=1";
    $conn->query($sql);

    //Calculo de algunos campos
    $sql="UPDATE ttemporal set ".$NuCampo_C['FechaNac']."=STR_TO_DATE(".$NuCampo_C['FechaNac'].
    ", '%d/%m/%Y')";
    $conn->query($sql);

    $sql="UPDATE ttemporal set ".$NuCampo_C['Repetidor']."='si' where ".$NuCampo_C['Repetidor'].">1";
    $conn->query($sql);

    $sql="UPDATE ttemporal set ".$NuCampo_C['Repetidor']."='no' where ".$NuCampo_C['Repetidor']."<>'si'";
    $conn->query($sql);

    //$Iniciales=$Nombre." ".mb_substr($Apellido1,0,1).". ".mb_substr($Apellido2,0,1).".";
    //$Iniciales=str_replace('\'',"",$Iniciales);

    $sql="UPDATE ttemporal set ".$NuCampo_C['NombreTu1']."= 
    concat(".$NuCampo_C['NombreTu1'].",' ',".$NuCampo_C['Apellido1Tu1'].",' ',".$NuCampo_C['Apellido2Tu1']."),
    ".$NuCampo_C['NombreTu2']."= 
    concat(".$NuCampo_C['NombreTu2'].",' ',".$NuCampo_C['Apellido1Tu2'].",' ',".$NuCampo_C['Apellido2Tu2'].")";
    $conn->query($sql);
    
    
    
  
    //Añadimos actualizamos la unidad en talumnounidad si tenemos el dato unidad o curso en seneca

   $sql="REPLACE INTO talumnos (IdAlumno, Alumno, Apellido1, Apellido2, Nombre, Direccion, CP, Localidad, Provincia,
    FechaNac, CorreoAlumno, TelefonoAl, Tutor1, CorreoTutor1, TelefonoTutor1, Tutor2, CorreoTutor2, TelefonoTutor2, Repetidor,
     Custodia) 
     SELECT ".$NuCampo_C['IdAlumno'].", ".$NuCampo_C['Alumno'].", ".$NuCampo_C['Apellido1'].", ".$NuCampo_C['Apellido2'].", ".$NuCampo_C['Nombre'].", 
     ".$NuCampo_C['Direccion'].", ".$NuCampo_C['CP'].", ".$NuCampo_C['Localidad'].", ".$NuCampo_C['Provincia'].", ".$NuCampo_C['FechaNac'].", 
     ".$NuCampo_C['CorreoAlumno'].", ".$NuCampo_C['TelefonoAl'].", ".$NuCampo_C['NombreTu1'].", ".$NuCampo_C['CorreoTutor1'].", ".$NuCampo_C['TelefonoTutor1'].", 
     ".$NuCampo_C['NombreTu2'].", ".$NuCampo_C['CorreoTutor2'].", ".$NuCampo_C['TelefonoTutor2'].", ".$NuCampo_C['Repetidor'].", ".$NuCampo_C['Custodia']."
      FROM ttemporal order by ".$NuCampo_C['UnidadUn'];
      $conn->query($sql);
    
    //primero insertamos en Talumnounidad sin unidad (por si no está asignada todavía)
    $sql1="REPLACE INTO talumnounidad (AlumnoIdAlUn, NivelAlUn, CursoAlUn) 
    SELECT a.".$NuCampo_C['IdAlumno'].", a.".$NuCampo_C['Nivel'].", '$CursoE' from 
    (select ".$NuCampo_C['IdAlumno'].", ".$NuCampo_C['Nivel']." from ttemporal) a";
    $conn->query($sql1);
    

    //después metemos la unidad 
    $sql2="UPDATE talumnounidad set UnidadIdAlUn= 
    (SELECT b.IdUnidad from 
    ((SELECT ".$NuCampo_C['UnidadUn'].", ".$NuCampo_C['IdAlumno']." from ttemporal) a inner join (SELECT IdUnidad, Unidad, CursoUn 
    from tunidades where CursoUn='$CursoE') b on a.".$NuCampo_C['UnidadUn']."=b.Unidad )
    where ".$NuCampo_C['IdAlumno']."=AlumnoIdAlUn)
    where CursoAlUn='$CursoE' and AlumnoIdAlUn in
    (SELECT ".$NuCampo_C['IdAlumno']." from ttemporal)";
    $conn->query($sql2);
    

    //si tiene unidad null en la tabla talumnounidad cambiar por sin unidad para que se pueda buscar el alumno
    extract(consultaBDunica("SELECT IdUnidad from tunidades where Unidad='sin unidad' and CursoUn='$CursoE'"));
    $sql3="UPDATE talumnounidad set UnidadIdAlUn=$IdUnidad where UnidadIdAlUn is null";
    $conn->query($sql3);
}
    
   
    
    
    
?>
    
    
    </center>
    <br>
    <br>
    <br>
    </html>