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
include ('13cabecera.php');
      $curso=$_SESSION['PCurso'];
      
      //Carga idalumno    
    $s_idalumno=$_SESSION['s_idalumno'];
    
    
    $mes=$_POST['OtroDato'];
    if (isset($_POST['mesnumero']))
    {    $mes=numeromes($_POST['mesnumero']);}
    $TopeI=$_SESSION['TopeI'];
    $TopeJ=$_SESSION['TopeJ'];
    $TopeR=$_SESSION['TopeR'];

    // Busca en la tabla talumnos los datos del alumno seleccionado
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
    on IdUnidad= UnidadIdAlUn where CursoUn='$curso' and AlumnoIdAlUn='$IdAlumno'"));

    //Array de la tabla de faltas
    $consulta1 = "SELECT * FROM tfaltas WHERE MONTH(FechaFa)='$mes' and AlumnoIdFa='$IdAlumno' order by FechaFa";
    $resSelect1 = $conn->prepare($consulta1);
    $resSelect1->execute();
    while($row1=$resSelect1->fetch())
    {
    extract($row1);
    $Arrayfaltas['dias'][]=nombrediasemana(date('N',strtotime($FechaFa)))."-".date('j',strtotime($FechaFa));
    $Arrayfaltas['I'][]=$HorasI;
    $Arrayfaltas['J'][]=$HorasJ;
    $Arrayfaltas['R'][]=$HorasR;
    }
    
    
    extract(consultaBDunica("Select sum(HorasI) as HFI FROM tfaltas WHERE MONTH(FechaFa)=$mes and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select sum(HorasJ) as HFJ FROM tfaltas WHERE MONTH(FechaFa)=$mes and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select sum(HorasR) as HR FROM tfaltas WHERE MONTH(FechaFa)=$mes and AlumnoIdFa='$IdAlumno'")); 
    extract(consultaBDunica("Select max(FechaActualizacion) as Frevision FROM tfaltas WHERE MONTH(FechaFa)=$mes"));
    
?>


<div class="container">
    <a href="01datosalumno.php"><button type="button" class="btn btn-outline-dark btn-lg" >
    DATOS DEL ALUMNO
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01disciplinaalumno.php"><button type="button" class="btn btn-outline-dark btn-lg" >
    DISCIPLINA
    </button></a>
    &nbsp;&nbsp;&nbsp;
    <a href="01absentismoalumno.php"><button type="button" class="btn btn-outline-dark btn-lg" >
    ABSENTISMO
    </button></a>
    </div>
    <br>


    <form method="POST" >

<!-- DETALLE MENSUAL DE FALTAS POR ALUMNO    -->
    <div class="container">
    <h3> <b>DETALLE MENSUAL DE FALTAS DEL ALUMNO/A: <br> </b><?= $Alumno ?> - (<?= $Unidad ?>) </h3>
    <br>
    <h4> <b>Mes: </b>
    <select name="mesnumero">
            <?php
            foreach([9,10,11,12,1,2,3,4,5,6] as $Opcion) 
            {
            if ($Opcion==$mes)
            { $seleccionado="selected"; } else {$seleccionado ="";}
            ?>
            <option <?php echo $seleccionado ?>><?php echo nombremes($Opcion);?></option>
            <?php 
            }
            ?>
    </select>
    <input type="submit" name="cambiarmes" value="Cambiar Mes" class="btn btn-primary" formaction="20faltasmesalumno.php"> 
            - (Revisado el día: <?= darfechaesp($Frevision) ?>) </h4>
                
    </div>
    <br>
    <br>
    
    <table  class="table table-hover" id="tablaregistros">
    <thead>
    <tbody> 
    <tr>
    <th scope="col"></th>
    <th scope="col">TOTAL</th>
    <?php 
    foreach($Arrayfaltas['dias'] as $dia)
    {   echo '<th scope="col">'.$dia.'</th>';  }
    ?>
    </tr>
    </thead>
    
    
    <tr class="table-success" >
    <th scope="row">Horas I</th>
    <th scope="row"><?= resaltarnumero($HFI,$TopeI) ?></th>
    <?php
    foreach($Arrayfaltas['I'] as $horas)
    {   echo '<th scope="col">'.resaltarnumero($horas,1).'</th>';  }
    ?>
    </tr>
    
    <tr class="table-secondary" >
    <th scope="row">Horas J</th>
    <th scope="row"><?= resaltarnumero($HFJ,$TopeJ) ?></th>
    <?php
    foreach($Arrayfaltas['J'] as $horas)
    {   echo '<th scope="col">'.resaltarnumero($horas,1).'</th>';  }
    ?>
    </tr>
    
    <tr class="table-success" >
    <th scope="row">Horas R</th>
    <th scope="row"><?= resaltarnumero($HR,$TopeR) ?></th>
    <?php
    foreach($Arrayfaltas['R'] as $horas)
    {   echo '<th scope="col">'.resaltarnumero($horas,1).'</th>';  }
    ?>
    </tr>

    </tbody>  
    </table>
</div>
  
  <br>  <br> <br>  <br>


