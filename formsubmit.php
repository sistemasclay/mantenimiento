<?php
include("clases/reportes_excel.php");
$repor= new reportes_excel();
$pagina="excel.php";

//echo $_POST["id_proceso"];

	$_POST["fechai"]=$_POST["fechai"]." 00:00:00";
	$_POST["fechaf"]=$_POST["fechaf"]." 23:59:59";

switch ($_POST["tipo"])
{
	case "1":

		$sql = "SELECT 	hv.id_registro id_registro,
										hv.id_orden_trabajo ordenes_trabajo,
					max(u.user_name || ' ' || u.user_lastname) usuario,
					max(m.nombre_maquina) maquina,
					max(s.nombre_sistema) sistema,
					max(ss.nombre_sub_sistema) sub_sistema,
					max(p.nombre_pieza) pieza,
					max(a.descripcion_actividad) actividad,
					max(hv.fecha_ult_mantenimiento) fecha_mantenimeinto,
					max(hv.prox_mant_est) proximo,
					max(hv.fecha_programada) progamado,
					max(hv.observaciones) observaciones,
					max(hv.fecha_registro) fecha_registro
				FROM registros hv
					INNER JOIN usuarios u ON u.id_user = hv.id_usuario
					INNER JOIN maquinas m ON m.id_maquina = hv.id_maquina
					INNER JOIN sistemas s ON s.id_sistema = hv.id_sistema
					INNER JOIN sub_sistemas ss ON ss.id_sub_sistema = hv.id_sub_sistema
					INNER JOIN piezas p ON p.id_pieza = hv.id_pieza
					INNER JOIN actividades a ON a.id_actividad = hv.id_actividad
				WHERE (hv.fecha_registro between '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."')
				GROUP BY id_registro
				ORDER BY id_registro";

		$repor->excel_bitacora($sql);
	break;
	case"2":
		$sql = "SELECT 	ot.id_orden_trabajo,
						m.nombre_maquina,
						s.nombre_sistema,
						ss.nombre_sub_sistema,
						p.nombre_pieza,
						a.nombre_actividad,
						(u.user_name || ' ' || u.user_lastname) usuario,
						ot.estado estado_orden,
						extract(month from ot.fecha_orden) mes,
						ot.fecha_orden,
						hv.fecha_ult_mantenimiento,
						hv.fecha_registro,
						ot.correctivo,
						(ot.fecha_fin_orden - ot.fecha_anterior) duracion
				FROM ordenes_trabajo ot
					INNER JOIN maquinas m ON m.id_maquina = ot.id_maquina
					INNER JOIN sistemas s ON s.id_sistema = ot.id_sistema
					INNER JOIN sub_sistemas ss ON ss.id_sub_sistema = ot.id_sub_sistema
					INNER JOIN piezas p ON p.id_pieza = ot.id_pieza
					INNER JOIN actividades a ON a.id_actividad = ot.id_actividad
					LEFT JOIN registros hv ON hv.id_orden_trabajo = ot.id_orden_trabajo
					LEFT JOIN usuarios u ON u.id_user = ot.id_usuario
				WHERE (ot.fecha_orden between '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."')
				ORDER BY ot.id_orden_trabajo
				";

		$repor->excel_cronograma($sql,$_POST["fechai"],$_POST["fechaf"]);
	break;
	case"3":
		if ($_POST["todas"])
		{
			$sql = "SELECT	m.id_maquina id_maquina,
						m.nombre_maquina nombre_maquina,
						u.id_user id_usuario,
						u.user_name nombre_usuario,
						hv.fecha fecha,
						hv.notas notas
				FROM 	hv_maquinas hv
					INNER JOIN maquinas m ON m.id_maquina = hv.id_maquina
					INNER JOIN usuarios u ON u.id_user = hv.id_usuario
				WHERE (hv.fecha between '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."')
				ORDER BY hv.id_maquina ASC, hv.fecha DESC
				";

			$repor->excel_hoja_vida_todas($sql,$_POST["fechai"],$_POST["fechaf"]);
		}
		else
		{
			$sql = "SELECT	m.id_maquina id_maquina,
						m.nombre_maquina nombre_maquina,
						u.id_user id_usuario,
						u.user_name nombre_usuario,
						hv.fecha fecha,
						hv.notas notas
				FROM 	hv_maquinas hv
					INNER JOIN maquinas m ON m.id_maquina = hv.id_maquina
					INNER JOIN usuarios u ON u.id_user = hv.id_usuario
				WHERE (hv.fecha between '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."') AND hv.id_maquina = ".$_POST["cod_maquina"]."
				ORDER BY hv.fecha
				";

			$repor->excel_hoja_vida($sql,$_POST["fechai"],$_POST["fechaf"],$_POST["cod_maquina"]);
		}		
	break;
	case '4':
	$fechai = $_POST["fechai"];
	$fechaf = $_POST["fechaf"];
	$cod_maquina = $_POST["cod_maquina"];
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=principal.php?seccion=reportes&cod_maquina='$cod_maquina'&activo=1&
					fechai='$fechai'&fechaf='$fechaf'\">";

		break;
	default:
		echo "gg";
	break;
}
?>
