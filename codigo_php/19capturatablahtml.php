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

//Crea tantas tablas como tablas tiene la web. Se almacenan en $Tablas[nºtabla][nºfila][nºcolumna] los indices comienzan en 0
  
  // Cuando pulsa cargar datos
    if (isset($_POST['grabar']))
    {    
    $datos= $_POST['datos'];
    $Tablas=capturatablashtml($datos);   
    }
?>

    <div class="container">
    <h3> <b>OBTENER TABLAS DE UN CÓDIGO HTML </b></h3>
    </div>
        
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Datos" class="btn btn-primary" formaction="">
    </div>
     
    <div>
    <h5 class="text-secondary">Pegar código html aquí (ctrl+A y ctrl+C)</h5>
    </div>
    <div class="input-group">
    <textarea autofocus name="datos" rows="2" cols="2" 
    class="form-control form-control-lg rounded-0 border-info" 
    autocomplete="off"></textarea>
    </div>
    <br><br><br>
 
</form>
<!-- Imprime las tablas capturadas -->
<?php
 foreach($Tablas as $n => $tabla)
{
echo '<br>'.'TABLA '.$n.'<br>';
echo imprimetablaarray($tabla);
}





// Para captura tablas csv
// Cuando pulsa cargar datos
    if (isset($_POST['grabar2']))
    {    
    $datos2= $_POST['datos2'];
    $Tabla2=capturatablascsv($datos2);
    }
?>

    <div class="container">
    <h3> <b>OBTENER TABLA DE UN TEXTO CSV </b></h3>
    </div>
        
    <form method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar2" value="Cargar Datos" class="btn btn-primary" formaction="">
    </div>
     
    <div>
    <h5 class="text-secondary">Pegar texto csv aquí (ctrl+A y ctrl+C)</h5>
    </div>
    <div class="input-group">
    <textarea autofocus name="datos2" rows="2" cols="2" 
    class="form-control form-control-lg rounded-0 border-info" 
    autocomplete="off"></textarea>
    </div>
    <br><br><br>
 
</form>
<!-- Imprime las tablas capturadas -->
<?php
echo imprimetablaarray($Tabla2);

?>
<br>
<br>
<br>
</html>