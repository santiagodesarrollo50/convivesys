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




    $_SESSION['Procedencia']="18sancionesaseneca.php";
    $curso=$_SESSION['PCurso'];
   
    //         MUESTRA LAS SANCIONES DEL ALUMNO 

    // Busca en la tabla tsanciones todos las sanciones pendientes
    $consulta5 = "SELECT * FROM tsanciones inner join talumnounidad on AlumnoIdSa=AlumnoIdAlUn
     WHERE ReSeneca = 'P' and CursoAlUn='$curso' ORDER BY UnidadIdAlUn, AlumnoIdSa";
    $resSelect5 = $conn->prepare($consulta5);
    $resSelect5->execute(); 
    
?>
<!-- Función para copiar texto al portapapeles -->
<script>
function copiarAlPortapapeles(id_elemento) {
    // Crea un campo de texto "oculto"
    var aux = document.createElement("input");
    // Asigna el contenido del elemento especificado al valor del campo
    aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
    // Añade el campo a la página
    document.body.appendChild(aux);
    // Selecciona el contenido del campo
    aux.select();
    // Copia el texto seleccionado
    document.execCommand("copy");
    // Elimina el campo de la página
    document.body.removeChild(aux);
  }
  </script>

     <center>
    <br><br><br>
    
     <div class="container">
     <h3> <b>SANCIONES PENDIENTES DE GRABAR EN SÉNECA </b> </h3>
    </div>
    <form name "modificarparte" id="modificarparte" method="POST" >
    <!-- Cabecera tabla de Sanciones-->


<?php
   
  
    //Imprime en pantalla las filas de sanciones del alumno seleccionado
    $b=0;
    while($row5 = $resSelect5->fetch())
    {
        extract($row5);
     
     // Busca en la tabla talumnos los datos del alumno 
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$AlumnoIdSa'"));
    extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
		ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$IdAlumno' AND CursoAlUn='$curso'"));
     //Busca letra de la sanción por alumno
    extract(consultaBDunica("SELECT * FROM tsancionparte WHERE SancionIdSaPa = '$IdSancion'"));
    
    //Procesamos todos los partes correspondientes a la sanción para obtener el unificado de hechos,
    // el unificado de tipos de parte, fecha último parte, profesor del último parte
    $consulta2 = "SELECT ParteIdSaPa FROM tsancionparte inner join tpartes on IdParte=ParteIdSaPa 
    WHERE SancionIdSaPa='$IdSancion' ORDER BY FechaPa";
    $resSelect2 = $conn->prepare($consulta2);
    $resSelect2->execute();
    unset($ProfeUltimoParte, $FechaUltimoParte, $HechosPartes, $TiposPartes, $HechosPa, $TipoPa, $FechaPa);
    while($row2 = $resSelect2->fetch())
    {
        extract($row2);
        extract(consultaBDunica("SELECT FechaPa, HechosPa, ProfesorIdPa, TipoPa FROM tpartes WHERE IdParte='$ParteIdSaPa'"));
        $HechosPartes=$HechosPartes."(".darfechaesp($FechaPa).") ".$HechosPa."<br>";
        $TiposPartes=$TiposPartes.$TipoPa."<br>";
    }
    $FechaUltimoParte=$FechaPa;
    extract(consultaBDunica("SELECT Profesor as ProfeUltimoParte FROM tprofesores WHERE IdProfesor = '$ProfesorIdPa'"));
    $HechosPartesFormatoNoHtml=str_replace('<br>'," * ",$HechosPartes);
    
    
    
    
    
?>

<table id="tablaregistros" class="table table-hover">

          <thead>

          <tbody>

          <tr>
    
    <th scope="col">Unidad</th>
    <th scope="col">Alumno</th>
    <th scope="col">Id</th>
    <th scope="col">F. último parte</th>
    <th scope="col">Prof. implicado</th>
    <th scope="col">Incidente</th>
    <th scope="col">Tipos Partes</th>
    <th scope="col">Tipo Sanción</th>
    <th scope="col">F. Inicio</th>
     <th scope="col">F. Fin</th>
     <th scope="col">Dias de Sancion</th>
     <th scope="col">Obs.</th>
    
           
     </tr>  

       
            
       </thead>     
       
<?php
    
        
        if ($b % 2 == 0) 
        { $colorb="table-success";     }//color para las filas pares
        else {$colorb="table-secondary";}//color para las filas impares
?>
        
        
        
    <tr class="<?php echo $colorb?>">
        <td><?= $Unidad ?></td>
        <td><a href="01disciplinaalumno.php?g_idalumno=<?= $IdAlumno ?>" ><?= $Alumno ?></a></td>
        <td><span class="badge bg-info"><H6><?= $LetraSancionxAlumno ?></H6></span> </td>
        <p id="text3<?= $IdSancion ?>" hidden><?= str_replace("-","/",darfechaesp($FechaUltimoParte)) ?></p> 
        <td><?= str_replace("-","/",darfechaesp($FechaUltimoParte)) ?> 
        <br>
        <button onclick="copiarAlPortapapeles('text3<?= $IdSancion ?>')">Copiar al portapapeles</button>
        </td>
        <td><?= $ProfeUltimoParte ?></td>
        <p id="text2<?= $IdSancion ?>" hidden><?= $HechosSa ?></p> 
        <td>  <?= $HechosSa ?> <br> <button onclick="copiarAlPortapapeles('text2<?= $IdSancion ?>')">Copiar al portapapeles</button> </td>
        
        <td>  <?= $TiposPartes ?>  </td>
        <td><?= $TipoSa ?></td>
        <td><?= darfechaesp($FechaInicio) ?></td>
        <td><?= darfechaesp($FechaFin) ?></td>
        <td><?= $DiasSancion ?></td>
        <td>  <?= $ObservacionesSa ?>  </td>
        
        
    </tr>

</table>
    <table id="tablaregistros" class="table table-hover">

          <thead>

          <tbody>


    <tr>
    <p id="text1<?= $IdSancion ?>" hidden><?= mb_substr($HechosPartesFormatoNoHtml,0,980) ?></p> 
    <p id="text11<?= $IdSancion ?>" hidden><?= str_replace("-","/",darfechaesp($FechaSa)) ?></p>
    <th scope="col">Descripción detallada </th>
    <th scope="col">Fecha acuerdo sanción</th>
    <th scope="col">Comunicación sanción a la familia</th>
    <th scope="col">Registro Séneca</th>
    </tr>
    </thead>
    <tbody>
    <tr class="<?php echo $colorb?>">

    
    <td> <?= mb_substr($HechosPartes,0,998)  ?>  
    <br>
    <button onclick="copiarAlPortapapeles('text1<?= $IdSancion ?>')">Copiar al portapapeles</button>
    </td>
    <td> <?= darfechaesp($FechaSa)  ?>  
    <br>
    <button onclick="copiarAlPortapapeles('text11<?= $IdSancion ?>')">Copiar al portapapeles</button>
    </td>
    <td>  <?= mb_substr($ComFamiliaSa,0,998)  ?>  </td> 
    <td>  <?php echo inputcambiarestado("ReSeneca",'TSa',$colorb); ?> </td> 
    </tr>


</tbody>
    </table>
<br><br>
<?php
        
    $b=$b+1;  }
?>


  
</tbody>
</form>
</table>

  </center>
    
      
    <br>
    <br>
  <br>
    <br>
  
 </body>
 
</html>