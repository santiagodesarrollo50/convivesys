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
    //Obtenemos datos guardados en la bbdd
    $sql = "SELECT * FROM tconfiguracion ORDER BY orden";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $arrayDatos = $stmt->fetchAll();
    $arrayDatosB=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='variable';
    });
    $arrayFechasTrim=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='fechastrimestres';
    });
    $arrayFechasFest=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='festivo';
    });
    $arrayTipoParte=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='tipoparte';
    });
    $arrayTipoSancion=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='tiposancion';
    });
    $arrayTramoHorario=array_filter($arrayDatos, function($row) {
        return $row['tipo']=='tramohorario';
    });
    $arrayCentroSS = array_filter($arrayDatos, function ($row) {
        return $row['tipo'] == 'centrosSS';
    });
    $arrayDatos=[];




    if(isset($_POST['guardarDatos'])) { 
        foreach ($arrayDatosB as $row) {
            $valorOrden = $row['orden'];
            $edit = $conn->query("UPDATE tconfiguracion set valor1='$_POST[$valorOrden]' WHERE orden='$valorOrden'");
        }
        foreach ($arrayFechasTrim as $row) {
            $valorOrden = $row['orden'];
            $ffecha1=$_POST['fecha1'.$valorOrden];
            $ffecha2=$_POST['fecha2'.$valorOrden];
            $edit = $conn->query("UPDATE tconfiguracion set fecha1='$ffecha1', fecha2='$ffecha2' WHERE orden='$valorOrden'");
        }
        foreach ($arrayFechasFest as $row) {
            $valorOrden = $row['orden'];
            if (isset($_POST['eliminar'.$valorOrden])) {
                $edit = $conn->query("DELETE FROM tconfiguracion WHERE orden='$valorOrden'");
            } else {
                $fetiqueta=$_POST['etiqueta'.$valorOrden];
                $ffecha1=$_POST['fecha1'.$valorOrden];
                $ffecha2=$_POST['fecha2'.$valorOrden];    
                $edit = $conn->query("UPDATE tconfiguracion set etiqueta = '$fetiqueta', fecha1='$ffecha1', fecha2='$ffecha2' 
                    WHERE orden='$valorOrden'");
            }            
        }
        foreach ($arrayTipoParte as $row) {
            $valorOrden = $row['orden'];            
            if (isset($_POST['eliminar'.$valorOrden])) {
                $edit = $conn->query("DELETE FROM tconfiguracion WHERE orden='$valorOrden'");
            } else {
                $valorId =$_POST['id'.$valorOrden];
                $fvalor1=$_POST['valor1'.$valorOrden];
                $fvalor2=$_POST['valor2'.$valorOrden];    
                $edit = $conn->query("UPDATE tconfiguracion set id='$valorId', valor1='$fvalor1', valor2='$fvalor2' 
                    WHERE orden='$valorOrden'");
            }            
        }
        //Guardamos parte por defecto
        $ordenTipoParteDefecto = $_POST["parteDefecto"];
        $edit = $conn->query("UPDATE tconfiguracion set valor2='si' WHERE orden='$ordenTipoParteDefecto'");

        foreach ($arrayTipoSancion as $row) {
            $valorOrden = $row['orden'];            
            if (isset($_POST['eliminar'.$valorOrden])) {
                $edit = $conn->query("DELETE FROM tconfiguracion WHERE orden='$valorOrden'");
            } else {
                $valorId =$_POST['id'.$valorOrden];
                $fvalor1=$_POST['valor1'.$valorOrden]; 
                $edit = $conn->query("UPDATE tconfiguracion SET id='$valorId', valor1='$fvalor1' WHERE orden='$valorOrden'");
            }            
        }
        foreach ($arrayTramoHorario as $row) {            
            $valorOrden = $row['orden'];
            if (isset($_POST['eliminar'.$valorOrden])) {
                $edit = $conn->query("DELETE FROM tconfiguracion WHERE orden='$valorOrden'");
            } else {
                $valorId =$_POST['id'.$valorOrden];
                $fvalor1=$_POST['valor1'.$valorOrden]; 
                $edit = $conn->query("UPDATE tconfiguracion SET id = '$valorId', valor1='$fvalor1' WHERE orden='$valorOrden'");
            }            
        }
        foreach ($arrayCentroSS as $row) {
            $valorOrden = $row['orden'];
            if (isset($_POST['eliminar' . $valorOrden])) {
                $edit = $conn->query("DELETE FROM tconfiguracion WHERE orden='$valorOrden'");
            } else {
                $valorEtiqueta = $_POST['etiqueta' . $valorOrden];
                $fvalor1 = $_POST['valor1' . $valorOrden];
                $fvalor2 = $_POST['valor2' . $valorOrden];
                $fvalor3 = $_POST['valor3' . $valorOrden];            
                $edit = $conn->query("UPDATE tconfiguracion SET etiqueta = '$valorEtiqueta', valor1='$fvalor1' ,
                    valor2='$fvalor2' , valor3='$fvalor3' WHERE orden='$valorOrden'");
            }
        }
        if ($_POST['fecha1Nuevo']!="" && $_POST['fecha2Nuevo']!="" && $_POST['etiquetaNuevo']!="") {
            $etiquetaNuevo = $_POST['etiquetaNuevo'];
            $fecha1Nuevo = $_POST['fecha1Nuevo'];
            $fecha2Nuevo = $_POST['fecha2Nuevo'];
            $edit = $conn->query("INSERT INTO tconfiguracion (id, etiqueta, fecha1, fecha2, tipo) 
                VALUES ('$etiquetaNuevo', '$etiquetaNuevo',  '$fecha1Nuevo', '$fecha2Nuevo', 'festivo')");
        }
        if ($_POST['tipoParteNuevoValor1']!="" && $_POST['tipoParteNuevoId']!="") {
            $idNuevo = $_POST['tipoParteNuevoId'];
            $valor1Nuevo = $_POST['tipoParteNuevoValor1'];
            $valor2Nuevo = $_POST['tipoParteNuevoValor2'];
            $edit = $conn->query("INSERT INTO tconfiguracion (id, valor1, valor2, tipo) 
                VALUES ('$idNuevo',  '$valor1Nuevo', '$valor2Nuevo', 'tipoparte')");
        }
        if ($_POST['tipoSancionNuevoValor1']!="" && $_POST['tipoSancionNuevoId']!="") {
            $idNuevo = $_POST['tipoSancionNuevoId'];
            $valor1Nuevo = $_POST['tipoSancionNuevoValor1'];
            $edit = $conn->query("INSERT INTO tconfiguracion (id, valor1, tipo) 
                VALUES ('$idNuevo', '$valor1Nuevo', 'tiposancion')");
        }
        if ($_POST['tramoHorarioNuevoValor1']!="" && $_POST['tramoHorarioNuevoId']!="") {
            $idNuevo = $_POST['tramoHorarioNuevoId'];
            $valor1Nuevo = $_POST['tramoHorarioNuevoValor1'];
            $edit = $conn->query("INSERT INTO tconfiguracion (id, valor1, tipo) 
                VALUES ('$idNuevo', '$valor1Nuevo',  'tramohorario')");
        }
        if ($_POST['centroSSNuevoEtiqueta'] != "" && $_POST['centroSSNuevoValor1'] != "" &&
                $_POST['centroSSNuevoValor2'] != "" && $_POST['centroSSNuevoValor3'] != "") {
            $etiquetaNuevo = $_POST['centroSSNuevoEtiqueta'];
            $valor1Nuevo = $_POST['centroSSNuevoValor1'];
            $valor2Nuevo = $_POST['centroSSNuevoValor2'];
            $valor3Nuevo = $_POST['centroSSNuevoValor3'];
            $edit = $conn->query("INSERT INTO tconfiguracion (etiqueta, valor1, valor2,valor3, tipo) 
                    VALUES ('$etiquetaNuevo', '$valor1Nuevo', '$valor2Nuevo', '$valor3Nuevo',  'centrosSS')");
        }
        header('location:25modificardatosbasicos.php');   
    }

    
?>

    <div class="container">
        <div class="row mt-4">            
            <form  method="post" class="p-3">

                <div class="col-md-8 mx-auto bg-light rounded p-4">
                    <div class="input-group-append">
                        <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                    </div>
                    <h5 class="text-center font-weight-bold">CONFIGURAR DATOS BÁSICOS DEL CENTRO</h5>
                    <hr class="my-1">
                    <?php
                        foreach ($arrayDatosB as $row) {
                    ?>
                        <div>
                            <h5 class="text-secundary"><?=$row['etiqueta']?>*</h5>
                        </div>
                        <div class="input-group">
                            <input type="text" name="<?=$row['orden']?>" id="<?=$row['orden']?>" 
                                value="<?=$row['valor1']?>" class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off" required>
                        </div>         
                    <?php        
                        }
                    ?> 
                </div>
                <br><br>

                <div class="col-md-12 mx-auto bg-light rounded p-4">
                    <div class="input-group-append">
                        <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                    </div>
                    <h5 class="text-center font-weight-bold">CONFIGURAR FECHAS DE LOS TRIMESTRES</h5>
                    <hr class="my-1">
                    <?php
                        foreach ($arrayFechasTrim as $row) {
                    ?>
                        <div>
                            <h5 class="text-secundary"><?=$row['etiqueta']?>*</h5>
                        </div>
                        <div class="input-group">
                            Fecha inicio:
                            <input type="date" name="fecha1<?=$row['orden']?>" id="fecha1<?=$row['orden']?>" 
                                value="<?=$row['fecha1']?>" class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off" required>
                            Fecha Fin: 
                            <input type="date" name="fecha2<?=$row['orden']?>" id="fecha2<?=$row['orden']?>" 
                                value="<?=$row['fecha2']?>" class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off" required>
                        </div>      
                    <?php        
                        }
                    ?> 
                </div>
                <br><br>

                <div class="col-md-12 mx-auto bg-light rounded p-4">
                    <div class="input-group-append">
                        <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                    </div>
                    <h5 class="text-center font-weight-bold">CONFIGURAR FECHAS DE LOS FESTIVOS</h5>
                    <hr class="my-1">
                    <?php
                        foreach ($arrayFechasFest as $row) {
                    ?>
                            <div class="input-group">
                                Nombre del festivo: 
                                <input type="text" name="etiqueta<?=$row['orden']?>" id="etiqueta<?=$row['orden']?>" 
                                    value="<?=$row['etiqueta']?>" class="form-control form-control-lg rounded-0 border-info"
                                    autocomplete="off" required>
                            </div>
                            <div class="input-group">
                                Fecha Inicio: 
                                <input type="date" name="fecha1<?=$row['orden']?>" id="fecha1<?=$row['orden']?>" 
                                    value="<?=$row['fecha1']?>" class="form-control form-control-lg rounded-0 border-info"
                                    autocomplete="off" required>
                                Fecha Fin:
                                <input type="date" name="fecha2<?=$row['orden']?>" id="fecha2<?=$row['orden']?>" 
                                    value="<?=$row['fecha2']?>" class="form-control form-control-lg rounded-0 border-info"
                                    autocomplete="off" required>
                                Eliminar:
                                <input type="checkbox" name="eliminar<?=$row['orden']?>" id="eliminar<?=$row['orden']?>">
                            </div>
                            <br><hr class="my-1"><br>
                    <?php        
                        }
                    ?> 
                        <br>
                        <div>
                            <h5>Añadir nuevo festivo</h5>
                        </div> 
                        <div>
                            <h6 class="text-secundary">Nombre del nuevo festivo</h6>
                        </div>
                        <div class="input-group">
                            <input type="text" name="etiquetaNuevo" id="etiquetaNuevo" 
                                class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off">
                        </div>
                        <div class="input-group">
                            Fecha inicio del nuevo festivo:
                            <input type="date" name="fecha1Nuevo" id="fecha1Nuevo" 
                                class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off">
                            Fecha Fin del nuevo festivo:
                            <input type="date" name="fecha2Nuevo" id="fecha2Nuevo" 
                                class="form-control form-control-lg rounded-0 border-info"
                                autocomplete="off">
                        </div>
                    </div>
                        <br><br>                       



                        <div class="col-md-12 mx-auto bg-light rounded p-4">
                            <div class="input-group-append">
                                <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                            </div>
                            <h5 class="text-center font-weight-bold">CONFIGURAR TIPOS DE PARTE</h5>
                            En el nombre corto, escribir una C o G al inicio para indicar contrario o grave. 
                            <hr class="my-1">
                            <?php
                                foreach ($arrayTipoParte as $row) {
                            ?>
                                    <div class="input-group">
                                        Nombre corto: 
                                        <input type="text" name="id<?=$row['orden']?>" id="id<?=$row['orden']?>" 
                                            value="<?=$row['id']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                        Nombre largo:
                                        <input type="text" name="valor1<?=$row['orden']?>" id="valor1<?=$row['orden']?>" 
                                            value="<?=$row['valor1']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                        Parte por defecto:
                                        <input type="radio" name="parteDefecto" id="valor2<?=$row['orden']?>" 
                                            <?= ($row['valor2'] == 'si') ? 'checked' : ''?> value="<?=$row['orden']?>"> &nbsp;&nbsp;
                                        Eliminar:
                                        <input type="checkbox" name="eliminar<?=$row['orden']?>" id="eliminar<?=$row['orden']?>">
                                    </div>
                                    <br><hr class="my-1"><br>
                            <?php        
                                }
                            ?> 
                                <br>
                                <div>
                                    <h5>Añadir nuevo tipo de parte</h5>
                                </div> 
                                <div class="input-group">
                                    Nombre corto:
                                    <input type="text" name="tipoParteNuevoId" id="tipoParteNuevoId" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                    Nombre largo:
                                    <input type="text" name="tipoParteNuevoValor1" id="tipoParteNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                        </div>
                        <br><br>



                        <div class="col-md-12 mx-auto bg-light rounded p-4">
                            <div class="input-group-append">
                                <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                            </div>
                            <h5 class="text-center font-weight-bold">CONFIGURAR TIPOS DE SANCIÓN</h5>
                            <hr class="my-1">
                            <?php
                                foreach ($arrayTipoSancion as $row) {
                            ?>
                                    <div class="input-group">
                                        Nombre corto: 
                                        <input type="text" name="id<?=$row['orden']?>" id="id<?=$row['orden']?>" 
                                            value="<?=$row['id']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                        Nombre largo:
                                        <input type="text" name="valor1<?=$row['orden']?>" id="valor1<?=$row['orden']?>" 
                                            value="<?=$row['valor1']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                        Eliminar:
                                        <input type="checkbox" name="eliminar<?=$row['orden']?>" id="eliminar<?=$row['orden']?>">
                                    </div>
                                    <br><hr class="my-1"><br>
                            <?php        
                                }
                            ?> 
                                <br>
                                <div>
                                    <h5>Añadir nuevo tipo de sanción</h5>
                                </div> 
                                <div class="input-group">
                                    Nombre corto:
                                    <input type="text" name="tipoSancionNuevoId" id="tipoSancionNuevoId" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                    Nombre largo:
                                    <input type="text" name="tipoSancionNuevoValor1" id="tipoSancionNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                        </div>
                        <br><br>



                        <div class="col-md-12 mx-auto bg-light rounded p-4">
                            <div class="input-group-append">
                                <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                            </div>
                            <h5 class="text-center font-weight-bold">CONFIGURAR TRAMOS HORARIOS</h5>
                            <hr class="my-1">
                            <?php
                                foreach ($arrayTramoHorario as $row) {
                            ?>
                                    <div class="input-group">
                                        Nombre corto: 
                                        <input type="text" name="id<?=$row['orden']?>" id="id<?=$row['orden']?>" 
                                            value="<?=$row['id']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                        Detalle del horario:
                                        <input type="text" name="valor1<?=$row['orden']?>" id="valor1<?=$row['orden']?>" 
                                            value="<?=$row['valor1']?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                        Eliminar:
                                        <input type="checkbox" name="eliminar<?=$row['orden']?>" id="eliminar<?=$row['orden']?>">
                                    </div>
                                    <br><hr class="my-1"><br>
                            <?php        
                                }
                            ?> 
                                <br>
                                <div>
                                    <h5>Añadir nuevo tramo horario</h5>
                                </div> 
                                <div class="input-group">
                                    Nombre corto:
                                    <input type="text" name="tramoHorarioNuevoId" id="tramoHorarioNuevoId" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                    Nombre largo:
                                    <input type="text" name="tramoHorarioNuevoValor1" id="tramoHorarioNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                    
                        </div><br><br>


                        <div class="col-md-12 mx-auto bg-light rounded p-4">
                            <div class="input-group-append">
                                <input type="submit" name="guardarDatos" value="Guardar" class="btn btn-info btn-lg rounded-1">
                            </div>
                            <h5 class="text-center font-weight-bold">CONFIGURAR CENTROS DE SERVICIOS SOCIALES PARA ANEXO II ABSENTISMO</h5>
                            <hr class="my-1">
                            <?php
                            foreach ($arrayCentroSS as $row) {
                                ?>
                                    <div class="input-group">
                                        Nombre del centro:
                                        <input type="text" name="etiqueta<?= $row['orden'] ?>" id="etiqueta<?= $row['orden'] ?>" 
                                            value="<?= $row['etiqueta'] ?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                        Dirección: 
                                        <input type="text" name="valor1<?= $row['orden'] ?>" id="valor1<?= $row['orden'] ?>" 
                                            value="<?= $row['valor1'] ?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="input-group">
                                         CP y Localidad: 
                                        <input type="text" name="valor2<?= $row['orden'] ?>" id="valor2<?= $row['orden'] ?>" 
                                            value="<?= $row['valor2'] ?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                        Zona: 
                                        <input type="text" name="valor3<?= $row['orden'] ?>" id="valor3<?= $row['orden'] ?>" 
                                            value="<?= $row['valor3'] ?>" class="form-control form-control-lg rounded-0 border-info"
                                            autocomplete="off" required>
                                        Eliminar:
                                        <input type="checkbox" name="eliminar<?= $row['orden'] ?>" id="eliminar<?= $row['orden'] ?>">
                                    </div>
                                    <br><hr class="my-1"><br>
                            <?php
                            }
                            ?> 
                                <br>
                                <div>
                                    <h5>Añadir nuevo centro de servicios sociales</h5>
                                </div> 
                                <div class="input-group">
                                    Nombre del centro:
                                    <input type="text" name="centroSSNuevoEtiqueta" id="tramoHorarioNuevoId" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                                <div class="input-group">
                                    Dirección:
                                    <input type="text" name="centroSSNuevoValor1" id="tramoHorarioNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                                <div class="input-group">
                                    CP y Localidad:
                                    <input type="text" name="centroSSNuevoValor2" id="tramoHorarioNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                    Zona:
                                    <input type="text" name="centroSSNuevoValor3" id="tramoHorarioNuevoValor1" 
                                        class="form-control form-control-lg rounded-0 border-info"
                                        autocomplete="off">
                                </div>
                    
                </div>




            </form>
        </div>
    </div>
    
   
   

        


 



</body>
</html>