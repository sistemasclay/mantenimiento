<?php

$pagina = $pagina . '?seccion=actividades';
//echo $pagina;
if ($_GET["codigo_actividad"]) {
	$datos_actividad = $datos->detalle_actividad($_GET["codigo_actividad"]);
	// echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
} else {
	if ($_POST["codigo_actividadb"]) {
		$datos_actividad = $datos->detalle_actividad($_POST["codigo_actividadb"]);
	}
}
?>
<div id="titulo">
	<h1>ACTIVIDADES</h1>
	<h2>Aquí podra ingresar la información general referente a los actividades que las máquinas usen</h2>
	<br>
	<form class="ui form" action="<?php $pagina ?>" method="post" name="busqueda">
		<table class="ui table">
			<tr>
				<td class="etq">
					Busqueda Rapida
				</td>
				<td>
					<input class="ctxt" name="codigo_actividadb" type="text" id="codigo_actividadb" />
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
			<div id="datos" class="scrollstyle">

				<form class="ui form" id="factividades" name="factividades" method="post" action="formsubmit_config.php">
					<input type="hidden" name="seccion" id="seccion" value="actividades" />
					<table class="ui table">
						<tr>
							<td class="etq">
								Codigo:
							</td>
							<td>
								<input class="ctxt" type="text" <?php if ($datos_actividad->fields["id_actividad"] != "") {
																	echo "readonly=\"readonly\"";
																} ?> name="codigo_actividad" id="codigo_actividad" value="<?php echo $datos_actividad->fields["id_actividad"];   ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Nombre:
							</td>
							<td>
								<input class="ctxt" type="text" name="nombre_actividad" id="nombre_actividad" value="<?php echo $datos_actividad->fields["nombre_actividad"]; ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Descripción:
							</td>
							<td>
								<input class="ctxt" type="text" name="descripcion_actividad" id="descripcion_actividad" value="<?php echo $datos_actividad->fields["descripcion_actividad"];   ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Activo:
							</td>
							<td>
								<input type="Checkbox" name="estado" id="estado" <?php if ($datos_actividad->fields["estado"] == "1") echo "checked" ?> />
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
		<div class="ui ten wide column " style="overflow:scroll; height:600px; overflow-x:hidden;">
			<div id="lista" class="scrollstyle">
				<table class="ui inverted yellow celled table">
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
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
					<?php
					$recordSet = $datos->listar_actividades();
					//echo $recordSet;
					$color = array('impar', 'par');
					$c_estilo = 0;

					while (!$recordSet->EOF) {

						if ($c_estilo % 2 != 0)
							echo '<tr class="' . $color[0] . '">';
						else
							echo '<tr class="' . $color[1] . '">';
						echo "
          <td>" . $recordSet->fields['id_actividad'] . "</td>
          <td>" . $recordSet->fields['nombre_actividad'] . "</td>
          <td>" . $recordSet->fields['descripcion_actividad'] . "</td>
          <td><a href=$pagina&&codigo_actividad="
							. $recordSet->fields['id_actividad'] . "><img src=\"imagenes/edit1.png\"> </a></td>
          <td><a onclick='return confirm(\"¿Seguro que desea eliminar  " . $recordSet->fields['nombre_actividad'] . "?\")' href=formsubmit_config.php?seccion=eliminar_actividad&codigo_actividad="
							. $recordSet->fields['id_actividad'] . "><img src=\"imagenes/delete3.png\"> </a></td>
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
<div id="">

	<br>

</div>
<!-- fin div principal-->