<?php session_start();

include("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();
$sistemas = $datos->listar_sistemas();
$actividad = $datos->listar_actividades();
$actividad2 = $datos->listar_actividades();

$actividad3 = $datos->listar_actividades();

$actividad4 = $datos->listar_actividades();

$actividad5 = $datos->listar_actividades();

$actividad6 = $datos->listar_actividades();

$actividad7 = $datos->listar_actividades();



$pagina = 'principal';
//echo $pagina;
if($_GET["codigo_orden"])
{
	$orden = $_GET["codigo_orden"];
}
else
{
	if($_POST["codigo_orden"])
	{
		$orden = $_POST["codigo_orden"];
	}
}

if($_GET["origen_manufactura"])
{
	$origen = $_GET["origen_manufactura"];
}
else
{
	if($_POST["origen_manufactura"])
	{
		$origen = $_POST["origen_manufactura"];
	}
}

$datos_orden = $datos->detalle_orden_registro($orden);
$mantenimiento = $datos->traer_mantenimiento_orden($orden);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="estilos/myc_registros.css"/>
	<script type="text/javascript" src="js/jquery-3.1.1.js"></script>

<script src="js/semantic.min.js"></script>
<link rel="stylesheet" href="css/semantic.min.css">
<!--	<link type="text/css" rel="stylesheet" href="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>-->
	<title>Monitoreo y Control</title>
</head>

<body>

<!-- Aqui esta el div -->
	<form class="forma" method="post" action="formsubmit_config.php">
<div class="ui four column grid">
  <div class="two column row">
    <div class="column">

				<input type="hidden" name="origen" id="origen" value="<?php echo $origen?>"/><!-- SI ESTE DATO ES 1 ENTONCES SE LLEGO A ESTE LUGAR DESDE EL SISTEMA DE MANUFACTURA-->

					<tr>
						<td class="datos_orden">
							<table class="tinfo">
								<tr>
									<td>
										<input type="hidden" name="codigo_registro" id="codigo_registro" value="<?php  echo $mantenimiento->fields["id_registro"]; ?>"/>
										<input type="hidden" name="codigoOrden" id="codigoOrden" value=" <?php echo $orden ?>"/>
										<input type="hidden" name="usuario" id="usuario" value="<?php echo $datos_orden->fields["id_user"];?>"/>
										<input type="hidden" name="maquina" id="maquina" value="<?php echo $datos_orden->fields["id_maquina"];?>"/>
										<input type="hidden" name="sistema" id="sistema" value="<?php echo $datos_orden->fields["id_sistema"];?>"/>
										<input type="hidden" name="pieza" id="pieza" value="<?php echo $datos_orden->fields["id_pieza"];?>"/>
										<input type="hidden" name="sub_sistema" id="sub_sistema" value="<?php echo $datos_orden->fields["id_sub_sistema"];?>"/>
										<input type="hidden" name="actividad" id="actividad" value="<?php echo $datos_orden->fields["id_actividad"];?>"/>
										<input type="hidden" name="correctivo" id="correctivo" value="<?php echo $datos_orden->fields["correctivo"];?>"/>

										<table class="tinfo2">
											<tr>
												<th>
													SISTEMAS</br>MANTENIMIENTO
												</th>
											</tr>
											<tr class="">
												<td>
													<!-- Aqui -->	<div class="ui  list">

													<?php
													while(!$sistemas->EOF){


													 ?>

														<div class="item">
															<div class="label">


															</div>
															<div class="ui toggle  checkbox">
																<div class="ui slider checkbox">
																	<?php $mostrar = $sistemas->fields["nombre_sistema"]; ?>
																	<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
																	<label><h3><?php echo $mostrar ?></h1></label>
																</div>
														</div>
														</div>
														<?php $sistemas->MoveNext();
																	}?>
																	<div class="item">
																	<h1>OBSERVACIONES</h1>
																	</div>
																	<div class="item">

																			<textarea name="name" rows="8" cols="80"></textarea>
																		</div>

																	</div>
															</div>


									<!-- HAsta aqui -->

												</td>
											</tr>
											<tr>
												<td>
													<input class="ui yellow button" type="button" value=" Cerrar Orden " onclick="cerrar_mantenimiento()"/>
												</td>
											</tr>
										</table>

									</td>
								</tr>
							</table>
						</td>

					<div id="datos" class="scrollstyle">
						<input type="hidden" name="seccion" id="seccion" value="agregar_insumo"/>





			</form>

		</div>
  </div>
  <div class="column">
		<table class="tinfo">
			<tr>
				<th>Insumo</th>
				<th>Referencia</th>
				<th>Cant</th>
				<th>Borrar</th>
			</tr>
			<tr class="impar">
				<td>
					<datalist id="insumo">
<?php

$recordSet=$datos->listar_insumos();

while (!$recordSet->EOF) {
echo "<option class=\"oplista\" value=\"".$recordSet->fields['id_insumo']."\">".$recordSet->fields['nombre_insumo']."</option>";
$recordSet->MoveNext();
}
?>
					</datalist>
					<input list="insumo" name="codigo_insumo" id="codigo_insumo" class="lista" >
				</td>
				<td>
					<input class="ctxt" type="text" name="referencia" id="referencia" />
				</td>
				<td>
					<input class="ctxt" type="text" name="cantidad" id="cantidad" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td> <input class="btn" type="submit" value="Agregar" /> </td>
				<td></td>
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
<td>".$insumo->fields['id_insumo']."-".$insumo->fields['nombre_insumo']."</td>
<td>".$recordSet->fields['referencia']."</td>
<td>".$recordSet->fields['cantidad']."</td>
<td><a onclick='return confirm(\"Â¿Seguro que desea eliminar el insumo ".$recordSet->fields['nombre_insumo']." del registro?\")' href=formsubmit_config.php?seccion=quitar_insumo&codigo_registro="
.$mantenimiento->fields["id_registro"]."&codigo_insumo=".$insumo->fields['id_insumo']."><img src=\"imagenes/delete3.png\"> </a></td>
</tr>
";
$c_estilo++;
$numero++;
$recordSet->MoveNext();
}
?>
</td>
		</table>

	</div>

</div>
<div class="ui grid">
  <div class="four wide column">
		<div id="ELECTRICOdiv" style="display: none;">
<!-- aqui -->
<h1>ELECTRICO</h1>
<div class="ui  list">
<?php
while(!$actividad->EOF){
?>

<div class="item">
	<div class="label">
	</div>
	<div class="ui toggle  checkbox">
		<div class="ui slider checkbox">
			<?php $mostrar = $actividad->fields["nombre_actividad"]; ?>
			<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
			<label><h3><?php echo $mostrar ?></h1></label>
		</div>
</div>
</div>
<?php $actividad->MoveNext();
			}?>



			</div>


<!-- HASTA -->

		</div>
	</div>
  <div class="four wide column">
		<div id="NEUMATICOdiv" style="display: none;">
			<h1>NEUMATICO</h1>
			<div class="ui  list">
			<?php
			while(!$actividad2->EOF){
			?>

			<div class="item">
				<div class="label">
				</div>
				<div class="ui toggle  checkbox">
					<div class="ui slider checkbox">
						<?php $mostrar = $actividad2->fields["nombre_actividad"]; ?>
						<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
						<label><h3><?php echo $mostrar ?></h1></label>
					</div>
			</div>
			</div>
			<?php $actividad2->MoveNext();
						}?>



						</div>
		</div>
	</div>
  <div class="four wide column">
		<div id="MECANICOdiv" style="display: none;">
			<h1>MECANICO</h1>
			<div class="ui  list">
			<?php
			while(!$actividad3->EOF){
			?>

			<div class="item">
				<div class="label">
				</div>
				<div class="ui toggle  checkbox">
					<div class="ui slider checkbox">
						<?php $mostrar = $actividad3->fields["nombre_actividad"]; ?>
						<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
						<label><h3><?php echo $mostrar ?></h1></label>
					</div>
			</div>
			</div>
			<?php $actividad3->MoveNext();
						}?>



						</div>
		</div>
	</div>
  <div class="four wide column">
		<div id="PROYECTOSdiv" style="display: none;">
			<h1>PROYECTOS</h1>
			<div class="ui  list">
			<?php
			while(!$actividad4->EOF){
			?>

			<div class="item">
				<div class="label">
				</div>
				<div class="ui toggle  checkbox">
					<div class="ui slider checkbox">
						<?php $mostrar = $actividad4->fields["nombre_actividad"]; ?>
						<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
						<label><h3><?php echo $mostrar ?></h1></label>
					</div>
			</div>
			</div>
			<?php $actividad4->MoveNext();
						}?>



						</div>
		</div>
	</div>
  <div class="four wide column">
		<div id="GENERALdiv" style="display: none;">
			<h1>GENERAL</h1>
			<div class="ui  list">
			<?php
			while(!$actividad5->EOF){
			?>

			<div class="item">
				<div class="label">
				</div>
				<div class="ui toggle  checkbox">
					<div class="ui slider checkbox">
						<?php $mostrar = $actividad5->fields["nombre_actividad"]; ?>
						<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
						<label><h3><?php echo $mostrar ?></h1></label>
					</div>
			</div>
			</div>
			<?php $actividad5->MoveNext();
						}?>



						</div>
		</div>
	</div>
  <div class="four wide column">
		<div id="ELECTRONICOdiv" style="display: none;">
			<h1>ELETRONICO</h1>
			<div class="ui  list">
			<?php
			while(!$actividad6->EOF){
			?>

			<div class="item">
				<div class="label">
				</div>
				<div class="ui toggle  checkbox">
					<div class="ui slider checkbox">
						<?php $mostrar = $actividad6->fields["nombre_actividad"]; ?>
						<input type="checkbox" name="<?php echo $mostrar;?>" id='<?php echo $mostrar;?>' onchange="showContent(<?php echo $mostrar;?>)">
						<label><h3><?php echo $mostrar ?></h1></label>
					</div>
			</div>
			</div>
			<?php $actividad6->MoveNext();
						}?>



						</div>
		</div>
	</div>
  <div class="four wide column">
		<div id="INFRAESTRUCTURAdiv" style="display: none;">
			<h1>INFRAESTRUCTURA</h1>
			<div class="ui  list">

		</div>
	</div>

</div>









			<!-- Se termino -->
					</td>
				</tr>


	<script>

		function cerrar_mantenimiento(){
			var registro = document.getElementById("codigo_registro").value;
			var observaciones = document.getElementById("observaciones_mantenimiento").value;
			var elOrigen = document.getElementById("origen").value;
			var codigoOrden = document.getElementById("codigoOrden").value;
			var page = "formsubmit_config.php?seccion=cerrar_mantenimiento&codigo_registro=";
			page = page.concat(registro.concat("&observaciones_mantenimiento=".concat(observaciones)));
			page = page.concat("&elOrigen=".concat(elOrigen));
			page = page.concat("&codigoOrden=".concat(codigoOrden));

			window.location.replace(page);
		}


		function iniciar_mantenimiento(){

		}

	</script>

	<script type="text/javascript">
	    function showContent(dato) {

					var content = dato.name+"div";
	        element = document.getElementById(content);
					console.log(element.name);
	        check = document.getElementById(dato.name);

	        if (check.checked) {
						console.log(element);
	            element.style.display='block';
	        }
	        else {
	            element.style.display='none';
	        }
	    }
	</script>
</body>
</html>
