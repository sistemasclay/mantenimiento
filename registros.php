<?php

include("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();
$sistemas = $datos->listar_sistemas();
$actividad = $datos->listar_actividades();



if ($_GET["codigo_orden"]) {
	$orden = $_GET["codigo_orden"];
} else {
	if ($_POST["codigo_orden"]) {
		$orden = $_POST["codigo_orden"];
	}
}

if ($_GET["origen_manufactura"]) {
	$origen = $_GET["origen_manufactura"];
} else {
	if ($_POST["origen_manufactura"]) {
		$origen = $_POST["origen_manufactura"];
	}
}

$datos_orden = $datos->detalle_orden_registro($orden);
$mantenimiento = $datos->traer_mantenimiento_orden($orden);

?>
<title>Pegasus Pro Mantenimiento</title>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/png" href="/mantenimiento/iconos/test4.ico" />
<link rel="stylesheet" type="text/css" href="estilos/myc_registros.css" />

<script type="text/javascript" src="js/jquery-3.1.1.js"></script>
<script src="js/semantic.min.js"></script>
<script type="text/javascript" src="/mantenimiento/js/sweetalert.js"></script>
<link rel="stylesheet" href="css/semantic.min.css">

<!-- Empieza Form -->

<body>


	<div class="contenedor">
		<div id="cabecera">
			<a href="/mantenimiento/index.php">
				<div id="logo" style="text-align:center"></div>
			</a>
			<?php
			if ($_SESSION["id_usuario"]) {
				require_once('menuppal.php');
			}
			?>
			<div id="lder">
				<br>

				<!-- <div id="salir"><a href="/mic/form_submit_login.php?salir=1"><img src="/mic/imgs/salir.png" width="30" height="30"><br />Salir</a></div> -->
				<!--    <div id="nomapp">COMPU M<span style="color:#c00;">&amp;</span>C<br/></div> -->
			</div>
		</div>
		<div class="contenidoRegistro">
			<form class="ui form" action="formActividades.php" method="post">
				<!-- Seccion de seleccion de SISTEMA -->
				<input type="hidden" name="origen" id="origen" value="<?php echo $origen ?>" /><!-- SI ESTE DATO ES 1 ENTONCES SE LLEGO A ESTE LUGAR DESDE EL SISTEMA DE MANUFACTURA-->
				<input type="hidden" name="codigo_registro" id="codigo_registro" value="<?php echo $mantenimiento->fields["id_registro"]; ?>" />
				<input type="hidden" name="codigoOrden" id="codigoOrden" value=" <?php echo $orden ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $datos_orden->fields["id_user"]; ?>" />
				<input type="hidden" name="maquina" id="maquina" value="<?php echo $datos_orden->fields["id_maquina"]; ?>" />
				<input type="hidden" name="sistema" id="sistema" value="<?php echo $datos_orden->fields["id_sistema"]; ?>" />
				<input type="hidden" name="pieza" id="pieza" value="<?php echo $datos_orden->fields["id_pieza"]; ?>" />
				<input type="hidden" name="sub_sistema" id="sub_sistema" value="<?php echo $datos_orden->fields["id_sub_sistema"]; ?>" />
				<input type="hidden" name="actividad" id="actividad" value="<?php echo $datos_orden->fields["id_actividad"]; ?>" />
				<input type="hidden" name="correctivo" id="correctivo" value="<?php echo $datos_orden->fields["correctivo"]; ?>" />
				<input type="hidden" name="estado" id="estado" value="999">


				<table class="tinfo">
					<th>
						SISTEMAS MANTENIMIENTO
					</th>
					<tr>

					<tr class="">
						<td>
							<!-- Aqui -->

							<div class="ui equal width grid">
								<?php
								$contador = 0;
								while (!$sistemas->EOF) { ?>
									<div class="ui toggle  checkbox">
										<div class="ui slider checkbox">
											<?php $mostrar = $sistemas->fields["nombre_sistema"]; ?>
											<input type="checkbox" name="<?php echo $mostrar; ?>" id='<?php echo $mostrar; ?>' onchange="showContent(<?php echo $mostrar; ?>)">
											<label class="coloring red">
												<h3><?php echo $mostrar ?></h1>
											</label>
										</div>
									</div>
								<?php
									$contador++;
									$sistemas->MoveNext();
								} ?>
							</div>
							<input type="hidden" id="contador" name="contador" value=" <?php echo $contador ?> ">






							<!-- HAsta aqui -->

						</td>
					</tr>
					<tr>
						<td style="text-align-last: center;">
							<input class="ui yellow button" type="submit" value=" Cerrar Orden " />
						</td>
					</tr>
				</table>

				</td>
				</tr>
				</table>
				<table class="tinfo">

					<th>Insumo</th>
					<th>Referencia</th>
					<th>Cant</th>
					<th>Borrar</th>

					<tr class="impar">
						<td>
							<datalist id="insumo">
								<?php
								$recordSet = $datos->listar_insumos();
								while (!$recordSet->EOF) {
									echo "<option class=\"oplista\" value=\"" . $recordSet->fields['id_insumo'] . "\">" . $recordSet->fields['nombre_insumo'] . "</option>";
									$recordSet->MoveNext();
								}
								?>
							</datalist>
							<input list="insumo" name="codigo_insumo" id="codigo_insumo" class="lista">
						</td>
						<td>
							<input class="ctxt" type="text" name="referencia" id="referencia" />
						</td>
						<td>
							<input class="ctxt" type="text" name="cantidad" id="cantidad" />
						</td>
						<td> <input class="ui yellow button" type="button" value="Agregar" onclick="registrarInsumo()" /></td>
					</tr>
					<tr>

					</tr>

					<?php
					$recordSet = $datos->traer_insumos_mantenimiento($mantenimiento->fields["id_registro"]);
					//echo $recordSet;
					$color = array('impar', 'par');
					$c_estilo = 0;
					$numero = 1;
					while (!$recordSet->EOF) {

						$insumo = $datos->detalle_insumo($recordSet->fields["id_insumo"]);

						if ($c_estilo % 2 != 0)
							echo '<tr class="' . $color[0] . '">';
						else
							echo '<tr class="' . $color[1] . '">';

						echo "
							<td>" . $insumo->fields['id_insumo'] . "-" . $insumo->fields['nombre_insumo'] . "</td>
							<td>" . $recordSet->fields['referencia'] . "</td>
							<td>" . $recordSet->fields['cantidad'] . "</td>
							<td><a onclick='return confirm(\"¿Seguro que desea eliminar el insumo " . $recordSet->fields['nombre_insumo'] . " del registro?\")' href=formsubmit_config.php?seccion=quitar_insumo&codigo_registro="
							. $mantenimiento->fields["id_registro"] . "&codigo_insumo=" . $insumo->fields['id_insumo'] . "><img src=\"imagenes/delete3.png\"> </a></td>
							</tr>
							";
						$c_estilo++;
						$numero++;
						$recordSet->MoveNext();
					}
					?>
					</td>
				</table>





				<!-- Se termina seccion SISTEMA -->

				<!-- Seccion de Insumos -->



				<!-- Se termina seccion Insumos -->

				<!-- Empieza Seccion de actividades por SISTEMAS -->



				<!-- Termina seccion de ACTIVIDADES -->


				<!-- Pruebas -->
				<div class="sistemas" id="sistemas">



				</div>

				<!-- Fin pruebas -->


		</div>
	</div>

	</div>
	</form>

	<!-- Termina Form -->

	<!-- script -->
	<script type="text/javascript">
		function registrarInsumo(orden) {
			var insumo = document.getElementById('codigo_insumo');

			var referencia = document.getElementById("referencia");
			var cantidad = document.getElementById("cantidad");
			var codigoRegistro = document.getElementById('codigo_registro');
			$.ajax({
				url: 'controladorBD.php',
				type: 'post',
				data: {
					estado: 9,
					infoRegistro: codigoRegistro.value,
					infoInsumo: insumo.value,
					infoReferencia: referencia.value,
					infoCantidad: cantidad.value
				},
				success: function(data) {
					res = JSON.parse(data);
					if (res.resultado) {
						alertaInicioCorrecto();
						window.location.replace("registros.php?codigo_orden=245");
					}
				}
			});


		}

		function traerFechaProgramada(codigoOrden) {
			$.ajax({
				url: 'controladorBD.php',
				type: 'post',
				data: {
					estado: 5,
					orden: codigoOrden
				},
				success: function(data) {
					res = JSON.parse(data);
					if (res.resultado) {
						var info = res.resultado;
						return info;
					} else {
						alert("No paso nada");
					}
				}

			});

		}


		function showContent(dato1) {
			dato = dato1;
			var content = dato.name + "div";
			check = document.getElementById(dato.name);

			if (check.checked) {

				genera_tabla(dato);
				validarInformacion(dato);

			} else {

				var divTest = document.getElementById('sistemas');
				var idTabla = dato.id + "tabla";
				var idDiv = dato.id + "Id";

				var divContenedor = document.getElementById(idTabla);
				var divContenedor2 = document.getElementById(idDiv);
				// divTest.removeChild(divContenedor);
				divTest.removeChild(divContenedor2);
			}
		}

		function genera_tabla(dato) {

			//Creo el div CONTENEDOR

			var atributo = document.createAttribute("class");
			atributo.value = "tinfo";

			var id = document.createAttribute("id");
			id.value = dato.name + "Id";
			var divContenedor = document.createElement("div");
			var divTes = document.getElementById('sistemas');
			var h1 = document.createElement("h1");
			h1.innerHTML = dato.name;

			// <input type="checkbox" name="<?php echo $mostrar; ?>" id='<?php echo $mostrar; ?>' onchange="showContent(<?php echo $mostrar; ?>)">



			divContenedor.setAttributeNode(atributo);
			divContenedor.setAttributeNode(id);


			divContenedor.appendChild(h1);

			divTes.appendChild(divContenedor);



			// 	$.ajax({
			//     type: "POST",
			//     url: "registros0.php",
			//     data: dato,
			//     dataType: 'json'
			// });
		}

		function cerrar_mantenimiento() {
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

		function validarInformacion(dato) {
			var nombreSistema = dato.name;

			$.ajax({
				url: 'controladorBD.php',
				type: 'post',
				data: {
					estado: 1,
					sistema: nombreSistema
				},
				success: function(data) {
					var res = JSON.parse(data);
					if (res.resultado) {
						var info = res.resultado;
						for (var i = 0; i < info.length; i++) {
							//Crear el div que da el estilo para despues insertar el input

							var divCheckbox = document.createElement("div");
							var divCheckboxClase = document.createAttribute("class");
							divCheckboxClase.value = "ui checkbox";
							divCheckbox.setAttributeNode(divCheckboxClase);


							var input = document.createElement("input");
							var atributo = document.createAttribute("type");
							var name = document.createAttribute("name");
							var id = document.createAttribute("id");
							var label = document.createElement("label");
							var valor = document.createAttribute("value");

							valor.value = info[i].id;
							label.innerHTML = info[i].descripcion_actividad;
							atributo.value = "checkbox";
							name.value = info[i].nombre_actividad;
							id.value = info[i].nombre_actividad + "idActividad";
							input.setAttributeNode(atributo);
							input.setAttributeNode(id);
							input.setAttributeNode(name);
							input.setAttributeNode(valor);

							var divContenedor = document.getElementById(dato.name + "Id");
							divContenedor.appendChild(divCheckbox);

							divCheckbox.appendChild(input);
							divCheckbox.appendChild(label);



						}

					} else {
						alertaIngreseDatos(nombreSistema);
					}

				}


			});


		};

		function alertaIngreseDatos(nombreSistema) {
			swal({
				title: "No existen Actividades asociadas al sistema " + nombreSistema,
				text: "",
				icon: "error",
			});
		}

		function alertaInicioCorrecto() {
			swal({
				title: "Registro Correcto",
				text: "Exitoso!",
				icon: "success",
			});
		}
	</script>