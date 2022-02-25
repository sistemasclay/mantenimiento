<?php
/**
 * Esta clase se creo con el fin de exportar la base de datos completa a un archivo de excel
 *
 * @author Juan Pablo Giraldo
 */
	include("adodb5/adodb-exceptions.inc.php");
	include("adodb5/adodb.inc.php");
	
class exportar_datos{
	
	function exportar_excel(){
	
		include("clases/conexion.php");
	
		require_once 'clases/PHPExcel.php';
		// Se crea el objeto PHPExcel
		$objPHPExcel = new PHPExcel();

		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator("Monitoreo y Control") //Autor
							 ->setLastModifiedBy("Monitoreo y Control") //Ultimo usuario que lo modificó
							 ->setTitle("Planilla BD Clay VERSION 10.0")
							 ->setSubject("Planilla BD Clay VERSION 10.0")
							 ->setDescription("Planilla BD Clay VERSION 10.0")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("backup excel");
							 
							 
		$estiloTituloReporte = array(
        	'font' => array(
	        	'name'      => 'Arial',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size' =>14,
	            	'color'     => array(
    	            	'rgb' => '000082'
        	       	)
            ),
			'borders' => array(
               	'allborders' => array(
                	'style' => PHPExcel_Style_Border::BORDER_NONE                    
               	)
            ), 
            'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'   => 0,
        			'wrap'          => TRUE
    		)
        );		
		
		$estiloTituloColumnas = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => '000000'
                )
            ),
/*			'fill' => array(
				'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
        		'startcolor' => array(
            		'rgb' => 'c47cf2'
        		),
        		'endcolor'   => array(
            		'argb' => 'FF431a5d'
        		)
			),
*/
			'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('rgb' => '969696')
			),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => TRUE
    		));
		$estiloInformacion = new PHPExcel_Style();
		$estiloInformacion->applyFromArray(
			array(
           		'font' => array(
               	'name'      => 'Arial',               
               	'color'     => array(
                   	'rgb' => '000000'
               	)
           	),
        ));

		//					  //
		//	HOJA DE USUARIOS  //
		//					  //
		
		$sql = "SELECT 	id_user, 
						user_name, 
						user_lastname,
						grade
				FROM usuarios
				WHERE estado = 1
				ORDER BY id_user";
				
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar los usuarios: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE USUARIOS";
		$titulosColumnas = array('Id Usuario', 'Nombre', 'Apellido', 'Contraseña', 'Nivel');
		
		$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells('A1:C1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3])
        		    ->setCellValue('E2',  $titulosColumnas[4]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(0)
        		    ->setCellValue('A'.$i,  $result->fields['id_user']." ")
		            ->setCellValue('B'.$i,  $result->fields['user_name'])
        		    ->setCellValue('C'.$i,  $result->fields['user_lastname'])
        		    ->setCellValue('D'.$i,  '')
        		    ->setCellValue('E'.$i,  $result->fields['grade']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:E".($i-1));
		
		for($i = 'A'; $i <= 'E'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
		
		//					  //
		//	HOJA DE PROCESOS  //
		//					  //
		
		$objPHPExcel->createSheet(1);
		
		$sql = "SELECT 	id_maquina, 
						nombre_maquina, 
						descripcion_maquina,
						horometro
				FROM maquinas
				WHERE estado = 1
				ORDER BY id_maquina";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE PROCESOS";
		$titulosColumnas = array('Id Proceso', 'Nombre', 'Descripción', 'Horometro');
		
		$objPHPExcel->setActiveSheetIndex(1)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(1)
        		    ->setCellValue('A'.$i,  $result->fields['id_maquina']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_maquina'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_maquina'])
        		    ->setCellValue('D'.$i,  $result->fields['horometro']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:D".($i-1));
		
		for($i = 'A'; $i <= 'D'; $i++){
			$objPHPExcel->setActiveSheetIndex(1)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Procesos');

		//					  //
		//	HOJA DE SISTEMAS  //
		//					  //
		
		$objPHPExcel->createSheet(2);
		
		$sql = "SELECT 	id_sistema, 
						nombre_sistema, 
						descripcion_sistema
				FROM sistemas
				WHERE estado = 1
				ORDER BY id_sistema";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE SISTEMAS";
		$titulosColumnas = array('Id Sistema', 'Nombre', 'Descripción');
		
		$objPHPExcel->setActiveSheetIndex(2)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(2)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(2)
        		    ->setCellValue('A'.$i,  $result->fields['id_sistema']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_sistema'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_sistema']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:C".($i-1));
		
		for($i = 'A'; $i <= 'C'; $i++){
			$objPHPExcel->setActiveSheetIndex(2)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Sistemas');

		//						  //
		//	HOJA DE SUB-SISTEMAS  //
		//						  //
		
		$objPHPExcel->createSheet(3);
		
		$sql = "SELECT 	id_sub_sistema, 
						nombre_sub_sistema, 
						descripcion_sub_sistema
				FROM sub_sistemas
				WHERE estado = 1
				ORDER BY id_sub_sistema";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE SUB-SISTEMAS";
		$titulosColumnas = array('Id Sub-Sistema', 'Nombre', 'Descripción');
		
		$objPHPExcel->setActiveSheetIndex(3)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(3)
        		    ->setCellValue('A'.$i,  $result->fields['id_sub_sistema']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_sub_sistema'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_sub_sistema']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:C".($i-1));
		
		for($i = 'A'; $i <= 'C'; $i++){
			$objPHPExcel->setActiveSheetIndex(3)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Sub-Sistemas');

		//					//
		//	HOJA DE PIEZAS  //
		//					//
		
		$objPHPExcel->createSheet(4);
				
		$sql = "SELECT 	id_pieza, 
						nombre_pieza, 
						descripcion_pieza
				FROM piezas
				WHERE estado = 1
				ORDER BY id_pieza";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE PIEZAS";
		$titulosColumnas = array('Id Pieza', 'Nombre', 'Descripción');
		
		$objPHPExcel->setActiveSheetIndex(4)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(4)
        		    ->setCellValue('A'.$i,  $result->fields['id_pieza']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_pieza'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_pieza']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:C".($i-1));
		
		for($i = 'A'; $i <= 'C'; $i++){
			$objPHPExcel->setActiveSheetIndex(4)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Piezas');

		//						 //
		//	HOJA DE ACTIVIDADES  //
		//						 //
				
		$objPHPExcel->createSheet(5);
				
		$sql = "SELECT 	id_actividad, 
						nombre_actividad, 
						descripcion_actividad
				FROM actividades
				WHERE estado = 1
				ORDER BY id_actividad";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE ACTIVIDADES";
		$titulosColumnas = array('Id actividad', 'Nombre', 'Descripción');
		
		$objPHPExcel->setActiveSheetIndex(5)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(5)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(5)
        		    ->setCellValue('A'.$i,  $result->fields['id_actividad']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_actividad'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_actividad']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:C".($i-1));
		
		for($i = 'A'; $i <= 'C'; $i++){
			$objPHPExcel->setActiveSheetIndex(5)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Actividades');
		
		//						//
		//	HOJA DE ESTANDARES	//
		//						//
		
		$objPHPExcel->createSheet(6);
				
		$sql = "SELECT 	id_maquina, 
						id_sistema, 
						id_sub_sistema,
						id_pieza,
						id_actividad,
						std_mantenimiento,
						frecuencia,
						unidad_frecuencia
				FROM estandares
				WHERE estado = 1
				ORDER BY id_estandar";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE ESTANDARES";
		$titulosColumnas = array('Maquina', 'Sistema', 'Sub-Sistema', 'Pieza', 'Actividad', 'Estandar', 'Frecuencia', 'Unidad Frecuencia');
		
		$objPHPExcel->setActiveSheetIndex(6)
					->mergeCells('A1:H1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(6)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3])
        		    ->setCellValue('E2',  $titulosColumnas[4])
        		    ->setCellValue('F2',  $titulosColumnas[5])
        		    ->setCellValue('G2',  $titulosColumnas[6])
        		    ->setCellValue('H2',  $titulosColumnas[7]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(6)
        		    ->setCellValue('A'.$i,  $result->fields['id_maquina']." ")
		            ->setCellValue('B'.$i,  $result->fields['id_sistema'])
        		    ->setCellValue('C'.$i,  $result->fields['id_sub_sistema'])
        		    ->setCellValue('D'.$i,  $result->fields['id_pieza'])
        		    ->setCellValue('E'.$i,  $result->fields['id_actividad'])
        		    ->setCellValue('F'.$i,  $result->fields['std_mantenimiento'])
        		    ->setCellValue('G'.$i,  $result->fields['frecuencia'])
        		    ->setCellValue('H'.$i,  $result->fields['unidad_frecuencia']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:H".($i-1));
		
		for($i = 'A'; $i <= 'H'; $i++){
			$objPHPExcel->setActiveSheetIndex(5)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Estandares');
		
		//					 //
		//	HOJA DE INSUMOS  //
		//					 //
				
		$objPHPExcel->createSheet(7);
				
		$sql = "SELECT 	id_insumo, 
						nombre_insumo, 
						descripcion_insumo,
						unidad_insumo
				FROM insumos
				WHERE estado = 1
				ORDER BY id_insumo";
		
		$result=$conexion_pg->Execute($sql);
		
		if ($result === false)
		{
			echo 'error al comprobar las máquinas: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		
		$tituloReporte = "DATOS DE INSUMOS";
		$titulosColumnas = array('Id Inusmo', 'Nombre', 'Descripción', 'Unidad Medida');
		
		$objPHPExcel->setActiveSheetIndex(7)
					->mergeCells('A1:D1');
					
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(7)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A2',  $titulosColumnas[0])
		            ->setCellValue('B2',  $titulosColumnas[1])
        		    ->setCellValue('C2',  $titulosColumnas[2])
        		    ->setCellValue('D2',  $titulosColumnas[3]);

					
		$i = 3;
		while (!$result->EOF) {
			$objPHPExcel->setActiveSheetIndex(7)
        		    ->setCellValue('A'.$i,  $result->fields['id_insumo']." ")
		            ->setCellValue('B'.$i,  $result->fields['nombre_insumo'])
        		    ->setCellValue('C'.$i,  $result->fields['descripcion_insumo'])
        		    ->setCellValue('D'.$i,  $result->fields['unidad_insumo']);
					$i++;
					$result->MoveNext();
		}					

		$result->Close();
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:D".($i-1));
		
		for($i = 'A'; $i <= 'D'; $i++){
			$objPHPExcel->setActiveSheetIndex(7)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Insumos');
		
		
		
		
	$conexion_pg->Close();
	

		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Planilla BD Clay VERSION 10.0.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}
?>