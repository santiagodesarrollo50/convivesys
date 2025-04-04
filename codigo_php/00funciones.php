  <?php    
    
    //display errores
    //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

 //iconos svg
$icono_mail_enviado='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
  <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
</svg>';
  
    $icono_mail_nodisponible='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square-dotted" viewBox="0 0 16 16">
  <path d="M2.5 0c-.166 0-.33.016-.487.048l.194.98A1.51 1.51 0 0 1 2.5 1h.458V0H2.5zm2.292 0h-.917v1h.917V0zm1.833 0h-.917v1h.917V0zm1.833 0h-.916v1h.916V0zm1.834 0h-.917v1h.917V0zm1.833 0h-.917v1h.917V0zM13.5 0h-.458v1h.458c.1 0 .199.01.293.029l.194-.981A2.51 2.51 0 0 0 13.5 0zm2.079 1.11a2.511 2.511 0 0 0-.69-.689l-.556.831c.164.11.305.251.415.415l.83-.556zM1.11.421a2.511 2.511 0 0 0-.689.69l.831.556c.11-.164.251-.305.415-.415L1.11.422zM16 2.5c0-.166-.016-.33-.048-.487l-.98.194c.018.094.028.192.028.293v.458h1V2.5zM.048 2.013A2.51 2.51 0 0 0 0 2.5v.458h1V2.5c0-.1.01-.199.029-.293l-.981-.194zM0 3.875v.917h1v-.917H0zm16 .917v-.917h-1v.917h1zM0 5.708v.917h1v-.917H0zm16 .917v-.917h-1v.917h1zM0 7.542v.916h1v-.916H0zm15 .916h1v-.916h-1v.916zM0 9.375v.917h1v-.917H0zm16 .917v-.917h-1v.917h1zm-16 .916v.917h1v-.917H0zm16 .917v-.917h-1v.917h1zm-16 .917v.458c0 .166.016.33.048.487l.98-.194A1.51 1.51 0 0 1 1 13.5v-.458H0zm16 .458v-.458h-1v.458c0 .1-.01.199-.029.293l.981.194c.032-.158.048-.32.048-.487zM.421 14.89c.183.272.417.506.69.689l.556-.831a1.51 1.51 0 0 1-.415-.415l-.83.556zm14.469.689c.272-.183.506-.417.689-.69l-.831-.556c-.11.164-.251.305-.415.415l.556.83zm-12.877.373c.158.032.32.048.487.048h.458v-1H2.5c-.1 0-.199-.01-.293-.029l-.194.981zM13.5 16c.166 0 .33-.016.487-.048l-.194-.98A1.51 1.51 0 0 1 13.5 15h-.458v1h.458zm-9.625 0h.917v-1h-.917v1zm1.833 0h.917v-1h-.917v1zm1.834 0h.916v-1h-.916v1zm1.833 0h.917v-1h-.917v1zm1.833 0h.917v-1h-.917v1zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
</svg>';
    $icono_sancion_pendiente='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16">
  <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
</svg>';
 
 
 
 
 // Avisos de mala seleccion en mostrar**
 
 $ArrayAvisos=[1 => "Para la acción pulsada, hay que seleccionar al menos y solo un parte o anotación",
    2 =>"No se puede desvincular el parte de la sanción, ya que quedaría la sanción sin ningún parte asociado. Puedes borrar directamente la sanción.",
    3 =>"No es posible borrar un parte con sanciones asociadas. Desvincular o borrar sanción primero",
    4 =>"Para la acción pulsada, hay que seleccionar al menos y solo una sanción",
    5 =>"Hay que seleccionar partes del mismo alumno",
    6 =>"Hay que seleccionar sanciones del mismo alumno",
    7 =>"Error en las fechas seleccionadas",
    20 =>"Error depurando"] ;



  //Conexión base datos 
  function conectarBaseDeDatos()  {
      if (session_status() === PHP_SESSION_NONE) {
          session_start();
      }
      if (!isset($_SESSION['bbddhost']) || !isset($_SESSION['bbdduser'])
          || !isset($_SESSION['bbddpass'])) {
          echo "<hr>Error en los datos de conexión";
          exit();
      }
      $DBHOST = $_SESSION['bbddhost'];
      $DBUSER = $_SESSION['bbdduser'];
      $DBNAME = 'disciplina';
      $DBPASS = $_SESSION['bbddpass'];
      $dsn = 'mysql:host=' . $DBHOST . ';dbname=' . $DBNAME;
      try {
          $conn = new PDO(
              $dsn,
              $DBUSER,
              $DBPASS,
              array(
                  PDO::ATTR_PERSISTENT => true,
                  PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
              )
          );
          return $conn;
      } catch (PDOException $e) {
          echo '<hr>Error de conexión a los datos: ';
          exit();
      }
  }

//Calcula la fecha del lunes anterior a la fecha dada
  function obtenerLunesAnterior($fechaString)  {
      $timestamp = strtotime($fechaString);
      if ($timestamp === false) {
          return false; // La fecha proporcionada no es válida
      }
      $diaSemana = date('N', $timestamp); // 1 (lunes) a 7 (domingo)  
      if ($diaSemana === '1') {
          // Si ya es lunes, devolvemos la misma fecha
          return date('Y-m-d', $timestamp);
      } else {
          // Calculamos la diferencia en días hasta el lunes anterior
          $diferencia = $diaSemana - 1;
          $timestampLunesAnterior = strtotime("-$diferencia days", $timestamp);
          return date('Y-m-d', $timestampLunesAnterior);
      }
  }



// Capturar tablas de un código html
function capturatablashtml($datos)
{
        //Array de tablas
        $ATablas=explode('<table ',$datos);
        
        unset($ATablas[0]);
        
        $ntabla=0;
        foreach($ATablas as $Tabla)
        {
            //Array Filas
            $AFilas=explode('<tr ',$Tabla);
            unset($AFilas[0]);
            $nfila=0;
            foreach($AFilas as $fila)
            {
                if(strpos($fila,'<td ')>1)
                {$separador='<td ';}
                else
                {$separador='<th ';}
                $AColumnas=explode($separador, $fila);
                unset($AColumnas[0]);
                $ncolumna=0;
                foreach($AColumnas as $elemento)
                {
                   if (strpos($elemento,'<input'))
                   {
                    //Es para el caso de las tablas de calificaciones con celdas input
                    $texto1=substr($elemento, strpos($elemento,'value=')+7);
                    $texto=substr($texto1,0,strpos($texto1,'"'));
                   }
                   else
                   {
                    $texto=trim(str_replace('&nbsp;',' ',preg_replace('/<[^>]+>/','','<'.$elemento))); 
                   }
                   $Tablas[$ntabla][$nfila][$ncolumna]=$texto;
                   $ncolumna++;
                   // linea alternativa que daba fallos trim(str_replace('&nbsp;',' ',preg_replace('/<[^>.]+>/','','<'.$elemento)));
                }
                $nfila++;
            }  
            $ntabla++;
        }

        return $Tablas;
}

// Capturar tablas de un texto ficher cvs
function capturatablascsv($datos)
{
    $AFilas=explode("\n",$datos);
            foreach($AFilas as $nfila => $fila)
            {
                $Tablas[$nfila]=str_getcsv($fila);
            }
    return $Tablas;
}


//Imprime tabla html correspondiente a un array. La primera fila la pone como cabecera.


function imprimetablaarray($array)
{
    $a='<table border="1"> <thead> <tr>';
    
    foreach($array[0] as $cabecera)
    {
        $a=$a.'<th> '.$cabecera.' </th>';
    }
    $a=$a.'</tr> </thead> <tbody>';
foreach(range(1,count($array)-1) as $n)
{
    $a=$a.'<tr>';
foreach($array[$n] as $elemento)
{
    $a=$a.'<td> '.$elemento.' </td>';
}
$a=$a.'</tr>';
}
$a=$a.'</tbody> </table> <br> <br> <br>';
return $a;
}


//Imprime tabla html correspondiente a un array con mejor formato. La primera fila la pone como cabecera.

function imprimetablaarray2($array)
{
    $a='<table  class="table table-hover" id="tablaregistros">      
    <thead> <tbody> <tr>';

    foreach($array[0] as $cabecera)
    {
        $a=$a.'<th scope="col">'.$cabecera.' </th>';
    }
    $a=$a.'</tr> </thead> <tbody>';

    foreach(range(1,count($array)-1) as $n)
    {
    if ($n % 2 == 0) 
    { $color="table-primary";   }//color para las filas pares
    else {$color="table-secondary"; }//color para las filas impares
    $a=$a.'<tbody> <tr class="'.$color.'" > <tr>';
    foreach($array[$n] as $elemento)
    {     $a=$a.'<td> '.$elemento.' </td>';     }
    $a=$a.'</tr>';
    }
    $a=$a.'</tbody> </table> <br> <br> <br>';
    return $a;
}



    //Resaltar un numero a partir de cierta cantidad

    function resaltarnumero($numero,$limite)
    {
        $color='warning';
        if($numero<$limite)
        {$salida=$numero;}
        else 
        {$salida='<span class="badge bg-'.$color.'"> 
            <strong>'.$numero.'</strong></span>';}
        return $salida;
    }

  //calculo el año dado el mes (en numero) para el presente curso escolar
function anodelmes ($mes)
{
    $fecha=date('Y-m-d');
    $anoactual=date("Y",strtotime($fecha));
    $mesactual=date("n",strtotime($fecha));
    if ($mesactual>8 && $mes<9)
    {$ano=$anoactual+1;}
    elseif ($mesactual<9 && $mes>8)
    {$ano=$anoactual-1;}
    else
    {$ano=$anoactual;}
    return $ano;
}





 
 //calculo del curso escolar (xxxx/xxxx) de una fecha dada (por defecto a fecha de hoy)
function cursoescolar ($fecha = null)
{
    if (is_null($fecha))
    {$fecha=date('Y-m-d');}
    $anoactual=date("Y",strtotime($fecha));
    $mesactual=date("n",strtotime($fecha));
    if ($mesactual>7)
    { $cursoescolar=$anoactual.'/'.($anoactual+1); }
    else
    { $cursoescolar=($anoactual-1).'/'.$anoactual; }
    return $cursoescolar;
}



 //calculo del curso escolar (xx/xx) de una fecha dada (por defecto a fecha de hoy)
 function cursoescolarcorto ($fecha = null)
 {
     if (is_null($fecha))
     {$fecha=date('Y-m-d');}
     $anoactual=date("y",strtotime($fecha));
     $mesactual=date("n",strtotime($fecha));
     if ($mesactual>7)
     { $cursoescolar=$anoactual.'/'.($anoactual+1); }
     else
     { $cursoescolar=($anoactual-1).'/'.$anoactual; }
     return $cursoescolar;
 }
 
//calcula los meses de curso que han transcurrido a fecha de hoy
function mesesdecurso ( )
{
    $mesactual=date("n");
    if ($mesactual>8)
    {     $mesescoger=$mesactual-8;    }
    else
    {     $mesescoger=$mesactual+4;    }
    return $mesescoger;
}


//calcula la fecha de inicio de curso actual = 1sep de xx
function iniciocursoacutal ( )
{
    $anoactual=date("Y");
    $mesactual=date("n");
    if ($mesactual>8)
    {   $fechainicio=$anoactual.'-09-01';   }
    else
    {   $fechainicio=($anoactual-1).'-09-01';    }
    return $fechainicio;
}

//calcula la fecha de inicio de curso de una fecha de ese curso = 1sep de xx
function iniciocurso ($fecha)
{
    $anoactual=date("Y",strtotime($fecha));
    $mesactual=date("n",strtotime($fecha));
    if ($mesactual>8)
    {   $fechainicio=$anoactual.'-09-01';   }
    else
    {   $fechainicio=($anoactual-1).'-09-01';    }
    return $fechainicio;
}

//calcula la fecha de fin de curso de una fecha de ese curso = 1sep de xx
function fincurso ($fecha)
{
    $anoactual=date("Y",strtotime($fecha));
    $mesactual=date("n",strtotime($fecha));
    if ($mesactual>8)
    {   $fechainicio=($anoactual+1).'-09-01';   }
    else
    {   $fechainicio=$anoactual.'-09-01';    }
    return $fechainicio;
}
 
//cambia el orden de un nombre de A A, N a N A A 
function nombreprimero($apellidosnombre)
{
    $nombre=substr($apellidosnombre,strpos($apellidosnombre,',')+2);
    $apellidos=substr($apellidosnombre,0,strlen($apellidosnombre)-strlen($nombre)-2);
    $nombreapellidos=$nombre.' '.$apellidos;
    return $nombreapellidos;
}

 
 //función para mostrar más texto en las tablas en los campos de hechos, obs ...
 function botonpopover($titulo, $texto,$colorfila,$n)
 {      $texto=str_replace('"',"-",$texto);    
     if(strlen($texto)<=$n)
        {echo $texto;}
        else
        { echo '<button type="button" class="'.$colorfila.'" style=" border: inset 0pt"
          class="btn btn-light" data-toggle="popover"
          data-placement="right" title="'.$titulo.'" 
          data-content="'.$texto.'">'.mb_substr($texto,0,$n).'...'.'</button>';
    }
    } 
 
 
 //$fecha en cualquier forma?, la devuelve como dd-mm-aaaa
 function darfechaesp($fecha) { 
    if ($fecha!="")
    {
    $fechaphp=strtotime($fecha);
    $ano=date('Y', $fechaphp);
    $mes=date('m', $fechaphp);
    $dia=date('d', $fechaphp);
    $fechaesp=$dia."-".$mes."-".$ano;
    return $fechaesp;
    }
    else
    {return "";}
}

    //devuelve el numero de mes dado el nombre
    function numeromes($nombremes) {
    $meses=Array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
    return array_search($nombremes,$meses)+1;
    }
    

     //devuelve el numero de mes dado el nombre corto
     function numeromescorto($nombremes) {
     $meses=Array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
     return array_search($nombremes,$meses)+1;
     }



    //devuelve el día de la semana a partir del numero de 1 a 7
    function nombrediasemana($numerodiasemana) {
        $dias=Array('Lu','Ma','Mi','Ju','Vi','Sa','Do');
        return $dias[$numerodiasemana-1];   
    }

    //devuelve en nombre del mes dado el numero
    function nombremes($numeromes) {
        $meses=Array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
        if($numeromes==0) {
            $salida = $meses[11];
        } elseif ($numeromes==13) {
            $salida = $meses[0];
        } else {
            $salida = $meses[$numeromes-1];
        }
        return $salida;
    }

  // de fecha cualquier formato a dd de mespalabre de yyyy 
    function fechaformatogranada($fecha= null) {
        // si no hay parametro es la fecha de hoy
        if (is_null($fecha))
         {$dia=date('j');
        $mes=date('m');
        $ano=date('Y');}
        else
        {$fechaphp=strtotime($fecha);
        $dia=date('j',$fechaphp);
        $mes=date('m',$fechaphp);
        $ano=date('Y',$fechaphp);}        
        $mespalabra=nombremes($mes);
        $resultado=$dia." de ".$mespalabra." de ".$ano;
        return $resultado;
    }
    
    
    //Calcula la edad que tiene en el día de hoy
   function calcularedad($fechanacimiento) {
    $nacimiento = new DateTime($fechanacimiento);
    $ahora = new DateTime(date("Y-m-d"));
    $diferencia = $ahora->diff($nacimiento);
    return $diferencia->format("%y");
    }

    //Calcula la edad que tiene en el día de hoy remarcando una edad limite
    function calcularedadformato($fechanacimiento, $edadlimite) {
        $nacimiento = new DateTime($fechanacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        $mes=$diferencia->format("%m");
        $edad= $diferencia->format("%y");
        if($edad<$edadlimite-1)
        {$salida=$edad;}
        elseif ($edad==$edadlimite-1 && $mes<10)
        //{$salida='<div class="text-warning"> <strong>'.$edad.'</strong></div>';}
        {$salida=$edad;}
        else 
        //{$salida='<div class="text-danger"> <strong>'.$edad.'</strong></div>';}
        {$salida='<span class="badge bg-danger"> <strong>'.$edad.'</strong></span>';}
        return $salida;
    }
   
    //Calcula la edad que tenía en una fecha dada
   function calcularedadaunafecha($fechanacimiento, $fecha ) {
    $nacimiento = new DateTime($fechanacimiento);
    $fechadada=  new DateTime($fecha);
    $diferencia = $fechadada->diff($nacimiento);
    return $diferencia->format("%y");
    }
    


    //Cuenta los días lectivos entre dos fechas
    function contardiaslectivos($fechai,$fechaf) {
        Global $conn;    
        $fecha=strtotime($fechai);
        $fechafin=strtotime($fechaf);    
        while ($fecha<=$fechafin)     {         
            if (!(date('D',$fecha)=="Sun" || date('D',$fecha)=="Sat")) {
                $Arrayfechas[$fecha]=1;
            }
            $fecha=strtotime("+ 1 days",$fecha);
        }    
        $consulta = "SELECT fecha1 as E_FestivoInicio, fecha2 as E_FestivoFin from tconfiguracion where tipo='festivo'";
        $resSelect3 = $conn->prepare($consulta);
        $resSelect3->execute();
        while($row2 = $resSelect3->fetch()) {
            extract($row2);    
            $festivoi=strtotime($E_FestivoInicio);
            $festivof=strtotime($E_FestivoFin);        
            foreach ($Arrayfechas as $fech => $valor)        {
                if(($festivoi <= $fech) && ($fech <= $festivof)) {
                    // si $fecha esta en periodo festivo
                    unset($Arrayfechas[$fech]); 
                }
            }
        }
        $contador=array_sum($Arrayfechas);
        return $contador;
    }
    
    
    
    //realiza una consulta sql donde solo importa el primer elemento extraido
    function consultaBDunica($textoconsulta)  { 
        Global $conn;
        $resSelect = $conn->prepare($textoconsulta);
        $resSelect->execute();
        $salida=$resSelect->fetch();
        if ($salida==false)
        {$salida=[];}
        return $salida;
    }




    //sustituye en cadenas expresiones del tipo <<alumno>> o <<nombremes(<<mes>>) por los valores correspondientes
    // con las variables del mismo nombre
    function componeretiquetas($cadena)  {
        $restoparte=$cadena;
        while (strpos($restoparte,"<<")!== false)  {
            $prevariable=substr($restoparte,strpos($restoparte,"<<")+2, strpos($restoparte,">>")-strpos($restoparte,"<<")-2);
            if(strpos($prevariable,"(")>0)  {
                $variable=substr($prevariable,strpos($prevariable,"(")+1, strpos($prevariable,")")-strpos($prevariable,"(")-1);
                $funcion=substr($prevariable,0,strpos($prevariable,"("));
                global ${$variable};
                $cadena=str_replace('<<'.$funcion.'('.$variable.')>>',$funcion(${$variable}),$cadena);
            } else {
                $variable=$prevariable;
                global ${$variable};
                $cadena=str_replace('<<'.$variable.'>>',${$variable},$cadena);
            }
            $restoparte=substr($restoparte,strpos($restoparte,">>")+2);
        }
        return $cadena;
    }


    //Cambia el estado (entre dos estados) 
    function inputcambiarestado($nombreestado,$tablaestado,$color,$pendiente ="P", $finalizado ="F") {
        $nombreid['TPa']=['IdParte'];
        $nombreid['TSa']=['IdSancion'];
        $nombreid['TAn']=['IdAnotacion'];
        $nombreid['TAl']=['IdAlumno'];
        $nombreid['TNo']=['IdAlumno'];
        
        global ${$nombreestado};
        global ${$nombreid[$tablaestado][0]};
         
        $a= '<input type="submit" name="'.$nombreestado.'"'.
            ' formaction="12cambiarestados.php?TablaEstado='.$tablaestado.'&Estado='.
        $nombreestado.'&IdEstado='.${$nombreid[$tablaestado][0]}.'&ValorEstado='.${$nombreestado}.
            '&pendiente='.$pendiente.'&finalizado='.$finalizado.'" ';
        if (${$nombreestado}==$pendiente) {
            $b=$a.'class="btn btn-warning" value="'.$pendiente.'"';
        } else {
            $b=$a.'style="border: inset 0pt" class="'.$color.'" value="'.$finalizado.'"';
        }
        $salida=$b.'>';
        return $salida;
    }
	
	
function enviaInsert( $sql, array $parametros = array()) {
    if ($parametros === null) {
        $parametros = array();
    }
    Global $conn;
    $consulta = $conn->prepare($sql);
    foreach ($parametros as $nombreParametro => $valorParametro) {
        $consulta->bindValue($nombreParametro , $valorParametro);
    }
    $r = $consulta->execute();
    
    return $r;
}
    
    
?>




 
