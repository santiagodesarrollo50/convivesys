
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
$ArrayIdPartes =$_POST['checkboxid'];

// Comprueba que solo ha sido seleccionado un parte
if (count($ArrayIdPartes)!=1)
{

$_SESSION['aviso']=1; //indicará que para esta opcion solo hay que elegir 1 parte
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
}




// Comprueba que no deja la sanción sin parte asociado
$consulta4 = "Select count(SancionIdSaPa) FROM tsancionparte WHERE SancionIdSaPa=$IdSancion";
    $resSelect4 = $conn->prepare($consulta4);
$resSelect4->execute(); 
$row4 = $resSelect4->fetch();
    extract($row4);
    
    if ($row4['count(SancionIdSaPa)']==1)
    {
    $_SESSION['aviso']=2; //indicará que no se puede eliminar la relación parte-sanción ya que se quedaría la sanción sin partes asociados
    $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    }
    
    else
    
    {    // Borra la relación entre parte y sanción
$consulta = "DELETE FROM tsancionparte WHERE (SancionIdSaPa=$IdSancion && ParteIdSaPa=$ArrayIdPartes[0])";
    $resSelect = $conn->prepare($consulta);
$resSelect->execute();


// Vuelve a mostrar alumno o pendientes
$Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    }
    
?>
