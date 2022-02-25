<?php

$pagina = $pagina . '?seccion=maquinas';
//echo $pagina;
if ($_GET["codigo_proceso"]) {
	$datos_maquina = $datos->detalle_proceso($_GET["codigo_proceso"]);
	// echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
} else {
	if ($_POST["codigo_procesob"]) {
		$datos_maquina = $datos->detalle_proceso($_POST["codigo_procesob"]);
	}
}
?>
<div id="titulo">
	<h1>MAQUINAS</h1>
	<h2>Aquí podra ingresar todas las maquinas a las que desee hacer seguimiento</h2>
	<br>

	<form class="ui form" action="<?php $pagina ?>" method="post" name="busqueda">
		<table class="tforma">
			<tr>
				<td class="etq">
					Busqueda Rapida
				</td>
				<td>
					<input class="ctxt" name="codigo_procesob" type="text" id="codigo_procesob" />
				</td>
				<td colspan="2">
					<input class="ui yellow button" type="submit" />
				</td>
			</tr>
		</table>
	</form>
</div>
<br>
<!--div principal-->
<!--  overflow: scroll; -->
<div class="ui container">
	<div class="ui two column grid">
		<div class="ui six wide column">


			<form class="ui form" id="fprocesos" name="fprocesos" method="post" action="formsubmit_config.php">
				<input type="hidden" name="seccion" id="seccion" value="maquinas" />
				<table class="ui small table">
					<tr>
						<td class="etq">
							Codigo:
						</td>
						<td>
							<input class="ctxt" type="text" <?php if ($datos_maquina->fields["id_maquina"] != "") {
																echo "readonly=\"readonly\"";
															} ?> name="codigo_proceso" id="codigo_proceso" value="<?php echo $datos_maquina->fields["id_maquina"];   ?>" />
						</td>
					</tr>
					<tr>
						<td class="etq">
							Nombre:
						</td>
						<td>
							<input class="ctxt" type="text" name="nombre_maquina" id="nombre_maquina" value="<?php echo $datos_maquina->fields["nombre_maquina"]; ?>" />
						</td>
					</tr>
					<tr>
						<td class="etq">
							Descripción:
						</td>
						<td>
							<input class="ctxt" type="text" name="descripcion_maquina" id="descripcion_maquina" value="<?php echo $datos_maquina->fields["descripcion_maquina"]; ?>" />
						</td>
					</tr>
					<tr>
						<td class="etq">
							Horometro:
						</td>
						<td>
							<input class="ctxt" type="number" name="horometro" id="horometro" value="<?php echo $datos_maquina->fields["horometro"]; ?>" />
						</td>
					</tr>
					<tr>
						<td class="etq">
							Activo:
						</td>
						<td>
							<input type="Checkbox" name="estado_maquina" id="estado_maquina" <?php if ($datos_maquina->fields["estado"] == "1") echo "checked" ?> />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input class="ui yellow button" type="submit" name="Submit" value="Guardar" />
						</td>
					</tr>
				</table>
			</form>

		</div>
		<div class="ui ten wide column" style="overflow:scroll; height:600px; overflow-x:hidden;">
			<div>
				<table class="ui inverted center yellow celled table">
					<tr>
						<th>
							<div class="tooltip">Codigo</div>
						</th>
						<th>
							<div class="tooltip">Nombre</div>
						</th>
						<th>
							<div class="tooltip">Descripción</div>
						</th>
						<th>
							<div class="tooltip">Horometro</div>
						</th>
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
					<?php
					$recordSet = $datos->listar_procesos();
					//echo $recordSet;
					$color = array('impar', 'par');
					$c_estilo = 0;

					while (!$recordSet->EOF) {

						if ($c_estilo % 2 != 0)
							echo '<tr class="' . $color[0] . '">';
						else
							echo '<tr class="' . $color[1] . '">';
						echo "
    		<td>" . $recordSet->fields['id_maquina'] . "</td>
    		<td>" . $recordSet->fields['nombre_maquina'] . "</td>
			<td>" . $recordSet->fields['descripcion_maquina'] . "</td>
			<td>" . $recordSet->fields['horometro'] . "</td>

    		<td><a href=$pagina&&codigo_proceso="
							. $recordSet->fields['id_maquina'] . "><img src=\"imagenes/edit1.png\"> </a></td>
    			<td><a onclick='return confirm(\"¿Seguro que desea eliminar  " . $recordSet->fields['user_name'] . "?\")' href=formsubmit_config.php?seccion=eliminar_proceso&codigo_proceso="
							. $recordSet->fields['id_maquina'] . "><img src=\"imagenes/delete3.png\"> </a></td>
    		</tr>
    		";
						$c_estilo++;
						$recordSet->MoveNext();
					}
					?>
				</table>
			</div>
		</div>

	</div>

	<br>

</div>
<!-- fin div principal-->