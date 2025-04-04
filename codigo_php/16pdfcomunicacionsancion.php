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
$curso=$_SESSION['PCurso'];

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


if (isset($_POST['generar']))  {  
  session_start();
  $ArrayIdSanciones = $_SESSION['arraysanciones'];
  $obs_sancion=$_POST['obs_sancion'];
  $firmante=$_POST['firmante'];

  foreach ($ArrayIdSanciones as $idsan) {
    $edit = $conn->query("UPDATE tsanciones SET observacionescomsancion='$obs_sancion' WHERE IdSancion='$idsan'"); 
  }
// iniciamos pdf
defineclasepdf ();
$pdf=new PDF();


$pdf->SetMargins(15,15);
$pdf->AliasNbPages();
//Primera página
$pdf->AddPage();

// Carga el título y cuerpo modelo de la comunicación
extract(consultaBDunica("SELECT * from tdatos where C_IdComunicacion='PDFComunicaSancion'"));
extract(consultaBDunica("SELECT valor1 as correoJefatura FROM tconfiguracion WHERE id='correojefaturaestudios'"));
extract(consultaBDunica("SELECT valor1 as localidad FROM tconfiguracion WHERE id='localidadies'"));

$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,10,utf8_decode($C_TituloComunicacion." ".$fechadehoy),0,1,'C');


$pdf->SetFont('Arial','',10);
$ytutoreslegales=$pdf->GetY()+5;//Aqui se escribirá los nombres de los tutores legales
$pdf->Ln();

$pdf->Cell(0,10,utf8_decode($localidad.', '.fechaformatogranada( )),0,1,'L');
$pdf->Ln(5);

$pdf->SetFont('Arial',"",8);
$pdf->MultiCell(0,4,
utf8_decode($C_CuerpoComunicacion),0);

extract(consultaBDunica("SELECT valor1 as Director from tconfiguracion where id='profesordirector'"));

 //Comprobar que las sanciones pertenecen al mismo alumno e identifica al alumno
  $anterior="";
  $n=0;
  while ($n < count($ArrayIdSanciones) )   {
    extract(consultaBDunica("SELECT * FROM tsanciones WHERE IdSancion = '$ArrayIdSanciones[$n]'"));

    if ($n>0 && $anterior!=$AlumnoIdSa)
    {
     $_SESSION['aviso']=5; //indicará error de selección
     $Procedencia=$_SESSION['Procedencia'];
    header('location:'.$Procedencia);
    }
    $anterior = $AlumnoIdSa;
    $n++;
   }

   extract(consultaBDunica("SELECT * FROM talumnos WHERE IdAlumno = '$AlumnoIdSa'"));
   extract(consultaBDunica("SELECT Unidad from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
   where AlumnoIdAlUn='$IdAlumno' and CursoUn='$curso'"));


//Cabecera tutores legales
$yseguir=$pdf->GetY();
$pdf->SetY($ytutoreslegales);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,utf8_decode($Tutor1),0,1,'R');
$pdf->Cell(0,5,utf8_decode($Tutor2),0,1,'R');
$pdf->SetY($yseguir);


$pdf->SetFont('Arial','B',9);
$pdf->Cell(50,8,utf8_decode('Identificación del alumno/a: '),0,0);
$pdf->SetFont('Arial',"",9);
$pdf->MultiCell(0,8, utf8_decode($Alumno.' ( '. $Unidad.' )'),0);


//Sanciones

$pdf->Ln(2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,utf8_decode('Sanciones aplicadas al alumno/a:'),0,1);



//Cabecera tabla sanciones
$pdf->SetFillColor(224,235,255);
     
    $pdf->Cell(102,7,utf8_decode('Tipo Sanción'),1,0,'L',true);
    $pdf->Cell(58,7,utf8_decode('Periodo de aplicación'),1,0,'L',true);
    $pdf->Cell(20,7,utf8_decode('Nº de días'),1,0,'L',true);
   
   $pdf->Ln();
   
   $sanciongravesino="no";
    foreach ($ArrayIdSanciones as $IdSan)     {
      extract(consultaBDunica("SELECT * FROM tsanciones WHERE IdSancion = '$IdSan'"));  
 
        //Busca el nombre largo sancion
        extract(consultaBDunica("SELECT valor1 as B_TiposSancion FROM tconfiguracion WHERE tipo='tiposancion' AND id = '$TipoSa'"));
        $pdf->SetFont('Arial','',9);
        
      $x = $pdf->GetX() + 102;
    $y = $pdf->GetY();  
      $pdf->MultiCell(102,7,utf8_decode($B_TiposSancion),1);
      $alto = $pdf->GetY()-$y; 
      $pdf->SetXY($x,$y);
      $pdf->Cell(58,$alto,utf8_decode(' desde '.darfechaesp($FechaInicio).' hasta '.darfechaesp($FechaFin)),1);
      $pdf->Cell(20,$alto,utf8_decode($DiasSancion),1,0,'C');
      $pdf->Ln();
    }
     
     
     //Incidentes partes que 

$pdf->Ln(2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,utf8_decode('Incidentes que motivan las sanciones:'),0,1);



//Cabecera tabla incidentes
$pdf->SetFillColor(224,235,255);
    $pdf->Cell(30,7,utf8_decode('Fecha Incidente'),1,0,'L',true);
    $pdf->Cell(150,7,utf8_decode('Tipo de Incidente'),1,0,'L',true);
   $pdf->Ln();
   $pdf->SetFont('Arial','',9);
   
   
   
  $resSelect4 = $conn->query(
     "SELECT distinct ParteIdSaPa FROM tsancionparte WHERE SancionIdSaPa 
      in (".implode(",", $ArrayIdSanciones).")"
  );    
   
    while($row4 = $resSelect4->fetch()) {
      extract($row4);        
      extract(consultaBDunica("SELECT * FROM tpartes WHERE IdParte = '$ParteIdSaPa'"));  
      //Busca el nombre largo tipo parte
      extract(consultaBDunica("SELECT valor1 as A_TiposParte FROM tconfiguracion WHERE tipo='tipoparte' AND id='$TipoPa'"));
      $x = $pdf->GetX();
      $y = $pdf->GetY();
      $pdf->SetX(30+$x);
      $pdf->MultiCell(150,7,utf8_decode($A_TiposParte),1);
      $alto = $pdf->GetY()-$y; 
      $pdf->SetXY($x,$y);
      $pdf->Cell(30,$alto,utf8_decode(darfechaesp($FechaPa)),1,0,"C");     
      $pdf->Ln();
    } 
    
         
         
   $pdf->Ln(3);
   
   $pdf->SetFont('Arial',"",8);
   
   
$pdf->MultiCell(0,4,utf8_decode('Si necesita alguna aclaración, pueden contactar con Jefatura de Estudios ('.$correoJefatura.')'),0);
$pdf->Ln(4);

if ($obs_sancion != "") {
  $obs_sancion = "Observaciones: ".$obs_sancion;
$pdf->MultiCell(0, 4, utf8_decode($obs_sancion), 1, 'L');
$pdf->Ln(4);
}

$pdf->MultiCell(0,4,utf8_decode('Atentamente,'),0,"C");
$pdf->Ln(3);


If ($firmante=="director")       {
  $pdf->SetX(95);
  $pdf->MultiCell(0,4,utf8_decode('El Director'),0);
  $pdf->Ln(15);
  $pdf->SetX(95);
  $pdf->MultiCell(0,4,utf8_decode('Fdo.: D. '.$Director),0);  
}  else {
  $pdf->SetX(100);
  $pdf->MultiCell(0,4,
  utf8_decode('Jefatura de Estudios'),0);
  $pdf->Ln(15);
  $pdf->SetX(100);
  $pdf->MultiCell(0,4,utf8_decode('Fdo.: _____________________________________________'),0);  
}
    
$pdf->Ln(3);
$pdf->MultiCell(0,4,
utf8_decode($C_Cuerpo2Comunicacion),0);
$pdf->Ln(2);




$pdf->SetFont('Arial',"B",9);
$pdf->MultiCell(0,4,utf8_decode($C_Cuerpo3Comunicacion),0,"C"); 
$pdf->Ln(12);

$pdf->SetFont('Arial',"",8);
$pdf->MultiCell(0,4,utf8_decode('Fdo.:________________________________________________________________'),0,"C"); 

$inicialessinpuntos=str_replace('.',' ',$Iniciales);
$namefile='Comunicación Sanción-'.$inicialessinpuntos.'('.$Unidad.').pdf'; 
$pdf->Output('D',utf8_decode($namefile));
$Procedencia=$_SESSION['Procedencia'];
header('location:'.$Procedencia);
  }













if (!isset($_POST['generar'])) { 
  include ('13cabecera.php');    
  $_SESSION['arraysanciones'] = $_POST['checkboxsancion'];
  $ArrayIdSanciones = $_SESSION['arraysanciones'];
  // Comprueba que se haya seleccionado una sanción
 if (count($ArrayIdSanciones)==0)    {
  $Procedencia=$_SESSION['Procedencia'];
  header('location:'.$Procedencia);
 }   


//Comprobar quién firma la comunicación (Director o Jefe Estudios)
$seleccionfirmadirector = "";
$seleccionfirmajefeestudios = "checked";
$obs_sancionmemoria = "";
foreach ($ArrayIdSanciones as $IdSan)  {
  extract(consultaBDunica("SELECT TipoSa, observacionescomsancion FROM tsanciones WHERE IdSancion = '$IdSan'"));
  if (substr($TipoSa,0,1)=="E") {
    $seleccionfirmadirector="checked";
    $seleccionfirmajefeestudios = "";
  }
  $obs_sancionmemoria = $observacionescomsancion;
}

 
  
?>


<div class="container"> <!-- centra el contenido -->
  <div class="row mt-4">
    <div class="col-md-8 mx-auto bg-light rounded p-4">
      <h5 class="text-center font-weight-bold">GENERANDO PDF COMUNICACIÓN DE SANCIÓN A LA FAMILIA</h5>
      <hr class="my-1">
      
      <form  method="post" class="p-3"> 
     
        <div>
          <h5 class="text-secundary">Observaciones a incluir en la comunicación de la sanción</h6>
        </div>
        
        <div class="input-group">
        <textarea name="obs_sancion" rows="5" cols="40" class="form-control form-control-lg rounded-0 border-info" 
             autocomplete="off"><?php echo $obs_sancionmemoria; ?></textarea>
        </div>
        <br><br>
       
       <div>
          <h5 class="text-secondary">Firma de la comunicación de la sanción</h5>
        </div>
        <div class="input-group">            
          <input type="radio" name="firmante" value="director" id="dire" <?php echo $seleccionfirmadirector; ?> > 
          <label for="dire">Director</label>
        </div>
        <div class="input-group">          
          <input type="radio" name="firmante" value="jefeestudios" id="jefee" <?php echo $seleccionfirmajefeestudios; ?>>
          <label for="jefee">Jefe Estudios</label>
        </div> 
        <br><br>
        <div class="input-group-append">
            <input type="submit" name="generar" value="Generar documento PDF" class="btn btn-info btn-lg rounded-1">
          </div>   

      </form>
    </div>
    
     
  </div>
</div>




</body>
</html>


<?php

}
?>