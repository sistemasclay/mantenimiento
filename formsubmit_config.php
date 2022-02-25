<?php session_start(); ?>
<script type="text/javascript">
	function alertaRegistroExitoso() {
		swal({
			title: "Se ha registrado la orden de trabajo",
			text: "Exitoso!",
			icon: "success",
		});
	};
</script>
<?php

include("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();
$pagina = "principal.php?seccion=" . $seccion;

if ($_GET["seccion"]) {
	$seccion = $_GET["seccion"];
} else {
	if ($_POST["seccion"]) {
		$seccion = $_POST["seccion"];
	}
}

switch ($seccion) {
	case "maquinas":

		if ($datos->comprobar_proceso($_POST["codigo_proceso"])) {
			echo "Actualizando...";
			if ($_SESSION["grade"] >= 3) {
				$datos->actualizar_proceso($_POST["codigo_proceso"], $_POST["nombre_maquina"], $_POST["descripcion_maquina"], $_POST["horometro"], $_POST["estado_maquina"]);
			} else {
				$datos->actualizar_proceso_2($_POST["codigo_proceso"], $_POST["nombre_maquina"], $_POST["descripcion_maquina"], $_POST["estado_maquina"]);
			}
			echo "Proceso " . $_POST["nombre_maquina"] . " Actualizado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "Registrando...";
			$datos->registro_proceso($_POST["codigo_proceso"], $_POST["nombre_maquina"], $_POST["descripcion_maquina"], $_POST["horometro"], $_POST["estado_maquina"]);
			echo "Proceso " . $_POST["nombre_maquina"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_proceso":
		$pagina = "principal.php?seccion=maquinas";
		echo "Eliminando..";
		$datos->borrar_proceso($_GET["codigo_proceso"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";

		break;

	case "usuarios":
		if ($datos->comprobar_usuario($_POST["codigo_empleado"])) {
			echo "actualizando...";
			$datos->actualizar_usuarios($_POST["codigo_empleado"], $_POST["user_name"], $_POST["user_lastname"], $_POST["contra_empleado"], $_POST["nivel_empleado"], $_POST["estado_empleado"]);
			echo "Informacion del usuario :" . $_POST["user_name"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_usuarios($_POST["codigo_empleado"], $_POST["user_name"], $_POST["user_lastname"], $_POST["contra_empleado"], $_POST["nivel_empleado"], $_POST["estado_empleado"]);
			echo "Usuario " . $_POST["user_name"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_empleado":
		$pagina = "principal.php?seccion=usuarios";
		echo "Eliminando..";
		$datos->borrar_usuario($_GET["codigo_empleado"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "sistemas":
		if ($datos->comprobar_sistema($_POST["codigo_sistema"])) {
			echo "actualizando...";
			$datos->actualizar_sistema($_POST["codigo_sistema"], $_POST["nombre_sistema"], $_POST["descripcion_sistema"], $_POST["estado"]);
			echo "Informacion del sistema :" . $_POST["nombre_sistema"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_sistema($_POST["codigo_sistema"], $_POST["nombre_sistema"], $_POST["descripcion_sistema"], $_POST["estado"]);
			echo "Sistema " . $_POST["nombre_sistema"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_sistema":
		$pagina = "principal.php?seccion=sistemas";
		echo "Eliminando..";
		$datos->borrar_sistema($_GET["codigo_sistema"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "sub_sistemas":
		if ($datos->comprobar_sub_sistema($_POST["codigo_sub_sistema"])) {
			echo "actualizando...";
			$datos->actualizar_sub_sistema($_POST["codigo_sub_sistema"], $_POST["nombre_sub_sistema"], $_POST["descripcion_sub_sistema"], $_POST["estado"]);
			echo "Informacion del sub-sistema :" . $_POST["nombre_sub_sistema"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_sub_sistema($_POST["codigo_sub_sistema"], $_POST["nombre_sub_sistema"], $_POST["descripcion_sub_sistema"], $_POST["estado"]);
			echo "Sub-sistema " . $_POST["nombre_sub_sistema"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_sub_sistema":
		$pagina = "principal.php?seccion=sub_sistemas";
		echo "Eliminando..";
		$datos->borrar_sub_sistema($_GET["codigo_sub_sistema"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "actividades":
		if ($datos->comprobar_actividades($_POST["codigo_actividad"])) {
			echo "actualizando...";
			$datos->actualizar_actividades($_POST["codigo_actividad"], $_POST["nombre_actividad"], $_POST["descripcion_actividad"], $_POST["estado"]);
			echo "Informacion de la actividad :" . $_POST["nombre_actividad"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_actividades($_POST["codigo_actividad"], $_POST["nombre_actividad"], $_POST["descripcion_actividad"], $_POST["estado"]);
			echo "Actividad " . $_POST["nombre_actividad"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_actividad":
		$pagina = "principal.php?seccion=actividades";
		echo "Eliminando..";
		$datos->borrar_actividad($_GET["codigo_actividad"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "insumos":
		if ($datos->comprobar_insumo($_POST["codigo_insumo"])) {
			echo "actualizando...";
			$datos->actualizar_insumos($_POST["codigo_insumo"], $_POST["nombre_insumo"], $_POST["descripcion_insumo"], $_POST["unidad_insumo"], $_POST["estado"]);
			echo "Informacion del insumo :" . $_POST["nombre_insumo"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_insumos($_POST["codigo_insumo"], $_POST["nombre_insumo"], $_POST["descripcion_insumo"], $_POST["unidad_insumo"], $_POST["estado"]);
			echo "Insumo " . $_POST["nombre_insumo"] . " Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_insumo":
		$pagina = "principal.php?seccion=insumos";
		echo "Eliminando..";
		$datos->borrar_insumo($_GET["codigo_insumo"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "piezas":
		if ($datos->comprobar_piezas($_POST["codigo_pieza"])) {
			echo "actualizando...";
			$datos->actualizar_piezas($_POST["codigo_pieza"], $_POST["nombre_pieza"], $_POST["descripcion_pieza"], $_POST["estado"]);
			echo "Informacion de la pieza :" . $_POST["nombre_pieza"] . " Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";
			$datos->registro_piezas($_POST["codigo_pieza"], $_POST["nombre_pieza"], $_POST["descripcion_pieza"], $_POST["estado"]);
			echo "Pieza " . $_POST["nombre_pieza"] . " Registrada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_pieza":
		$pagina = "principal.php?seccion=piezas";
		echo "Eliminando...";
		$datos->borrar_pieza($_GET["codigo_pieza"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "estandares":
		if ($datos->comprobar_estandar($_POST["codigo_proceso"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"]) or $datos->comprobar_estandar2($_POST["codigo_estandar"])) {
			echo "actualizando..." . '</br>';
			$estandar = $datos->detalle_estandar($_POST["codigo_proceso"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"]);
			$codigo_estandar = $estandar->fields["codigo_estandar"];
			$datos->actualizar_estandar($codigo_estandar, $_POST["codigo_proceso"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["tiempo_estandar"], $_POST["frecuencia"], $_POST["unidad_frecuencia"], $_POST["estado"]);
			echo "Informacion del Estandar Actualizada";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {

			echo "Registrando..." . '</br>';
			$datos->registro_estandar($_POST["codigo_proceso"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["tiempo_estandar"], $_POST["frecuencia"], $_POST["unidad_frecuencia"], $_POST["estado"]);
			echo "Nuevo Estandar Registrado";
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		}
		break;

	case "eliminar_estandar":
		$pagina = "principal.php?seccion=estandares";
		echo "Eliminando..";
		$datos->borrar_estandar($_GET["codigo_estandar"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "ordenes_trabajo":

		if ($_POST["codigo_orden"]) {
			echo "actualizando...";
			$datos->actualizar_orden_trabajo($_POST["codigo_orden"], $_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"], $_POST["observaciones"], $_POST["correctivo"]);
			echo "Informacion de la Orden :" . $_POST["codigo_orden"] . " Actualizada";
			if ($datos->comprobar_mantenimiento_orden($_POST["codigo_orden"])) {
				$datos->actualizar_mantenimiento_automatico($_POST["codigo_orden"], $_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"]);
			} else {
				$datos->registro_mantenimiento_automatico($_POST["codigo_orden"], $_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"]);
			}
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		} else {
			echo "registrando...";

			$datos->registro_orden_trabajo($_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"], $_POST["observaciones"], $_POST["correctivo"]);
			$orden = $datos->ultima_orden();
			if ($datos->comprobar_mantenimiento_orden($orden)) {
				$datos->actualizar_mantenimiento_automatico($orden, $_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"]);
			} else {
				$datos->registro_mantenimiento_automatico($orden, $_POST["codigo_maquina"], $_POST["codigo_sistema"], $_POST["codigo_sub_sistema"], $_POST["codigo_pieza"], $_POST["codigo_actividad"], $_POST["codigo_usuario"], $_POST["fecham"]);
			}
			echo "Nueva orden de trabajo registrada";
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=principal.php?seccion=ordenes_trabajo\">";
		}
		break;

	case "eliminar_orden":
		$pagina = "principal.php?seccion=ordenes_trabajo";
		echo "Eliminando..";
		$datos->borrar_orden_trabajo($_GET["codigo_orden"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";
		break;

	case "agregar_insumo":

		if ($_POST["cantidad"]) {
			if ($datos->comprobar_insumo_mantenimiento($_POST["codigo_registro"], $_POST["codigo_insumo"])) {
				echo "actualizando...";
				$datos->actualizar_insumo_mantenimiento($_POST["codigo_registro"], $_POST["codigo_insumo"], $_POST["cantidad"], $_POST["referencia"]);
				$orden = $datos->traer_orden_registro($_POST["codigo_registro"]);
				$datos->cerrar_mantenimiento($_POST["codigo_registro"], $_POST["observaciones_mantenimiento"]);
				echo "Insumo: " . $_POST["codigo_insumo"] . " Actualizado";
				//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$pagina."\">";
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=registros.php?codigo_orden=" . $orden . "\">";
			} else {
				echo "registrando...";
				$datos->registrar_insumo_mantenimiento($_POST["codigo_registro"], $_POST["codigo_insumo"], $_POST["cantidad"], $_POST["referencia"]);
				$orden = $datos->traer_orden_registro($_POST["codigo_registro"]);
				$datos->cerrar_mantenimiento($_POST["codigo_registro"], $_POST["observaciones_mantenimiento"]);
				echo "Insumo: " . $_POST["codigo_insumo"] . " Registrado";
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=registros.php?codigo_orden=" . $orden . "\">";
			}
		} else {
			$orden = $datos->traer_orden_registro($_POST["codigo_registro"]);
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=registros.php?codigo_orden=" . $orden . "\">";
		}

		break;

	case "quitar_insumo":

		echo "Eliminando..";
		$datos->borrar_insumo_mantenimiento($_GET["codigo_registro"], $_GET["codigo_insumo"]);
		$orden = $datos->traer_orden_registro($_GET["codigo_registro"]);
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=registros.php?codigo_orden=" . $orden . "\">";

		break;

	case "cerrar_mantenimiento":

		$codigoRegistro = $_GET["codigo_registro"];
		$codigoOrden = $_GET["codigoOrden"];
		$datos->actualizar_fechaFin_orden($codigoOrden);

		// echo $codigoRegistro;
		echo "actualizando...";
		$datos->cerrar_mantenimiento($_GET["codigo_registro"], $_GET["observaciones_mantenimiento"]);

		echo "cerro";
		$orden = $datos->traer_orden_registro($_GET["codigo_registro"]);
		if ($_GET["elOrigen"]) {
			$datos->cerrarTurnoManufactura($orden);
		}

		if (!$_POST["correctivo"]) {
			//$datos->registro_orden_trabajo_automatico($_POST["maquina"],$_POST["sistema"],$_POST["sub_sistema"],$_POST["pieza"],$_POST["actividad"]);
			//$orden = $datos->ultima_orden();
			//$datos->registro_mantenimiento_automatico2($orden,$_POST["maquina"],$_POST["sistema"],$_POST["sub_sistema"],$_POST["pieza"],$_POST["actividad"]);
		}
		echo "La orden de Trabajo " . $_GET["codigo_registro"] . " se ha cerrado exitosamente";
		if ($_SESSION["grade"] >= 2) {
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=principal.php?seccion=ordenes_trabajo\">";
			//echo "<meta http-equiv=\"refresh\" content=\"1;URL=registros.php?codigo_orden=".$orden."\">";
		} else {
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=../mic/mic_central/seleccion_proceso.php\">";
			//echo "<meta http-equiv=\"refresh\" content=\"1;URL=registros.php?codigo_orden=".$orden."\">";
		}


		break;

	case "hv_maquinas":
		$pagina = "principal.php?seccion=hv_maquinas";
		echo "Registrando...";
		$datos->registrar_HV_maquina($_POST["maquina"], $_POST["obs_hv"], $_POST["usuario"]);
		//$datos->borrar_insumo_mantenimiento($_GET["codigo_registro"],$_GET["codigo_insumo"]);
		//$orden = $datos->traer_orden_registro($_GET["codigo_registro"]);
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $pagina . "\">";

		break;

	case "iniciar_orden":
		$codigoOrden = $_GET["codigo_orden"];
		echo "Iniciando Orden de Trabajo";

		$resultado = $datos->iniciar_mantenimiento($codigoOrden);
		if ($resultado) {
			echo "Todo va bien";;
		} else {
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=principal.php?seccion=ordenes_trabajo\">";
		}


		break;

	default:
		echo "gg";
		break;
}
?>