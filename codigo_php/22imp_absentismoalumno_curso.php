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
include '00funciones.php';
$conn = conectarBaseDeDatos();
include('00apoyopdf.php');
session_start();
$curso=$_SESSION['PCurso'];

defineclasepdf ();
$pdf=new PDF();

$pdf->SetMargins(15,15);
$pdf->AliasNbPages();

// Recorre todo el alumnado con anotaciones abs
$consulta22 = "SELECT IdAlumno AS IdAlumnoselec FROM talumnos INNER JOIN talumnounidad 
ON idalumno=alumnoidalun WHERE CursoAlUn='$curso' AND IdAlumno IN 
(SELECT AlumnoIdAn FROM tanotacionesabs) ORDER BY UnidadIdAlUn";
$resSelect22 = $conn->prepare($consulta22);
$resSelect22->execute();
 

while($row22 = $resSelect22->fetch()) {
    extract($row22);
    $s_idalumno=$IdAlumnoselec;   
        
    // Busca en la tabla talumnos los datos del alumno seleccionado
    extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$s_idalumno'"));
    extract(consultaBDunica("SELECT Unidad FROM talumnounidad, tunidades WHERE IdUnidad=UnidadIdAlUn AND 
      CursoAlUn=CursoUn AND CursoUn='$curso' AND AlumnoIdAlUn='$s_idalumno'"));
    $pdf->AddPage('P');

    $text="REGISTROS DE ABSENTISMO DEL ALUMNO/A: ".$Alumno." (".$Unidad.")";
    $pdf->SetTitulo(utf8_decode($text));  
     
   //necesario ya que no lo imprime en la primera pagina con header
   $pdf->SetY(30);
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(0,10,$pdf->titulo,0,1,'C');
   $pdf->SetFillColor(224,235,255);

    $text='Fecha del informe: '.date("d-m-y").' (Curso Escolar '.$curso.' )';
    textoM($text,14);
    $pdf->ln(4);
    
   // TABLA DE pasos protocolo 




    //configuración de la tabla
    $Largocol=[50,30,10,30];
    $alineado=['C','C','C','C'];
    $pdf->SetWidths($Largocol);
    $pdf->SetAligns($alineado);
    $cabeceratabla=['Paso del Protocolo','Fecha','Estado','Curso Escolar'];
    $pdf->SetCabeceras($cabeceratabla);
    $pdf->SetColorRellenoCabeceras('165, 105, 189');

    $text="PASOS DEL PROTOCOLO DE ABSENTISMO:";    
     //Titulo segundo
     $pdf->SetFont('Arial','B',10);
     $pdf->Cell(0,16,utf8_decode($text),0,1,'C');
     $pdf->Ln(6);     
    
     $pdf->SetFont('Arial','B',10);
     $pdf->Cabecera(); //cabecer
 
    // Busca todas las anotaciones de absentismo del alumno seleccionado
    $consulta16 = "SELECT F_IdTipoAnotacion FROM tdatos WHERE substring(F_IdTipoAnotacion,1,2)<7";
    $resSelect16 = $conn->prepare($consulta16);
    $resSelect16->execute();
     
    $pdf->SetFont('Arial','',10);
    //Imprime en pantalla los partes del alumno seleccionado
    $a=0;
    while($row2 = $resSelect16->fetch()) {
        extract($row2);     
        $FechaAn="--";
        $EstadoAn="--";
        extract(consultaBDunica("Select * FROM tanotacionesabs WHERE AlumnoIdAn='$IdAlumno' AND
        TipoAn='$F_IdTipoAnotacion' order by FechaAn desc"));         
    
        if ($FechaAn=="--") { $FechaAn2=$FechaAn ;} else {$FechaAn2=darfechaesp($FechaAn);} 
        if ($EstadoAn=="--") {$EstadoAn2="--";}  else {$EstadoAn2=$EstadoAn;}
        if ($FechaAn=="--") {$FechaAn3= $FechaAn ;} else {$FechaAn3= cursoescolar($FechaAn);} 
        
        $Datosfila=[utf8_decode($F_IdTipoAnotacion),$FechaAn2,$EstadoAn2,$FechaAn3];        

        if ($a % 2 == 0) {
            $pdf->Row($Datosfila,'D','235, 237, 239'); 
        } else {
            $pdf->Row($Datosfila,'DF','235, 237, 239');
        }
        $a=$a+1;  
    }
        
    
   // TABLA DE RESUMEN FALTAS
   $text="RESUMEN MENSUAL DE FALTAS DE ASISTENCIA DEL CURSO ESCOLAR ".$curso;
        
   //Titulo segundo
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(0,16,utf8_decode($text),0,1,'C');
   $pdf->Ln(6);
   
    //configuración de la tabla
    $Largocol=[20,30,30,20,30];
    $alineado=['C','C','C','C','C'];
    $pdf->SetWidths($Largocol);
    $pdf->SetAligns($alineado);
    $cabeceratabla=['Mes','F. Injustificada','F. Justificada','Retraso','Revisado'];
    $pdf->SetCabeceras($cabeceratabla);
    $pdf->SetColorRellenoCabeceras('165, 105, 189');        
    $pdf->Cabecera(); //cabecera tabla partes        
    $pdf->SetFont('Arial','',10);
    
    //Imprime en pantalla los sanciones del alumno seleccionado      
    $Meses=Array(9 => 'Sep',10 => 'Oct',11 => 'Nov',12 => 'Dic',1 => 'Ene',2 => 'Feb',3 => 'Mar',4 => 'Abr',5 => 'May',6 => 'Jun');
    $b=0;
    foreach($Meses as $m => $mes) {    
        unset($CorreoTutorAn,$Frevision,$HFI,$HFJ,$HR);    
        extract(consultaBDunica("Select sum(HorasI) as HFI FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
        extract(consultaBDunica("Select sum(HorasJ) as HFJ FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
        extract(consultaBDunica("Select sum(HorasR) as HR FROM tfaltas WHERE MONTH(FechaFa)=$m and AlumnoIdFa='$IdAlumno'")); 
        extract(consultaBDunica("Select max(FechaActualizacion) as Frevision FROM tfaltas
        WHERE MONTH(FechaFa)=$m AND AlumnoIdFa='$IdAlumno'")); 
        extract(consultaBDunica("Select CorreoTutorAn FROM tanotacionesabs
        WHERE OtraInformacion='$m' and AlumnoIdAn='$IdAlumno'")); 
        $Datosfila=[$mes,$HFI,$HFJ,$HR,darfechaesp($Frevision)];
        if ($b % 2 == 0) {
            $pdf->Row($Datosfila,'D','235, 237, 239'); 
        } else {
            $pdf->Row($Datosfila,'DF','235, 237, 239'); 
        }
        $b=$b+1;  
    } 
        
    
   // TABLA DE ANOTACIONES 
   $text="ANOTACIONES DE ABSENTISMO:";
   //Titulo segundo
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(0,16,utf8_decode($text),0,1,'C');
   $pdf->Ln(6);
   
    //configuración de la tabla
    $Largocol=[30,40,100];
    $alineado=['C','C','C'];
    $pdf->SetWidths($Largocol);
    $pdf->SetAligns($alineado);
    $cabeceratabla=['Fecha',utf8_decode('Tipo Anotación'),utf8_decode('Anotación')];
    $pdf->SetCabeceras($cabeceratabla);
    $pdf->SetColorRellenoCabeceras('165, 105, 189');
    $pdf->Cabecera(); //cabecera tabla partes   
    $pdf->SetFont('Arial','',10);    
      
     // Busca todas las anotaciones de absentismo del alumno seleccionado
    $consulta6 = "SELECT * FROM tanotacionesabs WHERE AlumnoIdAn = '$IdAlumno' ORDER BY FechaAn DESC";
    $resSelect6 = $conn->prepare($consulta6);
    $resSelect6->execute();
    $a=0;
    while($row6 = $resSelect6->fetch()) {
        extract($row6);
        if (strlen($FechaCita)>0) {
            $Anotacionplus=$Anotacion."\n La familia ha sido citada el día ".darfechaesp($FechaCita).
            " a las ".$HoraCita." horas. ";
        } else {
            $Anotacionplus=$Anotacion;
        }        
        $Datosfila=[darfechaesp($FechaAn),utf8_decode($TipoAn),utf8_decode($Anotacionplus)];
        if ($a % 2 == 0) {
            $pdf->Row($Datosfila,'D','235, 237, 239');
        } else {
            $pdf->Row($Datosfila,'DF','235, 237, 239');
        }             
        $a=$a+1;  
    }        
} 
 
$pdf->Output('D',utf8_decode("Registros de absentismo (curso $curso).pdf"));
?>