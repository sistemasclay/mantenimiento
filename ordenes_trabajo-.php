<?php

$fecha = time();

$pagina = $pagina . '?seccion=ordenes_trabajo';
if ($_GET["codigo_orden"]) {
	$datos_orden = $datos->detalle_orden_trabajo($_GET["codigo_orden"]);
}



?>
<div id="titulo">
	<h1>Ordenes</h1>
	<h2>Aquí podra ingresar la información ordenes de trabajo para el personal de mantenimeinto</h2>
	<br>
</div>
<div class="ui container">
	<div class="ui two column grid">
		<div class="ui six wide column">
			<div class="ui container">

				<!--div principal-->
				<!--  overflow: scroll; -->
				<div id="">
					<div id="datos" class="scrollstyle">
						<form class="ui form" id="fordenes" name="fordenes" method="post" action="formsubmit_config.php">
							<input type="hidden" name="seccion" id="seccion" value="ordenes_trabajo" />
							<?php
							if ($_GET["codigo_orden"]) {
								echo '<input type="hidden" name="codigo_orden" id="codigo_orden" value="' . $_GET["codigo_orden"] . '"/>';
							}
							?>
							<table class="ui table">
								<tr>
									<td class="etq">
										Fecha Mantenimiento
									</td>
									<td>
										<input class="ctxt" type="text" name="fecham" id="fecham" onclick="displayCalendar(document.forms[0].fecham,'yyyy/mm/dd',this)" value="<?php if ($_GET["codigo_orden"]) {
																																													echo  $datos_orden->fields["fecha_orden"];
																																												} else {
																																													echo date("Y/m/d", $fecha);
																																												}  ?>" />
									</td>
								</tr>
								<tr>
									<td class="etq">
										Maquina:
									</td>
									<td>
										<select class="lista" name="codigo_maquina" id="codigo_maquina">
											<?php
											if ($_GET["codigo_orden"]) {
												$datos_maquina = $datos->detalle_proceso($datos_orden->fields["id_maquina"]);
												echo "<option class=\"oplista\" value=\" " . $datos_maquina->fields['id_maquina'] . " \">" . $datos_maquina->fields['nombre_maquina'] . "</option>";
											}
											$recordSet = $datos->listar_procesos();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_maquina'] . " \">" . $recordSet->fields['nombre_maquina'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="etq">
										Sistema:
									</td>
									<td>
										<select class="lista" name="codigo_sistema" id="codigo_sistema">
											<?php
											if ($_GET["codigo_orden"]) {
												$datos_sitema = $datos->detalle_sistema($datos_orden->fields["id_sistema"]);
												echo "<option class=\"oplista\" value=\" " . $datos_sitema->fields['id_sistema'] . " \">" . $datos_sitema->fields['nombre_sistema'] . "</option>";
											}
											$recordSet = $datos->listar_sistemas();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_sistema'] . " \">" . $recordSet->fields['nombre_sistema'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr <?php if (!$datos->estado_configuracion(4)) echo 'hidden'; ?>>
									<td class="etq">
										Sub-Sistema:
									</td>
									<td>
										<select class="lista" name="codigo_sub_sistema" id="codigo_sub_sistema">
											<?php
											if ($_GET["codigo_orden"]) {
												$datos_sub_sistema = $datos->detalle_sub_sistema($datos_orden->fields["id_sub_sistema"]);
												echo "<option class=\"oplista\" value=\" " . $datos_sub_sistema->fields['id_sub_sistema'] . " \">" . $datos_sub_sistema->fields['nombre_sub_sistema'] . "</option>";
											}
											$recordSet = $datos->listar_sub_sistemas();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_sub_sistema'] . " \">" . $recordSet->fields['nombre_sub_sistema'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr <?php if (!$datos->estado_configuracion(5)) echo 'hidden'; ?>>
									<td class="etq">
										Pieza:
									</td>
									<td>
										<select class="lista" name="codigo_pieza" id="codigo_pieza">
											<?php
											if ($_GET["codigo_orden"]) {
												$datos_pieza = $datos->detalle_pieza($datos_orden->fields["id_pieza"]);
												echo "<option class=\"oplista\" value=\" " . $datos_pieza->fields['id_pieza'] . " \">" . $datos_pieza->fields['nombre_pieza'] . "</option>";
											}
											$recordSet = $datos->listar_piezas();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_pieza'] . " \">" . $recordSet->fields['nombre_pieza'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr <?php if (!$datos->estado_configuracion(6)) echo 'hidden'; ?>>
									<td class="etq">
										Actividad:
									</td>
									<td>
										<select class="lista" name="codigo_actividad" id="codigo_actividad">
											<?php
											if ($_GET["codigo_orden"]) {
												$datos_actividad = $datos->detalle_actividad($datos_orden->fields["id_actividad"]);
												echo "<option class=\"oplista\" value=\" " . $datos_actividad->fields['id_actividad'] . " \">" . $datos_actividad->fields['nombre_actividad'] . "</option>";
											}
											$recordSet = $datos->listar_actividades();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_actividad'] . " \">" . $recordSet->fields['nombre_actividad'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="etq">
										Encargado:
									</td>
									<td>
										<select class="lista" name="codigo_usuario" id="codigo_usuario">
											<?php
											if ($_GET["codigo_orden"]) {
												if ($datos_orden->fields["id_usuario"]) {
													$datos_usuario = $datos->detalle_usuario($datos_orden->fields["id_usuario"]);
												} else {
													$datos_usuario = "";
												}
												echo "<option class=\"oplista\" value=\" " . $datos_usuario->fields['id_user'] . " \">" . $datos_usuario->fields['user_name'] . " " . $datos_usuario->fields['user_lastname'] . "</option>";
											}
											$recordSet = $datos->listar_usuarios_con_privilegio();

											while (!$recordSet->EOF) {
												echo "<option class=\"oplista\" value=\" " . $recordSet->fields['id_user'] . " \">" . $recordSet->fields['user_name'] . " " . $recordSet->fields['user_lastname'] . "</option>";
												$recordSet->MoveNext();
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="etq">
										Observaciones:
									</td>
									<td>
										<TEXTAREA class="ctxt" COLS=30 ROWS=4 name="observaciones" id="observaciones"><?php echo $datos_orden->fields["observaciones"]; ?></TEXTAREA>
									</td>
								</tr>
								<tr>
									<td class="etq">
										Correctivo:
									</td>
									<td>
										<input type="Checkbox" name="correctivo" id="correctivo" <?php if ($datos_orden->fields["correctivo"] == "1") echo "checked" ?> />
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
				</div>
			</div>
		</div>

		<div class="ui ten wide column" style="overflow:scroll; height:600px; overflow-x:hidden;">
			<div id="lista" class="scrollstyle">
				<table class="ui inverted yellow celled table">
					<tr>
						<th>Maquina</th>
						<th>Sistema</th>
						<th>Sub-Sistema</th>
						<th>Pieza</th>
						<th>Actividad</th>
						<th>Encargado</th>
						<th>Fecha</th>
						<th>Observaciones</th>
						<th>Editar</th>
						<th>Cancelar<br>Orden</th>
						<th>Iniciar/Cerrar Orden</th>
					</tr>
					<?php
					$recordSet = $datos->listar_ordenes_trabajo();
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
						<td>" . $recordSet->fields['id_sistema'] . "</td>
						<td>" . $recordSet->fields['id_sub_sistema'] . "</td>
						<td>" . $recordSet->fields['id_pieza'] . "</td>
						<td>" . $recordSet->fields['id_actividad'] . "</td>
						<td>" . $recordSet->fields['id_usuario'] . "</td>
						<td>" . $recordSet->fields['estado'] . "</td>
						<td>" . $recordSet->fields['nombre_maquina'] . "</td>
						<td>" . $recordSet->fields['nombre_sistema'] . "</td>

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

</div>
</div>
<!-- fin div principal-->