<?php

$jsonData = array();

require 'clases/configuracion_aplicacion.php';
$logicaTurno = new configuracion_aplicacion();
$datos = new configuracion_aplicacion();
$test = $_REQUEST["estado"];
$sistema = $_REQUEST["sistema"];




switch ($test) {
  case '1':
    $jsonData['resultado'] = $logicaTurno->listar_actividades_cerrar($sistema);

    break;

  case '2':
    // code...
    $jsonData['resultado'] = $logicaTurno->listar_sistemas_2();

    break;
    case '3':
    $orden = $_REQUEST["orden"];
    $maquina = $_REQUEST["maquina"];
    $sistema = $_REQUEST["sistema"];
    $subSistema = $_REQUEST["subSistema"];
    $pieza = $_REQUEST["pieza"];
    $actividad = $_REQUEST["actividad"];
    $usuario = $_REQUEST["usuario"];
    $fecha_programada=$_REQUEST["fecha_programada"];
    $jsonData['resultado'] = $logicaTurno->registro_mantenimiento_automatico23($orden,$maquina,$sistema,$subSistema,$pieza,$actividad,$usuario,$fecha_programada);
    break;

    case '5':
      $ordenEstado = $_REQUEST["orden"];
      $logicaTurno->traer_fecha_orden($ordenEstado);
      $jsonData['resultado'] = "Test";
      break;
//REGISTRO INSUMOS
    case '9':
    $codigoRegistro = $_REQUEST["infoRegistro"];
    $insumo = $_REQUEST["infoInsumo"];
    $referencia = $_REQUEST["infoReferencia"];
    $cantidad = $_REQUEST["infoCantidad"];

    $jsonData["resultado"] = $datos->registrar_insumo_mantenimiento2($codigoRegistro,$insumo,$cantidad,$referencia);
    break;

  default:
    // code...
    break;
}

echo json_encode($jsonData);

 ?>
