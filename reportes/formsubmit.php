<?php
include("../../clases/reportes_excel.php");
$repor= new reportes_excel();
$pagina="excel.php";

//echo $_POST["id_proceso"];

	$_POST["fechai"]=$_POST["fechai"]." 00:00:00";
	$_POST["fechaf"]=$_POST["fechaf"]." 23:59:59";        
	
switch ($_POST["tipo"])
{
	case "1":
	
		$sql = "SELECT 	hv.id_hoja_vida id_hoja_vida,
					max(u.user_name || ' ' || u.user_lastname) usuario,
					max(m.nombre_maquina) maquina,
					max(s.nombre_sistema) sistema,
					max(ss.nombre_sub_sistema) sub_sistema,
					max(p.nombre_pieza) pieza,
					max(a.nombre_actividad) actividad,
					max(hv.fecha_ult_mantenimiento) fecha_mantenimeinto,
					max(hv.prox_mant_est) proximo,
					max(hv.fecha_programada) progamado,
					max(hv.observaciones) observaciones,
					max(m.id_maquina) id_maquina
				FROM hoja_vida hv
					INNER JOIN usuarios u ON u.id_user = hv.id_usuario
					INNER JOIN maquinas m ON m.id_maquina = hv.id_maquina
					INNER JOIN sistemas s ON s.id_sistema = hv.id_sistema
					INNER JOIN sub_sistemas ss ON ss.id_sub_sistema = hv.id_sub_sistema
					INNER JOIN piezas p ON p.id_pieza = hv.id_sistema
					INNER JOIN actividades a ON ss.id_sub_sistema = hv.id_sub_sistema
				WHERE (hv.fecha_ult_mantenimiento between '".$_POST["fechai"]."' AND '".$_POST["fechaf"]."')
				GROUP BY id_hoja_vida
				ORDER BY id_maquina, id_hoja_vida";
		
		$repor->excel_bitacora($sql);
	break;
    /*
	case "2":
		if($_POST["opcion"]=="turno")
		{
		$sql = "SELECT *
				FROM turnos as b 
					INNER JOIN procesos as a ON (b.id_proceso= a.id_proceso) 
					INNER JOIN productos as c ON (b.id_producto= c.id_producto)
				WHERE b.id_proceso='".$_POST["id_proceso"]."' 
					AND id_turno<='".$_POST["tfin"]."' 
					AND id_turno>='".$_POST["tini"]."' 
				ORDER BY id_turno";
		}
		else
		{
			if($_POST["opcion"]=="fecha")
			{
				$sql = "SELECT *
						FROM turnos as b 
							INNER JOIN procesos as a ON (b.id_proceso= a.id_proceso) 
							INNER JOIN productos as c ON (b.id_producto= c.id_producto)
						WHERE b.id_proceso='".$_POST["id_proceso"]."' 
							AND fecha_inicio<='".$_POST["fechaf"]."' 
							AND fecha_inicio>='".$_POST["fechai"]."' 
						ORDER BY id_turno";
			}
		}
		$repor->excel_asistencia($sql,$_POST["id_proceso"]);
	break;
    
	case "3":
		if($_POST["opcion"]=="turno")
		{
			$sql = "SELECT *
					FROM turnos as b 
						INNER JOIN procesos as a ON (b.id_proceso= a.id_proceso) 
						INNER JOIN productos as c ON (b.id_producto= c.id_producto)
					WHERE b.id_proceso='".$_POST["id_proceso"]."' 
						AND id_turno<='".$_POST["tfin"]."' 
						AND id_turno>='".$_POST["tini"]."' 
					ORDER BY id_turno";
		}
		else
		{
			if($_POST["opcion"]=="fecha")
			{
				$sql = "SELECT *
						FROM turnos as b 
							INNER JOIN procesos as a ON (b.id_proceso= a.id_proceso) 
							INNER JOIN productos as c ON (b.id_producto= c.id_producto)
						WHERE b.id_proceso='".$_POST["id_proceso"]."' 
							AND fecha_inicio<='".$_POST["fechaf"]."' 
							AND fecha_inicio>='".$_POST["fechai"]."' 
						ORDER BY id_turno";
			}
		}
		$repor->excel_paradas($sql,$_POST["id_proceso"]);
	break;*/
}
?>

