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

$consulta = "DELETE FROM tpartes";
$resSelect = $conn->prepare($consulta);
$resSelect->execute();    
    
$consulta = "DELETE FROM tsanciones";
$resSelect = $conn->prepare($consulta);
$resSelect->execute();

$consulta = "DELETE FROM tsancionparte";
$resSelect = $conn->prepare($consulta);
$resSelect->execute();

header('location:24mantenimiento.php'); 
?>
   
