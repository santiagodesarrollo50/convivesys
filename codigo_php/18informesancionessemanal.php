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


//CONSTRUIR EL CUERPO DEL MENSAJE DE INFORME SEMANAL SANCIONES
//seleccionar semana (por defecto la próxima)

$proximolunes=date('Y-m-d',strtotime('next monday'));


 $_SESSION['Procedencia']="18informesancionessemanal.php";
 $curso=$_SESSION['PCurso'];



  
  // Definición de fsemanai segun ha pulsado submit o no
    if (isset($_POST['cambiarsemana'])) {
        $fsemanai= obtenerLunesAnterior($_POST['semanai']);
    } else {
        $fsemanai=$proximolunes;
    }
    
    
    

//Genera informe sanciones

    //definir limites semana
    $semanai=strtotime($fsemanai);//lunes
    $semanaf=$semanai+345600;//viernes
    
    
    
     //Definir cabecera tabla sanciones
   unset ($_SESSION['TablaSa']);
    $_SESSION['TablaSa'][0]=['Alumno/a','Tipo Sanción', 'Periodo',
    'Lu-'.date('d',$semanai),
    'Ma-'.date('d',strtotime("+ 1 days",$semanai)),
    'Mi-'.date('d',strtotime("+ 2 days",$semanai)),
    'Ju-'.date('d',strtotime("+ 3 days",$semanai)),
    'Vi-'.date('d',strtotime("+ 4 days",$semanai)),
    'Correo corporativo alumno','Observaciones'];
    $fila=1;
   
   //Definir cuerpo tabla sanciones
   
    $consulta = "SELECT * FROM tsanciones WHERE FechaFin >= '$fsemanai' order by TipoSa, FechaInicio";
    $resSelect = $conn->prepare($consulta);
    $resSelect->execute(); 
    
    
    while($row = $resSelect->fetch())  {
        extract ($row);
        $sancioni=strtotime($FechaInicio);
        $sancionf=strtotime($FechaFin);
        if ($semanaf < $sancioni) {
            $valor=["(Avance)",$TipoSa];
        } elseif ($semanai <= $sancioni) {
            $valor=[" ",$TipoSa];
        } else {
            $valor=["(Continuación)",$TipoSa];
        }
  
        extract(consultaBDunica("SELECT Alumno, CorreoAlumnoCorp FROM talumnos WHERE IdAlumno = '$AlumnoIdSa'"));
        extract(consultaBDunica("SELECT valor1 as B_TiposSancion FROM tconfiguracion WHERE tipo='tiposancion' AND id = '$TipoSa'"));
        extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
            ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$AlumnoIdSa' AND CursoAlUn='$curso'"));

        $_SESSION['TablaSa'] [$fila][0]= $Alumno.' ('.$Unidad.')';
        $_SESSION['TablaSa'] [$fila][1]= $B_TiposSancion;
        $_SESSION['TablaSa'] [$fila][2]= "de ". darfechaesp($FechaInicio). " a ".darfechaesp($FechaFin). " ".$valor[0];
        foreach([0,1,2,3,4] as $n) {
            $hoy=strtotime("+ $n days",$semanai);
            $fhoy=date("Y-m-d",$hoy);
            $consultac = "SELECT fecha1 as E_FestivoInicio FROM tconfiguracion WHERE fecha1<= '$fhoy' and '$fhoy' <= fecha2 and tipo='festivo'";
            $resSelectc = $conn->prepare($consultac);
            $resSelectc->execute(); 
            $numerofestivo=$resSelectc->rowCount();            
            if ($numerofestivo==1) {
                $_SESSION['TablaSa'] [$fila][3+$n]= "¡festivo!";
            } elseif (strtotime($FechaInicio)<=$hoy && $hoy<=strtotime($FechaFin))    {
                $_SESSION['TablaSa'] [$fila][3+$n]= "X";
            } else {
                $_SESSION['TablaSa'] [$fila][3+$n]= " ";
            }
        }
        $_SESSION['TablaSa'] [$fila][8]=$CorreoAlumnoCorp;
        $_SESSION['TablaSa'] [$fila][9]="";    
        $fila++;
    }
    
    
if (!isset($_POST['pdf'])) {
    ?>
    <!-- FImprime tabla HTML-->
    <div class="container">
    <h3> <b>INFORME SEMANAL DE SANCIONES: (Semana del </b><?= darfechaesp($fsemanai) ?>) </h3>
    
    <form name="opciones" method="POST" >
    <div id="menuopciones">
    <input type="submit" name="cambiarsemana" value="Cambiar Semana" class="btn btn-primary" 
        formaction='18informesancionessemanal.php' >
    <input type="date" name="semanai" value="<?= $fsemanai ?>">
    <input type="submit" name="pdf" value="Generar Informe PDF" class="btn btn-secondary" 
        formaction="16pdfsemanalsanciones.php">
    <input type="submit" name="enviarpdf" value="Enviar Informe al Claustro" class="btn btn-primary" formaction="16pdfsemanalsanciones.php">
    </div>
   
   
    <!-- Cabecera tabla de partes-->
    <table  class="table table-hover" id="tablaregistros">
        <thead>
            <tr>
                <?php
                foreach ($_SESSION['TablaSa'][0] as $elemento) {
                    echo '<th scope="col">'.$elemento.'</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>   
        <?php
        if (count($_SESSION['TablaSa']) > 1) {
            foreach (range(1, count($_SESSION['TablaSa']) - 1) as $a) {
                if ($a % 2 == 0) {
                    $color = "table-primary";
                } else {
                    $color = "table-secondary";
                }
                echo '<tr class="' . $color . '">';
                if ($_SESSION['TablaSa'][$a]) {
                    foreach (range(0, count($_SESSION['TablaSa'][$a]) - 2) as $n) {
                        echo '<td>' . $_SESSION["TablaSa"][$a][$n] . '</td>';
                    }
                    echo '<td><input type="text" name="Obs' . $a . '" value="' . $_SESSION["TablaSa"][$a][9] . '"></td>';
                }
                echo '</tr>';
            }
        } 
        ?>    
        </tbody>
     </table>
    <br>
    <br>
    <br>
    </html>
<?php
}
?>

        
    
 
