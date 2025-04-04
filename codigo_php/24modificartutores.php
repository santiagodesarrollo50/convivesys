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


// Cuando pulsa grabar nuevo parte
if (isset($_POST['opcion'])) {
  $profesor_f = $_POST['profesor'];
  $unidad_f = $_POST['unidad_f'];   
  extract(consultaBDunica("SELECT IdProfesor as Idprofe FROM tprofesores WHERE Profesor = '$profesor_f'"));
  $edit = $conn->query("UPDATE tunidades set ProfesorIdUn='$Idprofe' where IdUnidad='$unidad_f'");
} 

$consulta2 = "SELECT * FROM tunidades where CursoUn='$curso' ORDER BY Orden";
$resSelect2 = $conn->prepare($consulta2);
$resSelect2->execute();  
   
?>    
  <center>
  <!-- MOSTRAR LOS PARTES    -->
  <div class="container">
  <div class="col-md-8 mx-auto bg-light rounded p-4">
  <h3> <b>TUTORÍA DE LAS UNIDADES </b></h3>
  </div>

  <!-- Formulario de opciones por partes-->
  <form  method="POST" >
  <div id="menuopciones">
    
    UNIDAD:
    <select name="unidad_f" require class="col-md-5 form-control form-control-lg rounded-0 border-info">
            <?php
            $consulta3 = "SELECT IdUnidad, Unidad FROM tunidades where CursoUn='$curso' ORDER BY Orden";
            $resSelect3 = $conn->prepare($consulta3);
            $resSelect3->execute(); 

            while($row3 = $resSelect3->fetch()) 
            { extract($row3);
            
            ?>
            <option value=<?= $IdUnidad ?>><?= $Unidad ?></option>
            <?php 
            }
            ?>
            </select>
    TUTOR/A:
    <div class="input-group col-md-5">
      <select autofocus name="profesor" id="buscarp" class="form-control form-control-lg rounded-0 border-info selectpicker" 
        data-live-search="true" data-live-search-normalize="true" title="Ingrese nombre del alumno/a..." required>
        <br>
        <?php
            $sqlp = "SELECT Profesor FROM tprofesores";
            $stmtp = $conn->prepare($sqlp);
            $stmtp->execute();
            $resultadosp = $stmtp->fetchAll();
            foreach ($resultadosp as $rowp) {
              if ($rowp['Profesor']==$Profesor) {
                echo '<option selected>'.$rowp['Profesor'].'</option>'; 
              } else {
                echo '<option>'.$rowp['Profesor'].'</option>'; 
              }   
            }
        ?>
    </select>
           </div>
    <input type="submit" name="opcion" value="Modificar Tutor" class="btn btn-primary">
    </div>   
   
    <!-- Cabecera tabla de partes-->
    <table  class="table table-hover" id="tablaregistros">
 
<?php   
  //Imprime en pantalla los tutores
    $a=0;
    while($row2 = $resSelect2->fetch())
    {
      unset($Profesor,$ProfesorIdUn);
      extract($row2);
     
        // Busca el nombre del profesor
        if($Unidad=="sin unidad")
        {continue;}
        extract(consultaBDunica("SELECT Profesor FROM tprofesores WHERE IdProfesor = '$ProfesorIdUn'"));
        
        
        if ($a % 6 == 0)//Cabeceras cada 4 filas
        {
        ?>
          <thead>
    <tbody> 
          <tr>
    <th scope="col">Unidad</th>
    <th scope="col">Nivel</th>
    <th scope="col">Tutor</th>
    <th scope="col">Turno Tarde</th>
    </tr>
    </thead>     
    <?php
        }
        
        if ($a % 2 == 0) 
        { $color="table-primary";     }//color para las filas pares
        else {$color="table-secondary";}//color para las filas impares
        ?>
        
        <tr class="<?php echo $color ?>" >
        <td><?= $Unidad ?></td>
        <td><?= $Nivel ?></td>
        <td><?= $Profesor ?></td>
        <td><?= $TurnoTarde ?></td>        
        </tr>

        <?php
        $a=$a+1;  
        }    
    ?>
        
    </tbody>  
    </table>   
    </form>
    </center>  
    </div>
  
  <br>  <br> <br>  <br>
   </body>
 </html>