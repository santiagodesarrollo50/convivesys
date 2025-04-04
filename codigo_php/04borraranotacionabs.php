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


// Comprueba que solo ha sido seleccionado una anotacion
if (count($_POST['anotacionid'])!=1)
{
$_SESSION['aviso']=1; //indicará que para esta opcion solo hay que elegir 1 parte
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}


$Idanotacion = $_POST['anotacionid'][0];




$consulta = "DELETE FROM tanotacionesabs WHERE IdAnotacion='$Idanotacion'";
    $resSelect = $conn->prepare($consulta);
 try {   $resSelect->execute();  }
catch ( PDOException $e){
   echo $e->getMessage(); }
    
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    
    
     
     
?>
   
