<?php
/**
 * Description of pantallas_gigantes
 * en esta clase se encunetran todos los metodos necesarios para 
 * el funcionamiento de las pantallas gigantes
 * @author Juan Pablo Giraldo
 */
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");
class pantallas_gigantes {
	
	// METODO PARA LISTAR LOS DATOS DE LOS TURNOS ABIERTOS
	public function listar_turnos(){
		include("conexion.php");
        $sql = "SELECT m.nombre maquina, t.indicador_4 oee, t.unidades_conteo unid, coalesce(tp.terminado,'1') tp, coalesce(p.nombre_parada,'0') np
				FROM turnos t
					INNER JOIN procesos m ON m.id_proceso = t.id_proceso
					LEFT JOIN turno_parada tp ON tp.id_turno = t.id_turno AND tp.id_proceso = t.id_proceso AND tp.terminado = 0
					LEFT JOIN paradas p ON p.id_parada = tp.id_parada
				WHERE t.terminado = 0
				ORDER BY m.id_proceso";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result;
	}
	
	public function contar_turnos_abiertos(){
		include("conexion.php");
        $sql = "SELECT count(*) num_turnos
				FROM turnos t
				WHERE t.terminado = 0";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result->fields['num_turnos'];
	}
	
	public function ranking(){
		include("conexion.php");
        $sql = "SELECT m.nombre maquina, t.indicador_4 oee, t.unidades_conteo unid, m.id_proceso id
				FROM turnos t
					INNER JOIN procesos m ON m.id_proceso = t.id_proceso
				WHERE t.terminado = 0
				ORDER BY t.indicador_4 DESC";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result;
	}
	//ESTE METODO REGRESA TODOS LOS TURNOS ABIERTOS COMPRENDIDOS ENTRE LA MAQUINA INICIAL Y LA FINAL IDENTIFICANDOLAS POR EL ID
	//
	public function listar_turnos_maquinas($maqInicial,$maqFinal){
		include("conexion.php");
        $sql = "SELECT m.nombre maquina, t.indicador_4 oee, t.unidades_conteo unid, coalesce(tp.terminado,'1') tp, coalesce(p.nombre_parada,'0') np
				FROM turnos t
					INNER JOIN procesos m ON m.id_proceso = t.id_proceso
					LEFT JOIN turno_parada tp ON tp.id_turno = t.id_turno AND tp.id_proceso = t.id_proceso AND tp.terminado = 0
					LEFT JOIN paradas p ON p.id_parada = tp.id_parada
				WHERE t.terminado = 0 AND (t.id_proceso BETWEEN $maqInicial AND $maqFinal)
				ORDER BY m.id_proceso";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result;
	}
	
	public function contar_turnos_abiertos_maquinas($maqInicial,$maqFinal){
		include("conexion.php");
        $sql = "SELECT count(*) num_turnos
				FROM turnos t
				WHERE t.terminado = 0
					AND (t.id_proceso BETWEEN $maqInicial AND $maqFinal)";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result->fields['num_turnos'];
	}
	// METODOS PARA LA VERSION 3 DE LAS PANTALLAS GIGANTES
	//SE REQUIERE EL CAMBIO INFINITO DE PANTALLAS HASTA LLEGAR A LA ULTIMA MAQUINA Y FINALIZAR CON EL RANKING DE MAQUINAS
	public function listar_ids_maquinas(){
		include("conexion.php");
        $sql = "SELECT t.id_proceso proceso
				FROM turnos t
				WHERE t.terminado = 0
				ORDER BY t.id_proceso";
		$result = $conexion_pg->Execute($sql);
		if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$i=0;
		$datos = array();
		while(!$result->EOF){
			$datos[$i] = $result->fields['proceso'];
			$result->MoveNext();
			$i++;
		}
		
		$conexion_pg->Close();
		return $datos;
	}
		//este metodo separa la lista de completa de ids de maquinas en sub listas de 8 ids maquinas
	public function separar_en_8($maquinas){
		$en8 = array();
		$largo = count($maquinas);
		$i = 0;
		$i1_sublist = 0; //este iterador representa cuantas sublistas hay
		$i2_sublist = 0; //este iterador representa cuantos datos hay en la sublista, la cantidad maxima de datos será de 8
		while($i<$largo){
			$en8[$i1_sublist][$i2_sublist] = $maquinas[$i];
			$i++;
			$i2_sublist++;
			if($i2_sublist%8==0){
				$i2_sublist = 0;
				$i1_sublist++;
			}
		}
		return $en8;
	}
		//el arrray que le va a llegar a este metodo es una lista de los id de las maquinas que se desea mostrar maximo 8 maquinas
	public function listar_turnos_lista($array){
		$lista_maquinas = "";
		$i=0;
		$size = count($array);
		while($i<$size){
			$lista_maquinas = $lista_maquinas.$array[$i];
			$i++;
			if($i%$size!=0){
				$lista_maquinas = $lista_maquinas.",";
			}
		}
		include("conexion.php");
        $sql = "SELECT m.nombre maquina, 
						t.indicador_4 oee, 
						t.unidades_conteo unid, 
						coalesce(tp.terminado,'1') tp, 
						coalesce(p.nombre_parada,'0') np, 
						coalesce(tp.horas,0) tiempo,
						m.id_proceso id
				FROM turnos t
					INNER JOIN procesos m ON m.id_proceso = t.id_proceso
					LEFT JOIN turno_parada tp ON tp.id_turno = t.id_turno AND tp.id_proceso = t.id_proceso AND tp.terminado = 0
					LEFT JOIN paradas p ON p.id_parada = tp.id_parada
				WHERE t.terminado = 0 AND (t.id_proceso IN ($lista_maquinas))
				ORDER BY m.id_proceso";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result;
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
	
	function traer_parametro_oee($maquina){
		include("conexion.php");
        $sql = "SELECT oee_minimo
				FROM procesos
				WHERE id_proceso = $maquina";
		$result = $conexion_pg->Execute($sql);
        if ($result === false) {
			echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
        $conexion_pg->Close();
		
		return $result;
	}
}
?>