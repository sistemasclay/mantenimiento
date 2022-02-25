<?php
include("modelo/DatosTurno.php");
$datos = new DatosTurno();

if($_GET["planta"])
{
    $planta= $_GET["planta"];
}
else
{
	if($_POST["planta"])
	{
		$planta= $_POST["planta"];
	}
}

	$recordset = $datos->listar_ordenes_trabajo();

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
		<div id="title" class="textTitle">SELECCION DE</br>PROCESO</div>
	</div>
	<div>
	</div>
	<div class="main">
<?php
$i=0;
$lenght=$recordset->RecordCount();
while($i < $lenght)
{
	echo "<div class=\"spacev\"></div>";
	echo "<div class=\"processCtn\">";
	while(!$recordset->EOF)
	{
		$maquina = $recordset->fields['id_maquina'];
		$nom_maquina = $recordset->fields['nombre_maquina'];
		$turno = $recordset->fields['t_terminado'];
		$parada = $recordset->fields['tp_terminado'];

		if($turno == 0){
			//$datos->verificarCierre($maquina);
			if($parada == 0){
				$clase = "leftParada";
			}
			else{
				$clase = "leftActivo";
			}
		}
		else{
			$clase = "left";
		}

		if($i%2 == 1){
			echo "<div class=\"spaceh\"></div>";
		}
		echo "<div class=\"".$clase."\">";
			echo "<a class=\"textSelector\"href=\"reg_inicio.php?proceso=".$maquina."&planta=".$planta."\"><div class=\"number\">";
				echo "<div class=\"text\">".$maquina."<br>".$nom_maquina."</div>";
			echo "</div></a>";
		echo "</div>";

		$recordset->MoveNext();
		$i=$i+1;
		if($i%2 == 0){
			break;
		}
	}
	echo "</div>";
}
?>

	</div>
	<div class="footer" id="footer">

		<a class="link" href="http://172.16.96.55/calidad/index.php"><div class="calidad"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div></a> <!----> <div class="manufactura"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div> <!----> <a class="link" href="http://172.16.96.203:809/mantenimiento/index.php"><div class="mantenimiento"><img src="css/imagenes/cuadro.png" WIDTH=1 HEIGHT=1 BORDER=0></div></a>
	</div>
</body>
</html>
