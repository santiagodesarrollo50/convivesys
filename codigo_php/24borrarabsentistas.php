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
session_start();
$curso=$_SESSION['PCurso'];

//Borra las anotaciones del alumnado no matriculado en el presente curso
$sql="DELETE from tanotacionesabs where AlumnoIdAn not in
(SELECT AlumnoIdAlUn from talumnounidad where CursoAlUn='$curso')";
$conn->query($sql);

//Borra las anotaciones del alumnado mayor de 16
$sql2="DELETE from tanotacionesabs where AlumnoIdAn in 
(SELECT IdAlumno from talumnos where Timestampdiff(YEAR, FechaNac, curdate())>15)";
$conn->query($sql);



header('location:24mantenimiento.php');

     
     
?>
   
