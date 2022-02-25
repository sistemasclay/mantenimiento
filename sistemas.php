<?php

$pagina = $pagina . '?seccion=sistemas';
//echo $pagina;
if ($_GET["codigo_sistema"]) {
	$datos_sistema = $datos->detalle_sistema($_GET["codigo_sistema"]);
	// echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
} else {
	if ($_POST["codigo_sistemab"]) {
		$datos_sistema = $datos->detalle_sistema($_POST["codigo_sistemab"]);
	}
}
?>
<div id="titulo">
	<h1>SISTEMAS</h1>
	<h2>Aquí podra ingresar la información general referente a los sistemas que las máquinas usen</h2>
	<br>
	<form class="ui form" action="<?php $pagina ?>" method="post" name="busqueda">
		<table class="ui table">
			<tr>
				<td class="etq">
					Busqueda Rapida
				</td>
				<td>
					<input class="ctxt" name="codigo_sistemab" type="text" id="codigo_sistemab" />
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

				<form class="ui form" id="fsistemas" name="fsistemas" method="post" action="formsubmit_config.php">
					<input type="hidden" name="seccion" id="seccion" value="sistemas" />
					<table class="ui table">
						<tr>
							<td class="etq">
								Codigo:
							</td>
							<td>
								<input class="ctxt" type="text" <?php if ($datos_sistema->fields["id_sistema"] != "") {
																	echo "readonly=\"readonly\"";
																} ?> name="codigo_sistema" id="codigo_sistema" value="<?php echo $datos_sistema->fields["id_sistema"];   ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Nombre:
							</td>
							<td>
								<input class="ctxt" type="text" name="nombre_sistema" id="nombre_sistema" value="<?php echo $datos_sistema->fields["nombre_sistema"]; ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Descripción:
							</td>
							<td>
								<input class="ctxt" type="text" name="descripcion_sistema" id="descripcion_sistema" value="<?php echo $datos_sistema->fields["descripcion_sistema"];   ?>" />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Activo:
							</td>
							<td>
								<input type="Checkbox" name="estado" id="estado" <?php if ($datos_sistema->fields["estado"] == "1") echo "checked" ?> />
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

		<div class="ui ten wide column" style="overflow:scroll; height:600px; overflow-x:hidden;">
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
							<div class="tooltip">Descripcións</div>
						</th>
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
					<?php
					$recordSet = $datos->listar_sistemas();
					//echo $recordSet;
					$color = array('impar', 'par');
					$c_estilo = 0;

					while (!$recordSet->EOF) {

						if ($c_estilo % 2 != 0)
							echo '<tr class="' . $color[0] . '">';
						else
							echo '<tr class="' . $color[1] . '">';
						echo "
    		<td>" . $recordSet->fields['id_sistema'] . "</td>
    		<td>" . $recordSet->fields['nombre_sistema'] . "</td>
    		<td>" . $recordSet->fields['descripcion_sistema'] . "</td>
    		<td><a href=$pagina&&codigo_sistema="
							. $recordSet->fields['id_sistema'] . "><img src=\"imagenes/edit1.png\"> </a></td>
    			<td><a onclick='return confirm(\"¿Seguro que desea eliminar  " . $recordSet->fields['nombre_sistema'] . "?\")' href=formsubmit_config.php?seccion=eliminar_sistema&codigo_sistema="
							. $recordSet->fields['id_sistema'] . "><img src=\"imagenes/delete3.png\"> </a></td>
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


<script languaje="JavaScript">
	//sistemas
	var f16 = new LiveValidation('codigo_sistema');
	f16.add(Validate.Presence);
	f16.add(Validate.Numericality, {
		onlyInteger: true
	});

	var f17 = new LiveValidation('codigo_sistemab');
	f17.add(Validate.Numericality, {
		onlyInteger: true
	});

	var f18 = new LiveValidation('nombre_sistema');
	f18.add(Validate.Presence);

	var f19 = new LiveValidation('descripcion_sistema');
	f19.add(Validate.Presence);
</script>