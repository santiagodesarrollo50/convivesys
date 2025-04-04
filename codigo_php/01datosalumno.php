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
      
//Carga idalumno    
$s_idalumno=$_SESSION['s_idalumno'];
$_SESSION['Procedencia']="01datosalumno.php";
$curso=$_SESSION['PCurso'];    

// Busca en la tabla talumnos los datos del alumno seleccionado
extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
extract(consultaBDunica("SELECT * FROM tnotasalumno WHERE AlumnoIdNo= '$s_idalumno'"));
extract(consultaBDunica("SELECT Unidad, NivelAlUn as Nivel from tunidades inner join talumnounidad 
		ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));  
?>

<center>
    <div class="container">
        <a href="01datosalumno.php"><button type="button" class="btn btn-info btn-lg" >
        DATOS DEL ALUMNO
        </button></a>
        &nbsp;&nbsp;&nbsp;
        <a href="01disciplinaalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
        DISCIPLINA
        </button></a>
        &nbsp;&nbsp;&nbsp;
        <a href="01absentismoalumno.php"><button type="button" class="btn btn-outline-info btn-lg" >
        ABSENTISMO
        </button></a>
    </div>
    <br>
    
    <div class="container">
    <h3> <b>DATOS PERSONALES DEL ALUMNO/A: </b> 
    <br>
    <?= $Alumno ?> - (<?= $Unidad ?> - <?= $Nivel ?>) </h3>
	<h4> <b>NIE: </b>  <?= $IdAlumno ?></h4>
    </div>
    <br><br>
    
    <!-- Formulario de opciones por partes-->
    <form method="POST">
        <div id="menuopcionesdatos">
            <input type="submit" name="opciondatos" value="Modificar Datos del Alumno" class="btn btn-primary" 
                formaction="03modificardatosalumno.php">
        </div>
    </form>
   
   <div class="col-md-8 mx-auto bg-light rounded p-4">
    <!-- Cabecera tabla de partes-->
    <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
          <tr>
    <th scope="col">Iniciales</th>
    <th scope="col">Fecha Nacimiento</th>
    <th scope="col">Edad</th>
    <th scope="col">Repetidor del curso actual</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $Nombre.' '.substr($Apellido1,0,1).'. '.substr($Apellido2,0,1).'.' ?></td>
        <td><?= darfechaesp($FechaNac) ?></td>
        <td><?= calcularedad($FechaNac) ?></td>
        <td><?= $Repetidor ?></td>
    </tr>
    
    </tbody>  
    </table>
    
   <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
          <tr>
    <th scope="col">Direccion</th>
    <th scope="col">Custodia</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $Direccion.". &nbsp;&nbsp;&nbsp  ".$CP." - ".$Localidad." (".$Provincia.")" ?></td>
        <td><?= $Custodia ?></td>
    </tr>
    
    </tbody>  
    </table>
        
    
      <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
    <tr>
    <th scope="col">Correo del alumno/a</th>
    <th scope="col">Correo Corporativo del alumno/a</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $CorreoAlumno ?></td>
        <td><?= $CorreoAlumnoCorp ?></td>
    </tr>
    </tbody>  
    </table>
    
    
      <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
    <tr>
    <th scope="col">Tutor 1</th>
    <th scope="col">Teléfono Tutor 1</th>
    <th scope="col">Correo Tutor 1</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $Tutor1 ?></td>
        <td><?= substr($TelefonoTutor1,0,3)." ".substr($TelefonoTutor1,3,2)." ".substr($TelefonoTutor1,5,2)." ".
        substr($TelefonoTutor1,7,2) ?></td>
        <td><?= $CorreoTutor1 ?></td>
    </tr>
    <thead>
    <tbody> 
    <tr>
    <th scope="col">Tutor 2</th>
    <th scope="col">Teléfono Tutor 2</th>
    <th scope="col">Correo Tutor 2</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $Tutor2 ?></td>
        <td><?= substr($TelefonoTutor2,0,3)." ".substr($TelefonoTutor2,3,2)." ".substr($TelefonoTutor2,5,2)." ".
        substr($TelefonoTutor2,7,2) ?></td>
        <td><?= $CorreoTutor2 ?></td>
    </tr>

    <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
    <tr>
    <th scope="col">Situación Familiar</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $SituacionFamiliar ?></td>
    </tr>
    </tbody>  
    </table>

    <table  class="table table-hover" id="tablaregistros">
      <thead>
    <tbody> 
    <tr>
    <th scope="col">Observaciones de Jefatura Estudios</th>
    </tr>
        </thead>
    <tr class="table-primary">
        <td><?= $ObservacionesAl ?></td>
    </tr>




    </tbody>  
    </table>
    </div>
    <br>
    <br>
    <br>


    
 </center>
 </body>
 </html>
  