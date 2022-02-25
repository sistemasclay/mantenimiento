<?php
 session_start();
//include("clases/configuracion_aplicacion.php");
//$datos = new configuracion_aplicacion();



?>
			<div id="contenido">
				<div id="datos" class="scrollstyle">
					<table class="tinfo">
						<tr>
							<th> # </th>
							<th>Insumo</th>
							<th>Cant</th>
							<th>Unid</th>
							<th>Borrar</th>
						</tr>
<?php
		$recordSet=$datos->traer_insumos_mantenimiento($mantenimiento->fields["id_registro"]);
		//echo $recordSet;
		$color=array('impar','par');
		$c_estilo=0;
		$numero = 1;
		while (!$recordSet->EOF) {

		$insumo = $datos->detalle_insumo($recordSet->fields["id_insumo"]);

		if($c_estilo%2!=0)
			echo '<tr class="'.$color[0].'">';
		else
			echo '<tr class="'.$color[1].'">';

		echo "
		<td>".$numero."</td>
		<td>".$insumo->fields['id_insumo']."-".$insumo->fields['nombre_insumo']."</td>
		<td>".$recordSet->fields['cantidad']."</td>
		<td>".$insumo->fields['unidad_insumo']."</td>
		<td><a onclick='return confirm(\"¿Seguro que desea eliminar el insumo ".$recordSet->fields['nombre_insumo']." del registro?\")' href=formsubmit_config.php?seccion=quitar_insumo&codigo_registro="
				.$mantenimiento->fields["id_registro"]."&codigo_insumo=".$insumo->fields['id_insumo']."><img src=\"imagenes/delete3.png\"> </a></td>
		</tr>
		";
		$c_estilo++;
		$numero++;
		$recordSet->MoveNext();
		}
?>
					</table>
				</div>
				<div id="ingreso_insumos">

				<form class="forma" method="post" action="formsubmit_config.php">
					<input type="hidden" name="codigo_registro" id="codigo_registro" value="<?php  echo $mantenimiento->fields["id_registro"]; ?>"/>
					<input type="hidden" name="seccion" id="seccion" value="agregar_insumo"/>
					<table class="tinfo">
						<tr>
							<th> INSUMO </th>
							<th> CANTIDAD </th>
						</tr>
						<tr class="impar">
							<td>
								<select name="codigo_insumo" id="codigo_insumo">
<?php

	$recordSet=$datos->listar_insumos();

	while (!$recordSet->EOF) {
	echo "<option class=\"oplista\" value=\"".$recordSet->fields['id_insumo']."\">".$recordSet->fields['nombre_insumo']."</option>";
	$recordSet->MoveNext();
	}
?>
								</select>
							</td>
							<td>
								<input class="ctxt" type="text" name="cantidad" id="cantidad" />
							</td>
						</tr>
						<tr>
							<td colspan="2"> <input class="btn" type="submit" value=" Agregar a la lista " /> </td>
						</tr>

					</table>
				</form>

				</div>
			</div>
            <!-- fin div principal-->
