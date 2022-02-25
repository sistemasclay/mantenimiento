<?php
require 'modelo/LogicaTurno.php';
$logicaTurno = new LogicaTurno();

$oee_real = $logicaTurno->getOEEReal($_REQUEST["proceso"]);
$meta_oee = $logicaTurno->getMetaOEE($_REQUEST["proceso"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css"  href="css/styles_mic_oee.css"/>
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="js/mic_observaciones.js"></script>
	<title>Monitoreo y Control</title>
	<script>
<?php echo "var proceso = " . $_REQUEST["proceso"] . ";"; ?>
	</script>
</head>

<body>
	<div class="title">
		<div id="title" class="textTitle">OEE FINAL</div>
	</div>
	<div>
	</div>
	<div class="main">
		<div class="<?php if($oee_real<$meta_oee){echo "bad";} else{ echo"good";}?>">
			</br>
			</br>
			<?php echo $oee_real ?>
			</br>
			<?php if($oee_real>=$meta_oee){?>
			<div class="star"></div><div class="star"></div><div class="star"></div><div class="star"></div><div class="star"></div>
			<?php }?>
		</div>
		<a href="seleccion_proceso.php"><div class="img1"></div></a>
	</div>
	<div class="footer" id="footer">
		<div class="textFotter">PEGASUS PRO <span class="textFotter2"> by </span> <span class="textFotter3"> M&C </span> </div>
	</div>
</body>
</html>
