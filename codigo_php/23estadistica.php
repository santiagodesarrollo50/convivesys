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
    
    
// Cargamos fechas periodos

$consulta = "SELECT id as I_Trimestre, fecha1 as I_FechaInicio, fecha2 as I_FechaFin FROM tconfiguracion WHERE tipo='fechastrimestres'";
$resSelect3 = $conn->prepare($consulta);
$resSelect3->execute(); 

while($row3 = $resSelect3->fetch()) {
    extract($row3);
    $FechasTrim[$I_Trimestre]=[$I_FechaInicio, $I_FechaFin];
}
$FechasTrim['Todo'] = [$FechasTrim['1Trim'][0],$FechasTrim['3Trim'][1]];


$NombreTrimestre=['1Trim' => '1º Trimestre', '2Trim' => '2º Trimestre', '3Trim' => '3º Trimestre', 'Todo' => 'Todo el curso'];

$Periodos='Todo';

if(isset($_POST['cambiarperiodo'])) 
  {
        $Periodos=$_POST['fperiodo']; 
  }
  $Fechainicioperiodo=$FechasTrim[$Periodos][0];
  $Fechafinperiodo=$FechasTrim[$Periodos][1];

   
    
    ?>
 
 
    <center>
    <div class="container">
    <h3> <b>ESTADÍSTICA DE CONVIVENCIA. RESUMEN POR TRIMESTRES </b></h3>
    </div>
    
    <div  id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a  href="../estadisticas/EstTodosTrims.csv" ><button type="button" class="btn btn-dark" >
            Exportar CSV - Resumen por trimestres
          </button></a>
    </li> </ul> </div>  

    
    <?php

    //Definir tabla1
    $Tabla[0]=['Periodo','Fechas','Nº días','Nº Partes','Nº Partes Graves','Puntos Disciplina (C=1;G=2)',
    'Nº Alumnos','Nº Alumnos con Parte','Nº Alumnos Conflictivos (>5pts)','Nº Alumnos Sancionados',
'Nº Alumnos Expulsados','Nº Alumnos en AC','Nº Sanciones','Nº días de expulsión','Nº días de AC',
'Puntos de Sanción (AC=1;Exp=2)','Indice Sancionador (días de exp por cada parte grave)'];
 
    
    $a=1; 
    foreach ($NombreTrimestre as $trim => $trimestre)  {
        $Fechainicioperiodot=$FechasTrim[$trim][0];
        $Fechafinperiodot=$FechasTrim[$trim][1];
        unset($NPartes, $NPartesGraves, $NAlumnos, $NAlumnosParte, $NAlumnosConflictivos, $NAlumnosSancionados,
            $NAlumnosExp, $NAlumnosAC, $NSanciones, $NDiasExp, $NDiasAC);
        //Número de partes 
        extract(consultaBDunica("SELECT count(IdParte) as NPartes from tpartes where 
        FechaPa>='$Fechainicioperiodot' and FechaPa<='$Fechafinperiodot'
        "));
        // Número de partes graves
        extract(consultaBDunica("SELECT count(IdParte) as NPartesGraves from tpartes where 
        substring(TipoPa,1,1)='G' and FechaPa>='$Fechainicioperiodot' and FechaPa<='$Fechafinperiodot'
        "));
        // Número de alumnos matriculados
        extract(consultaBDunica("SELECT count(Alumno) as NAlumnos from talumnos"));
        //Número de alumnos con parte
        extract(consultaBDunica("SELECT count(distinct AlumnoIdPa) as NAlumnosParte from tpartes 
        where FechaPa>='$Fechainicioperiodot' and FechaPa<='$Fechafinperiodot'
        "));
        //Numero de alumnos conflictivos
        extract(consultaBDunica("SELECT count(IdAlumno) as NAlumnosConflictivos from talumnos where 
        IdAlumno in (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodot' and FechaPa<='$Fechafinperiodot') and
        IdAlumno in (SELECT AlumnoIdPa from tpartes group by AlumnoIdPa 
        having 2*count(case substring(TipoPa,1,1) when 'G' then '1' end)+count(case substring(TipoPa,1,1) when 'C' then '1' end)>5)
        "));
        //Número de alumnos sancionados
        extract(consultaBDunica("SELECT count(distinct AlumnoIdSa) as NAlumnosSancionados from tsanciones 
        where FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot'
        "));
        //Número de alumnos expulsados
        extract(consultaBDunica("SELECT count(distinct AlumnoIdSa) as NAlumnosExp from tsanciones 
        where substring(TipoSa,1,1)='E' and FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot'
        "));
        //Número de alumnos en AC
        extract(consultaBDunica("SELECT count(distinct AlumnoIdSa) as NAlumnosAC from tsanciones
        where substring(TipoSa,1,1)='A' and FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot'
        "));
        //Número de sanciones
        extract(consultaBDunica("SELECT count(IdSancion) as NSanciones from tsanciones where 
        FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot'
        "));
        //Número de días de sanción
        extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasSancion from tsanciones where 
        FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot'
        "));
        //Número de días de expulsión
        extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasExp from tsanciones where 
        FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot' and 
        substring(TipoSa,1,1)='E'
        "));
        //Número de días de AC
        extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasAC from tsanciones where 
        FechaSa>='$Fechainicioperiodot' and FechaSa<='$Fechafinperiodot' and 
        substring(TipoSa,1,1)='A'
        "));

        $indiceCastigo = "-";
        if ($NPartesGraves*2+($NPartes-$NPartesGraves)!=0) {
            $indiceCastigo = round(($NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC))/($NPartesGraves*2+($NPartes-$NPartesGraves)),1);
        } 

        $Tabla[$a]=[$trimestre, "de ".darfechaesp($Fechainicioperiodot)."<br>al ".darfechaesp($Fechafinperiodot),
        contardiaslectivos($Fechainicioperiodot,$Fechafinperiodot), $NPartes,
        $NPartesGraves, $NPartesGraves*2+($NPartes-$NPartesGraves), $NAlumnos,
        $NAlumnosParte, $NAlumnosConflictivos, $NAlumnosSancionados, $NAlumnosExp,
        $NAlumnosAC, $NSanciones, $NDiasExp, $NDiasAC, 
        $NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC),
        str_replace(".",",",$indiceCastigo)
        ];
        $a++;
    }
    
//Pasar tabla a csv
$namefile="./estadisticas/EstTodosTrims.csv";
$fh = fopen($namefile, 'w') or die("Se produjo un error al crear el archivo");
fputcsv($fh,$Tabla[0]);  
foreach (range(1,count($Tabla)-1) as $nn)
{
  fputcsv($fh,$Tabla[$nn]);
}
fclose($fh);

//Escribir tabla en pantalla
echo(imprimetablaarray2($Tabla));
    ?>
    
<br>




<form method="POST" >
    
    <input type="submit" name="cambiarperiodo" value="Cambiar Periodo" 
    class="btn btn-primary" formaction="23estadistica.php">
    
    <select  name="fperiodo" >
    <?php 
    foreach ($NombreTrimestre as $trim => $trimestre)
    {
        if ($trim==$Periodos)
            { echo  '<option value="'.$trim.'" selected>'.$trimestre.'</option>';}
    else
    { echo '<option value="'.$trim.'">'.$trimestre.'</option>';}
    }
    ?>
    </select> 
    <br><br><br>



<div class="container">
    <h3> <b>ESTADÍSTICA DE CONVIVENCIA POR UNIDADES (<?= $NombreTrimestre[$Periodos] ?>) </b></h3>
    </div>

    <div  id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a  href="<?= '../estadisticas/Estadistica_unidades'.$Periodos.'.csv' ?>" ><button type="button" class="btn btn-dark" >
          <?= 'Exportar CSV - Estadistica por unidades '.$NombreTrimestre[$Periodos] ?>
          </button></a>
    </li> </ul> </div>

    

    <table  class="table table-hover" id="tablaregistros">
      
       
   
<?php

//Definir tabla2
$Tabla2[0]=['Unidad','Nº Partes','Nº Partes Graves','Puntos Disciplina (C=1;G=2)',
'Puntos de Disciplina por alumno matriculado','Nº Alumnos','Nº Alumnos con Parte',
'Nº Alumnos Conflictivos (>5pts)','Nº Alumnos con Parte cada 100 alumnos','Nº Alumnos Sancionados',
'Nº Alumnos Expulsados','Nº Alumnos en AC','Nº Sanciones','Nº días de expulsión',
'Nº días de AC','Puntos de Sanción','Puntos de Sanción por cada alumno matriculado',
'Indice Sancionador (días de exp por cada parte grave)'];

    //lista las unidades 
    $consulta3 = "SELECT Unidad, IdUnidad from tunidades where IdUnidad in
    (SELECT UnidadIdAlUn FROM talumnounidad WHERE AlumnoIdAlUn in
    (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo')
    and CursoAlUn='$curso')
     order by Orden";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    
    $a=1;
    while($row3 = $resSelect3->fetch())
    {
    extract($row3);
            
    
    unset($NPartes, $NPartesGraves, $NAlumnos, $NAlumnosParte, $NAlumnosConflictivos, $NAlumnosSancionados,
    $NAlumnosExp, $NAlumnosAC, $NSanciones, $NDiasExp, $NDiasAC);

    //Número de partes por unidad
    extract(consultaBDunica("SELECT count(IdParte) as NPartes from tpartes where 
    FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' and
    AlumnoIdPa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    // Número de partes graves por unidad
    extract(consultaBDunica("SELECT count(IdParte) as NPartesGraves from tpartes where 
    substring(TipoPa,1,1)='G' and FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' and
    AlumnoIdPa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    // Número de alumnos matriculados por unidad
    extract(consultaBDunica("SELECT count(*) as NAlumnos from talumnounidad where UnidadIdAlUn='$IdUnidad'"));
    //Número de alumnos con parte
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosParte from talumnounidad where UnidadIdAlUn='$IdUnidad' and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo')
    "));
    //Numero de alumnos conflictivos
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosConflictivos from talumnounidad 
    where UnidadIdAlUn='$IdUnidad' and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo') and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes group by AlumnoIdPa 
    having 2*count(case substring(TipoPa,1,1) when 'G' then '1' end)+count(case substring(TipoPa,1,1) when 'C' then '1' end)>5)
    "));

    //Número de alumnos sancionados
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosSancionados from talumnounidad where UnidadIdAlUn='$IdUnidad' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de alumnos expulsados
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosExp from talumnounidad where UnidadIdAlUn='$IdUnidad' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where substring(TipoSa,1,1)='E') and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de alumnos en AC
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosAC from talumnounidad where UnidadIdAlUn='$IdUnidad' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where substring(TipoSa,1,1)='A') and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de sanciones
    extract(consultaBDunica("SELECT count(IdSancion) as NSanciones from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    //Número de días de sanción
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasSancion from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    if (!isset($NDiasSancion))
    {$NDiasSancion=0;}
    //Número de días de expulsión
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasExp from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    substring(TipoSa,1,1)='E' and
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    if (!isset($NDiasExp))
    {$NDiasExp=0;}
    //Número de días de AC
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasAC from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    substring(TipoSa,1,1)='A' and
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad where UnidadIdAlUn='$IdUnidad')
    "));
    if (!isset($NDiasAC))
    {$NDiasAC=0;}
        
$Tabla2[$a]=[$Unidad, $NPartes ,$NPartesGraves ,$NPartesGraves*2+($NPartes-$NPartesGraves) ,
str_replace(".",",",round(($NPartesGraves*2+($NPartes-$NPartesGraves))/$NAlumnos,1)) ,$NAlumnos ,
$NAlumnosParte ,$NAlumnosConflictivos ,
str_replace(".",",",round(100*$NAlumnosParte/$NAlumnos,1)) ,
$NAlumnosSancionados ,$NAlumnosExp ,$NAlumnosAC ,$NSanciones ,$NDiasExp ,$NDiasAC ,
$NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC) ,
str_replace(".",",",
round(($NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC))/$NAlumnos,1)) ,
str_replace(".",",",
round(($NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC))/($NPartesGraves*2+($NPartes-$NPartesGraves)),1)
)];

$a=$a+1;
}


//Pasar tabla a csv
$namefile2="./estadisticas/Estadistica_unidades".$Periodos.".csv";
$fh2 = fopen($namefile2, 'w') or die("Se produjo un error al crear el archivo");
fputcsv($fh2,$Tabla2[0]);  
foreach (range(1,count($Tabla2)-1) as $nn)
{
  fputcsv($fh2,$Tabla2[$nn]);
}
fclose($fh2);

//Escribir tabla en pantalla
echo(imprimetablaarray2($Tabla2));
    ?>
    





    <div class="container">
    <h3> <b>ESTADÍSTICA DE CONVIVENCIA POR BLOQUES (<?= $NombreTrimestre[$Periodos] ?>) </b></h3>
    </div>

    <div  id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a  href="<?= '../estadisticas/Estadistica_bloques'.$Periodos.'.csv' ?>" ><button type="button" class="btn btn-dark" >
          <?= 'Exportar CSV - Estadistica por bloques'.$NombreTrimestre[$Periodos] ?>
          </button></a>
    </li> </ul> </div>



    <table  class="table table-hover" id="tablaregistros">
   
   
<?php

//Definir tabla2
$Tabla4[0]=['Nivel','Nº Partes','Nº Partes Graves','Puntos Disciplina (C=1;G=2)',
'Puntos de Disciplina por alumno matriculado','Nº Alumnos','Nº Alumnos con Parte',
'Nº Alumnos Conflictivos (>5pts)','Nº Alumnos con Parte cada 100 alumnos','Nº Alumnos Sancionados',
'Nº Alumnos Expulsados','Nº Alumnos en AC','Nº Sanciones','Nº días de expulsión',
'Nº días de AC','Puntos de Sanción','Puntos de Sanción por cada alumno matriculado',
'Indice Sancionador (días de exp por cada parte grave)'];

    //lista de bloques
    $Bloques=['1º de E.S.O.','2º de E.S.O.','3º de E.S.O.','4º de E.S.O.','E.S.O.','1º C.F.G.B.','2º C.F.G.B.','C.F.G.B.',
    'Bachillerato','Bach.Pers.Adul.','G.M.','G.S.'];
    
    $a=1;
    foreach($Bloques as $elemente)
    {            
    unset($NPartes, $NPartesGraves, $NAlumnos, $NAlumnosParte, $NAlumnosConflictivos, $NAlumnosSancionados,
    $NAlumnosExp, $NAlumnosAC, $NSanciones, $NDiasExp, $NDiasAC);
      $elemento='%'.$elemente.'%';
    //Número de partes por bloque
    extract(consultaBDunica("SELECT count(IdParte) as NPartes from tpartes where 
    FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' and
    AlumnoIdPa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    // Número de partes graves por bloque
    extract(consultaBDunica("SELECT count(IdParte) as NPartesGraves from tpartes where 
    substring(TipoPa,1,1)='G' and FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' and
    AlumnoIdPa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    // Número de alumnos matriculados por bloque
    extract(consultaBDunica("SELECT count(*) as NAlumnos from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso'"));
    //Número de alumnos con parte por bloque
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosParte from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso' and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo')
    "));
    //Numero de alumnos conflictivos por bloque
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosConflictivos from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso' and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes where FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo') and
    AlumnoIdAlUn in (SELECT AlumnoIdPa from tpartes group by AlumnoIdPa 
    having 2*count(case substring(TipoPa,1,1) when 'G' then '1' end)+count(case substring(TipoPa,1,1) when 'C' then '1' end)>5)
    "));

    //Número de alumnos sancionados por bloque
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosSancionados from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de alumnos expulsados por bloque
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosExp from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where substring(TipoSa,1,1)='E') and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de alumnos en AC por bloque
    extract(consultaBDunica("SELECT count(AlumnoIdAlUn) as NAlumnosAC from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn 
    where Nivel like '$elemento' and CursoUn='$curso' and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where substring(TipoSa,1,1)='A') and
    AlumnoIdAlUn in (SELECT AlumnoIdSa from tsanciones where FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo')
    "));
    //Número de sanciones por bloque
    extract(consultaBDunica("SELECT count(IdSancion) as NSanciones from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    //Número de días de sanción por bloque
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasSancion from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    if (!isset($NDiasSancion))
    {$NDiasSancion=0;}
    //Número de días de expulsión por bloque
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasExp from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    substring(TipoSa,1,1)='E' and
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    if (!isset($NDiasExp))
    {$NDiasExp=0;}
    //Número de días de AC por bloque
    extract(consultaBDunica("SELECT sum(DiasSancion) as NDiasAC from tsanciones where 
    FechaSa>='$Fechainicioperiodo' and FechaSa<='$Fechafinperiodo' and 
    substring(TipoSa,1,1)='A' and
    AlumnoIdSa in (SELECT AlumnoIdAlUn from talumnounidad inner join tunidades on IdUnidad=UnidadIdAlUn where Nivel like '$elemento' and CursoUn='$curso')
    "));
    if (!isset($NDiasAC))
    {$NDiasAC=0;}

$indice1 = "-";
$indice2 = "-";
$indice3 = "-";
$indice4 = "-";
if ($NAlumnos!=0) {
    $indice1 = round(($NPartesGraves*2+($NPartes-$NPartesGraves))/$NAlumnos,1);
    $indice2 = round(100*$NAlumnosParte/$NAlumnos,1);
    $indice3 = round(($NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC))/$NAlumnos,1);    
}
if ($NPartesGraves*2+($NPartes-$NPartesGraves)!=0) {
    $indice4 = round(($NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC))/($NPartesGraves*2+($NPartes-$NPartesGraves)),1);
}
$Tabla4[$a]=[$elemente, $NPartes ,$NPartesGraves ,$NPartesGraves*2+($NPartes-$NPartesGraves) ,
str_replace(".",",",$indice1) ,$NAlumnos ,
$NAlumnosParte ,$NAlumnosConflictivos ,
str_replace(".",",",$indice2) ,
$NAlumnosSancionados ,$NAlumnosExp ,$NAlumnosAC ,$NSanciones ,$NDiasExp ,$NDiasAC ,
$NDiasExp*2+$NDiasAC+($NDiasSancion-$NDiasExp-$NDiasAC) ,
str_replace(".",",",$indice3) ,
str_replace(".",",",$indice4)
];

$a=$a+1;
}



//Pasar tabla a csv
$namefile4="./estadisticas/Estadistica_bloques".$Periodos.".csv";
$fh4 = fopen($namefile4, 'w') or die("Se produjo un error al crear el archivo");
fputcsv($fh4,$Tabla4[0]);  
foreach (range(1,count($Tabla4)-1) as $nn)
{
  fputcsv($fh4,$Tabla4[$nn]);
}
fclose($fh4);

//Escribir tabla en pantalla
echo(imprimetablaarray2($Tabla4));
    ?>







    <br>
<br>
<br>

<div class="container">
    <h3> <b>MOTIVOS DE PARTE (<?= $NombreTrimestre[$Periodos] ?>) </b></h3>
    </div>
    <div  id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a  href="<?= '../estadisticas/Estadistica_motivos_parte_'.$Periodos.'.csv' ?>" ><button type="button" class="btn btn-dark" >
          <?= 'Exportar CSV - Estadistica Motivos Parte '.$NombreTrimestre[$Periodos] ?>
          </button></a>
    </li> </ul> </div>
   
<?php
   //Definir tabla3
$Tabla3[0]=['Tipo Parte','Nº Partes'];

    //lista al alumnado 
    $consulta3 = "SELECT TipoPa as IdTipoParte from tpartes where
    FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' group by TipoPa order by count(IdParte) desc";
    $resSelect3 = $conn->prepare($consulta3);
    $resSelect3->execute();
    
    $a=1;
    while($row3 = $resSelect3->fetch())
    {
    extract($row3);
   
    unset($NPartestipo);

    //Número de partes por tipo
    extract(consultaBDunica("SELECT count(IdParte) as NPartestipo from tpartes where 
    FechaPa>='$Fechainicioperiodo' and FechaPa<='$Fechafinperiodo' and TipoPa='$IdTipoParte'"));
    if ($NPartestipo!=0)
      {
      $Tabla3[$a]=[$IdTipoParte,$NPartestipo];
      $a=$a+1;  
      } 
    }
    

//Pasar tabla a csv
$namefile3='./estadisticas/Estadistica_motivos_parte_'.$Periodos.'.csv';
$fh3 = fopen($namefile3, 'w') or die("Se produjo un error al crear el archivo");
fputcsv($fh3,$Tabla3[0]);  
foreach (range(1,count($Tabla3)-1) as $nn)
{
  fputcsv($fh3,$Tabla3[$nn]);
}
fclose($fh3);

//Escribir tabla en pantalla
echo(imprimetablaarray2($Tabla3));


    ?>   
    
    <br><br><br>
  
  </form>
 
  </center>
  <br>  <br> 
  <br>  <br>
    </body>
    </html>







    
    