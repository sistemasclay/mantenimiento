<?php
 session_start();


$pagina = $pagina.'?seccion=piezas';
//echo $pagina;
    if($_GET["codigo_pieza"])
    {
    $datos_pieza = $datos->detalle_pieza($_GET["codigo_pieza"]);
   // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
    }
	else
	{
		if($_POST["codigo_piezab"])
		{
			$datos_pieza = $datos->detalle_pieza($_POST["codigo_piezab"]);
		}
	}
?>
			<div id="titulo">
				<h1>PIEZAS</h1>
				<h2>Aquí podra ingresar la información general referente a las piezas que las máquinas usen</h2>
				<br>
				<form class="forma" action="<?php $pagina?>" method="post" name="busqueda">
						<table class="tforma">
							<tr>
								<td  class="etq">
									Busqueda Rapida
								</td>
								<td>
									<input class="ctxt" name="codigo_piezab" type="text" id="codigo_piezab" />
								</td>
								<td colspan="2">
									<input class="btn" type="submit" />
								</td>
							</tr>
						</table>
					</form>
			</div>
                <!--div principal-->
                <!--  overflow: scroll; -->
			<div id="contenido">
				<div id="datos" class="scrollstyle">

					<form class="forma" id="fpiezas" name="fpiezas" method="post" action="formsubmit_config.php">
						<input type="hidden" name="seccion" id="seccion" value="piezas"/>
						<table class="tforma" >
							<tr>
								<td class="etq">
									Codigo:
								</td>
								<td>
									<input class="ctxt" type="text" <?php  if($datos_pieza->fields["id_pieza"]!=""){ echo "readonly=\"readonly\""; } ?> name="codigo_pieza" id="codigo_pieza" value ="<?php  echo $datos_pieza->fields["id_pieza"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Nombre:
								</td>
								<td>
									<input class="ctxt" type="text" name="nombre_pieza" id="nombre_pieza" value ="<?php  echo $datos_pieza->fields["nombre_pieza"];?>" />
								</td>
							</tr>
							<tr>
								<td class="etq">
									Descripción:
								</td>
								<td>
									<input class="ctxt" type="text" name="descripcion_pieza" id="descripcion_pieza" value ="<?php  echo $datos_pieza->fields["descripcion_pieza"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Activo:
								</td>
								<td>
									<input type="Checkbox" name="estado" id="estado" <?php  if ($datos_pieza->fields["estado"]=="1") echo "checked" ?>  />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input class="btn" type="submit" name="Submit" value="Guardar" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="lista" class="scrollstyle">
					<table class="tinfo">
						<tr>
							<th><div class="tooltip">Codigo<span class="tooltiptext">Codigo de Identificación de la Pieza, debe ser un numero mayor a 0</span></div></th>
							<th><div class="tooltip">Nombre<span class="tooltiptext"> Nombre (descripción corta) de la Pieza</span></div></th>
							<th><div class="tooltip">Descripción<span class="tooltiptext">Descripción larga de la Pieza</span></div></th>
							<th>Editar</th>
							<th>Eliminar</th>
						</tr>
<?php
		$recordSet=$datos->listar_piezas();
		//echo $recordSet;
		$color=array('impar','par');
		$c_estilo=0;

		while (!$recordSet->EOF) {

			if($c_estilo%2!=0)
				echo '<tr class="'.$color[0].'">';
			else
				echo '<tr class="'.$color[1].'">';

			echo "
			<td>".$recordSet->fields['id_pieza']."</td>
			<td>".$recordSet->fields['nombre_pieza']."</td>
			<td>".$recordSet->fields['descripcion_pieza']."</td>
			<td><a href=$pagina&&codigo_pieza="
					.$recordSet->fields['id_pieza']."><img src=\"imagenes/edit1.png\"> </a></td>
				<td><a onclick='return confirm(\"¿Seguro que desea eliminar  ".$recordSet->fields['nombre_pieza']."?\")' href=formsubmit_config.php?seccion=eliminar_pieza&codigo_pieza="
					.$recordSet->fields['id_pieza']."><img src=\"imagenes/delete3.png\"> </a></td>
			</tr>
			";
			$c_estilo++;
			$recordSet->MoveNext();
		}
?>
					</table>
				</div>
			</div>
            <!-- fin div principal-->
