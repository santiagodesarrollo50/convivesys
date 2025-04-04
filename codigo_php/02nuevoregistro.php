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
session_start();
$_SESSION['s_alumno_unidad']="";

if (strpos($_SESSION['Procedencia'],'abs')>0)
{
    $_SESSION['Procedencia']="02nuevaanotacionabs.php";
    header('location:02nuevaanotacionabs.php');
}
else
{
    $_SESSION['Procedencia']="02nuevoparte.php";
    header('location:02nuevoparte.php');
}

?>