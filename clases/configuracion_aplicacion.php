<?php

/**
 * Description of configuracion_aplicacion
 * esta clase esta asociada a las configuracion de la informacion del
 * sistema.
 * @author Juan Pablo Giraldo
 */
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");
class configuracion_aplicacion
{

	//funciones para las fechas

	function getValores($fecha)
	{
		list($fechaT, $horaT) = explode(" ", $fecha);

		list($anno, $mes, $dia) = explode("-", $fechaT);
		list($hora, $min, $seg) = explode(":", $horaT);

		$valores = array("anno" => $anno, "mes" => $mes, "dia" => $dia, "hora" => $hora, "min" => $min, "seg" => $seg);
		return $valores;
	}

	function getTimeUNIXall($anno, $mes, $dia, $hora, $min, $seg)
	{
		return mktime($hora, $min, $seg, $mes, $dia, $anno);
	}

	function getFechaYMD_HIS($timeStamp)
	{
		$fecha = getFechaYMD($timeStamp) . ' ' . getFechaHIS($timeStamp);
		return $fecha;
	}

	function getTimeUNIX_BD($fecha)
	{
		$valores = $this->getValores($fecha);
		return $this->getTimeUNIXall($valores['anno'], $valores['mes'], $valores['dia'], $valores['hora'], $valores['min'], $valores['seg']);
	}
	function getDifFechas1($timeStamp1, $timeStamp2)
	{
		return floor(abs($timeStamp1 - $timeStamp2)); //3600 =>60 * 60
	}
	function getDifFechastodo($fecha1, $fecha2)
	{
		//return $this->getDifFechasH($this->getTimeUNIX_BD($fecha1), $this->getTimeUNIX_BD($fecha2));
		$fecha11 = $this->getValores($fecha1);
		$fecha22 = $this->getValores($fecha2);
		return $this->getDifFechas1($this->getTimeUNIXall($fecha11["anno"], $fecha11["mes"], $fecha11["dia"], $fecha11["hora"], $fecha11["min"], $fecha11["seg"]), $this->getTimeUNIXall($fecha22["anno"], $fecha22["mes"], $fecha22["dia"], $fecha22["hora"], $fecha22["min"], $fecha22["seg"]));
	}

	//funciones para configuracion de la aplicación

	function estado_configuracion($id_config)
	{

		include("conexion.php");
		$sql =	"SELECT * FROM configuracion WHERE id_configuracion = $id_config";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["estado"];
	}
	function actualizar_fechaFin_orden($codigo)
	{
		$fechaactual = date("Y-m-d H:i:s");
		$sql = " UPDATE ordenes_trabajo SET fecha_fin_orden = '$fechaactual' WHERE id_orden_trabajo= '$codigo'";

		include("conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function verificarOrdenP($codigo)
	{
		$sql = "SELECT fecha_anterior from ordenes_trabajo WHERE id_orden_trabajo = '$codigo'";
		include("conexion.php");

		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["fecha_anterior"];
	}
	function traerFechaOrdenTrabajo($codigo)
	{
		$sql = "SELECT fecha_orden from ordenes_trabajo WHERE id_orden_trabajo = '$codigo'";
		include("conexion.php");

		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["fecha_orden"];
	}


	//funciones para los procesos

	function listar_procesos()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM maquinas WHERE estado=1 ORDER BY id_maquina";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function registro_proceso($codigo, $nombre, $descripcion, $horometro, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "insert into maquinas(id_maquina,nombre_maquina,descripcion_maquina,horometro,estado)values($codigo,'$nombre','$descripcion',$horometro,$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '';
		}
		$conexion_pg->Close();
	}

	function registro_proceso_2($codigo, $nombre, $descripcion, $horometro, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO maquinas(id_maquina,nombre_maquina,descripcion_maquina,horometro,estado)values($codigo,'$nombre','$descripcion',$horometro,$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function comprobar_proceso($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM maquinas WHERE id_maquina=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_maquina"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function actualizar_proceso($codigo, $nombre, $descripcion, $horometro, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");  //id_planta,ip_maquina,maximo_horas,captura
		$sql = "UPDATE maquinas SET id_maquina=$codigo, nombre_maquina='$nombre', descripcion_maquina='$descripcion', horometro=$horometro, estado=$estado WHERE id_maquina=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_proceso_2($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");  //id_planta,ip_maquina,maximo_horas,captura
		$sql = "UPDATE maquinas SET nombre_maquina='$nombre',descripcion_maquina='$descripcion',estado=$estado WHERE id_maquina=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_proceso($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM maquinas WHERE id_maquina = $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_proceso($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE maquinas SET estado=0 WHERE id_maquina = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para los sistemas

	function listar_sistemas()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM sistemas WHERE estado=1 ORDER BY id_sistema";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
	function listar_actividades_cerrar($sistema)
	{
		include("conexion.php");
		$sql =	"SELECT id_actividad, nombre_actividad, descripcion_actividad
		 							FROM actividades ac INNER JOIN sistemas si
									ON si.id_sistema = ac.id_sistema WHERE ac.estado=1 AND  si.nombre_sistema ='$sistema' ";

		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$i = 0;

		while (!$result->EOF) {

			$myArray[$i]['id'] = $result->fields["id_actividad"];
			$myArray[$i]['nombre_actividad'] = $result->fields["nombre_actividad"];
			$myArray[$i]['descripcion_actividad'] = $result->fields["descripcion_actividad"];
			$result->MoveNext();
			$i++;
		}


		return $myArray;
	}
	function listar_actividades_cerrar2($sistema)
	{
		include("conexion.php");
		$sql =	"SELECT id_actividad, nombre_actividad
									FROM actividades ac INNER JOIN sistemas si
									ON si.id_sistema = ac.id_sistema WHERE ac.estado=1 AND  si.nombre_sistema ='$sistema' ";

		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$i = 0;



		return $result;
	}
	function listar_sistemas_2()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM sistemas WHERE estado=1 ORDER BY id_sistema";

		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$i = 0;

		while (!$result->EOF) {

			$myArray[$i]['id'] = $result->fields["id_sistema"];
			$myArray[$i]['nombre_sistema'] = $result->fields["nombre_sistema"];
			$result->MoveNext();
			$i++;
		}


		return $myArray;
	}

	function comprobar_sistema($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM sistemas WHERE id_sistema=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_sistema"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_sistema($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO sistemas(id_sistema,nombre_sistema,descripcion_sistema,estado)values($codigo,'$nombre','$descripcion',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_sistema($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE sistemas SET nombre_sistema='$nombre',descripcion_sistema='$descripcion',estado=$estado WHERE id_sistema=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_sistema($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM sistemas WHERE id_sistema = $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_sistema($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE sistemas SET estado=0 WHERE id_sistema = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para los sub_sistemas

	function listar_sub_sistemas()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM sub_sistemas WHERE estado=1 ORDER BY id_sub_sistema";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_sub_sistema($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM sub_sistemas WHERE id_sub_sistema=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_sub_sistema"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_sub_sistema($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO sub_sistemas(id_sub_sistema,nombre_sub_sistema,descripcion_sub_sistema,estado)values($codigo,'$nombre','$descripcion',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_sub_sistema($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE sub_sistemas SET nombre_sub_sistema='$nombre',descripcion_sub_sistema='$descripcion',estado=$estado WHERE id_sub_sistema=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_sub_sistema($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM sub_sistemas WHERE id_sub_sistema = $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_sub_sistema($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE sub_sistemas SET estado=0 WHERE id_sub_sistema = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para el usuarios
	function listar_usuarios()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM usuarios WHERE estado=1 AND grade <> 4 ORDER BY id_user";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
	function listar_usuarios_con_privilegio()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM usuarios WHERE estado=1 AND grade < 3 ORDER BY id_user";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_usuario($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM usuarios WHERE id_user=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_user"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_usuarios($codigo, $nombre, $apellido, $contrasena, $nivel, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		//$contrasena=md5($contrasena);
		include("conexion.php");
		$sql = "insert into usuarios(id_user,user_name,user_lastname,password,grade,estado)values($codigo,'$nombre','$apellido','$contrasena','$nivel',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_usuarios($codigo, $nombre, $apellido, $contrasena, $nivel, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		//$contrasena=md5($contrasena);
		include("conexion.php");
		$sql = "UPDATE usuarios SET user_name='$nombre',user_lastname='$apellido',password='$contrasena',grade='$nivel',estado=$estado WHERE id_user=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_usuario($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM usuarios WHERE id_user= $codigo and grade <> 4";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_usuario($codigo)
	{
		include("conexion.php");
		$sql = "update usuarios set estado = 0 WHERE id_user = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para los insumos
	function listar_insumos()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM insumos WHERE estado=1 ORDER BY id_insumo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_insumo($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM insumos WHERE id_insumo=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_insumo"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_insumos($codigo, $nombre, $descripcion, $unidad, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO insumos(id_insumo,nombre_insumo,descripcion_insumo,unidad_insumo,estado)values($codigo,'$nombre','$descripcion','$unidad',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_insumos($codigo, $nombre, $descripcion, $unidad, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE insumos SET nombre_insumo='$nombre',descripcion_insumo='$descripcion',unidad_insumo='$unidad',estado=$estado WHERE id_insumo=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_insumo($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM insumos WHERE id_insumo= $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_insumo($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE insumos SET estado = 0 WHERE id_insumo = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para los piezas
	function listar_piezas()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM piezas WHERE estado=1 ORDER BY id_pieza";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_piezas($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM piezas WHERE id_pieza=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_pieza"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_piezas($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO piezas(id_pieza,nombre_pieza,descripcion_pieza,estado)values($codigo,'$nombre','$descripcion',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_piezas($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE piezas SET nombre_pieza='$nombre',descripcion_pieza='$descripcion',estado=$estado WHERE id_pieza=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_pieza($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM piezas WHERE id_pieza= $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_pieza($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE piezas SET estado = 0 WHERE id_pieza = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//FUNCIONES PARA LAS ACTIVIDADES
	function listar_actividades()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM actividades WHERE estado=1 ORDER BY id_actividad";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_actividades($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM actividades WHERE id_actividad=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_actividad"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_actividades($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO actividades(id_actividad,nombre_actividad,descripcion_actividad,estado)values($codigo,'$nombre','$descripcion',$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_actividades($codigo, $nombre, $descripcion, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE actividades SET nombre_actividad='$nombre',descripcion_actividad='$descripcion',estado=$estado WHERE id_actividad=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_actividad($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM actividades WHERE id_actividad= $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_actividad($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE actividades SET estado = 0 WHERE id_actividad = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//FUNCIONES DE LOS ESTANDARES
	function listar_estandares()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM estandares WHERE estado=1 ORDER BY id_maquina, id_sistema, id_sub_sistema, id_pieza, id_actividad";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_estandar($maquina, $sistema, $sub_sistema, $pieza, $actividad)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM estandares WHERE id_maquina=$maquina AND id_sistema=$sistema AND id_sub_sistema=$sub_sistema AND id_pieza=$pieza AND id_actividad=$actividad";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_estandar"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function comprobar_estandar2($estandar)
	{
		if ($estandar) {
			include("conexion.php");
			$sql =	"SELECT * FROM estandares WHERE id_estandar=$estandar";
			$result = $conexion_pg->Execute($sql);
			if ($result === false) {
				echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
			}
			$conexion_pg->Close();
			$resultado = $result->fields["id_estandar"];
			$result->MoveNext();
			$result->Close();
			if ($resultado) {				// Es que  existe
				return true;
			} else {				// Es que no existe
				return false;
			}
		} else {
			return false;
		}
	}

	function registro_estandar($maquina, $sistema, $sub_sistema, $pieza, $actividad, $std_mant, $frecuencia, $unid_frec, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO estandares(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,frecuencia,std_mantenimiento,unidad_frecuencia,estado)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,$frecuencia,$std_mant,$unid_frec,$estado)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_estandar($id_estandar, $maquina, $sistema, $sub_sistema, $pieza, $actividad, $std_mant, $frecuencia, $unid_frec, $estado)
	{
		if ($estado == "on") {
			$estado = 1;
		} else {
			$estado = 0;
		}
		include("conexion.php");
		$sql = "UPDATE estandares SET id_maquina=$maquina,id_sistema=$sistema,id_sub_sistema=$sub_sistema,id_pieza=$pieza,id_actividad=$actividad,frecuencia=$frecuencia,std_mantenimiento=$std_mant,unidad_frecuencia=$unid_frec,estado=$estado WHERE id_estandar=$id_estandar";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function detalle_estandar($maquina, $sistema, $sub_sistema, $pieza, $actividad)
	{
		include("conexion.php");
		$sql = "SELECT * FROM estandares WHERE id_maquina=$maquina AND id_sistema=$sistema AND id_sub_sistema=$sub_sistema AND id_pieza=$pieza AND id_actividad=$actividad";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function detalle_estandar2($estandar)
	{
		include("conexion.php");
		$sql = "SELECT * FROM estandares WHERE id_estandar=$estandar";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_estandar($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE estandares SET estado = 0 WHERE id_estandar = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//funciones para los ordenes de trabajo
	function listar_ordenes_trabajo()
	{
		include("conexion.php");
		$sql =	"SELECT * FROM ordenes_trabajo ORDER BY fecha_orden DESC, id_orden_trabajo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function listar_ordenes_trabajo_usuario($usuario)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM ordenes_trabajo WHERE id_usuario = $usuario ORDER BY fecha_orden DESC, id_orden_trabajo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_orden_trabajo($codigo)
	{
		include("conexion.php");
		$sql =	"SELECT * FROM ordenes_trabajo WHERE id_orden_trabajo=$codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al comprobar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_orden_trabajo"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registro_orden_trabajo($maquina, $sistema, $sub_sistema, $pieza, $actividad, $usuario, $fecha, $observaciones, $correctivo)
	{
		if ($correctivo == "on") {
			$correctivo = 1;
		} else {
			$correctivo = 0;
		}
		include("conexion.php");
		$sql = "INSERT INTO ordenes_trabajo(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,id_usuario,fecha_orden,observaciones,correctivo)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,$usuario,'$fecha','$observaciones',$correctivo)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_orden_trabajo($orden, $maquina, $sistema, $sub_sistema, $pieza, $actividad, $usuario, $fecha, $observaciones, $correctivo)
	{
		if ($correctivo == "on") {
			$correctivo = 1;
		} else {
			$correctivo = 0;
		}
		include("conexion.php");
		$sql = "UPDATE ordenes_trabajo SET id_maquina='$maquina', id_sistema='$sistema', id_sub_sistema='$sub_sistema',id_pieza='$pieza',id_actividad='$actividad',id_usuario='$usuario',fecha_orden='$fecha',observaciones='$observaciones', correctivo=$correctivo WHERE id_orden_trabajo=$orden";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}
	function detalle_orden_trabajo_f($fechai, $fechaf, $cod_maquina)
	{
		include("conexion.php");
		$sql = "	SELECT * from ordenes_trabajo where  id_maquina=$cod_maquina and estado = 2 and fecha_orden BETWEEN $fechai and $fechaf ";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo "error" . $conexion_pg->ErrorMsg();
		}
		$conexion_pg->Close();
		return $result;
	}
	function detalle_orden_trabajo($codigo)
	{
		include("conexion.php");
		$sql = "SELECT * FROM ordenes_trabajo WHERE id_orden_trabajo= $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function borrar_orden_trabajo($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE ordenes_trabajo SET estado = 0 WHERE id_orden_trabajo = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//METODOS DE LA PANTALLA DE REGISTRO

	function detalle_orden_registro($codigo)
	{

		include("conexion.php");
		$sql = "SELECT *
				FROM ordenes_trabajo ot
					INNER JOIN maquinas m ON ot.id_maquina = m.id_maquina
					INNER JOIN sistemas s ON ot.id_sistema = s.id_sistema
					INNER JOIN sub_sistemas ss ON ot.id_sub_sistema = ss.id_sub_sistema
					INNER JOIN piezas p ON ot.id_pieza = p.id_pieza
					INNER JOIN actividades a ON ot.id_actividad = a.id_actividad
					INNER JOIN usuarios u ON ot.id_usuario = u.id_user
				WHERE id_orden_trabajo = $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function traer_mantenimiento_orden($orden)
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM registros
				WHERE id_orden_trabajo = $orden";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function traer_insumos_mantenimiento($registro)
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM insumos_mantenimiento
				WHERE id_registro = $registro";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function comprobar_insumo_mantenimiento($registro, $insumo)
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM insumos_mantenimiento
				WHERE id_registro = $registro
					AND id_insumo = $insumo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_insumo"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}

	function registrar_insumo_mantenimiento($registro, $insumo, $cantidad, $referencia)
	{

		include("conexion.php");
		$sql = "INSERT INTO insumos_mantenimiento(id_registro,id_insumo,cantidad,referencia)values($registro,$insumo,$cantidad,'$referencia')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}
	function registrar_insumo_mantenimiento2($registro, $insumo, $cantidad, $referencia)
	{

		include("conexion.php");
		$sql = "INSERT INTO insumos_mantenimiento(id_registro,id_insumo,cantidad,referencia)values($registro,$insumo,$cantidad,'$referencia')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return array("res" => "Siii");
	}

	function actualizar_insumo_mantenimiento($registro, $insumo, $cantidad, $referencia)
	{
		include("conexion.php");
		$sql = "UPDATE insumos_mantenimiento SET cantidad=$cantidad, referencia='$referencia'  WHERE id_registro=$registro AND id_insumo=$insumo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function borrar_insumo_mantenimiento($registro, $insumo)
	{
		include("conexion.php");
		$sql = "DELETE FROM insumos_mantenimiento WHERE id_registro = $registro AND id_insumo = $insumo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function cerrar_mantenimiento($registro, $observaciones)
	{
		$fecha = date("Y-m-d H:i:s");
		include("conexion.php");
		$sql = "UPDATE registros SET observaciones='$observaciones', fecha_registro='$fecha' WHERE id_registro=$registro";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();

		$orden = $this->traer_orden_registro($registro);
		include("conexion.php");
		$sql = "UPDATE ordenes_trabajo SET estado=2 WHERE id_orden_trabajo=$orden";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}
	function cerrar_mantenimiento2($orden)
	{
		$fecha = date("Y-m-d H:i:s");
		include("conexion.php");
		$sql = "UPDATE registros SET  fecha_registro='$fecha' WHERE id_orden_trabajo=$orden";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();


		include("conexion.php");
		$sql = "UPDATE ordenes_trabajo SET estado=2 WHERE id_orden_trabajo=$orden";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function traer_fecha_prox_mantenimiento($maquina, $sistema, $sub_sistema, $pieza, $actividad)
	{

		$fecha = time();
		include("conexion.php");
		$sql = "SELECT *
			FROM estandares
			WHERE id_maquina = $maquina
				AND id_sistema = $sistema
				AND id_sub_sistema = $sub_sistema
				AND id_pieza = $pieza
				AND id_actividad = $actividad";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();

		$frecuencia = $result->fields["frecuencia"];
		$unidad_frecuencia = $result->fields["unidad_frecuencia"];
		if ($unidad_frecuencia == 1) {
			$unidad_frecuencia = "hours";
		} else {
			$unidad_frecuencia = "days";
		}
		$fecha = strtotime('+' . $frecuencia . ' ' . $unidad_frecuencia, $fecha);

		return date("Y/m/d H:i:s", $fecha);
	}

	/*
	*	Esta funcion es diferente de la otra registro_orden_trabajo debido a que no solicita usuario, fecha u observaciones.
	*	Además, esta funcion se activa automaticamente cuando se cierra una orden de trabajo
	*/
	function registro_orden_trabajo_automatico($maquina, $sistema, $sub_sistema, $pieza, $actividad)
	{
		$fecha = $this->traer_fecha_prox_mantenimiento($maquina, $sistema, $sub_sistema, $pieza, $actividad);
		include("conexion.php");
		$sql = "INSERT INTO ordenes_trabajo(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,fecha_orden)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,'$fecha')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//Funcion para comprobar si ya existe un registro de mantenimiento para una orden especifica
	function comprobar_mantenimiento_orden($orden)
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM registros
				WHERE id_orden_trabajo = $orden";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_registro"];
		$result->MoveNext();
		$result->Close();
		if ($resultado) {				// Es que  existe
			return true;
		} else {				// Es que no existe
			return false;
		}
	}


	function traer_orden_registro($registro)
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM registros
				WHERE id_registro = $registro";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_orden_trabajo"];

		return $resultado;
	}

	//esta funcion busca el id de la ultima orden de trabajo creada
	function ultima_orden()
	{
		include("conexion.php");
		$sql = "SELECT MAX(id_orden_trabajo) orden FROM ordenes_trabajo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conn->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["orden"];
	}


	//este metodo se ejecuta de manera automatica cuando se genera una nueva orden
	function registro_mantenimiento_automatico($orden, $maquina, $sistema, $sub_sistema, $pieza, $actividad, $usuario, $fecha_programada)
	{
		include("conexion.php");
		$sql = "INSERT INTO registros(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,fecha_programada,id_orden_trabajo,id_usuario)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,'$fecha_programada',$orden,$usuario)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}
	function registro_mantenimiento_automatico23($sql)
	{
		include("conexion.php");
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		} else {
			return "LEo";
		}

		$conexion_pg->Close();
	}

	//este metodo se ejecuta de manera automatica cuando se cierra una orden
	function registro_mantenimiento_automatico2($orden, $maquina, $sistema, $sub_sistema, $pieza, $actividad)
	{
		$fecha = time();
		$fecha_sistema = $this->traer_fecha_prox_mantenimiento($maquina, $sistema, $sub_sistema, $pieza, $actividad);
		$proceso = $this->detalle_proceso($maquina);
		include("conexion.php");
		$sql = "INSERT INTO registros(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,id_orden_trabajo,fecha_ult_mantenimiento,prox_mant_est,horo_ult_mant)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,$orden,'" . date("Y/m/d H:i:s", $fecha) . "','$fecha_sistema'," . $proceso->fields["horometro"] . ")";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//esta funcion se ejecuta cuando se modifica una orden de trabajo para que el usuario del mantenimiento y la fecha tambien cambie
	function actualizar_mantenimiento_automatico($orden, $maquina, $sistema, $sub_sistema, $pieza, $actividad, $usuario, $fecha_programada)
	{
		include("conexion.php");
		$sql = "UPDATE registros SET id_maquina='$maquina', id_sistema='$sistema', id_sub_sistema='$sub_sistema',id_pieza='$pieza',id_actividad='$actividad', fecha_programada = '$fecha_programada' WHERE id_orden_trabajo=$orden";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function cerrar_orden_trabajo($codigo)
	{
		include("conexion.php");
		$sql = "UPDATE ordenes_trabajo SET estado = 2 WHERE id_orden_trabajo = $codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al borrar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
	}
	function traerNombreUsuario($codigo)
	{
		include("conexion.php");
		$sql = "SELECT *
					FROM usuarios
					WHERE id_user = $codigo";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al Buscar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();


		return $result->fields["user_name"];
	}

	/**********************************************************************/
	/* METODOS DE REGISTRO Y ACTUALIZACION DE LA PLANTILLA DE IMPORTACIÓN */
	/**********************************************************************/

	function registro_usuarios_plantilla($codigo, $nombre, $apellido, $contrasena, $nivel)
	{
		//$contrasena=md5($contrasena);
		include("conexion.php");
		$sql = "insert into usuarios(id_user,user_name,user_lastname,password,grade)values($codigo,'$nombre','$apellido','$contrasena','$nivel')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_usuarios_plantilla($codigo, $nombre, $apellido, $contrasena, $nivel)
	{
		//$contrasena=md5($contrasena);
		include("conexion.php");
		$sql = "UPDATE usuarios SET user_name='$nombre',user_lastname='$apellido',password='$contrasena',grade='$nivel' WHERE id_user=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_proceso_plantilla($codigo, $nombre, $descripcion, $horometro)
	{
		include("conexion.php");
		$sql = "INSERT INTO maquinas(id_maquina,nombre_maquina,descripcion_maquina,horometro)values($codigo,'$nombre','$descripcion',$horometro)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_proceso_plantilla($codigo, $nombre, $descripcion, $horometro)
	{
		include("conexion.php");  //id_planta,ip_maquina,maximo_horas,captura
		$sql = "UPDATE maquinas SET nombre_maquina='$nombre',descripcion_maquina='$descripcion',horometro=$horometro WHERE id_maquina=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_sistema_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "INSERT INTO sistemas(id_sistema,nombre_sistema,descripcion_sistema)values($codigo,'$nombre','$descripcion')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_sistema_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "UPDATE sistemas SET nombre_sistema='$nombre',descripcion_sistema='$descripcion' WHERE id_sistema=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_sub_sistema_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "INSERT INTO sub_sistemas(id_sub_sistema,nombre_sub_sistema,descripcion_sub_sistema)values($codigo,'$nombre','$descripcion')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_sub_sistema_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "UPDATE sub_sistemas SET nombre_sub_sistema='$nombre',descripcion_sub_sistema='$descripcion' WHERE id_sub_sistema=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_piezas_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "INSERT INTO piezas(id_pieza,nombre_pieza,descripcion_pieza)values($codigo,'$nombre','$descripcion')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_piezas_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "UPDATE piezas SET nombre_pieza='$nombre',descripcion_pieza='$descripcion' WHERE id_pieza=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_actividades_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "INSERT INTO actividades(id_actividad,nombre_actividad,descripcion_actividad)values($codigo,'$nombre','$descripcion')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_actividades_plantilla($codigo, $nombre, $descripcion)
	{
		include("conexion.php");
		$sql = "UPDATE actividades SET nombre_actividad='$nombre',descripcion_actividad='$descripcion' WHERE id_actividad=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_insumos_plantilla($codigo, $nombre, $descripcion, $unidad)
	{
		include("conexion.php");
		$sql = "INSERT INTO insumos(id_insumo,nombre_insumo,descripcion_insumo,unidad_insumo)values($codigo,'$nombre','$descripcion','$unidad')";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_insumos_plantilla($codigo, $nombre, $descripcion, $unidad)
	{
		include("conexion.php");
		$sql = "UPDATE insumos SET nombre_insumo='$nombre',descripcion_insumo='$descripcion',unidad_insumo='$unidad' WHERE id_insumo=$codigo";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registro_estandar_plantilla($maquina, $sistema, $sub_sistema, $pieza, $actividad, $std_mant, $frecuencia, $unid_frec)
	{
		if ($unid_frec == "Horas") {
			$unid_frec = 1;
		} else {
			$unid_frec = 2;
		}
		include("conexion.php");
		$sql = "INSERT INTO estandares(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,frecuencia,std_mantenimiento,unidad_frecuencia)values($maquina,$sistema,$sub_sistema,$pieza,$actividad,$frecuencia,$std_mant,$unid_frec)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function actualizar_estandar_plantilla($maquina, $sistema, $sub_sistema, $pieza, $actividad, $std_mant, $frecuencia, $unid_frec)
	{
		if ($unid_frec == "Horas")
			$unid_frec = 1;
		else
			$unid_frec = 2;

		include("conexion.php");
		$sql = "UPDATE estandares SET frecuencia=$frecuencia,std_mantenimiento=$std_mant,unidad_frecuencia=$unid_frec WHERE id_maquina=$maquina AND id_sistema=$sistema AND id_sub_sistema=$sub_sistema AND id_pieza=$pieza AND id_actividad=$actividad";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	function registrar_HV_maquina($maquina, $observaciones, $usuario)
	{
		$fecha = time();
		include("conexion.php");
		$sql = "INSERT INTO hv_maquinas(id_maquina,notas,fecha,id_usuario)values($maquina,'$observaciones','" . date("Y/m/d H:i:s", $fecha) . "',$usuario)";
		if ($conexion_pg->Execute($sql) === false) {
			echo 'error al insertar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
	}

	//FUNCIONES AUTOMATICAS

	function actualizar_horometros_automatico()
	{
		//primero se traen los id de las maquinas de mantenimiento
		//OJO: los ID de las maquinas en el sistema de mantenimiento deben iguales al sistema PEGASUS PRO
		include("conexion.php");
		$sql = "SELECT * FROM maquinas";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al traer la informacion de las maquinas desde el sistema de mantenimiento: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$maquinas = "";
		while (!$result->EOF) {
			//junta todos los id de maquinas y los separa por ,
			$maquinas = $maquinas . "'" . $result->fields["id_maquina"] . "',";
			$result->MoveNext();
		}
		$conexion_pg->Close();
		//se quita la ultima ,
		$maquinas = substr($maquinas, 0, strlen($maquinas) - 1);

		//se trae la informacion de las máquinas del PEGASUS PRO
		include("conexion_manufactura.php");
		$sql = "SELECT * FROM procesos WHERE id_proceso IN($maquinas) ORDER BY id_proceso";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al trer la informacion de las maquinas desde el sistema PEGASUS PRO: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$maq_pegasus;
		$horo_pegasus;
		$i = 0;
		while (!$result->EOF) {
			//junta todos los id de maquinas y los separa por ,
			$maq_pegasus[$i] = $result->fields["id_proceso"];
			$horo_pegasus[$i] = $result->fields["horometro_total"];
			$i++;
			$result->MoveNext();
		}
		$conexion_pg->Close();

		$j = 0;
		while ($j < $i) {
			include("conexion.php");
			$sql = "UPDATE maquinas
					SET horometro=" . $horo_pegasus[$j] . "
					WHERE id_maquina=" . $maq_pegasus[$j];
			if ($conexion_pg->Execute($sql) === false) {
				echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
			}
			$conexion_pg->Close();

			$j++;
		}
	}
	/*
	*	Este metodo se llama con las ordenes cuyo estandar este dado en horas
	*/
	function actualizar_fechas_ordenes()
	{

		include("conexion.php");
		//traer todas las ordenes de trabajo activas (estado = 1) y sus respectivos registros, estas ordenes deben tener estandares en horas
		$sql1 = "SELECT	r.id_registro,
					(CASE WHEN r.fecha_ult_mantenimiento is null THEN now() ELSE r.fecha_ult_mantenimiento END) fecha_ant_mant,
					r.*,
					e.*,
					m.*
				FROM	ordenes_trabajo ot
					INNER JOIN registros r ON ot.id_orden_trabajo = r.id_orden_trabajo
					INNER JOIN estandares e ON e.id_maquina = ot.id_maquina AND e.id_sistema = ot.id_sistema AND e.id_sub_sistema = ot.id_sub_sistema AND e.id_pieza = ot.id_pieza AND e.id_actividad = ot.id_actividad AND unidad_frecuencia = 1
					INNER JOIN maquinas m ON m.id_maquina = e.id_maquina
				WHERE ot.estado = 1
				ORDER BY ot.id_orden_trabajo";
		$result1 = $conexion_pg->Execute($sql1);
		if ($result1 === false) {
			echo 'error al trer la informacion de las ordenes: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		/*ejecutar el algoritmo: (FRE -[H_A - H_I])/([H_A - H_I]/[F_A - F_I])
		* donde: FRE es la frecuencia que debe estar en Horas,
		*		 H_A representa el Horometro Actual,
		*		 H_I representa el horometro al anterior mantenimiento,
		*		 F_A representa la fecha (solo fecha) actual y
		*		 F_I representa la fecha (solo fecha) del anterior mantenimiento
		* esto para todas y cada una de las ordenes del primer select con la informacion de las maquinas en el 2 select
		*/
		$i = 0;
		while (!$result1->EOF) {
			$hoy = time();

			//$fecha_ant_mant = date("Y/m/d H:i:s",$result1->fields["fecha_ant_mant"]);//trae la fecha de la BD
			$fecha_ant = $this->getTimeUNIX_BD($result1->fields["fecha_ant_mant"]);
			//$fecha_ant = $this->getTimeUNIX_BD($fecha_ant_mant);

			$diferencia_horas = $result1->fields["horometro"] - $result1->fields["horo_ult_mant"];

			$diferencia_dias = floor(abs(($hoy - $fecha_ant) / 86400));

			$horas_dia;
			try {
				$horas_dia = $diferencia_horas / $diferencia_dias;
			} catch (Exception $e) {
				$horas_dia = 0;
			}

			//echo $horas_dia.'</br>';
			//echo '***'.$result1->fields["id_registro"].'***</br>';

			if ($horas_dia) {

				try {
					$dias_adicion = floor(($result1->fields["frecuencia"] - $diferencia_horas) / $horas_dia);
					//echo $dias_adicion.'</br>';

					$ts_estimado = strtotime('+' . $dias_adicion . ' days', $hoy);
					//echo $ts_estimado.'</br>';

					$fecha_estimada = date("Y/m/d H:i:s", $ts_estimado);
					//echo $fecha_estimada.'</br>';

					include("conexion.php");
					$sql2 = "UPDATE ordenes_trabajo
							SET fecha_orden='" . $fecha_estimada . "'
							WHERE id_orden_trabajo=" . $result1->fields["id_orden_trabajo"];
					if ($conexion_pg->Execute($sql2) === false) {
						echo 'error al actulizar: ' . $conexion_pg->ErrorMsg() . '<BR>';
					}
					$conexion_pg->Close();
				} catch (Exception $e) {
					echo 'SE DETECTO UN ERROR EN EL SEGUNDO "try catch" POR FAVOR REVISAR';
				}
			}
			$i++;
			$result1->MoveNext();
		}
	}
	function traer_maquina_orden($orden)
	{
		//SELECT QUE SE USA PARA OBTENER LA MAQUINA DE LA ORDEN
		include("conexion.php");
		$sql =	"SELECT * FROM ordenes_trabajo WHERE id_orden_trabajo = $orden";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["id_maquina"];
	}
	function traer_fecha_orden($orden)
	{
		//SELECT QUE SE USA PARA OBTENER LA MAQUINA DE LA ORDEN
		include("conexion.php");
		$sql =	"SELECT * FROM ordenes_trabajo WHERE id_orden_trabajo = $orden";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al listar: ' . $conexion_pg->ErrorMsg() . '<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["fecha_orden"];
	}
	function iniciar_mantenimiento($codigo)
	{
		$fechaactual = date("Y-m-d H:i:s");

		$sql = "UPDATE ordenes_trabajo set fecha_anterior = '$fechaactual' WHERE estado = 1 and id_orden_trabajo = '$codigo' ";
		include("conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo "Nada de nada";
		}
		$conexion_pg->Close();
		$resultado = $result->fields["id_maquina"];
		if ($resultado) {
			return true;
		} else {
			return false;
		}
	}

	/********************************************************************************/


	//	FUNCIONES QUE AFECTAN O REQUIEREN DEL SISTEMA DE MANUFACTURA
	//  TODAS USAN AL SISTEMA DE MANUFACTURA

	public function getTurnoMaquina($maquina)
	{
		$sql = "SELECT batch FROM procesos WHERE id_proceso ='$maquina'";
		include("conexion_manufactura.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		return $result->fields["batch"];
	}



	function cerrarTurnoManufactura($orden)
	{

		$maquina = $this->traer_maquina_orden($orden);
		$maqturno = $this->getTurnoMaquina($maquina);
		$fechaactual = date("Y-m-d H:i:s");

		//se cierra el turno
		$sql = "UPDATE turnos
				SET	fecha_fin = '$fechaactual',
					desperdicio_1 = 0,
					desperdicio_2 = 0,
					desperdicio_3 = 0,
					produccion_final = 1,
					terminado = '1'
				WHERE id_turno='$maqturno'
					AND id_proceso='$maquina'
					AND terminado ='0'";

		include("conexion_manufactura.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();


		//se cierra la asistencia del turno
		$sql = "UPDATE turno_asistencia
				SET	fecha_fin = '$fechaactual',
					teminado = 1
				WHERE id_turno='$maqturno'
					AND id_proceso='$maquina'
					AND teminado = 0";

		include("conexion_manufactura.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();
	}
}
