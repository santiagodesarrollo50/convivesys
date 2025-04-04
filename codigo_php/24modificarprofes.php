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
    $consulta3 = "SELECT * FROM tprofesores ORDER BY Profesor";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    while ($row3 = $resSelect3->fetch()) {
        extract($row3);
        $correo_f = $_POST['correo'.$IdProfesor];
        $telefono_f = $_POST['telefono' . $IdProfesor];
        $edit = $conn->query("UPDATE tprofesores set CorreoProfesor='$correo_f', TelefonoProfesor='$telefono_f'
            WHERE IdProfesor='$IdProfesor'");
    }
    header('location:24modificarprofes.php');

} 

if (!isset($_POST['opcion'])) {

        $consulta2 = "SELECT * FROM tprofesores ORDER BY Profesor";
        $resSelect2 = $conn->prepare($consulta2);
        $resSelect2->execute();     
        ?>    
        <center>
        <!-- MOSTRAR LOS PARTES    -->
        <div class="container">
        <div class="col-md-8 mx-auto bg-light rounded p-4">
        <h3> <b>MODOFICAR DATOS DEL PROFESORADO </b></h3>
        </div>

        <!-- Formulario de opciones por partes-->
        <form  method="POST" >
        <div id="menuopciones">
            <input type="submit" name="opcion" value="Modificar Datos" class="btn btn-primary">
        </div>   
   
        <!-- Cabecera tabla de partes-->
        <table  class="table table-hover" id="tablaregistros">
 
        <?php   
        //Imprime en pantalla los tutores
        $a=0;
        while($row2 = $resSelect2->fetch())     {
            unset($Profesor,$ProfesorIdUn);
            extract($row2);
            if ($a % 6 == 0) {//Cabeceras cada 4 filas
            ?>
                <thead>
                <tbody> 
                    <tr>
                        <th scope="col">Profesor</th>
                        <th scope="col">Correo-e</th>
                        <th scope="col">Teléfono</th>
                        <th><input type="submit" name="opcion" value="Modificar Datos" class="btn btn-primary"></th>
                    </tr>
                   
                </thead>  
                    
            <?php
            }
        
            if ($a % 2 == 0)  {
                $color="table-primary";     
            } else {
                $color="table-secondary";
            }
            ?>        
            <tr class="<?php echo $color ?>" >
            <td><?= $Profesor ?></td>
            <td><input type="email" name="correo<?= $IdProfesor ?>" size="40" value="<?= $CorreoProfesor ?>" required
                        class="form-control form-control-lg rounded-0 border-info"></td>
            <td><input type="tel" name="telefono<?= $IdProfesor ?>" size="10" value="<?= $TelefonoProfesor ?>"
                        pattern="[0-9]{9}" class="form-control form-control-lg rounded-0 border-info" ></td>  
            <td></td>
            </tr>

            <?php
            $a=$a+1;  
            }    
        ?>
        
        </tbody>  
        </table>   
        </form>
        </center>  
<?php
}
?>
    </div>
  
  <br>  <br> <br>  <br>
   </body>
 </html>