<?php

include 'fpdf184/fpdf.php';
//require('fpdf184/tfpdf/tfpdf.php');


//display errores HAY QUE QUITARLO PARA QUE SE GENERE EL PDF
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
error_reporting(0);

function textoM($text,$tamaño,$estilo="",$alineado="J") { 
    global $pdf;
    $pdf->SetFont('Times',$estilo,$tamaño);
    $pdf->MultiCell(0,$tamaño/2,utf8_decode(componeretiquetas($text)),0,$alineado);
}

function textoG($text,$tamaño,$estilo="",$alineado="J") { 
    global $pdf;
    $pdf->SetFont('Times',$estilo,$tamaño);
    $pdf->MultiCell(0,$tamaño/2+2,utf8_decode(componeretiquetas($text)),0,$alineado);
}


//definición de la clase PDFf
function defineclasepdf () {
    class PDF extends FPDF
    {

    function Header()     {
    extract(consultaBDunica("SELECT valor1 as nombreies FROM tconfiguracion WHERE id='nombreies'"));
    //Logo
     $this->SetXY(15,15);
     $this->Image("juntaandalucia.jpg");
    //Arial bold 15
    $this->SetFont('Arial','B',11);
    //Movernos a la derecha
    $this->Cell(10);
    //Título
    $this->SetXY(110,15);
    $this->Cell(0,5,utf8_decode('CONSEJERÍA DE EDUCACIÓN Y DEPORTE'),0,2,'L');
    $this->SetFont('Arial','B',9);
    
    $this->Cell(0,5,utf8_decode($nombreies),0,0,'L');
    //Salto de línea
    $this->Ln(10);      
   }
   
   /*Pie de página
   function Footer(){
    Posición: a 1,5 cm del final
    $this->SetY(-15);
    Arial italic 8
    $this->SetFont('Arial','I',8);
    Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
   }
   */
   
   
    //funciones de tabla
    var $widths;
    var $aligns;
    var $tituloscabeceras;
    var $colorrellenocabeceras;
    var $titulo;


    function SetTitulo($w) {
        //Set the array of column widths
        $this->titulo=$w;
    }




    function SetColorRellenoCabeceras($w) {
        //Set the array of column widths
        $this->colorrellenocabeceras=$w;
    }

    function SetCabeceras($w) {
        //Set the array of column widths
        $this->tituloscabeceras=$w;
    }
 
    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }





    function Cabecera( ) { //de la tabla    
        $this->SetFillColor($this->colorrellenocabeceras);
        //Calculate the height of the row
        $nb=0; 
        for($i=0;$i<count($this->tituloscabeceras);$i++)
        
        $nb=max($nb,$this->NbLines($this->widths[$i],$this->tituloscabeceras[$i]));
        $h=5*$nb;
    
        //Draw the cells of the row
        for($i=0;$i<count($this->tituloscabeceras);$i++)
        {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h,'DF');
        //Print the text
        $this->MultiCell($w,5,$this->tituloscabeceras[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }



    function Row($data,$relleno,$colorrelleno)    {       
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++) {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->SetFillColor($colorrelleno);
        $this->Rect($x,$y,$w,$h,$relleno);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
    }

    function CheckPageBreak($h) {
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)  {
        $this->AddPage($this->CurOrientation);
        $this->Cabecera();
        }
    }

    function NbLines($w,$txt) {
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)  {
        $c=$s[$i];
        if($c=="\n")  {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax) {
            if($sep==-1) {
                if($i==$j)
                    $i++;
            } else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        } else
            $i++;
    }
    return $nl;
    }

}
}
   ?>
