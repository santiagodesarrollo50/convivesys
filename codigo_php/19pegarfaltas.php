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

<div class="container">
    <h3> <b>OBTENER FALTAS MENSUALES DEL ALUMNADO </h3>
    </div>   
    
    <!-- Formulario de opciones por partes-->
    <form name "modificarparte" id="modificarparte" method="POST" >
    <div id="menuopciones">
    <input type="submit" name="grabar" value="Cargar Faltas" class="btn btn-primary" formaction="">
   </div> 
   
   <div>
            <h5 class="text-secondary">Instrucciones: <br>
            1. Usando Chrome, acceder a la pantalla de Seneca "FALTAS DE ASISTENCIA DE UNA UNIDAD EN UN PERIODO" <br>
            2. Seleccionar fechas dentro de un mismo mes.<br>
            3. Seleccionar y copiar todo Ctrl+A y Ctrl+C. <br>
            4. Pegar en el hueco del formulario de abajo en esta web Ctrl+V.<br>
            5. Pulsar "Cargar faltas"
            </h5>
          </div>
           <div class="input-group">
               <textarea autofocus name="faltas" rows="2" cols="2" 
               class="form-control form-control-lg rounded-0 border-info" 
               autocomplete="off"></textarea>
            </div>
        <br><br><br>    
  
  
<?php

// Cuando pulsa grabar 
if (isset($_POST['grabar'])) {
    $faltas= $_POST['faltas'];
    $faltas=str_replace(' , ',', ',$faltas);//evitar fallo seneca en los nombres con un apellido morales , santiago
    $faltas=str_replace('CJ','6J-0I-0R', $faltas);
    $faltas=str_replace('CI','0J-6I-0R', $faltas);

    //carga las fecha de los días de falta
    $cabeceras=Array();
    preg_match_all("/\d{1,2} (?:Ene|Feb|Mar|Abr|May|Jun|Jul|Ago|Sep|Oct|Nov|Dic)/", $faltas, $acoincide);
    $cabeceras=$acoincide[0];
    $numerofechas=count($cabeceras);
    

    //Patron captura el nombre del alumno y las filas siguientes (tantas como días) con la información de las faltas
    $arrayAlumno=Array();
    $arrayFaltasAlumno=Array();
    $patronAlumnoFaltas="/([^\n]*, [^\n]*)\n((?:[0-9]J-[0-9]I-[0-9]R\R| \R){".$numerofechas."})/";
    preg_match_all($patronAlumnoFaltas, $faltas, $acoincide2);
    $arrayAlumno=$acoincide2[1];
    $arrayFaltasAlumno=$acoincide2[2];
    //print_r($arrayAlumno);
    

    
    // Capturar la unidad con patrones 
    preg_match_all('/Unidad:\R([^\n]+)\R/', $faltas, $output_array);
    $unidadnombre=trim($output_array[1][0]);
    unset($unidadselect);
    extract(consultaBDunica("SELECT IdUnidad as unidadselect from tunidades where 
        Unidad='$unidadnombre' and CursoUn='$curso'"));           
    if (!isset($unidadselect)) {
        $unidadnombre="No se ha detectado la unidad";
    }           
          
           
    // Identificamos las fecha de la cabecera se guardan en $fechafalta
    foreach($cabeceras as $col=>$fechacabecera)   {
        $fechacabecera2=trim($fechacabecera);
        $dia=substr($fechacabecera2,0,strlen($fechacabecera2)-4);
        $mes=numeromescorto(substr($fechacabecera2,strlen($fechacabecera2)-3,3));
        $ano=anodelmes($mes);
        $fechafalta[$col]=$ano."-".$mes."-".$dia;
    }
    
    //Iniciamos tabla html con las faltas
    echo '<div class="container">';
    echo '<h4> <b>Unidad: '.$unidadnombre.'&nbsp;&nbsp;&nbsp; Mes: '.nombremes($mes).'( '.$ano.')</h4> </div>';
    echo '<table  class="table table-hover" id="tablaregistros">   <thead>   <tbody>  <tr>';
    echo '<th scope="col">Alumno:</th>';
    foreach($fechafalta as $dia) {
        echo '<th scope="col">'.darfechaesp($dia).'</th>';
    }
    echo '</tr>   </thead>';


    //Bucle para cada alumno
    foreach($arrayAlumno as $cont=>$AlumnoT) {
        $insertarlo=false;
        //buscamo Id alumno
        unset($IdAlumno);
        $AlumnoT=trim($AlumnoT);
        extract(consultaBDunica("SELECT IdAlumno from talumnos inner join talumnounidad on idalumno=alumnoidalun 
            where Alumno='$AlumnoT' and UnidadIdAlUn='$unidadselect'"));
        if (!isset($IdAlumno)) {
            echo "<br> Error: alumno no identificado en el grupo ".$AlumnoT.
            "<br> Cargar este alumno en la base de datos para poder registrar las faltas";
            break;
        }
        
        //Borramos todas las faltas del alumno en el periodo tratado
        $primeraFecha=$fechafalta[0];
        $ultimaFecha=end($fechafalta);
        $conn->query("DELETE FROM tfaltas WHERE AlumnoIdFa='$IdAlumno' and FechaFa>='$primeraFecha' and FechaFa<='$ultimaFecha'");
        
        
        $insert = "INSERT INTO tfaltas (AlumnoIdFa, FechaFa, HorasI, HorasJ, HorasR, FechaActualizacion)
                    VALUES ";

        $arrayFaltasUnAlumno=Array();
        $arrayFaltasUnAlumno=preg_split('/\n/', $arrayFaltasAlumno[$cont], $numerofechas);
        //print_r($arrayFaltasUnAlumno);
        
        //Imprimimos en la tabla los datos de faltas del alumno
        echo '<tr>'; 
        echo '<th scope="col">'.$AlumnoT.'</th>';

        //Registramos todas las faltas del alumno
        foreach ($arrayFaltasUnAlumno as $col=> $numFaltas) {
            $fefalta=$fechafalta[$col];
            //$frevision=date("Y-m-d");
            
            
            if (strlen($numFaltas)>5) {
                $arrayHoras=preg_split('/[JIR]-?/',$numFaltas);
                $horasj=$arrayHoras[0];
                $horasi=$arrayHoras[1];
                $horasr=$arrayHoras[2];
                echo '<th scope="col">'.$horasj.'J-'.$horasi.'I-'.$horasr.'R</th>';
                $insert = $insert." ('$IdAlumno', '$fefalta' , $horasi, $horasj, $horasr, CURDATE()),";
                $insertarlo=true;
            }  else {
                echo '<th scope="col"> </th>';
            } 
            
        }
        echo '</tr>';
        if ($insertarlo) {
            //echo '<br>'.substr($insert,0,-1).'<br>';

            $conn->query(substr($insert,0,-1));
        }
    }

    echo '</tbody>     </table>';
}

?>

    
    <br>
    <br>
    <br>
    </html>