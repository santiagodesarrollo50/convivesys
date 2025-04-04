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


if ($_SESSION['Procedencia']=='02nuevoparte.php')
{
    $_SESSION['Procedencia']="02nuevaanotacionabs.php";
    header('location:02nuevaanotacionabs.php');
}
elseif ($_SESSION['Procedencia']=='02nuevaanotacionabs.php')
{
    $_SESSION['Procedencia']="02nuevoparte.php";
    header('location:02nuevoparte.php');
}
elseif ($_SESSION['Procedencia']=='01absentismoalumno.php')
{
    $_SESSION['Procedencia']="01disciplinaalumno.php";
    header('location:02nuevoparte.php');
}
else
{
    $_SESSION['Procedencia']="01absentismoalumno.php";
    header('location:02nuevaanotacionabs.php');
}

?>