
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
session_start();


// Comprueba que solo ha sido seleccionado un parte
if (count($_POST['checkboxsancion'])!=1)
{
$_SESSION['aviso']=4; //indicará que para esta opcion solo hay que elegir 1 parte
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}

$IdSancion = $_POST['checkboxsancion'][0];




error_reporting(E_ALL);
ini_set('display_errors', 1);




$consulta = "DELETE FROM tsanciones WHERE IdSancion=$IdSancion";
    $resSelect = $conn->prepare($consulta);
 try {   $resSelect->execute();  }
catch ( PDOException $e){
   echo $e->getMessage(); }
   
 $consulta = "DELETE FROM tsancionparte WHERE SancionIdSaPa=$IdSancion";
    $resSelect = $conn->prepare($consulta);
 try {   $resSelect->execute();  }
catch ( PDOException $e){
   echo $e->getMessage(); }  
   
   
    
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
   
    
?>
