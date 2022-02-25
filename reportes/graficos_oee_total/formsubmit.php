<? session_start(); ?>
<?php

include("../../clases/graficos_oee_total.php");
$repor= new reportes_graficos();
//$pagina="../reportes.php";
$sql = "SELECT  indicador_1,indicador_2,indicador_3,tiempo_turno,unidades_conteo from  turnos where terminado='1' AND fecha_inicio<='".$_POST["fechaf"]."' AND fecha_inicio>='".$_POST["fechai"]."'"; // order by id_turno
  $inf= "De ".$_POST["fechai"]." a ".$_POST["fechaf"];
$repor->pdf_grafico_oee_total($sql,$inf);
        ?>

