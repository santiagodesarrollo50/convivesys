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
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include '00funciones.php';
$conn = conectarBaseDeDatos();
include('00apoyopdf.php');
session_start();
$curso=$_SESSION['PCurso'];
$diaSemana=["Lunes","Martes","Miércoles","Jueves","Viernes"];

// Comprueba que se haya seleccionado una única sanción
if (isset($_POST['checkboxsancion'])) {
    $ArrayIdSanciones = $_POST['checkboxsancion'];
    if (count($ArrayIdSanciones) != 1) {
        $Procedencia = $_SESSION['Procedencia'];
        header('location:'.$Procedencia);
    }
} else {
    $Procedencia = $_SESSION['Procedencia'];
    header('location:' . $Procedencia);
}


//Composición del informe seguimiento aula convivencia pdf
$s_idSancion=$ArrayIdSanciones[0]; 

//Comprobar que hay fechas de AC 
$condicionHayFechasSancion=true;

//Comprobar que la sanción es de aula de convivencia para seguir y obtiene datos fecha inicio y fecha fin de sanción
extract(consultaBDunica("SELECT TipoSa, AlumnoIdSa, FechaInicio, FechaFin, DiasSancion FROM tsanciones WHERE IdSancion = '$s_idSancion'"));
extract(consultaBDunica("SELECT valor1 as TipoSancionLargo FROM tconfiguracion WHERE tipo='tiposancion' AND id = '$TipoSa'"));
$condicionSancionAC = (strpos(strtolower($TipoSancionLargo), 'aula') !== false);

if ($condicionSancionAC && $condicionHayFechasSancion) {
    //Obtener los datos necesarios Nombre alumno, grupo, array de días en el aula de convivencia
    extract(consultaBDunica("SELECT Alumno FROM talumnos WHERE IdAlumno = '$AlumnoIdSa'"));
    extract(consultaBDunica("SELECT Unidad from tunidades inner join talumnounidad 
		ON IdUnidad=UnidadIdAlUn WHERE AlumnoIdAlUn='$AlumnoIdSa' AND CursoAlUn='$curso'"));

    //Recorremos las fechas de sanción para construir el array de dias de sancion arrayDiasSancion    
    $arrayDiasSancion = [];
    $fechaPunteroStr=$FechaInicio; 
    $fechaPunteroTime=strtotime($FechaInicio);
    for ($n=1; $fechaPunteroStr<=$FechaFin; $n++) { 
        $condicionNoFinSemana = !(date('D',$fechaPunteroTime)=="Sun" || date('D',$fechaPunteroTime)=="Sat");
        extract(consultaBDunica("SELECT count(fecha1) as siFestivo FROM tconfiguracion WHERE
            fecha1<= '$fechaPunteroStr' and '$fechaPunteroStr' <= fecha2 and tipo='festivo'"));
        $condicionNoEsFestivo = ($siFestivo==0); 
        if ($condicionNoFinSemana && $condicionNoEsFestivo) {
            array_push($arrayDiasSancion,$fechaPunteroStr);
        }
        $fechaPunteroTime= strtotime("+ $n days",strtotime($FechaInicio));
        $fechaPunteroStr= date('Y-m-d',$fechaPunteroTime); 
    }
    
    //Construimos tabla del informe en tablaSeguimiento
    $tablaSeguimiento=[];
    $numeroPaginas = intdiv($DiasSancion-1,5);
    $ordenArrayDiasSancion=0;
    for ($pagina=0; $pagina<=$numeroPaginas; $pagina++) {
        //Columna 0
        $fila=0;
        foreach ( ["","1ª","2ª","3ª","R","4ª","5ª","6ª"] as $texto) {
            $tablaSeguimiento[$pagina][$fila][0]=utf8_decode($texto); 
            $fila++;
        }
        //Resto columnas
        for ($columna=1; $columna<=5 && $ordenArrayDiasSancion<$DiasSancion; $columna++) {
            $cabeceraFecha=utf8_decode($diaSemana[date('N',strtotime($arrayDiasSancion[$ordenArrayDiasSancion]))-1]."\n".
                darfechaesp($arrayDiasSancion[$ordenArrayDiasSancion]));
            $ordenArrayDiasSancion++;
            $textoFijo="Conducta:\n\n\nTrabajo:\n\n\n";
            $fila=0;
            foreach ([$cabeceraFecha,$textoFijo,$textoFijo,$textoFijo," ",$textoFijo,$textoFijo,$textoFijo] as $texto) {
                $tablaSeguimiento[$pagina][$fila][$columna]=$texto; 
                $fila++;
            }           
        }
    }
    
    //Genera documento pdf
    defineclasepdf ();
    $pdf=new PDF();

    $pdf->SetMargins(15,15);
    $pdf->AliasNbPages();

    for($avancePagina=0; $avancePagina<=$numeroPaginas; $avancePagina++) {
        //Calcula el numero de columnas (y su ancho) que hay en esta página
        if($DiasSancion>($avancePagina+1)*5) {
            $numerocolumnas=5;
        } else {
            $numerocolumnas=$DiasSancion-($avancePagina)*5;
        }

        $pdf->AddPage('P');
        $text="SEGUIMIENTO AULA DE CONVIVENCIA";
        $pdf->SetTitulo(utf8_decode($text));    
        
        //necesario ya que no lo imprime en la primera pagina con header
        $pdf->SetY(30);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,10,$pdf->titulo,0,1,'C');
        
        // TABLA DE SEGUIMIENTO
        //configuración de la tabla
        $pdf->SetFillColor(224,235,255);
        $anchocolumna=165/$numerocolumnas;
        $Largocol=[10,$anchocolumna,$anchocolumna,$anchocolumna,$anchocolumna,$anchocolumna];
        $alineado=['C','L','L','L','L','L'];
        $pdf->SetWidths($Largocol);
        $pdf->SetAligns($alineado);
        $cabeceratabla=$tablaSeguimiento[$avancePagina][0];
        $pdf->SetCabeceras($cabeceratabla);
        $pdf->SetColorRellenoCabeceras('165, 105, 189');

        $text='ALUMNO/A: '.$Alumno. " (".$Unidad.")";        
        textoM($text,12);
        $pdf->ln(1);

        $text='Fechas de la sanción: del '.darfechaesp($FechaInicio). " al ".darfechaesp($FechaFin);        
        textoM($text,11);
        $pdf->ln(3);

        $text='     Concretar en cada hora la conducta: Bien/Regular/Mal';        
        textoM($text,10);
        

        $text='     Concretar en cada hora el trabajo realizado: Bien/Regular/Mal';        
        textoM($text,10);
        $pdf->ln(3);
        
        //Imprime cabecera tabla seguimiento
        $pdf->SetFont('Arial','B',10);
        $pdf->Cabecera();  
            
        //Imprime en pantalla la tabla de seguimiento
        $pdf->SetFont('Arial','',10);
        for($n=1; $n<=7; $n++) {
            $Datosfila=$tablaSeguimiento[$avancePagina][$n];
            if ($n % 2 == 0)
            { $pdf->Row($Datosfila,'D','235, 237, 239'); }
            else 
            { $pdf->Row($Datosfila,'DF','235, 237, 239'); }            
        }

        $pdf->ln(5);
        $text='Página: '.($avancePagina+1)."/".($numeroPaginas+1);        
        textoM($text,9);
    }
               
    
$pdf->Output('D',utf8_decode('Seguimiento  Aula Convivencia '.$Alumno.' ('.$Unidad.').pdf'));
}

//header('location:01disciplinaalumno.php'); 
$Procedencia=$_SESSION['Procedencia'];
header('location:'.$Procedencia);
?>