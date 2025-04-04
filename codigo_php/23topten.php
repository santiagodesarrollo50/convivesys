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


    ?>
    <center>
      
      <div class="container">
    <h3> <b>ALUMNADO CONFLICTIVO (>5 PUNTOS DISCIPLINA):</b></h3>
    </div>
    
    
    
 

    <table  class="table table-hover" id="tablaregistros">
      
    <thead>
            <tbody>
          <tr>
    <th scope="col">Posición</th>
    <th scope="col">Alumno</th>
    <th scope="col">Unidad</th>
    <th scope="col">Puntos disciplina <br> (Contrario=1;Grave=2)</th>
    <th scope="col">Nº de partes</th>
    <th scope="col">Porcentaje de partes sobre el total</th>
        </tr>
        </thead>
   
<?php
   
   
    //lista al alumnado 
    extract(consultaBDunica("SELECT count(idparte) as numeroPartesTotales from tpartes"));
    $consulta3 = "SELECT 2*count(case substring(p.TipoPa,1,1) when 'G' then '1' end)+count(case substring(p.TipoPa,1,1) when
      'C' then '1' end) 
      as puntosdis,
	    count(p.IdParte) as NuPartes, a.Alumno as Alumno, IdAlumno
      FROM tpartes p inner join talumnos a on a.idalumno=p.alumnoidpa group by AlumnoIdPa 
      having puntosdis >=4     order by puntosdis Desc , NuPartes Desc";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();    
    
    $a=0;
    while($a<15 && ($row3 = $resSelect3->fetch()) ) {
      extract($row3);   
      extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
		    ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));
      if ($a % 2 == 0) {
        $color="table-primary";
      } else {$color="table-secondary";}//color para las filas impares
      
?>


<tr class="<?php echo $color ?>" >

<td><?= $a+1  ?></td>
<td><?= $Alumno ?></td>
<td><?= $Unidad ?></td>
<td><?= $puntosdis ?></td>
<td><?= $NuPartes ?></td>
<td><?= round($NuPartes*100/$numeroPartesTotales) ?>  &percnt;</td>
</tr>
        
        <?php 
$a++;
}
    
    ?>
    </tbody>  
    </table>  
  

<br>
<br>
<br>
<br>
    <div class="container">
    <h3> <b>PROFESORADO:</b></h3>
    </div>
    
    
    
 

    <table  class="table table-hover" id="tablaregistros">
      
    <thead>
            <tbody>
          <tr>
    <th scope="col">Posición</th>
    <th scope="col">Profesor</th>
    <th scope="col">Nº Partes</th>
    <th scope="col">Porcentaje de partes sobre el total</th>
        </tr>
        </thead>
   
<?php
   

    //lista al profesorado
    $consulta3 = "SELECT  pr.profesor as Profesor, count(pa.TipoPa) as npartes FROM tpartes pa inner join tprofesores pr
	on pa.profesoridpa=pr.idprofesor group by pa.ProfesorIdPa order by npartes Desc";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    
    $a=0;
    foreach(range(0,14) as $a)
    {
    unset($row3,$Profesor,$npartes);
	$row3 = $resSelect3->fetch();
    extract($row3);
     

if ($a % 2 == 0) 
{ $color="table-primary";     }//color para las filas pares
else {$color="table-secondary";}//color para las filas impares
?>


<tr class="<?php echo $color ?>" >

<td><?= $a+1  ?></td>
<td><?= $Profesor ?></td>
<td><?= $npartes ?></td>
<td><?= round($npartes*100/$numeroPartesTotales) ?>  &percnt;</td>

</tr>
        
        <?php 
        }
    
    ?>
    </tbody>  
    </table>  
  
 
  </center>
  <br>  <br> 
  <br>  <br>
  

    
    </body>
    </html> 
    