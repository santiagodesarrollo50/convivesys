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
include_once ('13cabecera.php');
$Curso=$_SESSION['PCurso'];
$_SESSION['Procedencia']="18AnexoI.php";

//CONSTRUIR EL CUERPO DEL MENSAJE DEL ANEXO I

// Definición fecha informe 
if (isset($_POST['cambiarfechainf'])) {
    $fechainf= $_POST['fechainf']; 
} else {
    $fechainf=date('Y-m-d');
}

//definir limites 
$consulta2 = "SELECT fecha1 as I_FechaInicio, fecha2 as I_FechaFin FROM tconfiguracion WHERE tipo='fechastrimestres'";
$resSelect2 = $conn->prepare($consulta2);
$resSelect2->execute(); 
    
    
while($row = $resSelect2->fetch())
{
    extract ($row);
    if (strtotime($I_FechaInicio)<=strtotime($fechainf) &&
    strtotime($fechainf)<=strtotime($I_FechaFin))
    { $fechainiciotrim=$I_FechaInicio; }
}
if (!isset($fechainiciotrim))
{ 
    echo '<script> alert("Fecha no válida. Fecha incluida en periodo de vacaciones") </script>';
    $fechainiciotrim=$I_FechaInicio;
    $fechainf=$I_FechaFin;
}
   
    
        
    //Definir tabla faltas
   unset ($_SESSION['TablaSa']);
    $_SESSION['TablaSa'][0]=['','Alumno/a: Apellidos y Nombre','Curso', 'Fecha Nacimiento',
    'SEP', 'OCT', 'NOV', 'DIC', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'Motivo Abs.',
    '1ªCarta', 'Contrato', '2ªCarta', 'Padres', 'Anexo II', 'Devolucion'];
   
    
    //selecciona al alumnado a incluir en el anexo I sin quitar los mayores de 16
   $finicioc=iniciocursoacutal ( );
   $consulta = "SELECT IdAlumno, Alumno, Unidad, FechaNac from talumnos, talumnounidad, tunidades
    where idalumno=alumnoidalun and unidadidalun=idunidad and CursoAlUn='$Curso' and
   Timestampdiff(YEAR, FechaNac, '$fechainiciotrim')<16 and IdAlumno IN
( select DISTINCT AlumnoIdAn from tanotacionesabs where 
 substring(TipoAn,1,2)<7 AND FechaAn > '$finicioc' AND FechaAn <= '$fechainf')
 ORDER BY Orden, Alumno";
    $resSelect = $conn->prepare($consulta);
    $resSelect->execute(); 
    
    
     
     $fila=1;
     while($row = $resSelect->fetch())
{
        extract ($row);
    
    $_SESSION['TablaSa'] [$fila][0]= $IdAlumno;
    $_SESSION['TablaSa'] [$fila][1]= $Alumno;
    $_SESSION['TablaSa'] [$fila][2]= $Unidad;
    $_SESSION['TablaSa'] [$fila][3]= darfechaesp($FechaNac).'
    ('.calcularedadaunafecha($FechaNac,$fechainf).' años)';
    
    foreach([9,10,11,12,1,2,3,4,5,6] as $n => $numeromes) {
        extract(consultaBDunica("Select sum(HorasI) as HFI FROM tfaltas 
        WHERE MONTH(FechaFa)=$numeromes and AlumnoIdFa='$IdAlumno'")); 
        $_SESSION['TablaSa'] [$fila][4+$n]= $HFI;
    }
    
    foreach(range(1,5) as $n) {
        unset($FechaAn);
        extract(consultaBDunica("SELECT FechaAn FROM tanotacionesabs 
        WHERE AlumnoIdAn='$IdAlumno' and substring(TipoAn,1,2)=$n and FechaAn<= '$fechainf' order by FechaAn desc"));        
        $_SESSION['TablaSa'] [$fila][14+$n]= darfechaesp($FechaAn);
    }       

    // Asigna columna Devolución
    $_SESSION['TablaSa'] [$fila][20]= '';
    $fila++;
} 
    
     if (!isset($_POST['pdf']))  {
    ?>




<!-- FImprime tabla HTML-->

    <div>
    <h3> <b>ANEXO I: (Datos a fecha de </b> <?= darfechaesp($fechainf) ?>) </h3>
    <br>
    <h6> Completar el motivo del absentismo. Seleccionar alumnado para el informe.</h6>
    
    <form name "opciones" method="POST" >
    <div id="menuopciones">
    <input type="submit" name="cambiarfechainf" value="Cambiar Fecha Anexo I" class="btn btn-primary" formaction="18AnexoI.php">
    <input type="date" name="fechainf" value="<?= $fechainf ?>">
    <input type="submit" name="pdf" value="Generar PDF Anexo I" class="btn btn-secondary" 
    formaction="16pdfABSAnexoI.php">
    </div>
   
   
    <!-- Cabecera tabla de partes-->
    <table  class="table table-hover" id="tablaregistros">
      
    <thead>
    
          <tr>
        <th scope="col"></th>      
    <?php
    foreach (range(1,count($_SESSION['TablaSa'][0])-1) as $a)
    { echo '<th scope="col">'.$_SESSION['TablaSa'][0][$a].'</th>';}
    ?>
        </tr>
        </thead>
   <tbody> 
   
   <?php
   foreach (range(1, count($_SESSION['TablaSa'])-1) as $a)
   {
    $alumnoid=$_SESSION['TablaSa'][$a][0];
    unset($ABS_Motivo);
    extract(consultaBDunica("SELECT ABS_Motivo FROM tnotasalumno WHERE AlumnoIdNo='$alumnoid'")); 

       if ($a % 2 == 0) 
        { $color="table-primary";     }//color para las filas pares
        else {$color="table-secondary";}//color para las filas impares
        
        echo '<tr class="'.$color.'">';
        
        echo '<th scope="row"><input type="checkbox" name="checkboxid[]"  checked
        value="'.$a.'" ></th>
        <input type="hidden" name="Alumnoid'.$a.'" value="'.$alumnoid.'">';
        foreach (range(1,count($_SESSION['TablaSa'][0])-2) as $n)
        {
            if ($n==14)
            {
            $resSelect3 = $conn->prepare("SELECT J_MotivosAbs from tdatos where J_MotivosAbs is not null");
            $resSelect3->execute(); 
            ?>
           <td> <select name="<?= 'Motivo'.$a ?>" required >
           <option> </option>
           <?php 
            while($row3 = $resSelect3->fetch()) 
            { extract($row3);
            
            if ($ABS_Motivo==$J_MotivosAbs)
            { $seleccionado="selected"; } else {$seleccionado ="";}
            ?>
            <option <?php echo $seleccionado ?> ><?php echo $J_MotivosAbs;?></option>
            <?php 
            }
            ?>
            </select></td>
            <?php
            }
            else
            {            echo '<td>'.$_SESSION["TablaSa"][$a][$n].'</td>';}
            
    }
    ?>
            <td>
            <input type="text" name="<?= 'Devolucion'.$a ?>">
            </td>
        </tr>
            <?php
  
   }
   
   ?>
   </div>
   </tbody>
   
   
   </table>
   
   <br>
   <br>
   <br>
   </html>
    
    <?php
}

?>
    
 
