<?php session_start();
include("clases/test_class.php");
$datos = new test_class();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="estilos/myc_gral.css"/>
	<script type="text/javascript" src="jquery/jquery.min.js"></script>
	<title>Monitoreo y Control</title>
</head>

<body>
<div id="contenedor">
	<?php require_once('includes/cabecera.php'); ?>
	<div id="pantalla">
		<table class="tmenu">
			<tr>
				<td class="menu">
					<table>
						<tr>
							<td>
								USUARIOS
							</td>
						</tr>
						<tr>
							<td>
								MAQUINAS
							</td>
						</tr>
						<tr>
							<td>
								SISTEMAS
							</td>
						</tr>
						<tr>
							<td>
								ACTIVIDADES
							</td>
						</tr>
						<tr>
							<td>
								INSUMOS
							</td>
						</tr>
						<tr>
							<td>
								ESTANDARES
							</td>
						</tr>
					</table>
				</td>
				<td class="pantalla">
					<?php 

$sql1 = "SELECT	max(t.id_turno) turno,
				t.id_proceso proceso,
				sum(t.tiempo_turno) tiempo_turno,
				sum(t.tiempo_maquina) tiempo_maquina,
				max(extract(month from t.fecha_inicio)) mes,
				extract(day from t.fecha_inicio) dia
		FROM turnos t
			INNER JOIN procesos p ON t.id_proceso = p.id_proceso
			INNER JOIN productos pro ON pro.id_producto = t.id_producto
			INNER JOIN producto_proceso pp ON pp.id_proceso = p.id_proceso AND pp.id_producto = pro.id_producto
		WHERE (t.fecha_inicio BETWEEN '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."')
		GROUP BY proceso, dia
		ORDER BY proceso, dia";

		$result1 = $datos->ejecutar_sql($sql1);

$lineas = array();
		while (!$result1->EOF)
		{
			//convierte el dato tiempo_turno, que originalmente esta en segundos, a horas
			$horas = round(($result1->fields['tiempo_turno']/3600),2);

			//al dato numerico de mes que viene de la BD se le debe restar 1 (-1) para que encaje correctamente con alguno de los meses de arriba
			$mes = ($result1->fields['mes'])-1;

			//preparar las listas de datos
			$lineas[$result1->fields['mes']][$result1->fields['dia']][$result1->fields['proceso']] = $result1->fields['tiempo_turno'];
			$result->MoveNext();
		}
					/*
					$fecha1 = "2016-06-01";
					$fecha2 = "2016-06-15";
					for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
						echo $i . "<br />";
					//aca puedes comparar $i a una fecha en la bd y guardar el resultado en un arreglo

					}
					*/
					//require_once('usuarios.php');
					/*$fecha = time();
					echo $fecha;
					echo '<br>';
					echo date("Y/m/d H:i:s",$fecha);
					echo '<br>';
					$fecha = strtotime('+85 days', $fecha);
					echo $fecha;
					echo '<br>';
					echo date("Y/m/d H:i:s",$fecha);
					echo '<br>';
					$fecha = strtotime('+7 hours', $fecha);
					echo $fecha;
					echo '<br>';
					echo date("Y/m/d H:i:s",$fecha)
					*/
					?>
				</td>
			</tr>
		</table>
	</div>
	<?php require_once('includes/piep.php'); ?>
</div>
</body>
</html>
