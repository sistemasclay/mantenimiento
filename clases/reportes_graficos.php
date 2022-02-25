<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Juan Pablo Giraldo
 */
ini_set('memory_limit','512M');
include("adodb5/adodb-exceptions.inc.php");
include("adodb5/adodb.inc.php");
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

 require('fpdf.php');



class reportes_graficos {

   function detalle_proceso($codigo)
    {
        include("conexion.php");
        $sql="SELECT * FROM procesos WHERE id_proceso = '$codigo'";
        $result=$conexion_pg->Execute($sql);
        if ($result === false)
            {
            echo 'error al Buscar: '.$conn->ErrorMsg().'<BR>';
            }
         $conexion_pg->Close();
         return $result;
    }


        function listar_etiquetas()
    {
        include("conexion.php");
       // $sql =	"SELECT * FROM ordenes_produccion";
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


    function listar_paradas()
    {
        include("conexion.php");
       // $sql =	"SELECT * FROM ordenes_produccion";
       $sql = "SELECT 	id_parada, 
						btrim(nombre_parada) nombre_parada, 
						tipo_parada, 
						unidad_tiempo, 
						predeterminada_parada, 
						estado_parada, 
						grupo_parada, 
						tiempo_programado 
				FROM paradas order by id_parada";
       $result=$conexion_pg->Execute($sql);
		if ($result === false)
				{
				echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
				}
				$conexion_pg->Close();
			$i=0;
			while (!$result->EOF)
		{
				$ar[$i]["id_parada"]=$result->fields["id_parada"];
				$ar[$i]["nombre_parada"]=$result->fields["nombre_parada"];
				$result->MoveNext();
				$i++;
		}
         return $ar;
    }


 function tiempo_segundos($segundos){
    $minutos=$segundos/60;
    $horas=floor($minutos/60);
    $minutos2=$minutos%60;
    $segundos_2=$segundos%60%60%60;
    if($minutos2<10)$minutos2='0'.$minutos2;
    if($segundos_2<10)$segundos_2='0'.$segundos_2;

    if($segundos<60){ /* segundos */
    $resultado= round($segundos).' Segundos';
    }elseif($segundos>60 && $segundos<3600){/* minutos */
    $resultado= $minutos2.':'.$segundos_2.' Minutos';
    }else{/* horas */
    $resultado= $horas.":".$minutos2.":".$segundos_2;
    }
    return $resultado;
}

function grafica_produccion($turnos,$conteos)
{
	// Some data
	$datay=array(1,1,300);
	// Create the graph and setup the basic parameters
	$graph = new Graph(800,500,'auto');
	$graph->img->SetMargin(100,30,30,200);
	$graph->SetScale("textlin");
	$graph->SetShadow();
	$graph->SetFrame(false); // No border around the graph
	// Add some grace to the top so that the scale doesn't
	// end exactly at the max value.
	
	$graph->yaxis->scale->SetGrace(20);
	$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
	
	// Setup X-axis labels
	//$a = array(250,251,256);//$gDateLocale->GetShortMonth();
	//$graph->xaxis->SetTickLabels($a);
	$graph->xaxis->SetTickLabels($turnos);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,6);
	$graph->xaxis->SetLabelAngle(90);
	//$graph->yaxis->title->Set("Unidades Conteo");
	//$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
	
	// Setup graph title ands fonts
	$graph->title->Set("TURNOS VS. UNIDADES");
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,24);
	//$graph->xaxis->title->Set("Numero Turno");
	$graph->xaxis->title->SetFont(FF_ARIAL,FS_NORMAL,16);
	$graph->yaxis->title->Set("UNIDADES");
	$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
	$graph->yaxis->title->SetMargin(30);
	
	// Create a bar pot
	//$bplot = new BarPlot($datay);
	$bplot = new BarPlot($conteos);
	//$bplot->SetFillColor(array('royalblue2','seagreen4','yellow2','orangered'));
	$bplot->SetFillColor('#FF0000');
	$bplot->SetWidth(0.5);
	//$bplot->SetShadow();
	
	// Setup the values that are displayed on top of each bar
	$bplot->value->Show();
	
	// Must use TTF fonts if we want text at an arbitrary angle
	//$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
	$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,10);
	$bplot->value->SetAngle(45);
	
	// Black color for positive values and darkred for negative values
	$bplot->value->SetColor("black","darkred");
	$graph->Add($bplot);
	
	if (file_exists('produccion.png'))
	{
	unlink('produccion.png');
	}
	// Finally stroke the graph
	$graph->Stroke('produccion.png');

}


function grafica_ocurrencias($codigos,$conteos,$nombres)
{
	// Some data
	$datay=array(1,1,300);
	
	// Create the graph and setup the basic parameters
	$graph = new Graph(800,500,'auto');
	$graph->img->SetMargin(100,30,30,200);
	$graph->SetScale("textint");
	$graph->SetShadow();
	$graph->SetFrame(false); // No border around the graph
	// Add some grace to the top so that the scale doesn't
	// end exactly at the max value.
	
	$graph->yaxis->scale->SetGrace(20);
	//$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,10);
	$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
	$graph->yaxis->SetLabelMargin(3); 
	
	// Setup X-axis labels
	
	//$a = array(250,251,256);//$gDateLocale->GetShortMonth();
	//$graph->xaxis->SetTickLabels($a);
	//$graph->xaxis->SetTickLabels($codigos);
	$graph->xaxis->SetTickLabels($nombres);
	//$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,10);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,6);
	$graph->xaxis->SetLabelAngle(90);
	$graph->xaxis->SetLabelMargin(3); 
	//$graph->yaxis->title->Set("Unidades Conteo");
	//$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
	
	// Setup graph title ands fonts
	
	$graph->title->Set("PARADAS VS. OCURRENCIAS");
	//$graph->title->SetFont(FF_ARIAL,FS_BOLD,24);
	$graph->title->SetFont(FF_ARIAL,FS_NORMAL,24);
	//$graph->xaxis->title->Set("Causal");
	//$graph->xaxis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
	$graph->yaxis->title->Set("OCURRENCIAS");
	$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
	$graph->yaxis->title->SetMargin(15);
	
	// Create a bar pot
	//$bplot = new BarPlot($datay);
	$bplot = new BarPlot($conteos);
	$bplot->SetFillColor('#FF0000');
	$bplot->SetWidth(0.5);
	//$bplot->SetShadow();
	
	// Setup the values that are displayed on top of each bar
	$bplot->value->Show();
	
	// Must use TTF fonts if we want text at an arbitrary angle
	//$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
	$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,6);
	//$bplot->value->SetAngle(45);
	$bplot->value->SetAngle(90);
	
	// Black color for positive values and darkred for negative values
	$bplot->value->SetColor("black","darkred");
	$graph->Add($bplot);
	
	if (file_exists('ocurrencias.png'))
	{
	unlink('ocurrencias.png');
	}
	// Finally stroke the graph
	$graph->Stroke('ocurrencias.png');

}

function grafica_tiempo_paro($codigos,$conteos,$nombres)
{
	// Some data
	$datay=array(1,1,300);
	
	// Create the graph and setup the basic parameters
	$graph = new Graph(800,500,'auto');
	$graph->img->SetMargin(100,30,30,200);
	$graph->SetScale("textint");
	$graph->SetShadow();
	$graph->SetFrame(false); // No border around the graph
	// Add some grace to the top so that the scale doesn't
	// end exactly at the max value.
	
	$graph->yaxis->scale->SetGrace(20);
	$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
	// Setup X-axis labels
	
	//$a = array(250,251,256);//$gDateLocale->GetShortMonth();
	//$graph->xaxis->SetTickLabels($a);
	//$graph->xaxis->SetTickLabels($codigos);
	$graph->xaxis->SetTickLabels($nombres);
	//$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,10);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,6);
	$graph->xaxis->SetLabelAngle(90);
	//$graph->yaxis->title->Set("Unidades Conteo");
	//$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
	
	// Setup graph title ands fonts
	$graph->title->Set("PARADAS VS. TIEMPO MINUTOS");
	$graph->title->SetFont(FF_ARIAL,FS_NORMAL,24);
	//$graph->xaxis->title->Set("Codigo Parada");
	//$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,16);
	$graph->yaxis->title->Set("MINUTOS");
	$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
	$graph->yaxis->title->SetMargin(15);
	
	// Create a bar pot
	//$bplot = new BarPlot($datay);
	$bplot = new BarPlot($conteos);
	$bplot->SetFillColor('#FF0000');
	$bplot->SetWidth(0.5);
	//$bplot->SetShadow();
	
	// Setup the values that are displayed on top of each bar
	$bplot->value->Show();
	
	// Must use TTF fonts if we want text at an arbitrary angle
	//$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
	$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,7);
	$bplot->value->SetAngle(90);
	
	// Black color for positive values and darkred for negative values
	$bplot->value->SetColor("black","darkred");
	$graph->Add($bplot);
	
	if (file_exists('tiempos.png'))
	{
	unlink('tiempos.png');
	}
	// Finally stroke the graph
	$graph->Stroke('tiempos.png');

}

function grafica_indicador($turnos,$conteos,$titulo)
{
	// Some data
	$datay=array(1,1,300);
	
	// Create the graph and setup the basic parameters
	$graph = new Graph(800,500,'auto');
	$graph->img->SetMargin(100,30,30,200);
	$graph->SetScale("textint");
	$graph->SetShadow();
	$graph->SetFrame(false); // No border around the graph
	// Add some grace to the top so that the scale doesn't
	// end exactly at the max value.
	
	$graph->yaxis->scale->SetGrace(20);
	$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,10);
	
	// Setup X-axis labels
	//$a = array(250,251,256);//$gDateLocale->GetShortMonth();
	//$graph->xaxis->SetTickLabels($a);
	$graph->xaxis->SetTickLabels($turnos);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
	$graph->xaxis->SetLabelAngle(90);
	//$graph->yaxis->title->Set("Unidades Conteo");
	//$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
	
	// Setup graph title ands fonts
	$graph->title->Set("Turnos VS. ".$titulo.".");
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,24);
	//$graph->xaxis->title->Set("Numero Turno");
	$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,16);
	
	
	// Create a bar pot
	//$bplot = new BarPlot($datay);
	$bplot = new BarPlot($conteos);
	$bplot->SetFillColor('#FF0000');
	$bplot->SetWidth(0.5);
	//$bplot->SetShadow();
	
	// Setup the values that are displayed on top of each bar
	$bplot->value->Show();
	
	// Must use TTF fonts if we want text at an arbitrary angle
	//$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
	$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,10);
	$bplot->value->SetAngle(45);
	
	// Black color for positive values and darkred for negative values
	$bplot->value->SetColor("black","darkred");
	$graph->Add($bplot);
	
	if (file_exists('indicador.png'))
	{
	unlink('indicador.png');
	}
	// Finally stroke the graph
	$graph->Stroke('indicador.png');

}


	function pdf_grafico_produccion($sql)
	{
		// echo $sql;
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
		echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		//ob_end_clean();
		$pdf=new FPDF('L','mm','Letter');
		//$pdf->AddPage();
		$pdf->SetFillColor(255, 255, 255);
		$pdf->AddPage();
		$pdf->Image('logo2.jpg',10,8,50);
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(80);
		$pdf->Cell(40,8,'Grafico de Produccion',0,0,'C',true);
		//  $pdf->Ln();
		$pdf->Cell(10);
		$pdf->Cell(40,8,$result->fields['nombre'],0,0,'L',true);
		$turnos=array();
		$unidades=array();
	
		if ($result->EOF)
		{
			echo "no existen turnos para esta maquina";             
			// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
			echo "<script languaje='javascript' type='text/javascript'>
			window.setTimeout(window.close(), 12000);
			</script>";             
		}
		$j=0;
		while (!$result->EOF) {
			$turnos[]=$result->fields["persona"].' '.$result->fields["id_turno"];
			$unidades[]=$result->fields["produccion_final"];
			$result->MoveNext();
			$j++;
			if($j==30){
				break;
			}
		}
		$this->grafica_produccion($turnos,$unidades);
		$pdf->Image('produccion.png',10,30,250,150);
		$pdf->Output();
	}

	function pdf_grafico_ocurrencias($sql,$id_proceso)
	{
		$codigos=array();
		$totales=array();
		$paradas=$this->listar_paradas();
		$cantidad_paradas = count($paradas);
		//echo $cantidad_paradas;
		$i=0;
		$totales_p=0;
		$j=0;
		for($i=0;$i<$cantidad_paradas;$i++)
		{
			include("conexion.php");
			$sqlf=$sql." "."AND id_parada='".$paradas[$i]["id_parada"]."'";
			$result=$conexion_pg->Execute($sqlf);
			if ($result === false)
			{
				echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
			}
			$cantidad_contada=0;
			/*if ($result->EOF)
			{
				echo "no existen turnos para esta maquina";
				exit;
			}*/
			while (!$result->EOF) {
				$cantidad_contada=$cantidad_contada+1;
				$result->MoveNext();
			}
			if($cantidad_contada>=1)
			{										
				$codigos[]=$paradas[$i]["id_parada"];
				$nombres[]=trim($paradas[$i]["nombre_parada"]);
				$totales[]=$cantidad_contada;
	
	
				// $inf_p[$totales_p]["id_parada"]=$result->fields["id_parada"];
				// $inf_p[$totales_p]["nombre_parada"]=$result->fields["nombre_parada"];
				$totales_p++;
				$j++;
			}
				$conexion_pg->Close();
			if($j==30){
				break;
			}
		}
		$iterator=0;
		for ($i=0;$i<=$totales_p-1;$i++)
		{
			for ($j=$i+1;$j<$totales_p;$j++)
			{
				if ($totales[$i]<$totales[$j])
				{
					$auxt=$totales[$i];
					$auxc=$codigos[$i];
					$auxn=$nombres[$i];
					
					$totales[$i]=$totales[$j];
					$codigos[$i]=$codigos[$j];
					$nombres[$i]=$nombres[$j];
		
		
					$totales[$j]=$auxt;
					$codigos[$j]=$auxc;
					$nombres[$j]=$auxn;
				}
			}
		}
	
		$pdf=new FPDF('L','mm','Letter');
		
		$pdf->SetFillColor(255, 255, 255);
		$pdf->AddPage();
		$pdf->Image('logo2.jpg',10,8,50);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(80);
		$pdf->Cell(70,8,'Grafico de Ocurrencias Paradas',0,0,'C',true);
	
		$pdf->Cell(10);
		$maquina = $this->detalle_proceso($id_proceso);
		$pdf->Cell(40,8,$maquina->fields['nombre'],0,0,'L',true);
		$this->grafica_ocurrencias($codigos, $totales, $nombres);
		$pdf->Image('ocurrencias.png',10,30,250,150);
		$pdf->AddPage();
		$pdf->Ln();
		$pdf->Ln();
		for($i=0;$i<$totales_p;$i++)
		{
			$pdf->Cell(40);
			$pdf->Cell(40,8,$codigos[$i]." - ".$nombres[$i],0,0,'L',true);
			$pdf->Ln();
		}
		
		$pdf->Output();
	}

	function pdf_grafico_tiempo_paro($sql,$id_proceso)
	{
		$codigos=array();
		$totales=array();
		$paradas=$this->listar_paradas();
		$cantidad_paradas = count($paradas);
		//echo $cantidad_paradas;
		$i=0;
		$totales_p=0;
		$j=0;
		for($i=0;$i<$cantidad_paradas;$i++)
		{
			include("conexion.php");
			$sqlf=$sql." "."AND id_parada='".$paradas[$i]["id_parada"]."'";
			$result=$conexion_pg->Execute($sqlf);
			if ($result === false)
			{
				echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
			}
			$cantidad_contada=0;
			/*if ($result->EOF)
			{
				echo "no existen turnos para esta maquina";
				exit;
			}*/
			while (!$result->EOF)
			{
				$cantidad_contada=$cantidad_contada+$result->fields['horas'];
				$result->MoveNext();
			}
			if($cantidad_contada>=1)
			{
				$codigos[]=$paradas[$i]["id_parada"];
				$nombres[]=$paradas[$i]["nombre_parada"];
				//$totales[]=$cantidad_contada/60;
				$totales[]=$cantidad_contada/60;
				$totales_p++;
				$j++;
			}
			$conexion_pg->Close();
			if($j==30){break;}
		}

        for ($i=0;$i<=$totales_p-1;$i++)
		{
			for ($j=$i+1;$j<$totales_p;$j++)
			{
				if ($totales[$i]<$totales[$j])
				{
					$auxt=$totales[$i];
					$auxc=$codigos[$i];
					$auxn=$nombres[$i];
		
					$totales[$i]=$totales[$j];
					$codigos[$i]=$codigos[$j];
					$nombres[$i]=$nombres[$j];

					$totales[$j]=$auxt;
					$codigos[$j]=$auxc;
					$nombres[$j]=$auxn;
				}
			}
		}

		$pdf=new FPDF('L','mm','Letter');
	
		$pdf->SetFillColor(255, 255, 255);
		$pdf->AddPage();
		$pdf->Image('logo2.jpg',10,8,50);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(80);
		$pdf->Cell(70,8,'Grafico de Tiempos Paradas',0,0,'C',true);
	
		$pdf->Cell(10);
		$maquina = $this->detalle_proceso($id_proceso);
		$pdf->Cell(40,8,$maquina->fields['nombre'],0,0,'L',true);
		$this->grafica_tiempo_paro($codigos, $totales, $nombres);
		$pdf->Image('tiempos.png',10,30,250,150);
		$pdf->AddPage();
		$pdf->Ln();
		$pdf->Ln();
				for($i=0;$i<$totales_p;$i++)
			{
				$pdf->Cell(40);
				$pdf->Cell(40,8,$codigos[$i]." - ".$nombres[$i],0,0,'L',true);
				$pdf->Ln();
			}
		$pdf->Output();
   }

	function pdf_grafico_indicadores($sql,$indicador)
	{
		$etiquetas=$this->listar_etiquetas();
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
		{
		echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
		}
		$conexion_pg->Close();
		//ob_end_clean();
		$pdf=new FPDF('L','mm','Letter');
		//$pdf->AddPage();
		$pdf->SetFillColor(255, 255, 255);
		$pdf->AddPage();
		$pdf->Image('logo2.jpg',10,8,50);
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(80);
		$pdf->Cell(40,8,'Grafico de Indicador',0,0,'C',true);
		//$pdf->Ln();
		$pdf->Cell(10);
		$pdf->Cell(40,8,$result->fields['nombre'],0,0,'L',true);
		$turnos=array();
		$unidades=array();
		if ($result->EOF)
		{
			echo "no existen turnos para esta maquina";
			exit;
		}
		while (!$result->EOF) {
			$turnos[]=$result->fields["id_turno"];
			$unidades[]= str_replace(",",".",$result->fields[($indicador+1)]);
			$result->MoveNext();
		}
   
		$this->grafica_indicador($turnos,$unidades,$etiquetas[$indicador-1]["etiqueta"]);
		$pdf->Image('indicador.png',10,30,250,150);
		$pdf->Output();  
	}

}
?>
