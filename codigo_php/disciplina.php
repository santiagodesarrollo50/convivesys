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
if (isset($_POST["bbddhost"])) {
  $_SESSION['bbddhost']=$_POST['bbddhost'];
  $_SESSION['bbdduser']=$_POST['bbdduser'];
  $_SESSION['bbddname'] = $_POST['bbddname'];
  $_SESSION['bbddpass']=$_POST['bbddpass'];
}
include ('13cabecera.php');

//Mantenimiento: Los partes con más de 7 días cambian su estado a finalizados.
// Las sanciones en las que ha pasado 1 días desde su cumplimiento pasan a finalizado
$consulta1="UPDATE tpartes SET EstadoPa= 'F' WHERE DATEDIFF(curdate(),FechaPa)>7"; //días de plazo
$consulta2="UPDATE tsanciones SET EstadoSa= 'F' WHERE DATEDIFF(curdate(),FechaFin)>1"; //días de plazo
$conn->query($consulta1);
$conn->query($consulta2);
?>
    
<section>    
  <?php
    //Muestra cabecera con fecha, número de partes ,...
    extract(consultaBDunica("SELECT count(IdParte) as NumPartes from tpartes"));
    echo "<center>";
    echo "<h2>" . "Bienvenidos ". "</h2>" . "<br>";
    $dia=date('d');
    $mes=date('m');
    echo "<h3> ".fechaformatogranada()."</h3> <br> <br>";
    echo "<h3>" . "Hay " . $NumPartes . " partes" . "</h3><br> <br>";
    $anofincurso=date("Y",strtotime(iniciocursoacutal ( )))+1;
    $fechafincurso=$anofincurso."-06-30";
    $quedan=contardiaslectivos(date("Y-m-d"), $fechafincurso);
    echo "<h3> Quedan  ".$quedan." dias lectivos para terminar el curso </h3><br> <br>";
    echo "</center>";
  ?>
</section>

</body>
</html>


