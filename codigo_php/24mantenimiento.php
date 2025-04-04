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



extract(consultaBDunica("SELECT count(IdParte) as NumPartes from tpartes"));
extract(consultaBDunica("SELECT count(IdSancion) as NumSanciones from tsanciones"));
extract(consultaBDunica("SELECT count(IdFalta) as NumFaltas from tfaltas"));
extract(consultaBDunica("SELECT count(IdProfesor) as NumProfesores from tprofesores"));
extract(consultaBDunica("SELECT count(IdAlumno) as NumAlumnos from talumnos"));
extract(consultaBDunica("SELECT count(IdUnidad) as NumUnidades from tunidades"));
?>


  
<body style="background-color:#EBEDEF;">



<div class="container">
  <div class="container-fluid">

  <H2 class="navbar-brand">ACTUALIZAR PARA UN NUEVO CURSO - ¡CUIDADO! Peligro de borrado de datos no recuperables</H2>

  <p><a  href="25modificardatosbasicos.php">
        <button type="button"   class="btn btn-dark" >1. Datos básicos del centro y del curso</button>
  </a></p>
    
  <p><h5>2. Crear una copia de todos los registros del curso terminado</h5>
      <a href="22imp_disciplinaalumno_curso.php">
       <button type="button" class="btn btn-dark" >Disciplina</button>
        </a>
      <a href="22imp_absentismoalumno_curso.php">
       <button type="button" class="btn btn-dark" >Absentismo</button>
        </a>
      <a href="23estadistica.php">
       <button type="button" class="btn btn-dark" >Estadísticas</button>
        </a>
      
  </p> 

  <p><a  href="24borrardisciplina.php">
        <button type="button" class="btn btn-danger" >
        3. Borrar todos los registros de disciplina.
        </button>
  </a>(Hay <?= $NumPartes ?> partes y <?= $NumSanciones ?> sanciones registradas)</p>       
  
    <p><a  href="24borrarfaltasasistencia.php"><button type="button" class="btn btn-danger" >
      4. Borrar todos los registros de faltas de asistencia del alumnado</button></a> (Hay <?= $NumFaltas ?> faltas registradas)</p>      
  
    <p><a  href="24borrarprofesorado.php"><button type="button" class="btn btn-danger" >5. Borrar todo el profesorado
    </button></a> (Hay <?= $NumProfesores ?> profesores registrados)</p>      

    <p><a  href="24borrarunidades.php">
        <button type="button" class="btn btn-danger" >
        6. Borrar todas las unidades 
        </button></a>  (Hay <?= $NumUnidades ?> unidades registradas)</p> 

    <p><a  href="24borraralumnado.php "><button type="button" class="btn btn-danger" >
        7. Borrar todo el alumnado
    </button></a>(Hay <?= $NumAlumnos ?> alumnos registrados)</p>
      
    <p><a  href="24anadirprofesorado.php"><button type="button" 
    class="btn btn-info" >8. Importar Profesorado</button></a></p>
    
    <p><a  href="24anadirprofesoradocorreotlf.php"><button type="button" 
    class="btn btn-info" >9. Importar Correos y Teléfono del Profesorado</button></a></p>

    <p><a  href="24anadirunidades.php"><button type="button" 
    class="btn btn-info" >10. Importar Unidades</button></a></p> 

    <p><a  href="24anadiralumnos.php"><button type="button" 
    class="btn btn-info" >11. Importar Alumnado</button></a></p>
  
    <p><a  href="24anadircorreoalumnos.php"><button type="button" 
    class="btn btn-info" >12. Importar Correos corporativos del Alumnado</button></a></p>

    <p><a  href="24borrarabsentistas.php"><button type="button" 
    class="btn btn-danger" >13. Borrar anotaciones de absentismo</button>
  </a> Solo del alumnado +16 años o no matriculado (esperar a octubre con matricula estable)</p> 

    <p><a  href="24modificartutores.php"><button type="button" 
    class="btn btn-dark" >14. Modificación asignación de profesores tutores</button></a></p>

    <p><a  href="24modificarprofes.php"><button type="button" 
    class="btn btn-dark" >14. Modificación datos profesorado</button></a></p>

  </div>
  </div>

