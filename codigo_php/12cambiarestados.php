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
include '00funciones.php';
$conn = conectarBaseDeDatos();
//include "13cabecera.php";

session_start();
$ArrayTabla['TPa']=['tpartes','IdParte','#marcadorP'];
$ArrayTabla['TSa']=['tsanciones','IdSancion','#marcadorS'];
$ArrayTabla['TAn']=['tanotacionesabs','IdAnotacion','#marcadorA'];
$ArrayTabla['TAl']=['talumnos','IdAlumno','#marcadorAbs'];
$ArrayTabla['TNo']=['tnotasalumno','AlumnoIdNo','#marcadorAbs'];

$tabla=$_GET['TablaEstado'];
$nombreestado=$_GET['Estado'];
$Id=$_GET['IdEstado'];
$valor=$_GET['ValorEstado'];
$pendiente=$_GET['pendiente'];
$finalizado=$_GET['finalizado'];

$tablaupdate=$ArrayTabla[$tabla]['0'];
$idupdate=$ArrayTabla[$tabla]['1'];
$marcador=$ArrayTabla[$tabla]['2'];

if ($tabla=='TNo') {
    extract(consultaBDunica("SELECT count(*) as numero FROM tnotasalumno WHERE AlumnoIdNo='$Id'"));
    if ($numero==0) {
        $conn->query("INSERT INTO tnotasalumno (AlumnoIdNo, ABS_Seguimiento) VALUES ('$Id','NO')");
    }        
}

if ($valor==$pendiente) { 
    $edit = $conn->query("UPDATE $tablaupdate set $nombreestado='$finalizado' where $idupdate='$Id'");
} else { 
    $edit = $conn->query("UPDATE $tablaupdate set $nombreestado='$pendiente' where $idupdate='$Id'"); 
}

$Procedencia=$_SESSION['Procedencia']; 
header('location:'.$Procedencia.$marcador.$Id);
?>