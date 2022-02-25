<?php

include("../clases/adodb5/adodb-exceptions.inc.php");
include("../clases/adodb5/adodb.inc.php");
date_default_timezone_set('America/Lima');

class DatosTurno {

    public function DatosTurno() {
        // Tratado como constructor en PHP 5.3.0 - 5.3.2
        // Tratado como método regular a partir de PHP 5.3.3
    }

    public function validarOperario($codigo) {
		$sql = "SELECT count(*) cnt FROM usuarios WHERE id_user ='$codigo' AND estado = 1 AND grade=1";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		$result->MoveFirst();
		return (int) $result->fields["cnt"] > 0 ? true : false;
//return true;
    }

    public function validarOP($codigo) {
		$sql = "SELECT count(*) cnt FROM ordenes_produccion WHERE id_orden_produccion ='$codigo' ";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		$result->MoveFirst();
		return (int) $result->fields["cnt"] > 0 ? true : false;
//return true;
    }

    public function validarProducto($codigo) {
		$sql = "SELECT count(*) cnt FROM productos WHERE id_producto ='$codigo' ";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		$result->MoveFirst();
		return (int) $result->fields["cnt"] > 0 ? true : false;
    }

	public function validarParada($codigo) {
		$sql = "SELECT count(*) cnt FROM paradas WHERE id_parada ='$codigo' ";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		$result->MoveFirst();
		return (int) $result->fields["cnt"] > 0 ? true : false;
    }

	public function validarTurnoCerrado($maquina, $turno) {
		$sql = "SELECT count(*) cnt FROM turnos WHERE id_maquina ='$maquina' AND id_turno = '$turno' AND terminado = 1";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		$result->MoveFirst();
		return (int) $result->fields["cnt"] > 0 ? true : false;
    }

	public function traerOrdenTurno($maquina, $turno){
		$sql = 	"SELECT orden_produccion
				 FROM turnos
				 WHERE id_maquina = '$maquina' AND id_turno = '$turno' AND terminado = '0'";

		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
		if ($result === false) die("fallo consulta");
		$conexion_pg->Close();
		return $result->fields[0];
	}

    public function traerDatosTurno($maquina) {
		$sql = "SELECT p.id_maquina maquina,
					p.nombre_maquina nom_maquina,
					t.id_turno turno,
					case when (t.terminado = 0) then 1 else 0 end estadoTurno
				FROM maquinas p
					LEFT JOIN turnos t ON p.id_maquina = t.id_maquina AND t.id_turno = p.batch
				WHERE p.id_maquina = '$maquina'";
		include("../clases/conexion.php");
	    $result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();

		$unidades = round($result->fields["unidades"],1);

		return array(
			'maquina' 			=> $result->fields["maquina"],
			'nom_maquina'		=> $result->fields["nom_maquina"],
			'turno' 			=> $result->fields["turno"],
			'estadoTurno' 		=> $result->fields["estadoturno"]
		);
    }

    public function traerDatosParada($maquina) {
		$sql = "SELECT pro.id_maquina maquina,
					pro.descripcion nom_maquina,
					pro.batch turno,
					CASE WHEN (tp.terminado = 0) THEN 1 ELSE 0 END enparo,
					CASE WHEN (tp.id_parada = '999') THEN 0 ELSE 1 END registrada,
					tp.horas tiempo,
					CASE WHEN (t.terminado = 0) THEN 1 ELSE 0 END estadoturno,
					p.nombre_parada causal,
					tp.id_parada parada
				FROM maquinas pro
					LEFT JOIN turnos t ON pro.id_maquina = t.id_maquina AND t.terminado = 0
					LEFT JOIN turno_parada tp ON t.id_maquina = tp.id_maquina AND t.id_turno = tp.id_turno AND tp.terminado = 0
					LEFT JOIN paradas p ON p.id_parada = tp.id_parada
				WHERE pro.id_maquina = '$maquina'";
		include("../clases/conexion.php");
	    $result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();

		$tiempo = $this->tiempo_segundos($result->fields["tiempo"]);

		return array(
			'maquina' 			=> $result->fields["maquina"],
			'nom_maquina'		=> $result->fields["nom_maquina"],
			'turno' 			=> $result->fields["turno"],
			'enParo' 			=> $result->fields["enparo"],
			'registrada' 		=> $result->fields["registrada"],
			'causal' 			=> $result->fields["causal"],
			'tiempo' 			=> $tiempo,
			'estadoTurno' 		=> $result->fields["estadoturno"],
			'parada'			=> $result->fields["parada"],
		);
    }

	function tiempo_segundos($segundos)
	{
		$minutos=$segundos/60;
		$horas=floor($minutos/60);
		$minutos2=$minutos%60;
		$segundos_2=$segundos%60%60%60;
		if($minutos2<10)$minutos2='0'.$minutos2;
		if($segundos_2<10)$segundos_2='0'.$segundos_2;

		if($segundos<60){ /* segundos */
			$resultado= '00:00:'.round($segundos);
		}
		elseif($segundos>60 && $segundos<3600){/* minutos */
			$resultado= '00:'.$minutos2.':'.$segundos_2;
		}
		else{/* horas */
			if($horas<10)
			{
				$horas='0'.$horas;
			}
			$resultado= $horas.":".$minutos2.":".$segundos_2;
		}
		return $resultado;
	}

	public function getTurnoMaquina($maquina){
		$sql ="SELECT batch FROM maquinas WHERE id_maquina ='$maquina'";
        include("../clases/conexion.php");
        $result = $conexion_pg->Execute($sql);
        if ($result === false) die("fallo consulta");
        $conexion_pg->Close();
		return $result->fields["batch"];
	}

	public function listarOrdenesUsuarioMaquina($usuario, $maquina){
		include("../clases/conexion.php");
		$sql =	"SELECT *
				FROM ordenes_trabajo ot
				WHERE ot.id_maquina = '$maquina' AND ot.id_usuario = '$usuario' AND ot.estado = 1
				ORDER BY id_orden_trabajo";
		//echo '</BR> SQL: '.$sql.'</br>';
		$result=$conexion_pg->Execute($sql);
		//echo 'Resultado: '.$result;
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	function detalleProceso($codigo)
    {
        include("../clases/conexion.php");
        $sql="SELECT * FROM maquinas WHERE id_maquina = $codigo";
        $result=$conexion_pg->Execute($sql);
        if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function detalleSistema($codigo)
    {
		include("../clases/conexion.php");
		$sql="SELECT * FROM sistemas WHERE id_sistema = $codigo";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function detalleSubSistema($codigo)
    {
		include("../clases/conexion.php");
		$sql="SELECT * FROM sub_sistemas WHERE id_sub_sistema = $codigo";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function detallePieza($codigo)
    {
		include("../clases/conexion.php");
		$sql="SELECT * FROM piezas WHERE id_pieza= $codigo";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function detalleActividad($codigo)
    {
		include("../clases/conexion.php");
		$sql="SELECT * FROM actividades WHERE id_actividad= $codigo";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function detalleUsuario($codigo)
    {
		include("../clases/conexion.php");
		$sql="SELECT * FROM usuarios WHERE id_user= $codigo and grade <> 4";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }

	function estadoConfiguracion($id_config){

		include("../clases/conexion.php");
		$sql =	"SELECT * FROM configuracion WHERE id_configuracion = $id_config";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result->fields["estado"];
	}

    public function iniciarTurno($maquina, $usuario, $orden) {

		$fecha		= date("Y-m-d H:i:s");
		$maqturno	= $this->getTurnoMaquina($maquina);

		//preguntar si el turno que quiero abrir ya fue abierto

		$datos_turno = $this->traerDatosTurno($maquina);//el campo estadoTurno retorna 1 si hay un turno abierto para la maquina
		if($datos_turno['estadoTurno'] == 0){

			//preguntar si el turno que quiero abrir ya esta cerrado

			$turnocerrado = $this->validarTurnoCerrado($maquina, $maqturno); //true si el turno que estoy tratando de abrir esta cerrado
			if($turnocerrado) $this->aumentarTurno($maquina);

			//
			$maqturno	= $this->getTurnoMaquina($maquina);

			$sql = "INSERT INTO turnos (id_turno,id_maquina,fecha_inicio,fecha_fin, orden_trabajo, id_usuario) values ('$maqturno','$maquina','$fecha','$fecha', $orden, $usuario)";
			include("../clases/conexion.php");
			if ($conexion_pg->Execute($sql) === false) die("fallo consulta");

			return;

		}
    }

	public function aumentarTurno($maquina)
	{
		$maqturno = $this->getTurnoMaquina($maquina);

		$sql = "UPDATE maquinas
				SET	batch=".($maqturno+1)."
				WHERE id_maquina='$maquina'";

		include("../clases/conexion.php");
        if ($conexion_pg->Execute($sql) === false)
            die("fallo consulta");
        $conexion_pg->Close();
	}

    public function registrarParada($maquina,$codigo) {
        //registra parada con $codigo
		//si la parada actual tiene el codigo 999, actualizar el codigo; si la parada actual tiene cualquier codigo distinto a 999 cerrar la parada e iniciar
		//una nueva parada con el codigo enviado.
		if($this->validarParada($codigo)){

			$fecha=date("Y-m-d H:i:s");
			$datosparada = $this->traerDatosParada($maquina);
			$maqturno = $this->getTurnoMaquina($maquina);
			$paradaactual = $datosparada["parada"];
			$fecha_inicio = $this->fecha_parada($maqturno,$maquina);
			$intervalo_segundos = $this->diferencia_tiempo_segundos($fecha_inicio);
			$intervalo_hora = $this->tiempo_segundos($intervalo_segundos);//el mismo dato de "intervalo_segundos" pero en formato hh:mm:ss


			include("../clases/conexion.php");
			if($paradaactual == "999"){
				$sql = "UPDATE turno_parada SET id_parada = '$codigo', fecha_fin = '$fecha' WHERE id_maquina = '$maquina' AND id_turno = '$maqturno' and terminado = '0'";
				if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
			}
			else{

				//primero se cierra la parada abierta
				$sql = "UPDATE turno_parada SET fecha_fin='$fecha',horas='$intervalo_segundos',terminado='1', intervalo_horas='$intervalo_hora' WHERE id_maquina = '$maquina' AND id_turno = '$maqturno' and terminado = '0'";
				if ($conexion_pg->Execute($sql) === false) die("fallo consulta");

				//acontinuacion se abre una nueva con el codigo que se envia
				$sql = "INSERT INTO turno_parada (id_turno,id_maquina,id_parada,fecha_inicio) VALUES ('$maqturno','$maquina','$codigo','$fecha')";
				if ($conexion_pg->Execute($sql) === false) die("fallo consulta");

				//re-ajustar las unidades de salida de parada
				$sql = "UPDATE conteos c1 SET conteo_parada = (cast(c.conteo AS numeric) + p.unidades_salida) FROM maquinas p INNER JOIN conteos c ON c.id_puerto = p.puerto WHERE p.id_maquina= '$maquina' AND c1.id_puerto = p.puerto";
				if ($conexion_pg->Execute($sql) === false) die("fallo consulta");

			}
			$conexion_pg->Close();
			return true;
		}
		else{
			return false;
		}
    }
	public function diferencia_tiempo_segundos($fecha_inicio) //la fecha de inicio de la parada
	{
		include("../clases/fechas.php");
		$fechas = new conversiones_fechas();

		$fechaactual= date("Y-m-d H:i:s");
		$fechainial = date($fecha_inicio);
		$intervalo=$fechas->getDifFechastodo($fechaactual,$fechainial);

		return ($intervalo);
	}

	public function fecha_parada($turno, $maquina)
	{
		$sql = "SELECT *
				FROM turno_parada
				WHERE id_turno='$turno' AND terminado ='0' AND id_maquina='$maquina'";
		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();
		return $result->fields["fecha_inicio"];
	}

	public function cerrarTurno($turno) {

		//DATOS DEL CIERRE DE TURNO
		$fechaactual= date("Y-m-d H:i:s");
		$maquina = $turno['maquina'];
		$maqturno = $this->getTurnoMaquina($maquina);
		$desperdicio1 = $turno['materiales'][0];
		$desperdicio2 = $turno['materiales'][1];
		$desperdicio3 = $turno['materiales'][2];
		$prod_final = $turno['pruduccionFinal'];
		$orden_turno = $this->traerOrdenTurno($maquina, $maqturno);

		//ante todo se deben cerrar las paradas abiertas del turno
		$sql = "UPDATE turno_parada
				SET	terminado = '1'
				WHERE id_maquina = '$maquina'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//Acontinuación se guardan las unidades terminadas de la orden
		$sql = 	"UPDATE ordenes_produccion
				SET	cant_terminada = cant_terminada + $prod_final,
					terminada = CASE WHEN (cant_terminada + $prod_final) >= cantidad THEN 1 ELSE 0 END
				WHERE id_orden_produccion = $orden_turno";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//se cierra el turno
		$sql = "UPDATE turnos
				SET	fecha_fin = '$fechaactual',
					desperdicio_1 = '$desperdicio1',
					desperdicio_2 = '$desperdicio2',
					desperdicio_3 = '$desperdicio3',
					produccion_final = '$prod_final',
					terminado = '1'
				WHERE id_turno='$maqturno'
					AND id_maquina='$maquina'
					AND terminado ='0'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();


		//se cierra la asistencia del turno
		$sql = "UPDATE turno_asistencia
				SET	fecha_fin = '$fechaactual',
					teminado = 1
				WHERE id_turno='$maqturno'
					AND id_maquina='$maquina'
					AND teminado = 0";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//se reinician los conteos

		$sql = "UPDATE conteos
				SET	conteo = '0',
					fecha_conteo = '$fechaactual'
				FROM maquinas
				WHERE id_maquina='$maquina'
					AND puerto = id_puerto";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//se actualiza el horometro de la maquina

		$sql = "UPDATE maquinas p
				SET	horometro_total = cast(horometro_total as numeric) + cast((t.tiempo_turno - t.tiempo_total_paro) as numeric)
				FROM turnos t
				WHERE p.id_maquina = '$maquina'
					AND t.id_turno = '$maqturno'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		try{
			$this->calcular_indicadores($maquina, $maqturno);
		}
		catch (Exception $e) {
			$fichero = 'log_errores.txt';
			// Abre el fichero para obtener el contenido existente
			$actual = file_get_contents($fichero);
			// Añade un nuevo registro al fichero
			$actual .= "FECHA: ".$fechaactual.". ERROR: ".$e->getMessage()."\n";
			// Escribe el contenido al fichero
			file_put_contents($fichero, $actual);
		}

		return true;
    }

	public function cerrarTurnoAutomatico($maquina, $maqturno){
		//DATOS DEL CIERRE DE TURNO
		//YA QUE EL TURNO SE CERRO AUTOMATICAMENTE NO SE REGISTRAN LOS DESPERDICIOS NI LA PRODUCCION FINAL
		//EN ESTE CASO LOS DESPERDICIOS = 0 Y LAS PRODUCCION FINAL = UNIDADES CONTEO
		$fechaactual= date("Y-m-d H:i:s");
		$desperdicio1 = 0;
		$desperdicio2 = 0;
		$desperdicio3 = 0;
		$orden_turno = $this->traerOrdenTurno($maquina, $maqturno);

		$sql = "SELECT unidades_conteo
				FROM turnos
				WHERE id_maquina = $maquina and id_turno = $maqturno";

        include("../clases/conexion.php");
        $result = $conexion_pg->Execute($sql);
        if ($result === false) die("fallo consulta");
        $conexion_pg->Close();
		$prod_final = $result->fields["unidades_conteo"];
		$prod_final = round($prod_final);
		//ante todo se deben cerrar las paradas abiertas del turno
		$sql = "UPDATE turno_parada
				SET	terminado = '1'
				WHERE id_maquina = '$maquina'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//Acontinuación se guardan las unidades terminadas
		$sql = 	"UPDATE ordenes_produccion
				SET	cant_terminada = cant_terminada + $prod_final,
					terminada = CASE WHEN (cant_terminada + $prod_final) >= cantidad THEN 1 ELSE 0 END
				WHERE id_orden_produccion = $orden_turno";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//y finalmente se cierra el turno
		$sql = "UPDATE turnos
				SET	fecha_fin = '$fechaactual',
					desperdicio_1 = '$desperdicio1',
					desperdicio_2 = '$desperdicio2',
					desperdicio_3 = '$desperdicio3',
					produccion_final = '$prod_final',
					terminado = '1'
				WHERE id_turno='$maqturno'
					AND id_maquina='$maquina'
					AND terminado ='0'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//tambien se debe cerrar la asistencia del turno
		$sql = "UPDATE turno_asistencia
				SET	fecha_fin = '$fechaactual',
					teminado = 1
				WHERE id_turno='$maqturno'
					AND id_maquina='$maquina'
					AND teminado = 0";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//se reinician los conteos

		$sql = "UPDATE conteos
				SET	conteo = '0',
					fecha_conteo = '$fechaactual'
				FROM maquinas
				WHERE id_maquina='$maquina'
					AND puerto = id_puerto";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//se actualiza el horometro de la maquina

		$sql = "UPDATE maquinas p
				SET	horometro_total = cast(horometro_total as numeric) + cast((t.tiempo_turno - t.tiempo_total_paro) as numeric)
				FROM turnos t
				WHERE p.id_maquina = '$maquina'
					AND t.id_turno = '$maqturno'";

		include("../clases/conexion.php");
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();


		$this->calcular_indicadores($maquina, $maqturno);

		return true;
	}

	function guardar_observaciones($turno){
		$maquina = $turno['maquina'];
		$observaciones = $turno['observaciones'];
		$maqturno = $this->getTurnoMaquina($maquina);

		include("../clases/conexion.php");
		$sql =	"UPDATE turnos SET obsevaciones = '$observaciones' WHERE id_maquina = $maquina AND id_turno = $maqturno";
		if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		$conexion_pg->Close();

		//$this->calcular_indicadores($maquina, $maqturno);

		return true;
	}

	public function calcular_indicadores($maquina, $turno)
	{
		include("../clases/fechas.php");
		$fechas = new conversiones_fechas();

		$sql ="SELECT 	coalesce(t.indicador_1, '1') indicador_1,
						coalesce(t.indicador_2, '1') indicador_2,
						coalesce(t.indicador_3, '1') indicador_3,
						coalesce(t.indicador_4, '1') indicador_4,
						coalesce(t.indicador_5, '1') indicador_5,
						coalesce(t.indicador_6, '1') indicador_6,
						coalesce(t.indicador_7, '1') indicador_7,
						coalesce(t.tiempo_maquina, 1) tiempo_maquina,
						coalesce(t.unidades_conteo, '1') unidades_conteo,
						coalesce(t.tiempo_hombre, 1) tiempo_hombre,
						coalesce(t.tiempo_turno, 1) tiempo_turno,
						coalesce(t.tiempo_paro_prog, 1) tiempo_paro_prog,
						coalesce(t.tiempo_paro_no_p, 1) tiempo_paro_no_p,
						coalesce(t.tiempo_paro_g1, 1) tiempo_paro_g1,
						coalesce(t.tiempo_paro_g2, 1) tiempo_paro_g2,
						coalesce(t.tiempo_paro_g3, 1) tiempo_paro_g3,
						coalesce(t.tiempo_total_paro, 1) tiempo_total_paro,
						coalesce(t.tiempo_std_prog, 1) tiempo_std_prog,
						coalesce(t.dato_extra, 1) dato_extra,
						coalesce(t.desperdicio_1, 1)desperdicio_1,
						coalesce(t.desperdicio_2, 1)desperdicio_2,
						coalesce(t.desperdicio_3, 1)desperdicio_3,
						coalesce(t.produccion_final, 1) produccion_final,
						coalesce(pp.var1, '1') var1,
						coalesce(pp.var2, '1') var2,
						coalesce(pp.var3, '1') var3,
						coalesce(p.dato_extra1, '1') dato_extra1,
						coalesce(p.dato_extra2, '1') dato_extra2,
						coalesce(p.dato_extra3, '1') dato_extra3,
						coalesce(t.unidades_desperdicio,'0') ud
				FROM turnos AS t
					LEFT JOIN producto_proceso pp ON pp.id_maquina = t.id_maquina AND pp.id_producto = t.id_producto
					LEFT JOIN productos p ON p.id_producto=t.id_producto
				WHERE t.id_maquina=$maquina AND t.id_turno = $turno";

		include("../clases/conexion.php");
		$variables=$conexion_pg->Execute($sql);
		if ($variables === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		$formulas=$fechas->listar_formulas();
		$i=0;
		while(!$formulas->EOF)
		{
			$formulas_r[$i]=$formulas->fields["formula"];
			$formulas->MoveNext();
			$i++;
		}

		for( $i = 0; $i < count($formulas_r); $i ++)
		{
			$formulas_r[$i]=str_replace("indicador_1",$variables->fields["indicador_1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_2",$variables->fields["indicador_2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_3",$variables->fields["indicador_3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_4",$variables->fields["indicador_4"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_5",$variables->fields["indicador_5"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_6",$variables->fields["indicador_6"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_7",$variables->fields["indicador_7"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_maquina",$variables->fields["tiempo_maquina"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("datop_extra",$variables->fields["dato_extra"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p1",$variables->fields["dato_extra1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p2",$variables->fields["dato_extra2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p3",$variables->fields["dato_extra3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio1",$variables->fields["desperdicio_1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio2",$variables->fields["desperdicio_2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio3",$variables->fields["desperdicio_3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("produccion_final",$variables->fields["produccion_final"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_averias",$variables->fields["tiempo_paro_g1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_cuadre_ajuste",$variables->fields["tiempo_paro_g2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_pequeñas",$variables->fields["tiempo_paro_g3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_standart_prog",$variables->fields["tiempo_std_prog"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_hombre",$variables->fields["tiempo_hombre"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("total_paro",$variables->fields["tiempo_total_paro"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tot_par_no_p",$variables->fields["tiempo_paro_no_p"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tota_paro_p",$variables->fields["tiempo_paro_prog"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_turno",$variables->fields["tiempo_turno"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("unidades_conteo",$variables->fields["unidades_conteo"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp1",$variables->fields["var1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp2",$variables->fields["var2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp3",$variables->fields["var3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("unidades_desperdicio",$variables->fields["ud"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace(",",".",$formulas_r[$i]);
		}

		for( $i = 0; $i < count($formulas_r); $i ++)
		{
			$str = $formulas_r[$i].';';
			eval('$res='.$str);
			$indicadores[$i]= Abs($res);
			if(is_float($indicadores[$i]))
			{
				$indicadores[$i]=number_format($indicadores[$i],2,".","");
			}
		}

		$sql = "UPDATE turnos
				SET indicador_1='".$indicadores[0]."',
					indicador_2='".$indicadores[1]."',
					indicador_3='".$indicadores[2]."',
					indicador_4='".$indicadores[3]."',
					indicador_5='".$indicadores[4]."',
					indicador_6='".$indicadores[5]."',
					indicador_7='".$indicadores[6]."',
					tiempo_maquina='".$indicadores[7]."'
				WHERE id_maquina=$maquina AND id_turno = $turno";

		include("../clases/conexion.php");
        if ($conexion_pg->Execute($sql) === false)
            die("fallo consulta");
		$conexion_pg->Close();
		//-----------------------------------------
		//SE DEBE REPETIR LA ACCION PARA PODER ACTUALIZAR DATOS QUE DEPENDEN DE OTROS
		//-----------------------------------------
		//include("../clases/fechas.php");
		//$fechas = new conversiones_fechas();

		$sql ="SELECT 	coalesce(t.indicador_1, '1') indicador_1,
						coalesce(t.indicador_2, '1') indicador_2,
						coalesce(t.indicador_3, '1') indicador_3,
						coalesce(t.indicador_4, '1') indicador_4,
						coalesce(t.indicador_5, '1') indicador_5,
						coalesce(t.indicador_6, '1') indicador_6,
						coalesce(t.indicador_7, '1') indicador_7,
						coalesce(t.tiempo_maquina, 1) tiempo_maquina,
						coalesce(t.unidades_conteo, '1') unidades_conteo,
						coalesce(t.tiempo_hombre, 1) tiempo_hombre,
						coalesce(t.tiempo_turno, 1) tiempo_turno,
						coalesce(t.tiempo_paro_prog, 1) tiempo_paro_prog,
						coalesce(t.tiempo_paro_no_p, 1) tiempo_paro_no_p,
						coalesce(t.tiempo_paro_g1, 1) tiempo_paro_g1,
						coalesce(t.tiempo_paro_g2, 1) tiempo_paro_g2,
						coalesce(t.tiempo_paro_g3, 1) tiempo_paro_g3,
						coalesce(t.tiempo_total_paro, 1) tiempo_total_paro,
						coalesce(t.tiempo_std_prog, 1) tiempo_std_prog,
						coalesce(t.dato_extra, 1) dato_extra,
						coalesce(t.desperdicio_1, 1)desperdicio_1,
						coalesce(t.desperdicio_2, 1)desperdicio_2,
						coalesce(t.desperdicio_3, 1)desperdicio_3,
						coalesce(t.produccion_final, 1) produccion_final,
						coalesce(pp.var1, '1') var1,
						coalesce(pp.var2, '1') var2,
						coalesce(pp.var3, '1') var3,
						coalesce(p.dato_extra1, '1') dato_extra1,
						coalesce(p.dato_extra2, '1') dato_extra2,
						coalesce(p.dato_extra3, '1') dato_extra3,
						coalesce(t.unidades_desperdicio,'0') ud
				FROM turnos AS t
					LEFT JOIN producto_proceso pp ON pp.id_maquina = t.id_maquina AND pp.id_producto = t.id_producto
					LEFT JOIN productos p ON p.id_producto=t.id_producto
				WHERE t.id_maquina=$maquina AND t.id_turno = $turno";

		include("../clases/conexion.php");
		$variables=$conexion_pg->Execute($sql);
		if ($variables === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		$formulas=$fechas->listar_formulas();
		$i=0;
		while(!$formulas->EOF)
		{
			$formulas_r[$i]=$formulas->fields["formula"];
			$formulas->MoveNext();
			$i++;
		}

		for( $i = 0; $i < count($formulas_r); $i ++)
		{
			$formulas_r[$i]=str_replace("indicador_1",$variables->fields["indicador_1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_2",$variables->fields["indicador_2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_3",$variables->fields["indicador_3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_4",$variables->fields["indicador_4"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_5",$variables->fields["indicador_5"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_6",$variables->fields["indicador_6"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("indicador_7",$variables->fields["indicador_7"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_maquina",$variables->fields["tiempo_maquina"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("datop_extra",$variables->fields["dato_extra"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p1",$variables->fields["dato_extra1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p2",$variables->fields["dato_extra2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("dato_extra_p3",$variables->fields["dato_extra3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio1",$variables->fields["desperdicio_1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio2",$variables->fields["desperdicio_2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("desperdicio3",$variables->fields["desperdicio_3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("produccion_final",$variables->fields["produccion_final"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_averias",$variables->fields["tiempo_paro_g1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_cuadre_ajuste",$variables->fields["tiempo_paro_g2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("paro_pequeñas",$variables->fields["tiempo_paro_g3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_standart_prog",$variables->fields["tiempo_std_prog"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_hombre",$variables->fields["tiempo_hombre"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("total_paro",$variables->fields["tiempo_total_paro"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tot_par_no_p",$variables->fields["tiempo_paro_no_p"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tota_paro_p",$variables->fields["tiempo_paro_prog"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("tiempo_turno",$variables->fields["tiempo_turno"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("unidades_conteo",$variables->fields["unidades_conteo"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp1",$variables->fields["var1"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp2",$variables->fields["var2"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("Variable_pp3",$variables->fields["var3"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace("unidades_desperdicio",$variables->fields["ud"],$formulas_r[$i]);
			$formulas_r[$i]=str_replace(",",".",$formulas_r[$i]);
		}

		for( $i = 0; $i < count($formulas_r); $i ++)
		{
			$str = $formulas_r[$i].';';
			eval('$res='.$str);
			$indicadores[$i]= Abs($res);
			if(is_float($indicadores[$i]))
			{
				$indicadores[$i]=number_format($indicadores[$i],2,".","");
			}
		}

		$sql = "UPDATE turnos
				SET indicador_1='".$indicadores[0]."',
					indicador_2='".$indicadores[1]."',
					indicador_3='".$indicadores[2]."',
					indicador_4='".$indicadores[3]."',
					indicador_5='".$indicadores[4]."',
					indicador_6='".$indicadores[5]."',
					indicador_7='".$indicadores[6]."',
					tiempo_maquina='".$indicadores[7]."'
				WHERE id_maquina=$maquina AND id_turno = $turno";

		include("../clases/conexion.php");
        if ($conexion_pg->Execute($sql) === false)
            die("fallo consulta");
		$conexion_pg->Close();
	}

	function listar_procesos(){
		include("../clases/conexion.php");
		$sql =	"SELECT p.nombre_maquina nombre_maquina,
					p.id_maquina id_maquina,
					coalesce(t.terminado,1) t_terminado
				FROM maquinas p
					LEFT JOIN turnos t ON t.id_maquina = p.id_maquina
				WHERE p.estado = 1
				ORDER BY id_maquina";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}

	/**********************************************/
	/*METODOS QUE AFECTAN LOS TITULOS DEL REGISTRO*/
	/**********************************************/
    public function traerDatosDesperdicios() {
		$sql = "SELECT *
				FROM variables etq
				WHERE id_variable in (13, 14, 15, 16)
				ORDER BY id_variable";
		include("../clases/conexion.php");
	    $result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();
		$indice = 0;
		$datos = array();
		while(!$result->EOF){
			$datos[$indice] = $result->fields["etiqueta"];
			$indice++;
			$result->MoveNext();
		}
		return array(
			'desp1'	=> $datos[0],
			'desp2'	=> $datos[1],
			'desp3'	=> $datos[2],
			'prod'  => $datos[3]
		);
    }
	public function traerDatoExtraTurno() {
		$sql = "SELECT etiqueta
				FROM variables etq
				WHERE id_variable = 9";
		include("../clases/conexion.php");
	    $result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();
		$indice = 0;
		$datos ="";
		while(!$result->EOF){
			$datos = $result->fields["etiqueta"];
			$result->MoveNext();
		}
		return array(
			'dturno'	=> $datos
		);
    }
	/*******************************************/
	/*METODOS PARA RETIRAR PERSONAS DE UN TURNO*/
	/*******************************************/

	//ESTE METODO SE ENCARGARA DE PREGUNTAR CUANTOS OPERARIOS HAY ACTIVOS EN EL MOMENTO
	public function validarSalida($maquina){
		$maqturno = $this->getTurnoMaquina($maquina);
		$actuales=0;//en esta variable se guardara la cantidad de personas registradas actualmente
		include("../clases/conexion.php");
		$sql = "SELECT count(*) cnt
				FROM turno_asistencia
				WHERE id_turno = $maqturno
					AND id_maquina = $maquina
					AND teminado = 0";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");

		$actuales = $result->fields["cnt"];

		return $actuales;
	}

	public function sacarUsuario($usuario, $maquina){

		$maqturno = $this->getTurnoMaquina($maquina);
		$fechaactual= date("Y-m-d H:i:s");
		if($this->validarUsuarioSalida($usuario, $maquina) > 0){
			$sql = "UPDATE turno_asistencia
					SET	fecha_fin = '$fechaactual',
						teminado = 1
					WHERE id_maquina = $maquina
						AND id_turno = $maqturno
						AND id_empleado = $usuario";

			include("../clases/conexion.php");
			if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
			$conexion_pg->Close();

			return true;
		}
		else{
			return false;
		}
	}

	public function ingresarUsuario($usuario, $maquina){

		$maqturno = $this->getTurnoMaquina($maquina);
		$fechaactual= date("Y-m-d H:i:s");

		if($this->validarUsuarioIngreso($usuario, $maquina)){
			include("../clases/conexion.php");
			$sql = "INSERT INTO turno_asistencia (id_turno,id_maquina,id_empleado,fecha_inicio,fecha_fin) values ($maqturno,$maquina,$usuario,'$fechaactual','$fechaactual')";
			if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
			$conexion_pg->Close();

			return true;
		}
		else{
			return false;
		}
	}

	public function validarUsuarioIngreso($usuario, $maquina){
		$maqturno = $this->getTurnoMaquina($maquina);
		if($this->validarOperario($usuario)){
			include("../clases/conexion.php");
			$sql = "SELECT count(*) ctn
					FROM turno_asistencia
					WHERE id_turno = $maqturno
						AND id_maquina = $maquina
						AND id_empleado = $usuario
						AND teminado = 0";
			$result = $conexion_pg->Execute($sql);
			if ($result === false) die("fallo consulta");
			$conexion_pg->Close();

			if($result->fields['ctn']>=1){
				return false;
			}
			else{
				return true;
			}
		}
		else{
			return false;
		}
	}

	public function validarUsuarioSalida($usuario, $maquina){
		$maqturno = $this->getTurnoMaquina($maquina);
		$actuales=0;//en esta variable se guardara la cantidad de personas registradas actualmente
		include("../clases/conexion.php");
		$sql = "SELECT count(*) cnt
				FROM turno_asistencia
				WHERE id_turno = $maqturno
					AND id_maquina = $maquina
					AND id_empleado = $usuario
					AND teminado = 0";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");

		$actuales = $result->fields["cnt"];

		return $actuales;
	}
	/*****************************************************/
	/*METODO PARA VERIFICAR EL CIERRE AUTOMATICO DE TURNO*/
	/*****************************************************/
	public function verificarCierre($maquina){

		$maqturno = $this->getTurnoMaquina($maquina);

		$sql = "SELECT tiempo_turno
				FROM turnos
				WHERE id_turno = $maqturno and id_maquina = $maquina";

		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();

		$tiempoturno = $result->fields["tiempo_turno"];
		$horasturno = ($tiempoturno/3600);

		$sql = "SELECT maximo_horas
				FROM maquinas
				WHERE id_maquina = $maquina";

		include("../clases/conexion.php");
		$result = $conexion_pg->Execute($sql);
        if ($result === false)
            die("fallo consulta");
        $conexion_pg->Close();

		$maxhoras = $result->fields["maximo_horas"];

		if($horasturno > $maxhoras){
			$this->cerrarTurnoAutomatico($maquina, $maqturno);
		}
	}
	/*******************************************/
	/* METODO PARA EL LLAMADO DE MANTENIMIENTO */
	/*******************************************/
	//este metodo se ejecuta cuando se presiona el boton del telefono
	function llamado($maquina, $codigo){
		$maqturno = $this->getTurnoMaquina($maquina);
		include("../clases/conexion.php");
		if($codigo == 0){
			$sql = "UPDATE turnos
					SET	llamando = 1
					WHERE id_maquina = $maquina
						AND id_turno = $maqturno
						AND terminado = 0";
			if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		}
		else{
			$sql = "UPDATE turnos
					SET	llamando = 0
					WHERE id_maquina = $maquina
						AND id_turno = $maqturno
						AND terminado = 0";
			if ($conexion_pg->Execute($sql) === false) die("fallo consulta");
		}
		$conexion_pg->Close();
	}

	function esta_llamando($maquina){
		$maqturno = $this->getTurnoMaquina($maquina);
		include("../clases/conexion.php");
		$sql = "SELECT llamando
				FROM turnos
				WHERE id_turno = $maqturno
					AND id_maquina = $maquina
					AND terminado = 0";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");
		$resultado = $result->fields["llamando"];
		$conexion_pg->Close();
		return $resultado;
	}
//Esta funcion valida si la maquina en el parametro usa la versión lite, retorna TRUE de ser así, FALSE en caso contrario
	function es_lite($maquina){
		include("../clases/conexion.php");
		$sql = "SELECT captura
				FROM maquinas
				WHERE id_maquina = $maquina";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");
		$conexion_pg->Close();
		if($result->fields["captura"]==0){
			return true;
		}
		else{
			return false;
		}
	}

	function getOEEReal($maquina){
		$maqturno = $this->getTurnoMaquina($maquina);

		include("../clases/conexion.php");
		$sql = "SELECT t.indicador_5 oee_real
				FROM maquinas p
					LEFT JOIN turnos t ON p.id_maquina = t.id_maquina AND t.id_turno = p.batch
				WHERE p.id_maquina = '$maquina'";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");
		$conexion_pg->Close();
		return $result->fields["oee_real"];
	}

	function getMetaOEE($maquina){
		include("../clases/conexion.php");
		$sql = "SELECT p.oee_minimo meta_oee
				FROM maquinas p
				WHERE p.id_maquina = '$maquina'";
		$result = $conexion_pg->Execute($sql);
        if ($result === false)die("fallo consulta");
		$conexion_pg->Close();
		return $result->fields["meta_oee"];
	}
}

?>
