<?php
/*
 * Copyright (C) [2025] [Santigo Morales Domingo]
 *
 * Este programa se distribuye con la esperanza de que sea �til, pero
 * SIN NINGUNA GARANT�A.
 * 
 * El software ha sido dise�ado por un programador novato. Por la seguridad 
 * de sus datos es recomendable instalarlo y usarlo en un servidor web local 
 * desconectado de internet. La seguridad de los datos es su responsabilidad.
 */
session_start();

if (strpos($_SESSION['Procedencia'],'disciplina')>0)
{header('location:21unidaddisciplina.php');}
else
{header('location:21unidadabsentismo.php');}

?>