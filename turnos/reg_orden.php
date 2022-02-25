<?php
include("modelo/DatosTurno.php");
$datos = new DatosTurno();

if($_GET["operario"])
{
    $usuario= $_GET["operario"];
}
if($_GET["maquina"])
{
    $maquina= $_GET["maquina"];
}

	$recordSet = $datos->listarOrdenesUsuarioMaquina($usuario, $maquina);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
<!--	<meta http-equiv="refresh" content="3">-->
	<link rel="stylesheet" type="text/css"  href="css/styles_selector.css"/>
	<title>Registro Maquinas M&C </title>
</head>
<body>
	<div class="title">
		<div id="title" class="textTitle">ORDENES DE <?php echo $operario;?> EN LA MAQUINA <?php echo $maquina;?></div>
	</div>
	<div>
	</div>
	<div class="main">
		<table class="tinfo">
			<tr>
				<th>Maquina</th>
				<th>Sistema</th>
				<?php
					if($datos->estadoConfiguracion(4)) echo '<th>Sub-Sistema</th>';
					if($datos->estadoConfiguracion(5)) echo '<th>Pieza</th>';
					if($datos->estadoConfiguracion(6)) echo '<th>Actividad</th>';
				?>
				<th>Encargado</th>
				<th>Fecha</th>
				<th>Observaciones</th>
				<th>Seleccionar</th>
			</tr>
<?php
		$color=array('impar','par');
		$c_estilo=0;
		while (!$recordSet->EOF) {

			if($c_estilo%2!=0)
				echo '<tr class="'.$color[0].'">';
			else
				echo '<tr class="'.$color[1].'">';


			$datos_orden = $datos->detalleProceso($recordSet->fields["id_maquina"]);
			$datos_sistema = $datos->detalleSistema($recordSet->fields["id_sistema"]);
			$datos_sub_sistema = $datos->detalleSubSistema($recordSet->fields["id_sub_sistema"]);
			$datos_pieza = $datos->detallePieza($recordSet->fields["id_pieza"]);
			$datos_actividad = $datos->detalleActividad($recordSet->fields["id_actividad"]);
			if($recordSet->fields["id_usuario"]){
				$datos_usuario = $datos->detalleUsuario($recordSet->fields["id_usuario"]);
			}
			else{
				$datos_usuario="";
			}

			$estado = $recordSet->fields["estado"];
			if($estado == 0){
				$desc_estado = "Cancelada";
			}
			if($estado == 1){
				$desc_estado = "Activa";
			}
			if($estado == 2){
				$desc_estado = "Cerrada";
			}

			echo "<td>".$datos_orden->fields['nombre_maquina']."</td>";
			echo "<td>".$datos_sistema->fields['nombre_sistema']."</td>";
			if($datos->estadoConfiguracion(4)) echo "<td>".$datos_sub_sistema->fields['nombre_sub_sistema']."</td>";
			if($datos->estadoConfiguracion(5)) echo "<td>".$datos_pieza->fields['nombre_pieza']."</td>";
			if($datos->estadoConfiguracion(6)) echo "<td>".$datos_actividad->fields['nombre_actividad']."</td>";
			echo "<td>".$datos_usuario->fields['user_name']." ".$datos_usuario->fields['user_lastname']."</td>";
			echo "<td>".$recordSet->fields["fecha_orden"]."</td>";
			echo "<td>".$recordSet->fields["observaciones"]."</td>";
			echo "<td><a href=turno&estado=1&orden".$recordSet->fields['id_orden_trabajo']."&maquina=".$maquina."&usuario=".$usuario."><img src=\"imagenes/edit1.png\"> </a></td>";
			$c_estilo++;
			$recordSet->MoveNext();
		}
?>
		</table>
	</div>
	<div class="footer" id="footer">

		<a class="link" href="http://172.16.96.55/calidad/index.php"><div class="calidad"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div></a> <!----> <div class="manufactura"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div> <!----> <a class="link" href="http://172.16.96.203:809/mantenimiento/index.php"><div class="mantenimiento"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div></a>
	</div>
</body>
</html>
