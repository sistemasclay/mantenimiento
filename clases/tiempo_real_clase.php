<?php
/**
 * Description of teimpo real
 * esta clase esta asociada a la informacion del dash board de las maquinas del
 * sistema.
 * @author Juan Pablo Giraldo
 */
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");

ini_set('SMTP','localhost');
ini_set('smtp_port',25);
ini_set('sendmail_from','servidor.maquinas@noreply.com');

class tiempo_real {

	
	
	function listar_turno_asistencia($id_turno,$id_proceso)
	{		
        include("conexion.php");
		$sql = "SELECT *
		FROM turno_asistencia as a INNER JOIN personal as b ON (a.id_empleado= b.id_persona)
		WHERE id_turno='".$id_turno."' AND id_proceso='".$id_proceso."'";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	
	function variables($id_variable) 
	{
		
		include("conexion.php");
		$sql =	"SELECT * from variables where id_variable='".$id_variable."'";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
		
	function insertar($sql)
	{

		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if($result==false)
		{ 
			$result=$conexion_pg->ErrorMsg(); 
		}
		$conexion_pg->Close();		 
		return $result;
		
	}
		
	function parametro_oee()
	{		
		include("conexion.php");
		$sql =	"SELECT porcentaje from parametro_oee ";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
	}
		
	function listado_mensajes()
	{
		include("conexion.php");
		$sql =	"SELECT * from mensajes order by fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
        {
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
        }
		$conexion_pg->Close();
		return $result;
	
	}
		
	function mensaje_especifico($fecha_inicio, $fecha_fin)
	{	
		include("conexion.php");
		$sql = "SELECT  *  FROM mensajes WHERE  fecha_inicio='".$fecha_inicio."' and fecha_fin='".$fecha_fin."'";
		$result=$conexion_pg->Execute($sql);
		$conexion_pg->Close();
		return $result;	
	}
	
	function listar_etiquetas()
	{ 		
		include("conexion.php");
		$sql = "SELECT * FROM variables order by id_variable";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		$i=0;
        while (!$result->EOF)
		{
			$ar[$i]["etiqueta"]=$result->fields["etiqueta"];
			$result->MoveNext();
			$i++;
		}
		return $ar;
    }

	function contar() 
	{
	
		include("conexion.php");
		$sql = "SELECT count(*) as cuenta FROM turnos WHERE  terminado='0'";
		$result=$conexion_pg->Execute($sql);
		$num= $result->fields["cuenta"];
		$conexion_pg->Close();
		 
		return $num;
	}
	
	function mensajes()
	{
		include("conexion.php");
		$sql = "SELECT  *  FROM mensajes WHERE  fecha_inicio<= now() and now()<=fecha_fin ";

		$result=$conexion_pg->Execute($sql);
		$conexion_pg->Close();
		 
		return $result;
		  
	}
	
	function ranking()
    {
        include("conexion.php");
        $sql =	"SELECT t.*,p.nombre FROM turnos as t,procesos as p WHERE t.id_proceso=p.id_proceso and terminado='0' order by unidades_conteo::float desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
            {
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
            }
         $conexion_pg->Close();
         return $result;
    }
	
    //funciones para las plantas
    function listar_turnos()
    {
        include("conexion.php");
        $sql =	"SELECT t.*,p.nombre, p.oee_minimo FROM turnos as t,procesos as p WHERE t.id_proceso=p.id_proceso and terminado='0' order by id_proceso";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
            {
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
            }
		$conexion_pg->Close();
		return $result;
    }

	function traer_parada_turno($id_turno,$id_proceso) //la actual
    {
        include("conexion.php");
		$sql =	"SELECT * FROM turno_parada as b left JOIN paradas as a ON (b.id_parada= a.id_parada) WHERE terminado='0' AND id_turno='".$id_turno."' AND id_proceso='".$id_proceso."'";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$conexion_pg->Close();
		return $result;
    }

    function traer_paradas_turno($id_turno,$id_proceso)
    {
		include("conexion.php");
		$sql =	"SELECT * FROM turno_parada WHERE  id_turno='$id_turno' AND id_proceso='$id_proceso'";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		//Codigo añadido para manejar las alertas por correo
/*		 
		$parada = $result->fields[2];
		if($parada != '' and $parada != '999')
		{
			$sql2 = "SELECT * FROM em_paradas WHERE id_parada='$parada'";
			$result2=$conexion_pg->Execute($sql2);
			$sql3 = "SELECT enviado FROM turno_parada WHERE id_turno='$id_turno' AND id_proceso='$id_proceso' AND terminado = '0'";
			$result3=$conexion_pg->Execute($sql3);
			if ($result2 === false)
			{
				echo 'error al listar emails: '.$conexion_pg->ErrorMsg().'<BR>';
			}
			else
			{
				while(!$result2->EOF and $result3->fields[0] == 0) {
					$mail_to = $result2->fields[2];
					$subject = 'HAY UNA MAQUINA PARADA. CODIGO PARADA '.$parada;
					
					$sql4="SELECT nombre_parada FROM paradas WHERE id_parada='$parada'";
					$result4=$conexion_pg->Execute($sql4);
					$body_message = 'Hay una maquina que esta parada por '.$result4->fields[0];
					
					$mail_status = mail($mail_to, $subject, $body_message);
					if($mail_status) 
					{
						
					}
					else 
					{
						echo 'El correo no pudo ser enviado';
					}
					$result4->Close();
					$result2->MoveNext();
				}
				$sql5 = "UPDATE turno_parada SET enviado = 1 WHERE id_turno='$id_turno' AND id_proceso='$id_proceso'";
				if ($conexion_pg->Execute($sql5) === false) {
					echo 'error al insertar: '.$conexion_pg->ErrorMsg().'<BR>';
				}
				$result2->Close();
			}
		}
//*/		 
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
	
	function progreso_orden_ejecucion($orden){
		include("conexion.php");
		
		$sql = "SELECT	unidades_conteo
				FROM turnos
				WHERE terminado = 0 AND orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		while(!$result->EOF){
			$cant_terminada = $cant_terminada + $result->fields[0];
			$result->MoveNext();
		}
		
		$sql = "SELECT cantidad, cant_terminada
				FROM ordenes_produccion
				WHERE id_orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$cantidad=$result->fields['cantidad'];
		$cant_terminada = $cant_terminada + $result->fields['cant_terminada'];
		$porcentaje = round(($cant_terminada*100)/$cantidad,2);
		$conexion_pg->Close();
		return $porcentaje;
	}
	
	function progreso_orden_ejecucion2($orden){ 
		include("conexion.php");
		
		$sql = "SELECT	unidades_conteo
				FROM turnos
				WHERE terminado = 0 AND orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		while(!$result->EOF){
			$cant_terminada = $cant_terminada + $result->fields[0];
			$result->MoveNext();
		}
		
		$sql = "SELECT cantidad, cant_terminada
				FROM ordenes_produccion
				WHERE id_orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$cantidad=$result->fields['cantidad'];
		$cant_terminada = $cant_terminada + $result->fields['cant_terminada'];
		$porcentaje = round(($cant_terminada*100)/$cantidad,2);
		$conexion_pg->Close();
		return $cant_terminada." de ".$cantidad;
	}
	
	function listar_procesos(){
		include("conexion.php");
		$sql = "SELECT * FROM PROCESOS";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listater: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
		
	}
	
	//retorna 0 si no hay maquinas paradas, de lo contrario envia un numero equivalente a la cantidad de maquinas paradas
	function hay_parada_999(){
		include("conexion.php");
        $sql=	"SELECT count(*) paradas
				FROM turno_parada 
				WHERE terminado = 0
				 AND id_parada = 999";
		$result=$conexion_pg->Execute($sql);
		if ($result === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$cant_parada = $result->fields['paradas'];
		/*if($cant_parada > 0){
			$sql="UPDATE turno_parada SET anunciado = 1 WHERE id_turno_parada IN(".$this->traer_paradas_999().")";
			if ($conexion_pg->Execute($sql) === false){
				echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
			}
		}*/
		
		$conexion_pg->Close();
		return $cant_parada;
	}
	
	function traer_paradas_999(){
		include("conexion.php");
        $sql=	"SELECT id_turno_parada paradas
				FROM turno_parada 
				WHERE terminado = 0
				 AND id_parada = 999";
		$result=$conexion_pg->Execute($sql);
		$paradas = "";		
		if ($result === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		while(!$result->EOF){
			$paradas = $paradas.$result->fields['paradas'];
			$result->MoveNext();
			if(!$result->EOF){
				$paradas = $paradas.",";
			}
		}
		$conexion_pg->Close();
		return $paradas;
	}
	
	function traer_on_sin_turno(){
		include("conexion.php");
        $sql="	SELECT max(conteo) conteo, p.id_proceso, CASE WHEN (max(t.terminado) IS NULL) THEN 0 ELSE 1 END con_turno
				FROM conteos
					INNER JOIN procesos p ON p.puerto = id_puerto
					LEFT JOIN turnos t ON p.id_proceso = t.id_proceso AND terminado = 0
				GROUP BY p.id_proceso";
		$result=$conexion_pg->Execute($sql);
		$on_sin_turno = 0;		
		if ($result === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		while(!$result->EOF){
			$conteo = $result->fields['conteo'];
			$conturno = $result->fields['con_turno']; // si es 0 entonces no hay turno abierto, si es 1 si lo hay
			if($conteo != 0 && $conturno == 0){
				$on_sin_turno = 1;
				break;
			}			
			$result->MoveNext();
		}	
		$conexion_pg->Close();
		return $on_sin_turno;
	}
	
	//ESTE METODO RETORNA 0 SI NO HAY MAQUINAS CON BAJO OEE Y 1 SI SI LAS HAY
	function baja_productividad (){
		
		include("conexion.php");
        $sql="	SELECT p.id_proceso, t.indicador_4 oee, case when (t.indicador_4 < p.oee_minimo) then 1 else 2 end estado_oee
				FROM turnos t
					INNER JOIN procesos p ON p.id_proceso = t.id_proceso
				WHERE t.terminado = 0
					AND baja_anunciada = 0";
		$result=$conexion_pg->Execute($sql);
		$baja_productividad = 0;//esta variable se retorna como 0 si no hay ninguna máquina con bajo oee y retorna 1 en caso contrario
		if ($result === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		while(!$result->EOF){
			$estado_oee = $result->fields['estado_oee'];//si es 1 entonces el OEE esta bajo si es 2 entonces esta bien
			if($estado_oee == 1){
				$baja_productividad = 1;
				break;
			}
			else{
				
			}
			$result->MoveNext();
		}
		$conexion_pg->Close();
		if($baja_productividad == 1){
			$this->anunciar();
		}
		else{
			$this->cambiar_anunciado();
		}
		
		return $baja_productividad;
		
	}
	
	function anunciar(){
		include("conexion.php");
        $sql="	UPDATE turnos
				SET	baja_anunciada = 1
				WHERE terminado = 0";
		if ($conexion_pg->Execute($sql) === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
	}
	
		function cambiar_anunciado(){
		include("conexion.php");
        $sql="	UPDATE turnos
				SET	baja_anunciada = 0
				WHERE terminado = 0";
		if ($conexion_pg->Execute($sql) === false){
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
	}
	
}
?>


