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

require_once ('00funciones.php');
$conn = conectarBaseDeDatos();

//Para mostrar mensajes de error 
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// O para no mostrar mensajes de error
ini_set('display_errors', 0); ini_set('display_startup_errors', 0); error_reporting(0);

// Topes para resaltar faltas
$_SESSION['TopeI']=17;
$_SESSION['TopeJ']=29;
$_SESSION['TopeR']=7;
$_SESSION['PCurso']=cursoescolarcorto ( );
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <title>ConviveSys V.0</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="X-UA-Compatible" content="ie=edge">  -->
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  </head>
  
  <body style="background-color:#EBEDEF;">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js">
    </script>
    <!-- Script para que funcionen los cuadros popover -->
    <script type="text/javascript">
      $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
      });
    </script> 

    <header>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">          
          <div>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <h4>CONVIVESYS</h4><br />                    
                </li>                
                <li class="nav-item">
                <a  href="00buscaralumno.php" >
                    <button type="button" class="btn btn-dark" title="Ver partes, sanciones, anotaciones de absentismo,...">Ver Registros de un Alumno</button>
                </a>
                </li>
                <li class="nav-item">
                <a  href="02nuevoregistro.php" >
                    <button type="button" class="btn btn-light" title="Escribir un nuevo parte o nueva anotación de absentismo">Nuevo Registro</button>
                </a>
                </li>
                <li class="nav-item">
                <a  href="02disciplinapendiente.php">
                    <button type="button" class="btn btn-dark" title="Ver partes, sanciones, anotaciones de absentismo cuya gestión no ha finalizado">Ver Registros Pendientes</button>
                </a>
                </li>
                <li class="nav-item">
                <a  href="21unidadregistros.php">
                    <button type="button" class="btn btn-light" title="Ver partes, sanciones, anotaciones de absentismo de los alumnos de una unidad">Ver Registros de una Unidad</button>
                </a>
                </li>
                <li class="nav-item">
                <a  href="17informes.php">
                    <button type="button" class="btn btn-dark" title="Estadísticas de convivencia, Informe mensual de sanciones,...">Informes y Seguimientos</button>
                </a>
                </li>
                <li class="nav-item">
                <a  href="24mantenimiento.php">
                    <button type="button" class="btn btn-danger" title="">Configuración y Mantenimiento</button>
                </a>
                </li>
                <li class="nav-item">
                    <a class="navbar-brand" href="disciplina.php"><?php echo 'CURSO ' . $_SESSION['PCurso'] ?></a>
                </li>
            </ul>
          </div>
        </div>
        </nav>     
      </header>
<br /><br />