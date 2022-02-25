<?php
session_start();

$pagina = $pagina.'?seccion=sub_sistemas';
//echo $pagina;
    if($_GET["codigo_sub_sistema"])
    {
    $datos_sub_sistema = $datos->detalle_sub_sistema($_GET["codigo_sub_sistema"]);
   // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
    }
	else
	{
		if($_POST["codigo_sub_sistemab"])
		{
			$datos_sub_sistema = $datos->detalle_sub_sistema($_POST["codigo_sub_sistemab"]);
		}
	}
?>
			<div id="titulo">
				<h1>SUB-SISTEMAS</h1>
				<h2>Aquí podra ingresar la información general referente a los sub-sistemas que las máquinas usen</h2>
				<br>
				<form class="forma" action="<?php $pagina?>" method="post" name="busqueda">
						<table class="tforma">
							<tr>
								<td  class="etq">
									Busqueda Rapida
								</td>
								<td>
									<input class="ctxt" name="codigo_sub_sistemab" type="text" id="codigo_sub_sistemab" />
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

					<form class="forma" id="fsub_sistemas" name="fsub_sistemas" method="post" action="formsubmit_config.php">
						<input type="hidden" name="seccion" id="seccion" value="sub_sistemas"/>
						<table class="tforma" >
							<tr>
								<td class="etq">
									Codigo:
								</td>
								<td>
									<input class="ctxt" type="text" <?php  if($datos_sub_sistema->fields["id_sub_sistema"]!=""){ echo "readonly=\"readonly\""; } ?> name="codigo_sub_sistema" id="codigo_sub_sistema" value ="<?php  echo $datos_sub_sistema->fields["id_sub_sistema"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Nombre:
								</td>
								<td>
									<input class="ctxt" type="text" name="nombre_sub_sistema" id="nombre_sub_sistema" value ="<?php  echo $datos_sub_sistema->fields["nombre_sub_sistema"];?>" />
								</td>
							</tr>
							<tr>
								<td class="etq">
									Descripción:
								</td>
								<td>
									<input class="ctxt" type="text" name="descripcion_sub_sistema" id="descripcion_sub_sistema" value ="<?php  echo $datos_sub_sistema->fields["descripcion_sub_sistema"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Activo:
								</td>
								<td>
									<input type="Checkbox" name="estado" id="estado" <?php  if ($datos_sub_sistema->fields["estado"]=="1") echo "checked" ?>  />
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
							<th><div class="tooltip">Codigo<span class="tooltiptext">Codigo de Identificación del Sub Sistema, debe ser un numero mayor a 0</span></div></th>
							<th><div class="tooltip">Nombre<span class="tooltiptext"> Nombre (descripción corta) del Sub Sistema</span></div></th>
							<th><div class="tooltip">Descripción<span class="tooltiptext">Descripción larga del Sub Sistema</span></div></th>
							<th>Editar</th>
							<th>Eliminar</th>
						</tr>
<?php
		$recordSet=$datos->listar_sub_sistemas();
		//echo $recordSet;
		$color=array('impar','par');
		$c_estilo=0;

		while (!$recordSet->EOF) {

							if($c_estilo%2!=0)
							echo '<tr class="'.$color[0].'">';
					else
							echo '<tr class="'.$color[1].'">';
		echo "
		<td>".$recordSet->fields['id_sub_sistema']."</td>
		<td>".$recordSet->fields['nombre_sub_sistema']."</td>
		<td>".$recordSet->fields['descripcion_sub_sistema']."</td>
		<td><a href=$pagina&&codigo_sub_sistema="
				.$recordSet->fields['id_sub_sistema']."><img src=\"imagenes/edit1.png\"> </a></td>
			<td><a onclick='return confirm(\"¿Seguro que desea eliminar  ".$recordSet->fields['nombre_sub_sistema']."?\")' href=formsubmit_config.php?seccion=eliminar_sub_sistema&codigo_sub_sistema="
				.$recordSet->fields['id_sub_sistema']."><img src=\"imagenes/delete3.png\"> </a></td>
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


            <script  languaje= "JavaScript" >
//sub_sistemas
var f16 = new LiveValidation('codigo_sub_sistema');
f16.add( Validate.Presence );
f16.add( Validate.Numericality, { onlyInteger: true } );

var f17 = new LiveValidation('codigo_sub_sistemab');
f17.add( Validate.Numericality, { onlyInteger: true } );

var f18 = new LiveValidation('nombre_sub_sistema');
f18.add( Validate.Presence );

var f19 = new LiveValidation('descripcion_sub_sistema');
f19.add( Validate.Presence );
</script>
