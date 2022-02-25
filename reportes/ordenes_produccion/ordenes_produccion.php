<?php session_start(); ?>
<?php
$fecha = time();
include("../../clases/reportes.php");
$repor= new reportes();
//$datos = new configuracion_aplicacion();
$procesos = $repor->listar_procesos();
$etiquetas=$repor->listar_etiquetas();
//echo $etiquetas[0]["etiqueta"];
$ordenes_produccion=$repor->listar_ops();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="../../estilos/myc_gral.css"/>

  <link type="text/css" rel="stylesheet" href="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
  <title>Monitoreo y Control</title>
</head>

<body>
<div id="contenedor">
  <?php require_once('..\..\includes\cabecera.php'); ?>
  <div id="contenido">
  	<h1>REPORTES ORDENES DE PRODUCCION</h1>
    <form class="forma"  action="formsubmit.php" target="_blank" method="POST" name="parametros">

            <table class="tforma">
                 <tr>
                    <td class="etq">SELECCION</td>
                    <td class="etq">
                    DESDE
                    </td>
                    <td class="etq">
                    HASTA
                    </td>
                </tr>
                <tr>
                    <td class="etq"><input type="radio" name="opcion" value="turno" checked/>Por OP<br/></td>
                    <td>
                        <select class="lista"  name="tini" id="tini">
                            <?php
                            while (!$ordenes_produccion->EOF) {
      echo "<option class=\"oplista\" value=\" ".$ordenes_produccion->fields['id_orden_produccion']." \">".$ordenes_produccion->fields['id_orden_produccion']."-".$ordenes_produccion->fields['nombre_producto']."</option>";
                           $ordenes_produccion->MoveNext();
                            }
                            ?>
                        </select>
                    </td>
                                                    <td>
                        <select class="lista" name="tfin" id="tfin">
                            <?php
                            $ordenes_produccion->MoveFirst();
                            while (!$ordenes_produccion->EOF) {
      echo "<option class=\"oplista\" value=\" ".$ordenes_produccion->fields['id_orden_produccion']." \">".$ordenes_produccion->fields['id_orden_produccion']."-".$ordenes_produccion->fields['nombre_producto']."</option>";
                           $ordenes_produccion->MoveNext();
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="etq"><input type="radio" name="opcion" value="fecha" checked/>Por Fecha<br/></td>
                    <td>

                        <input class="ctxt" type="text" name="fechai" id="fechai" onclick="displayCalendar(document.forms[0].fechai,'yyyy/mm/dd',this)" value="<?php  echo date("Y/m/d",$fecha);  ?>"/>

                    </td>
                    <td>
                        <input class="ctxt" type="text" name="fechaf" id="fechaf" onclick="displayCalendar(document.forms[0].fechaf,'yyyy/mm/dd',this)" value="<?php  echo date("Y/m/d",$fecha);  ?>" />

                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" value="ver Reporte"/>
                    </td>
                </tr>
            </table>

        </form>
  </div>
  <?php require_once('..\..\includes\piep.php'); ?>
</div>
</body>
</html>
