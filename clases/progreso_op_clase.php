<?php
/**
 * Descripcion de progreso_ops
 * @author Juan Pablo Giraldo
 */
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");

ini_set('memory_limit','1024M');

class progreso_op {
	
	function listar_ops_inconclusas(){
		
        include("conexion.php");
       $sql = "SELECT *
		FROM ordenes_produccion
		WHERE terminada = 0 
		ORDER BY fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	function listar_ops_inconclusas_fecha($fechai,$fechaf){
		
        include("conexion.php");
       $sql = "SELECT *
		FROM ordenes_produccion
		WHERE terminada = 0 AND fecha_inicio BETWEEN '$fechai' and '$fechaf'
		ORDER BY fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	function listar_ops_historico(){
		
        include("conexion.php");
       $sql = "SELECT *
		FROM ordenes_produccion 
		ORDER BY fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
		
	function listar_ops_historico_fecha($fechai,$fechaf){
		
        include("conexion.php");
       $sql = "SELECT *
		FROM ordenes_produccion 
		WHERE fecha_inicio BETWEEN '".$fechai."' and '".$fechaf."'
		ORDER BY fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	function listar_ops_ejecutandose(){
		
        include("conexion.php");
		$sql = "SELECT 	id_orden_produccion, 
						orden.id_proceso, 
						orden.id_producto, 
						cantidad, 
						orden.estado, 
						orden.fecha_inicio, 
						orden.fecha_fin
				FROM ordenes_produccion orden
					INNER JOIN turnos t ON id_orden_produccion = orden_produccion AND t.terminado = 0
				ORDER BY orden.fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	function listar_ops_ejecutandose_fecha($fechai,$fechaf){
        include("conexion.php");
		$sql = "SELECT id_orden_produccion, 
						orden.id_proceso, 
						orden.id_producto, 
						cantidad, 
						orden.estado, 
						orden.fecha_inicio, 
						orden.fecha_fin
				FROM ordenes_produccion orden
					INNER JOIN turnos t ON id_orden_produccion = orden_produccion AND t.terminado = 0
				WHERE orden.fecha_inicio BETWEEN '$fechai' and '$fechaf'
				ORDER BY orden.fecha_inicio desc";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
		
    }
	
	function datos_op($orden){
		include("conexion.php");
		$sql = "SELECT *
				FROM ordenes_produccion
				WHERE id_orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
	
	function OEE_orden($orden){
		include("conexion.php");
		$sql = "SELECT 	orden_produccion,
						sum(cast(indicador_5 as numeric)*tiempo_turno)/sum(tiempo_turno) oee_op
				FROM turnos
				WHERE orden_produccion = ".$orden." GROUP BY orden_produccion";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$oee_orden = round($result->fields['oee_op'],2);
		
		return $oee_orden;
	}
	
	function desperdicio_orden($orden){
		include("conexion.php");
		$sql = "SELECT 	orden_produccion,
						(sum(coalesce(desperdicio_1,0))+sum(coalesce(desperdicio_2,0))+sum(coalesce(desperdicio_3,0))) desperdicio
				FROM turnos
				WHERE orden_produccion = ".$orden." GROUP BY orden_produccion";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$desperdicio = $result->fields['desperdicio'];
		
		return $desperdicio;
	}
	
	function listar_turnos_op($orden){
		include("conexion.php");
		$sql = "SELECT *
				FROM turnos t
				INNER JOIN procesos proc ON proc.id_proceso = t.id_proceso
				WHERE orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
	
	function listar_operarios_turno($turno, $maquina){
		include("conexion.php");
		$sql = "SELECT nombre_persona
				FROM turno_asistencia
				INNER JOIN personal ON id_persona = id_empleado
				WHERE id_turno = ".$turno." AND id_proceso = ".$maquina;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$cadena="";
		while(!$result->EOF){
			$cadena.=$result->fields['nombre_persona'];
			$result->MoveNext();
			if(!$result->EOF){
				$cadena.=',';
			}
		}
		
		$conexion_pg->Close();
		return $cadena;
	}
		
	function progreso_orden($orden){
		include("conexion.php");
		$sql = "SELECT cantidad, cant_terminada
		FROM ordenes_produccion
		WHERE id_orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$cantidad=$result->fields['cantidad'];
		$cant_terminada=$result->fields['cant_terminada'];
		
		$porcentaje = round(($cant_terminada*100)/$cantidad,2);
		
		$conexion_pg->Close();
		return $porcentaje;
	}
	
	function progreso_orden_ejecucion($orden){ //Juan Pablo Giraldo
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
	
	function producto_orden($orden){
		include("conexion.php");
		$sql = "SELECT p.id_producto id_producto, p.nombre_producto nombre_producto
		FROM ordenes_produccion op
			INNER JOIN productos p ON op.id_producto = p.id_producto
		WHERE op.id_orden_produccion = ".$orden;
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;		
	}
	
	function contar(){
	
		include("conexion.php");
		$sql = "SELECT count(*) as cuenta FROM ordenes_produccion WHERE  terminada=0";
		$result=$conexion_pg->Execute($sql);
		$num= $result->fields["cuenta"];
		$conexion_pg->Close();
		 
		return $num;	  
	}
	
	function fecha_ultimo_turno($orden){
		include("conexion.php");
		$sql = "SELECT orden_produccion, max(fecha_fin) fecha_fin
				FROM turnos
				WHERE orden_produccion = ".$orden."  
				GROUP BY orden_produccion";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$fecha_fin = $result->fields["fecha_fin"];
		return $fecha_fin;
	}
	
	 function tiempo_segundos($segundos){
		$minutos=$segundos/60;
		$horas=floor($minutos/60);
		$minutos2=$minutos%60;
		$segundos_2=$segundos%60%60%60;
		if($minutos2<10)$minutos2='0'.$minutos2;
		if($segundos_2<10)$segundos_2='0'.$segundos_2;
		if($segundos<60){ /* segundos */
		$resultado= '00:00:'.round($segundos);
		}elseif($segundos>60 && $segundos<3600){/* minutos */
		$resultado= '00:'.$minutos2.':'.$segundos_2;
		}else{/* horas */
			if($horas<10){$horas='0'.$horas;}
		$resultado= $horas.":".$minutos2.":".$segundos_2;
		}
		return $resultado;
	}
	
	function tiempos_indicadores_op($orden){
		include("conexion.php");
		$sql = "SELECT	orden_produccion op,
						sum(tiempo_total_paro)tiempo_total_paro,
						sum(tiempo_paro_prog)tiempo_paro_prog,
						sum(tiempo_paro_no_p)tiempo_paro_no_p,
						sum(tiempo_paro_g1)tiempo_paro_g1,
						sum(tiempo_paro_g2)tiempo_paro_g2,
						sum(tiempo_paro_g3)tiempo_paro_g3,
						sum(tiempo_std_prog)tiempo_std_prog,
						sum(tiempo_hombre)tiempo_hombre,
						sum(tiempo_maquina)tiempo_maquina,
						round(sum(cast(indicador_1 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_1,
						round(sum(cast(indicador_2 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_2,
						round(sum(cast(indicador_3 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_3,
						round(sum(cast(indicador_4 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_4,
						round(sum(cast(indicador_5 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_5,
						round(sum(cast(indicador_6 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_6,
						round(sum(cast(indicador_7 as numeric)*tiempo_turno)/sum(tiempo_turno),2)indicador_7,
						sum(cast(unidades_conteo as numeric))unidades_conteo,
						sum(produccion_final)produccion_final,
						sum(desperdicio_1)desperdicio_1,
						sum(desperdicio_2)desperdicio_2,
						sum(desperdicio_3)desperdicio_3
				FROM turnos
				WHERE orden_produccion = ".$orden." 
				GROUP BY orden_produccion";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
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
			$ar[$i]['etiqueta']=$result->fields['etiqueta'];
			$result->MoveNext();
			$i++;
		}
		return $ar;
    }
	
	function asistencia_op($orden)
	{
		include("conexion.php");
		$sql = "SELECT 	ta.id_empleado id_empleado,
						p.nombre_persona nombre_persona,
						t.fecha_inicio fecha_inicio,
						t.fecha_fin fecha_fin,
						t.tiempo_hombre tiempo_hombre,
						t.id_turno id_turno,
						t.id_proceso id_proceso
				FROM turnos t
					INNER JOIN turno_asistencia ta ON t.id_turno = ta.id_turno AND t.id_proceso = ta.id_proceso
					INNER JOIN personal p ON p.id_persona = ta.id_empleado
				WHERE orden_produccion = ".$orden." 
				ORDER BY t.id_turno, t.id_proceso";
				
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}
	function reagrupar_ordenes()
	{
		include("conexion.php");
		$sql = "SELECT 	t.id_turno,
						t.id_proceso,
						orden_produccion,
						id_tabla,
						(CURRENT_DATE - CAST(t.fecha_inicio AS DATE)) intervalo,
						dias_terminar_auto dias,
						porc_terminar_auto porcentaje
				FROM turnos t
				INNER JOIN (SELECT	max(id_tabla) tabla,
									orden_produccion op
							FROM turnos
							GROUP BY orden_produccion) tp ON id_tabla = tp.tabla
				INNER JOIN ordenes_produccion ON id_orden_produccion = orden_produccion";
					
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		while (!$result->EOF)
		{
			$op = $result->fields[2];
			$progreso = $this->progreso_orden($op);
			if($result->fields["intervalo"] >= $result->fields["dias"] && $progreso >= $result->fields["porcentaje"])
			{
				$sql2 = "update ordenes_produccion set terminada = '1' where id_orden_produccion = ".$result->fields["orden_produccion"]." ";
				$result2=$conexion_pg->Execute($sql2);
				if ($result === false)
				{
					echo 'error al actualizar: '.$conexion_pg->ErrorMsg().'<BR>';
				}
				$result2->Close();
			}
			$result->MoveNext();
		}
		$result->Close();
		$conexion_pg->Close();
	}
	
	function cerrar_orden ($orden){
		include("conexion.php");
		$sql2 = "update ordenes_produccion set terminada = '1' where id_orden_produccion = ".$orden." ";
		$result2=$conexion_pg->Execute($sql2);
		if ($result === false)
		{
			echo 'error al actualizar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$result2->Close();
		$conexion_pg->Close();
	}
	
	function exportar_excel($fechai, $fechaf){
	
		include("conexion.php");
	
		require_once 'PHPExcel.php';
		
		/** PHPExcel_IOFactory */
		require_once 'PHPExcel/IOFactory.php';
		
		// Se crea el objeto PHPExcel
		$objPHPExcel = new PHPExcel();

		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator("Monitoreo y Control") //Autor
							 ->setLastModifiedBy("Monitoreo y Control") //Ultimo usuario que lo modificó
							 ->setTitle("Reporte de Ordenes")
							 ->setSubject("Reporte de Ordenes")
							 ->setDescription("Reporte de Ordenes")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("reporte excel");
							 
							 
		$estiloTituloReporte = array(
        	'font' => array(
	        	'name'      => 'Arial',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size' 		=>14,
	            	'color'	=> array(
						'rgb' => '000082'
        	       	)
            ),
			'borders' => array(
               	'allborders' => array(
					'style'	=> PHPExcel_Style_Border::BORDER_NONE                    
               	)
            ), 
            'alignment' =>  array(
        			'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'	=> 0,
        			'wrap'		=> TRUE
    		)
        );		
		
		$estiloTituloColumnas = array(
            'font' => array(
                'name'	=> 'Arial',
                'bold'	=> true,                          
                'color'	=> array(
					'rgb'	=> '000000'
                )
            ),
			'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('rgb' => '969696')
			),
			'alignment' =>  array(
        		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        		'wrap'       => TRUE
    		)
		);
		$estiloInformacion = array(
           	'font' => array(
				'name'	=> 'Arial',               
               	'color'	=> array(
					'rgb' => '000000'
               	)
           	),
        );
		$estiloInformacion2 = array(
           	'font' => array(
				'name'      => 'Arial',               
               	'color'     => array(
					'rgb' => '000000'
               	)
           	),
			'alignment' =>  array(
        			'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'      => TRUE
    		),
        );

		//						//
		//	HOJA DE EJECUCION	//
		//						//
		
		IF($fechai)
		{
			$result = $this->listar_ops_ejecutandose_fecha($fechai,$fechaf);
			$tituloReporte = "ORDENES EN EJCUCION ENTRE $fechai y $fechaf";
		}
		ELSE
		{
			$result = $this->listar_ops_ejecutandose();
			$tituloReporte = "ORDENES EN EJCUCION";
		}
		
		$titulosColumnas = array('Orden Producción', 'Id Producto', 'Producto', 'Progreso', 'Unidades Ordenadas', 'Unidades Terminadas', 'Unidades Restantes', 'OEE', 'Desperdicio');
		
		$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells('A1:i1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3])
        		    ->setCellValue('E2',  $titulosColumnas[4])
        		    ->setCellValue('F2',  $titulosColumnas[5])
        		    ->setCellValue('G2',  $titulosColumnas[6])
        		    ->setCellValue('H2',  $titulosColumnas[7])
        		    ->setCellValue('I2',  $titulosColumnas[8]);
		
		$i = 3;
		while (!$result->EOF) {
			
			$id_op			= $result->fields['id_orden_produccion'];
			$id_proceso		= $result->fields['id_orden_proceso'];
			$id_producto	= $result->fields['id_producto'];
			$cantidad		= $result->fields['cantidad'];
			$estado			= $result->fields['estado'];
			$fecha_inicio	= $result->fields['fecha_inicio'];
			$fecha_fin		= $result->fields['fecha_fin'];
			$cantidad_ter	= $result->fields['cant_terminada'];
			
			$progreso 		= $this->progreso_orden_ejecucion($id_op);
			$producto		= $this->producto_orden($id_op);
			$oee 			= $this->OEE_orden($id_op);
			$desperdicio 	= $this->desperdicio_orden($id_op);
			$cant_rest		= $cantidad - $cant_terminada;
						
			$objPHPExcel->setActiveSheetIndex(0)
        		    ->setCellValue('A'.$i,	$id_op." ")
		            ->setCellValue('B'.$i,	$id_producto." ")
        		    ->setCellValue('C'.$i,	$producto->fields["nombre_producto"])
        		    ->setCellValue('D'.$i,	$progreso)
        		    ->setCellValue('E'.$i,	$cantidad)
        		    ->setCellValue('F'.$i,	$cantidad_ter)
        		    ->setCellValue('G'.$i,	$cant_rest)
        		    ->setCellValue('H'.$i,	$oee)
        		    ->setCellValue('I'.$i,	$desperdicio);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estiloTituloColumnas);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('C3:C'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('D3:D'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('E3:G'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('H3:I'.($i-1))->applyFromArray($estiloInformacion2);
		
		/*for($i = 'A'; $i <= 'I'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}*/
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
		
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
		
		
		$objPHPExcel->getActiveSheet()->setTitle('EJECUCION');
		
		//						//
		//	HOJA DE INCONCLUSAS	//
		//						//
		
		$objPHPExcel->createSheet(1);
		
		IF($fechai)
		{
			$result = $this->listar_ops_inconclusas_fecha($fechai,$fechaf);
			$tituloReporte = "ORDENES INCONCLUSAS ENTRE $fechai y $fechaf";
		}
		ELSE
		{
			$result = $this->listar_ops_inconclusas();
			$tituloReporte = "ORDENES INCONLUSAS";
		}
		
		$titulosColumnas = array('Orden Producción', 'Id Producto', 'Producto', 'Progreso', 'Unidades Ordenadas', 'Unidades Terminadas', 'Unidades Restantes', 'OEE', 'Desperdicio');
		
		$objPHPExcel->setActiveSheetIndex(1)
					->mergeCells('A1:i1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3])
        		    ->setCellValue('E2',  $titulosColumnas[4])
        		    ->setCellValue('F2',  $titulosColumnas[5])
        		    ->setCellValue('G2',  $titulosColumnas[6])
        		    ->setCellValue('H2',  $titulosColumnas[7])
        		    ->setCellValue('I2',  $titulosColumnas[8]);

		$i = 3;
		while (!$result->EOF) {
			
			$id_op			= $result->fields['id_orden_produccion'];
			$id_proceso		= $result->fields['id_orden_proceso'];
			$id_producto	= $result->fields['id_producto'];
			$cantidad		= $result->fields['cantidad'];
			$estado			= $result->fields['estado'];
			$fecha_inicio	= $result->fields['fecha_inicio'];
			$fecha_fin		= $result->fields['fecha_fin'];
			$cantidad_ter	= $result->fields['cant_terminada'];
			
			$progreso 		= $this->progreso_orden($id_op);
			$producto		= $this->producto_orden($id_op);
			$oee 			= $this->OEE_orden($id_op);
			$desperdicio 	= $this->desperdicio_orden($id_op);
			$cant_rest		= $cantidad - $cant_terminada;
				
			$objPHPExcel->setActiveSheetIndex(1)
        		    ->setCellValue('A'.$i,	$id_op." ")
		            ->setCellValue('B'.$i,	$id_producto." ")
        		    ->setCellValue('C'.$i,	$producto->fields["nombre_producto"])
        		    ->setCellValue('D'.$i,	$progreso)
        		    ->setCellValue('E'.$i,	$cantidad)
        		    ->setCellValue('F'.$i,	$cantidad_ter)
        		    ->setCellValue('G'.$i,	$cant_rest)
        		    ->setCellValue('H'.$i,	$oee)
        		    ->setCellValue('I'.$i,	$desperdicio);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estiloTituloColumnas);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('C3:C'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('D3:D'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('E3:G'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('H3:I'.($i-1))->applyFromArray($estiloInformacion2);
		
		/*for($i = 'A'; $i <= 'I'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}*/
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
		
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
		
		
		$objPHPExcel->getActiveSheet()->setTitle('INCONCLUSAS');
		
				//						//
		//	HOJA DE EJECUCION	//
		//						//
		
		$objPHPExcel->createSheet(2);
		
		IF($fechai)
		{
			$result = $this->listar_ops_historico_fecha($fechai,$fechaf);
			$tituloReporte = "ORDENES EN EJCUCION ENTRE $fechai y $fechaf";
		}
		ELSE
		{
			$result = $this->listar_ops_historico();
			$tituloReporte = "ORDENES EN EJCUCION";
		}
		
		$titulosColumnas = array('Orden Producción', 'Id Producto', 'Producto', 'Progreso', 'Unidades Ordenadas', 'Unidades Terminadas', 'Unidades Restantes', 'OEE', 'Desperdicio');
		
		$objPHPExcel->setActiveSheetIndex(2)
					->mergeCells('A1:i1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(2)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3])
        		    ->setCellValue('E2',  $titulosColumnas[4])
        		    ->setCellValue('F2',  $titulosColumnas[5])
        		    ->setCellValue('G2',  $titulosColumnas[6])
        		    ->setCellValue('H2',  $titulosColumnas[7])
        		    ->setCellValue('I2',  $titulosColumnas[8]);
		
		$i = 3;
		while (!$result->EOF) {
			
			$id_op			= $result->fields['id_orden_produccion'];
			$id_proceso		= $result->fields['id_orden_proceso'];
			$id_producto	= $result->fields['id_producto'];
			$cantidad		= $result->fields['cantidad'];
			$estado			= $result->fields['estado'];
			$fecha_inicio	= $result->fields['fecha_inicio'];
			$fecha_fin		= $result->fields['fecha_fin'];
			$cantidad_ter	= $result->fields['cant_terminada'];
			
			$progreso 		= $this->progreso_orden($id_op);
			$producto		= $this->producto_orden($id_op);
			$oee 			= $this->OEE_orden($id_op);
			$desperdicio 	= $this->desperdicio_orden($id_op);
			$cant_rest		= $cantidad - $cant_terminada;
			
			$objPHPExcel->setActiveSheetIndex(2)
        		    ->setCellValue('A'.$i,	$id_op." ")
		            ->setCellValue('B'.$i,	$id_producto." ")
        		    ->setCellValue('C'.$i,	$producto->fields["nombre_producto"])
        		    ->setCellValue('D'.$i,	$progreso)
        		    ->setCellValue('E'.$i,	$cantidad)
        		    ->setCellValue('F'.$i,	$cantidad_ter)
        		    ->setCellValue('G'.$i,	$cant_rest)
        		    ->setCellValue('H'.$i,	$oee)
        		    ->setCellValue('I'.$i,	$desperdicio);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estiloTituloColumnas);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('C3:C'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('D3:D'.($i-1))->applyFromArray($estiloInformacion2);
		$objPHPExcel->getActiveSheet()->getStyle('E3:G'.($i-1))->applyFromArray($estiloInformacion);
		$objPHPExcel->getActiveSheet()->getStyle('H3:I'.($i-1))->applyFromArray($estiloInformacion2);
		
		/*for($i = 'A'; $i <= 'I'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}*/
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);

		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
		
		
		$objPHPExcel->getActiveSheet()->setTitle('HISTORICO');
		
		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Reporte de Ordenes.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
}
?>