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
  	<h1>REPORTES</h1>
        <div align="center">
            <br/>
	<table align="center" border="0" cellpadding="0" cellspacing="5" width="30%" >
		<tr>
			<td><a href="escritos/escritos.php"> <IMG SRC="../img_menu/9.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Escritos" onmouseover="src='../img_menu/9A.png'"
onMouseOut="src='../img_menu/9.png'" ></a></td>
			<td><a href="excel/excel.php"><IMG SRC="../img_menu/7.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Excel" onmouseover="src='../img_menu/7A.png'"
onMouseOut="src='../img_menu/7.png'"></a></td>
			<td><a href="graficos/graficos.php"><IMG SRC="../img_menu/4.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Graficos" onmouseover="src='../img_menu/4A.png'"
onMouseOut="src='../img_menu/4.png'"></a></td>
		</tr>

		<tr>
			<td><br/>  <a href="reportes_oee.php"><IMG SRC="../img_menu/8.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Graficos OEE" onmouseover="src='../img_menu/8A.png'"
onMouseOut="src='../img_menu/8.png'"></a></td>
			<td><br/><a href="graficos_paradas_maquina/graficos_paradas_maquina.php"><IMG SRC="../img_menu/6.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Paradas Maquina" onmouseover="src='../img_menu/6A.png'"
onMouseOut="src='../img_menu/6.png'"></a></td>
			<td><br/><a href="ordenes_produccion/ordenes_produccion.php"><IMG SRC="../img_menu/5.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Ordenes de produccion" onmouseover="src='../img_menu/5A.png'"
onMouseOut="src='../img_menu/5.png'"></a></td>
		</tr>
		
		<tr>
			<td></td>
			<td><br/><a href="praktiplast/excel.php"> <IMG SRC="../img_menu/B1.png" WIDTH=180 HEIGHT=65 BORDER=0 ALT="Escritos" onmouseover="src='../img_menu/B2.png'"
onMouseOut="src='../img_menu/B1.png'" ></a></td>
			<td></td>
		</tr>
	
	</table>
            </div>
  </div>
  <?php require_once('..\includes\piep.php'); ?>
</div>
</body>
</html>