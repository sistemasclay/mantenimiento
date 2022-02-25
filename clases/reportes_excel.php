<?php

/**
 * Description of reportes_excel
 *
 * @author Juan Pablo Giraldo
 */
ini_set('memory_limit','512M');
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");

class reportes_excel {
    //put your code here

	function tiempo_segundos($segundos)
	{
		$minutos=$segundos/60;
		$horas=floor($minutos/60);
		$minutos2=$minutos%60;
		$segundos_2=$segundos%60%60%60;
		if($minutos2<10)$minutos2='0'.$minutos2;
		if($segundos_2<10)$segundos_2='0'.$segundos_2;
		if($segundos<60) /* segundos */
		{
			$resultado= '00:00:'.round($segundos);
			/*   if($segundos<10)
			{$resultado=$resultado.'0';} */
		}
		elseif($segundos>60 && $segundos<3600) /* minutos */
		{
			$resultado= '00:'.$minutos2.':'.$segundos_2;
		}
		else /* horas */
		{
			if($horas<10){$horas='0'.$horas;}
			$resultado= $horas.":".$minutos2.":".$segundos_2;
		}
		return $resultado;
	}

	function grupo_paro($grupo)
	{
		$nombre_grupo="";
		switch ($grupo)
		{
			case "1":
				$nombre_grupo="Averias";
			break;
			case "2":
				$nombre_grupo="Cuadre y Ajuste";
			break;
			case "3":
				$nombre_grupo="Pequena Parada";
			break;
		}
	return $nombre_grupo;
	}

    function listar_turno_paradas($id_turno,$id_proceso)
    {
		include("conexion.php");
		$sql = "SELECT *
				FROM turno_parada as a
					INNER JOIN paradas as b ON (a.id_parada= b.id_parada)
				WHERE id_turno='".$id_turno."'
					AND id_proceso='".$id_proceso."'
				ORDER BY fecha_inicio";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
	}


	function listar_etiquetas()
	{
		include("conexion.php");
		$sql = "SELECT *
				FROM variables
				ORDER BY id_variable";
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

	function listar_turno_asistencia($id_turno,$id_proceso)
	{
		include("conexion.php");
		// $sql =	"SELECT * FROM ordenes_produccion";
		$sql = "SELECT *
				FROM turno_asistencia as a
					INNER JOIN personal as b ON (a.id_empleado= b.id_persona)
				WHERE id_turno='".$id_turno."'
					AND id_proceso='".$id_proceso."'";
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		return $result;
    }


    function excel_bitacora($sql)
    {
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		/** Error reporting */
		error_reporting(E_ALL);

		/** PHPExcel */
		//require_once '../../clases/PHPExcel.php';
		require_once 'clases/PHPExcel.php';

		/** PHPExcel_IOFactory */
		//require_once '../../clases/PHPExcel/IOFactory.php';
		require_once 'clases/PHPExcel/IOFactory.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Efiprocesos S.A.S.")
									->setLastModifiedBy("Efiprocesos S.A.S.")
									->setTitle("Bitacora de Mantenimientos")
									->setSubject("Bitacora de Mantenimientos")
									->setDescription("Bitacora de Mantenimientos")
									->setKeywords("office 2007 openxml php")
									->setCategory("Informe");

		// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,2, 'BITACORA DE MANTENIMIENTOS');

		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(16);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(5)->setRowHeight(60);

		// agregar cabeceras
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,5, 'Orden de Trabajo')
					->setCellValueByColumnAndRow(1,5, 'Usuario')
					->setCellValueByColumnAndRow(2,5, 'Maquina')
					->setCellValueByColumnAndRow(3,5, 'Sistema')
					->setCellValueByColumnAndRow(4,5, 'Sub-Sistema')
					->setCellValueByColumnAndRow(5,5, 'Pieza')
					->setCellValueByColumnAndRow(6,5, 'Actividad')
					->setCellValueByColumnAndRow(7,5, 'Fecha Proximo Mantenimiento')
					->setCellValueByColumnAndRow(8,5, 'Fecha Mantenimiento Programado')
					->setCellValueByColumnAndRow(9,5, 'Fecha Realizado')
					->setCellValueByColumnAndRow(10,5, 'Observaciones')
					->getStyle('A5:K5')->getAlignment()->setWrapText(true);
		$i=6;
		while (!$result->EOF)
		{
			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(30);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$i,$result->fields['ordenes_trabajo'])
						->setCellValueByColumnAndRow(1,$i,$result->fields['usuario'])
						->setCellValueByColumnAndRow(2,$i,$result->fields['maquina'])
						->setCellValueByColumnAndRow(3,$i,$result->fields['sistema'])
						->setCellValueByColumnAndRow(4,$i,$result->fields['sub_sistema'])
						->setCellValueByColumnAndRow(5,$i,$result->fields['pieza'])
						->setCellValueByColumnAndRow(6,$i,$result->fields['actividad'])
						->setCellValueByColumnAndRow(7,$i,$result->fields['proximo'])
						->setCellValueByColumnAndRow(8,$i,$result->fields['progamado'])
						->setCellValueByColumnAndRow(9,$i,$result->fields['fecha_registro'])
						->setCellValueByColumnAndRow(10,$i,$result->fields['observaciones'])
						->getStyle('A'.$i.':K'.$i)->getAlignment()->setWrapText(true);
			$i++;
			$result->MoveNext();
		}

		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:K'.($i-1))->applyFromArray(
							array(
								'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
									'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
								),
								'borders' => array(
									'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN
									)
								)
							)
						);

		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Bitacora de Mantenimientos');
/*
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Efiprocesos logo');
		$objDrawing->setDescription('Efiprocesos logo');
		$objDrawing->setPath('logo3.jpg');
		$objDrawing->setHeight(80);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setOffsetX(-10);
		$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
*/
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Bitacora de Mantenimientos.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
		$objWriter->save('php://output');
		exit;
    }


	function excel_cronograma($sql,$fechai,$fechaf)
    {
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		/** Error reporting */
		error_reporting(E_ALL);

		/** PHPExcel */
		//require_once '../../clases/PHPExcel.php';
		require_once 'clases/PHPExcel.php';

		/** PHPExcel_IOFactory */
		//require_once '../../clases/PHPExcel/IOFactory.php';
		require_once 'clases/PHPExcel/IOFactory.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Efiprocesos S.A.S.")
									->setLastModifiedBy("Efiprocesos S.A.S.")
									->setTitle("Cronograma Ordenes Trabajo")
									->setSubject("Cronograma Ordenes Trabajo")
									->setDescription("Cronograma Ordenes Trabajo")
									->setKeywords("office 2007 openxml php")
									->setCategory("Informe");

		// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,1, 'Cronograma Ordenes Trabajo');

		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(7);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(16);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(20);
//		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(3)->setRowHeight(70);

		//de aqui en adelante serian los dias de los meses se debe dejar de un ancho de mas o menos 2
		/*
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(16);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(16);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(20);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(16);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(16);
		*	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(16);
		*/

		//$fechai,$fechaf

		$fechai = date("Y/m/d", strtotime($fechai ."- 1 days"));
		$j=0;
		for($i=$fechai;$i<=$fechaf;$i = date("Y/m/d", strtotime($i ."+ 1 days"))){
			$fechas[$j] = $i;
			$j++;
		}

		// agregar cabeceras
		//primero, los meses, estos estan en a partir de la posicion (4,1)
		//segundo, los encabezados
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,3, 'Orden')
					->setCellValueByColumnAndRow(1,3, 'Máquina')
					->setCellValueByColumnAndRow(2,3, 'Sistema')
					->setCellValueByColumnAndRow(3,3, 'Sub-Sistema')
					->setCellValueByColumnAndRow(4,3, 'Pieza')
					->setCellValueByColumnAndRow(5,3, 'Actividad')
					->setCellValueByColumnAndRow(6,3, 'Encargado')
	->setCellValueByColumnAndRow(7,3, 'Correctivo')
						->setCellValueByColumnAndRow(8,3, 'Duracion')
					->setCellValueByColumnAndRow(9,3, 'Fecha Ultimo Mantenimiento Efectuado')
//					->setCellValueByColumnAndRow(8,3, 'Fecha Mantenimiento Segun Sistema')
					->setCellValueByColumnAndRow(10,3, 'Fecha Mantenimiento Programado')
					->setCellValueByColumnAndRow(11,3, 'Fecha Mantenimiento Efectuado')
					->getStyle('A3:L3')->getAlignment()->setWrapText(true);


		$objPHPExcel->setActiveSheetIndex(0)->getStyle('J3')->applyFromArray(
												array(
													'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb' => '0000C8')
													),
													'font'  => array(
														'color' => array('rgb' => 'FFFFFF'),
													)
												)
											);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('K3')->applyFromArray(
												array(
													'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb' => 'FF0000')
													)
												)
											);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('L3')->applyFromArray(
												array(
													'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb' => '00FF00')
													)
												)
											);
		//tercero, los días, iniciando a partir de la posicion (4,2)
		$j=12;
		$columna = 'M';
		for($i=0;$i<count($fechas);$i++){
			$objPHPExcel->getActiveSheet()->getStyle($columna.'3')->getAlignment()->setTextRotation(90);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($columna)->setWidth(3);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,3, $fechas[$i]);
			$j++;
			++$columna;
		}

		$i=4;
		$hoy = date("Y/m/d",time());
		while (!$result->EOF)
		{
			$comparo = $result->fields["correctivo"];
			$correctivo = "Leo";
			if($comparo==1){
				$correctivo = "Si";
			}else{
				$correctivo = "No";
			}
			$fecha_orden = date("Y/m/d",strtotime($result->fields['fecha_orden']));

			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(40);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$i, $result->fields['id_orden_trabajo'])
						->setCellValueByColumnAndRow(1,$i, $result->fields['nombre_maquina'])
						->setCellValueByColumnAndRow(2,$i, $result->fields['nombre_sistema'])
						->setCellValueByColumnAndRow(3,$i, $result->fields['nombre_sub_sistema'])
						->setCellValueByColumnAndRow(4,$i, $result->fields['nombre_pieza'])
						->setCellValueByColumnAndRow(5,$i, $result->fields['nombre_actividad'])
						->setCellValueByColumnAndRow(6,$i, $result->fields['usuario'])
							->setCellValueByColumnAndRow(7,$i,$correctivo)
							->setCellValueByColumnAndRow(8,$i,$result->fields['duracion'])
						->setCellValueByColumnAndRow(9,$i, $result->fields['fecha_ult_mantenimiento'])
//						->setCellValueByColumnAndRow(8,$i, '2016/02/0'.$i)
						->setCellValueByColumnAndRow(10,$i, $fecha_orden)
						->setCellValueByColumnAndRow(11,$i, $result->fields['fecha_registro']);
						//ahora se colorean los cuadritos
			$j=10;
			$columna = 'M';
			for($iterator=0;$iterator<count($fechas);$iterator++){
				$fecha_orden = date("Y/m/d",strtotime($result->fields['fecha_orden']));//convierte la fecha de Y-m-d H:i:s -> Y/m/d para crear una igualdad
				$fecha_ult_mantenimiento = date("Y/m/d",strtotime($result->fields['fecha_ult_mantenimiento']));//convierte la fecha de Y-m-d H:i:s -> Y/m/d para crear una igualdad
				$fecha_registro = date("Y/m/d",strtotime($result->fields['fecha_registro']));//convierte la fecha de Y-m-d H:i:s -> Y/m/d para crear una igualdad
				if($fechas[$iterator]==$fecha_orden){
					//$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,$i, 'X');
					$objPHPExcel->setActiveSheetIndex(0)->getStyle($columna.$i)->applyFromArray(
															array(
																'fill' => array(
																	'type' => PHPExcel_Style_Fill::FILL_SOLID,
																	'color' => array('rgb' => 'FF0000')
																)
															)
														);
				}
				if($fechas[$iterator]==$fecha_registro){
					//$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,$i, 'X');
					$objPHPExcel->setActiveSheetIndex(0)->getStyle($columna.$i)->applyFromArray(
															array(
																'fill' => array(
																	'type' => PHPExcel_Style_Fill::FILL_SOLID,
																	'color' => array('rgb' => '00FF00')
																)
															)
														);
				}
				if($fechas[$iterator]==$fecha_ult_mantenimiento){
					//$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,$i, 'X');
					$objPHPExcel->setActiveSheetIndex(0)->getStyle($columna.$i)->applyFromArray(
															array(
																'fill' => array(
																	'type' => PHPExcel_Style_Fill::FILL_SOLID,
																	'color' => array('rgb' => '0000C8')
																)
															)
														);
				}
				$j++;
				++$columna;
			}

			$i++;
			$result->MoveNext();
		}

				$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:'.$columna.($i-1))->applyFromArray(
															array(
																'borders' => array(
																	'allborders' => array(
																		'style' => PHPExcel_Style_Border::BORDER_THIN
																	)
																)
															)
														);

				$objPHPExcel->setActiveSheetIndex(0)
							->getStyle('A3:L'.($i-1))->getAlignment()->setWrapText(true);

				$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:L'.($i-1))->applyFromArray(
											array(
												'alignment' => array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
												)
											)
										);

		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Cronograma Ordenes Trabajo');




		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Cronograma Ordenes Trabajo.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
		$objWriter->save('php://output');
		exit;
    }

	function excel_hoja_vida($sql,$fechai,$fechaf,$maq_reporte)
    {
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
			echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		/** Error reporting */
		error_reporting(E_ALL);

		/** PHPExcel */
		//require_once '../../clases/PHPExcel.php';
		require_once 'clases/PHPExcel.php';

		/** PHPExcel_IOFactory */
		//require_once '../../clases/PHPExcel/IOFactory.php';
		require_once 'clases/PHPExcel/IOFactory.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Efiprocesos S.A.S.")
									->setLastModifiedBy("Efiprocesos S.A.S.")
									->setTitle("Hoja de Vida Máquina ".$maq_reporte)
									->setSubject("Hoja de Vida Máquina ".$maq_reporte)
									->setDescription("Hoja de Vida Máquina ".$maq_reporte)
									->setKeywords("office 2007 openxml php")
									->setCategory("Informe");

		// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,1, 'Hoja de Vida Máquina '.$maq_reporte);

		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(40);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(3)->setRowHeight(30);

		$fechai = date("Y/m/d", strtotime($fechai ."- 1 days"));
		$j=0;
		for($i=$fechai;$i<=$fechaf;$i = date("Y/m/d", strtotime($i ."+ 1 days"))){
			$fechas[$j] = $i;
			$j++;
		}

		// agregar cabeceras
		//primero, los meses, estos estan en a partir de la posicion (4,1)
		//segundo, los encabezados
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,3, 'ID Máquina')
					->setCellValueByColumnAndRow(1,3, 'Nombre Máquina')
					->setCellValueByColumnAndRow(2,3, 'ID Usuario')
					->setCellValueByColumnAndRow(3,3, 'Nombre Usuario')
					->setCellValueByColumnAndRow(4,3, 'Fecha')
					->setCellValueByColumnAndRow(5,3, 'Observaciones')
					->getStyle('A3:F3')->getAlignment()->setWrapText(true);

		$i=4;
		$columna = 'A';
		while (!$result->EOF)
		{
			//$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(40);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$i, $result->fields['id_maquina'])
						->setCellValueByColumnAndRow(1,$i, $result->fields['nombre_maquina'])
						->setCellValueByColumnAndRow(2,$i, $result->fields['id_usuario'])
						->setCellValueByColumnAndRow(3,$i, $result->fields['nombre_usuario'])
						->setCellValueByColumnAndRow(4,$i, $result->fields['fecha'])
						->setCellValueByColumnAndRow(5,$i, $result->fields['notas']);


			$i++;
			$result->MoveNext();
		}

				$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:F'.($i-1))->applyFromArray(
															array(
																'borders' => array(
																	'allborders' => array(
																		'style' => PHPExcel_Style_Border::BORDER_THIN
																	)
																)
															)
														);

				$objPHPExcel->setActiveSheetIndex(0)
							->getStyle('A3:F'.($i-1))->getAlignment()->setWrapText(true);

				$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:F'.($i-1))->applyFromArray(
											array(
												'alignment' => array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
												)
											)
										);

		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Hoja de Vida Máquina '.$maq_reporte);
/*
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Efiprocesos logo');
		$objDrawing->setDescription('Efiprocesos logo');
		$objDrawing->setPath('logo3.jpg');
		$objDrawing->setHeight(80);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setOffsetX(-10);
		$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
*/
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Hoja de vida Maquina '.$maq_reporte.'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
		$objWriter->save('php://output');
		exit;
    }


}
?>
