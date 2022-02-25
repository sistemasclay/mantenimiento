<?php


include_once("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();

$fecha = time();
$pagina = $pagina.'?seccion=reportes';

?>
			<div id="titulo">
				<h1>REPORTES</h1>
			</div>
                <!--div principal-->
			<div id="">
				<form class="ui form" action="formsubmit.php" target="" method="POST" name="parametros">
					<table class="ui table">
						<tr>
							<td class="etq">
							DESDE
							</td>
							<td class="etq">
							HASTA
							</td>
						</tr>
						<tr>
							<td>
								<input class="ctxt" type="text" name="fechai" id="fechai" onclick="displayCalendar(document.forms[0].fechai,'yyyy/mm/dd',this)" value="<?php  echo date("Y/m/d",$fecha);  ?>" autocomplete="off"/>
							</td>
							<td>
								<input class="ctxt" type="text" name="fechaf" id="fechaf" onclick="displayCalendar(document.forms[0].fechaf,'yyyy/mm/dd',this)" value="<?php  echo date("Y/m/d",$fecha);  ?>" autocomplete="off"/>
							</td>
						</tr>
						<tr>
							<td class="etq">
								Seleccione un reporte
							</td>
							<td colspan="2">
								<select class="lista" size="1" name="tipo" id="tipo" onchange="tipoReporte()">
								<option value="1" >Bitacora de Mantenimientos</option>
								<option value="2" >Cronograma de Ordenes</option>
								<option value="3" >Hoja de Vida de Máquina</option>
                <option value="4"> Duracion Mantenimiento</option>
<!--								<option value="3" >Reporte Paradas</option>-->
								</select>
							</td>
						</tr>
						<tr style="display: none;" id="seccion_maquina">
							<td class="etq">
								Máquina
							</td>
							<td name="maquina" id="maquina">
								<datalist id="insumo">
<?php

	$recordSet=$datos->listar_procesos();

	while (!$recordSet->EOF) {
	echo "<option class=\"oplista\" value=\"".$recordSet->fields['id_maquina']."\">".$recordSet->fields['nombre_maquina']."</option>";
	$recordSet->MoveNext();
	}
?>
									</datalist>
									<input list="insumo" name="cod_maquina" id="cod_maquina" class="lista" >
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input class="ui yellow button"  type="submit" value="Ver Reporte"/>
							</td>
						</tr>
					</table>
				</form>

        <?php

          $tabla = $_GET["activo"];
         if ($tabla ==1):
           $infoTabla = $datos->detalle_orden_trabajo_f($_GET["fechai"],$_GET["fechaf"],$_GET['cod_maquina']);

            ?>
            <br>
            <table class="ui inverted yellow celled table">
              <tr>
                <th>Maquina</th>
                <th>Nombre</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Duracion</th>
                  <tr>

                  <?php
                  $color=array('impar','par');
              		$c_estilo=0;
              		$numero = 1;
              		while (!$infoTabla->EOF) {
                    $nombre = $datos->traerNombreUsuario($infoTabla->fields['id_usuario']);
                    $fechai=$infoTabla->fields['fecha_anterior'];
                    $fechaf=$infoTabla->fields['fecha_fin_orden'];
                    $test = $datos->getDifFechastodo($fechaf,$fechai);
                    $test = round($test/3600,2);

              		if($c_estilo%2!=0)
              			echo '<tr class="'.$color[0].'">';
              		else
              			echo '<tr class="'.$color[1].'">';

              		echo "
              		<td>".$infoTabla->fields['id_maquina']."</td>
              		<td>".$nombre."</td>
                  <td>".$fechai."</td>
                  <td>".$fechaf."</td>
                  <td>".$test."</td>

              		";
              		$c_estilo++;
              		$numero++;
              		$infoTabla->MoveNext();
              		}
                   ?>





              </table>
        <?php endif; ?>
			</div>
<script>
function tipoReporte() {
	var reporte = document.getElementById("tipo").value;
	if(reporte == 3){
		document.getElementById("seccion_maquina").style.display = 'table-row';
	}else if (reporte ==4) {
		document.getElementById("seccion_maquina").style.display = 'table-row';

	}
	else{
		document.getElementById("seccion_maquina").style.display = 'none';
	}

}
</script>
            <!-- fin div principal-->
