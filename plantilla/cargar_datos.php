<?php
ini_set('memory_limit','1024M');
include("../clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../clases/PHPExcel.php';

/** PHPExcel_IOFactory */
require_once '../clases/PHPExcel/IOFactory.php';
require_once '../clases/PHPExcel/Reader/Excel5.php';

$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load("Planilla BD Sistema Mantenimiento.xls");

/***********************/
/* LECTURA DE USUARIOS */
/***********************/

$objPHPExcel->setActiveSheetIndex(0);//usuarios
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $usuarios["id_user"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $usuarios["user_name"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $usuarios["user_lastname"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $usuarios["password"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $usuarios["grade"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($usuarios);


/***********************/
/* LECTURA DE MAQUINAS */
/***********************/

$objPHPExcel->setActiveSheetIndex(1);//procesos
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $procesos["id_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $procesos["nombre_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $procesos["descripcion_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $procesos["horometro"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($procesos);


/***********************/
/* LECTURA DE SISTEMAS */
/***********************/

$objPHPExcel->setActiveSheetIndex(2);//Sistemas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $sistemas["id_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $sistemas["nombre_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $sistemas["descripcion_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sistemas);

/***************************/
/* LECTURA DE SUB-SISTEMAS */
/***************************/

$objPHPExcel->setActiveSheetIndex(3);//Sub-Sistemas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $sub_sistemas["id_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $sub_sistemas["nombre_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $sub_sistemas["descripcion_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);


/*********************/
/* LECTURA DE PIEZAS */
/*********************/

$objPHPExcel->setActiveSheetIndex(4);//Piezas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $piezas["id_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $piezas["nombre_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $piezas["descripcion_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);

/**************************/
/* LECTURA DE ACTIVIDADES */
/**************************/

$objPHPExcel->setActiveSheetIndex(5);//ACTIVIDADES
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $actividades["id_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $actividades["nombre_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $actividades["descripcion_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);


/*************************/
/* LECTURA DE ESTANDARES */
/*************************/

$objPHPExcel->setActiveSheetIndex(6);//ESTANDARES
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $estandares["id_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $estandares["id_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $estandares["id_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $estandares["id_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $estandares["id_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$filas_excel)->getValue();
   $estandares["std_mantenimiento"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$filas_excel)->getValue();
   $estandares["frecuencia"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$filas_excel)->getValue();
   $estandares["unid_frec"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);

/**********************/
/* LECTURA DE INSUMOS */
/**********************/

$objPHPExcel->setActiveSheetIndex(7);//INSUMOS
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $insumos["id_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $insumos["nombre_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $insumos["descripcion_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $insumos["unidad_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);


//-------------------------USUARIOS
$registros= count($usuarios["id_user"]);
$i=0;
echo "Registrando Usuarios..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_usuario($usuarios["id_user"][$i]))
    {
    $datos->actualizar_usuarios_plantilla($usuarios["id_user"][$i],$usuarios["user_name"][$i],$usuarios["user_lastname"][$i],$usuarios["password"][$i],$usuarios["grade"][$i]);
    }
    else
    {
       $datos->registro_usuarios_plantilla($usuarios["id_user"][$i],$usuarios["user_name"][$i],$usuarios["user_lastname"][$i],$usuarios["password"][$i],$usuarios["grade"][$i]);
    }
}
echo "Registro de Usuarios finalizado...<br>";

//---------------------------MAQUINAS

$registros= count($procesos["id_maquina"]);
$i=0;
echo "Registrando Procesos..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_proceso($procesos["id_maquina"][$i]))
    {
        $datos->actualizar_proceso_plantilla($procesos["id_maquina"][$i], $procesos["nombre_maquina"][$i], $procesos["descripcion_maquina"][$i], $procesos["horometro"][$i]);
    }
    else
    {
        $datos->registro_proceso_plantilla($procesos["id_maquina"][$i], $procesos["nombre_maquina"][$i], $procesos["descripcion_maquina"][$i], $procesos["horometro"][$i]);
    }
}
echo "Registrando Procesos finalizado..<br>";



//---------------------------SISTEMAS

$registros= count($sistemas["id_sistema"]);
$i=0;
echo "Registrando sistemas..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_sistema($sistemas["id_sistema"][$i]))
    {
        $datos->actualizar_sistema_plantilla($sistemas["id_sistema"][$i], $sistemas["nombre_sistema"][$i], $sistemas["descripcion_sistema"][$i]);
    }
    else
    {
        $datos->registro_sistema_plantilla($sistemas["id_sistema"][$i], $sistemas["nombre_sistema"][$i], $sistemas["descripcion_sistema"][$i]);
    }
}
echo "Registrando Sistemas finalizado..<br>";



//---------------------------SUB-SISTEMAS

$registros= count($sub_sistemas["id_sub_sistema"]);
$i=0;
echo "Registrando Sub-Sistemas...<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_sub_sistema($sub_sistemas["id_sub_sistema"][$i]))
    {
        $datos->actualizar_sub_sistema_plantilla($sub_sistemas["id_sub_sistema"][$i],$sub_sistemas["nombre_sub_sistema"][$i],$sub_sistemas["descripcion_sub_sistema"][$i]);
    }
    else
    {
        $datos->registro_sub_sistema_plantilla($sub_sistemas["id_sub_sistema"][$i],$sub_sistemas["nombre_sub_sistema"][$i],$sub_sistemas["descripcion_sub_sistema"][$i]);
    }
}
echo "Registrando Sub-Sistemas finalizado..<br>";


//---------------------------PIEZAS

$registros= count($piezas["id_pieza"]);
$i=0;
echo "Registrando Piezas...<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_piezas($piezas["id_pieza"][$i]))
    {
        $datos->actualizar_piezas_plantilla($piezas["id_pieza"][$i], $piezas["nombre_pieza"][$i],$piezas["descripcion_pieza"][$i]);
    }
    else
    {
        $datos->registro_piezas_plantilla($piezas["id_pieza"][$i], $piezas["nombre_pieza"][$i],$piezas["descripcion_pieza"][$i]);
    }
}
echo "Registrando Piezas finalizado...<br>";


//---------------------------ACTIVIDADES

$registros= count($actividades["id_actividad"]);
$i=0;
echo "Registrando Actividades..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_actividades($actividades["id_actividad"][$i]))
    {
       $datos->actualizar_actividades_plantilla($actividades["id_actividad"][$i],$actividades["nombre_actividad"][$i],$actividades["descripcion_actividad"][$i]);
    }
    else
    {
        $datos->registro_actividades_plantilla($actividades["id_actividad"][$i],$actividades["nombre_actividad"][$i],$actividades["descripcion_actividad"][$i]);
    }
}
echo "Registrando Actividades finalizado..<br>";


//---------------------------ESTANDARES

$registros= count($estandares["id_maquina"]);
$i=0;
echo "Registrando estandares..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_estandar($estandares["id_maquina"][$i],$estandares["id_sistema"][$i],$estandares["id_sub_sistema"][$i],$estandares["id_pieza"][$i],$estandares["id_actividad"][$i]))
    {
        $datos->actualizar_estandar_plantilla($estandares["id_maquina"][$i],$estandares["id_sistema"][$i],$estandares["id_sub_sistema"][$i],$estandares["id_pieza"][$i],$estandares["id_actividad"][$i],$estandares["std_mantenimiento"][$i],$estandares["frecuencia"][$i],$estandares["unid_frec"][$i]);
    }
    else
    {
        $datos->registro_estandar_plantilla($estandares["id_maquina"][$i],$estandares["id_sistema"][$i],$estandares["id_sub_sistema"][$i],$estandares["id_pieza"][$i],$estandares["id_actividad"][$i],$estandares["std_mantenimiento"][$i],$estandares["frecuencia"][$i],$estandares["unid_frec"][$i]);
    }
}
echo "Registrando estandares finalizado..<br>";

//---------------------------INSUMOS

$registros= count($insumos["id_insumo"]);
$i=0;
echo "Registrando Insumos..<br>";
for($i=0;$i<$registros;$i++)
{
    if($datos->comprobar_insumo($insumos["id_insumo"][$i]))
    {
       $datos->actualizar_insumos_plantilla($insumos["id_insumo"][$i],$insumos["nombre_insumo"][$i],$insumos["descripcion_insumo"][$i], $insumos["unidad_insumo"][$i]);
    }
    else
    {
        $datos->registro_insumos_plantilla($insumos["id_insumo"][$i],$insumos["nombre_insumo"][$i],$insumos["descripcion_insumo"][$i], $insumos["unidad_insumo"][$i]);
    }
}
echo "Registrando Insumos finalizado..<br>";

       $pagina="../principal.php";
       echo "la Informacion fue cargada a la base de datos con exito.";
       echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";

?>


