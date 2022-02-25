<?php
ini_set('memory_limit','1024M');
/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../clases/PHPExcel.php';

/** PHPExcel_IOFactory */
require_once '../clases/PHPExcel/IOFactory.php';
require_once '../clases/PHPExcel/Reader/Excel5.php';

$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load("Planilla BD Sistema Mantenimiento.xls");


/***********************/
/* LECTURA DE USUARIOS */
/***********************/

$objPHPExcel->setActiveSheetIndex(0);//usuarios
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $usuarios["id_user"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $usuarios["user_name"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $usuarios["user_lastname"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $usuarios["password"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $usuarios["grade"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($usuarios);


/***********************/
/* LECTURA DE MAQUINAS */
/***********************/

$objPHPExcel->setActiveSheetIndex(1);//procesos
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $procesos["id_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $procesos["nombre_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $procesos["descripcion_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $procesos["horometro"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($procesos);


/***********************/
/* LECTURA DE SISTEMAS */
/***********************/

$objPHPExcel->setActiveSheetIndex(2);//Sistemas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $sistemas["id_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $sistemas["nombre_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $sistemas["descripcion_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sistemas);

/***************************/
/* LECTURA DE SUB-SISTEMAS */
/***************************/

$objPHPExcel->setActiveSheetIndex(3);//Sub-Sistemas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $sub_sistemas["id_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $sub_sistemas["nombre_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $sub_sistemas["descripcion_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);


/*********************/
/* LECTURA DE PIEZAS */
/*********************/

$objPHPExcel->setActiveSheetIndex(4);//Piezas
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $piezas["id_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $piezas["nombre_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $piezas["descripcion_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);

/**************************/
/* LECTURA DE ACTIVIDADES */
/**************************/

$objPHPExcel->setActiveSheetIndex(5);//ACTIVIDADES
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $actividades["id_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $actividades["nombre_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $actividades["descripcion_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);


/*************************/
/* LECTURA DE ESTANDARES */
/*************************/

$objPHPExcel->setActiveSheetIndex(6);//ESTANDARES
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $estandares["id_maquina"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $estandares["id_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $estandares["id_sub_sistema"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $estandares["id_pieza"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $estandares["id_actividad"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$filas_excel)->getValue();
   $estandares["std_mantenimiento"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$filas_excel)->getValue();
   $estandares["frecuencia"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$filas_excel)->getValue();
   $estandares["unid_frec"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);

/**********************/
/* LECTURA DE INSUMOS */
/**********************/

$objPHPExcel->setActiveSheetIndex(7);//INSUMOS
$leer_siguiente=1;
$filas_excel=3;
$indice_array=0;
$valor_celda="";
while($leer_siguiente==1)
{
   $valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   if($valor_celda=="")
   {
       $leer_siguiente=0;
   }
   else
   {
   $insumos["id_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$filas_excel)->getValue();
   $insumos["nombre_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$filas_excel)->getValue();
   $insumos["descripcion_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$filas_excel)->getValue();
   $insumos["unidad_insumo"][$indice_array]=$valor_celda=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$filas_excel)->getValue();
   $filas_excel++;
   $indice_array++;
   }
}
//print_r($sub_sistemas);

?>




<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
		<link rel="stylesheet" type="text/css" href="../estilos/myc_gral.css"/>
        <link rel="stylesheet" type="text/css" href="../tab/tabcontent.css"  />
		<script type="text/javascript" src="../tab/tabcontent.js"></script>
		<script type="text/javascript" src="../js/form-submit.js"></script>
		<script type="text/javascript" src="../js/ajax.js"></script>
    </head>
    <body>
		<table border=0 cellPadding=5 cellSpacing=0 width="100%" >
			<tbody>
				<span>La Hoja De excel Contiene Los Siguentes Datos:</span>
			<tr>
				<td valign=top>
				<table border="0" align="left">
				<tr>
					<td valign="top">
						<ul id="countrytabs" class="shadetabs" style="list-style:none">
							<li><a href="#" rel="country1" class="selected">Usuarios</a></li>
							<li><a href="#" rel="country2">Maquinas</a></li>
							<li><a href="#" rel="country3">Sistemas</a></li>
							<li><a href="#" rel="country4">Sub-Sistemas</a></li>
							<li><a href="#" rel="country5">Piezas</a></li>
							<li><a href="#" rel="country6">Actividades</a></li>
							<li><a href="#" rel="country7">Estandares</a></li>
							<li><a href="#" rel="country8">Insumos</a></li>
							<br>
							<a href="../index.php">Inicio</a><br>
									<span>Por favor revise que la informacion sea correcta</span>
									<a href="cargar_datos.php">Cargar a base de datos</a>
						</ul>
					</td>
					
					<td valign="top">
						<!--div principal-->
						<div style="border:3px solid gray; width:1000px; height:500px; margin-bottom:auto; padding: 10px">
							<div id="country1" class="tabcontent" style="height:500px;">
								<div id="lista_instituciones" style="overflow:scroll; height:500px; overflow-x:hidden;" >
									<p align="center" class="white"><b>USUARIOS</b></p>
									<table border=1 align=center class="tinfo">
										<tr>
											<th><b> Codigo</b></th>
											<th><b> Nombres </b></th>
											<th><b> Apellidos </b></th>
											<th><b> Contraseña </b></th>
											<th><b> Nivel </b></th>
										</tr>
<?
$registros= count($usuarios["id_user"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
	echo "<td align=\"center\">".$usuarios["id_user"][$i]."</td>";
	echo "<td align=\"center\">".$usuarios["user_name"][$i]."</td>";
	echo "<td align=\"center\">".$usuarios["user_lastname"][$i]."</td>";
	echo "<td align=\"center\">".$usuarios["password"][$i]."</td>";
	echo "<td align=\"center\">".$usuarios["grade"][$i]."</td>";
    echo "</tr>";
	$c_estilo++;
}
?>
									</table>
								</div>
							</div>
               
							<div id="country2" class="tabcontent" style="height:500px;">
								<div id="lista_procesos" style="overflow:scroll; height:500px; overflow-x:hidden;" >
									<p align="center" class="white">PROCESOS</p>
									<table border=1 align=center class="tinfo">
										<tr>
											<th><b>Codigo</b></th>
											<th><b>Nombre </b></th>
											<th><b>Descripcion </b></th>
											<th><b>Horometro</b></th>
										</tr>
<?
$registros= count($procesos["id_maquina"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$procesos["id_maquina"][$i]."</td>";
echo "<td align=\"center\">".$procesos["nombre_maquina"][$i]."</td>";
echo "<td align=\"center\">".$procesos["descripcion_maquina"][$i]."</td>";
echo "<td align=\"center\">".$procesos["horometro"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
									</table>
								</div>
							</div>

							<div id="country3" class="tabcontent" style="height:500px;">
								<div id="lista_departamentos" style="overflow:scroll; height:500px; overflow-x:hidden;" >
									<p align="center" class="white">SISTEMAS</p>
									<table border=1 align=center class="tinfo">
										<tr>
											<th><b>Codigo</b></th>
											<th><b>Nombre </b></th>
											<th><b>Descripcion </b></th>
										</tr>
<?
$registros= count($sistemas["id_sistema"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$sistemas["id_sistema"][$i]."</td>";
echo "<td align=\"center\">".$sistemas["nombre_sistema"][$i]."</td>";
echo "<td align=\"center\">".$sistemas["descripcion_sistema"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
									</table>
								</div>
							</div>

							<div id="country4" class="tabcontent" style="height:500px;">
								<div id="lista_personal" style="overflow:scroll; height:500px; overflow-x:hidden;" >
									<p align="center" class="white">SUB-SISTEMAS</p>
									<table border=1 align=center class="tinfo">
										<tr>
											<th><b>Codigo</b></th>
											<th><b>Nombre</b></th>
											<th><b>Descripcion</b></th>
										</tr>
<?
$registros= count($sub_sistemas["id_sub_sistema"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$sub_sistemas["id_sub_sistema"][$i]."</td>";
echo "<td align=\"center\">".$sub_sistemas["nombre_sub_sistema"][$i]."</td>";
echo "<td align=\"center\">".$sub_sistemas["descripcion_sub_sistema"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
									</table>
								</div>
							</div>
							
							<div id="country5" class="tabcontent" style="overflow:scroll; height:500px; overflow-x:hidden; overflow-y:hidden;">
								<div id="lista_paradas" style="overflow:scroll; height:500px; overflow-x:hidden;" >
									<p align="center" class="white">PIEZAS</p>
									<table border=1 align=center class="tinfo">
									<tr>
										<th><b>Codigo</b></th>
										<th><b>Nombre</b></th>
										<th><b>Descripcion</b></th>
									</tr>
<?
$registros= count($piezas["id_pieza"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$piezas["id_pieza"][$i]."</td>";
echo "<td align=\"center\">".$piezas["nombre_pieza"][$i]."</td>";
echo "<td align=\"center\">".$piezas["descripcion_pieza"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
									</table>
								</div>
							</div>
							
						<div id="country6" class="tabcontent" style="overflow:scroll; height:500px; overflow-x:hidden; overflow-y:hidden;">
							<div id="lista_instituciones" style="overflow:scroll; height:500px; overflow-x:hidden;" >
								<p align="center" class="white">ACTIVIDADES</p>
								<table border=1 align=center class="tinfo">
									<tr>
										<th><b>Codigo</b></th>
										<th><b>Nombre</b></th>
										<th><b>Descripcion</b></th>
									</tr>
<?
$registros= count($actividades["id_actividad"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$actividades["id_actividad"][$i]."</td>";
echo "<td align=\"center\">".$actividades["nombre_actividad"][$i]."</td>";
echo "<td align=\"center\">".$actividades["descripcion_actividad"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
								</table>
							</div>
						</div>

						<div id="country7" class="tabcontent" style="overflow:scroll; height:500px; overflow-x:hidden; overflow-y:hidden;">
							<div id="lista_instituciones" style="overflow:scroll; height:500px; overflow-x:hidden;" >
								<p align="center" class="white">ESTANDARES</p>
								<table border=1 align=center class="tinfo">
									<tr>
										<th><b>Maquina</b></th>
										<th><b>Sistema</b></th>
										<th><b>Sub-Sistema</b></th>
										<th><b>Pieza</b></th>
										<th><b>Actividad</b></th>
										<th><b>Estandar</b></th>
										<th><b>Frecuencia</b></th>
										<th><b>Unidad Frecuencia</b></th>
									</tr>
<?
$registros= count($estandares["id_maquina"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$estandares["id_maquina"][$i]."</td>";
echo "<td align=\"center\">".$estandares["id_sistema"][$i]."</td>";
echo "<td align=\"center\">".$estandares["id_sub_sistema"][$i]."</td>";
echo "<td align=\"center\">".$estandares["id_pieza"][$i]."</td>";
echo "<td align=\"center\">".$estandares["id_actividad"][$i]."</td>";
echo "<td align=\"center\">".$estandares["std_mantenimiento"][$i]."</td>";
echo "<td align=\"center\">".$estandares["frecuencia"][$i]."</td>";
echo "<td align=\"center\">".$estandares["unid_frec"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
								</table>
							</div>
						</div>
						
						<div id="country8" class="tabcontent" style="overflow:scroll; height:500px; overflow-x:hidden; overflow-y:hidden;">
							<div id="lista_instituciones" style="overflow:scroll; height:500px; overflow-x:hidden;" >
								<p align="center" class="white">INSUMOS</p>
								<table border=1 align=center class="tinfo">
									<tr>
										<th><b>Codigo</b></th>
										<th><b>Nombre</b></th>
										<th><b>Descripcion</b></th>
										<th><b>Unidad de Medida</b></th>
									</tr>
<?
$registros= count($insumos["id_insumo"]);

$color=array('impar','par');
$c_estilo=0;
$i=0;
for($i=0;$i<$registros;$i++)
{
	if($c_estilo%2!=0)
		echo '<tr class="'.$color[0].'">';
	else
		echo '<tr class="'.$color[1].'">';
echo "<td align=\"center\">".$insumos["id_insumo"][$i]."</td>";
echo "<td align=\"center\">".$insumos["nombre_insumo"][$i]."</td>";
echo "<td align=\"center\">".$insumos["descripcion_insumo"][$i]."</td>";
echo "<td align=\"center\">".$insumos["unidad_insumo"][$i]."</td>";
echo "</tr>";
$c_estilo++;
}
?>
								</table>
							</div>
						</div>				


            <!-- fin div principal-->
			</div>

            </td>
            </tr>
           </table>

           	<script type="text/javascript">
           	var countries=new ddtabcontent("countrytabs")
           	countries.setpersist(true)
           	countries.setselectedClassTarget("link") //"link" or "linkparent"
           	countries.init()
			</script>
      </td>
	</tr>
   </tbody>
  </table>
    </body>
</html>
