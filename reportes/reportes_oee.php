<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="estilos/myc_gral.css"/>
	<title>Monitoreo y Control</title>
</head>

<body>
	<div id="contenedor">
		<?php require_once('..\includes\cabecera.php'); ?>
		<div id="contenido">
			<h1>GRAFICOS OEE</h1>
			<div align="center">
				<br/>
				<table align="center" border="0" cellpadding="0" cellspacing="5" width="30%" >
					<tr>
						<td>
							<a href="graficos_oee_maquina/graficos_oee_maquina.php">
	<IMG SRC="../img_menu/3.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="OEE MAQUINA" onmouseover="src='../img_menu/3A.png'"onMouseOut="src='../img_menu/3.png'"/>
							</a>
						</td>
						<td>
							<a href="graficos_oee_operario/graficos_oee_operario.php">
	<IMG SRC="../img_menu/2.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="OEE Operario" onmouseover="src='../img_menu/2A.png'"onMouseOut="src='../img_menu/2.png'"/>
							</a>
						</td>
					</tr>
					<tr>
						<td>
							<a href="graficos_oee_total/graficos_oee_total">
	<IMG SRC="../img_menu/1.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="OEE Total" onmouseover="src='../img_menu/1A.png'"onMouseOut="src='../img_menu/1.png'"/>
							</a>
						</td>
						<td>
							<a href="graficos_oee_producto/graficos_oee_producto">
	<IMG SRC="../img_menu/10.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="OEE Producto" onmouseover="src='../img_menu/10A.png'"onMouseOut="src='../img_menu/10.png'"/>
							</a>
						</td>
					</tr>
				</table>
            </div>
  </div>
  <?php require_once('..\includes\piep.php'); ?>
</div>
</body>
</html>