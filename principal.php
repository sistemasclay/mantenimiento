<?php include_once("layout.php");
/*
include_once("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();
*/

include_once("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();

$pagina = 'principal.php';
//echo $pagina;
if($_GET["seccion"])
{
	$seccion = $_GET["seccion"];
	//echo $datos_planta->fields["nombre"]."-".$datos_planta->fields["id_planta"];
}
else
{
	if($_POST["seccion"])
	{
		$seccion = $_POST["seccion"];
	}
}
?>

<div class="ui fluid container">
	<div class="ui two column grid"id="contenedor">
		<div class="ui two wide column">
			<div class="ui vertical menu">
				<?php if($_SESSION["user"]){?>


	<?php
						if($_SESSION["grade"]>=2){
							if($datos->estado_configuracion(1))//CONFIGURACION 1 ES 'USUARIOS'
							{
								echo'
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=usuarios"><IMG SRC="imgs/1.png" ALT="Operarios" onmouseover="src=\'imgs/1A.png\'" onMouseOut="src=\'imgs/1.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(2))//CONFIGURACION 2 ES 'M�QUINAS'
							{
								echo'
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=maquinas"><IMG SRC="imgs/2.png" ALT="Maquinas" onmouseover="src=\'imgs/2A.png\'" onMouseOut="src=\'imgs/2.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(3))//CONFIGURACION 3 ES 'SISTEMAS'
							{
								echo'
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=sistemas"><IMG SRC="imgs/3.png" ALT="Sistemas" onmouseover="src=\'imgs/3A.png\'" onMouseOut="src=\'imgs/3.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(4))//CONFIGURACION 4 ES 'SUBSISTEMAS'
							{
								echo'
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=sub_sistemas"><IMG SRC="imgs/4.png" ALT="Sub-Sistemas" onmouseover="src=\'imgs/4A.png\'" onMouseOut="src=\'imgs/4.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(5))//CONFIGURACION 5 ES 'PIEZAS'
							{
								echo '
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=piezas"><IMG SRC="imgs/5.png" ALT="Piezas" onmouseover="src=\'imgs/5A.png\'" onMouseOut="src=\'imgs/5.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(6))//CONFIGURACION 6 ES 'ACTIVIDADES'
							{
								echo '
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=actividades"><IMG SRC="imgs/6.png" ALT="Actividades" onmouseover="src=\'imgs/6A.png\'" onMouseOut="src=\'imgs/6.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(7))//CONFIGURACION 7 ES 'INSUMOS'
							{
								echo '
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=insumos"><IMG SRC="imgs/7.png" ALT="Insumos" onmouseover="src=\'imgs/7A.png\'" onMouseOut="src=\'imgs/7.png\'" ></a>
										</td>
									</tr>
								';
							}
							if($datos->estado_configuracion(8))//CONFIGURACION 8 ES 'ESTANDARES'
							{
								echo '
									<tr>
										<td class="menuitem">
											<a href="principal.php?seccion=estandares"><IMG SRC="imgs/8.png" ALT="Estandares" onmouseover="src=\'imgs/8A.png\'" onMouseOut="src=\'imgs/8.png\'" ></a>
										</td>
									</tr>
								';
							}

						}
						if($datos->estado_configuracion(10))//CONFIGURACION 10 ES 'ORDENES DE TRABAJO'
						{
							echo '
								<tr>
									<td class="menuitem">
										<a href="principal.php?seccion=ordenes_trabajo"><IMG SRC="imgs/10.png" ALT="Ordenes" onmouseover="src=\'imgs/10A.png\'" onMouseOut="src=\'imgs/10.png\'" ></a>
									</td>
								</tr>
							';
						}
						if($datos->estado_configuracion(11) & $_SESSION["grade"]>=2)//CONFIGURACION 11 ES 'REPORTES'
						{
							echo '
								<tr>
									<td class="menuitem">
										<a href="principal.php?seccion=reportes"><IMG SRC="imgs/11.png" ALT="Reportes" onmouseover="src=\'imgs/11A.png\'" onMouseOut="src=\'imgs/11.png\'" ></a>
									</td>
								</tr>
							';
						}
						if($datos->estado_configuracion(12))//CONFIGURACION 12 ES 'HOJA DE VIDA M�QUINAS'
						{
							echo '
								<tr>
									<td class="menuitem">
										<a href="principal.php?seccion=hv_maquinas"><IMG SRC="imgs/12.png" ALT="HV M�quinas" onmouseover="src=\'imgs/12A.png\'" onMouseOut="src=\'imgs/12.png\'" ></a>
									</td>
								</tr>
							';
						}
	?>


				<?php }?>
				</td>
			</div>
		</div>
		<div class="ui fourteen wide column">

	<?php
						if($_SESSION["user"]){
							switch ($seccion)
							{
								case 'usuarios':
									require_once('usuarios.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'maquinas':
									require_once('maquinas.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'sistemas':
									require_once('sistemas.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'sub_sistemas':
									require_once('sub_sistemas.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'piezas':
									require_once('piezas.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'actividades':
									require_once('actividades.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'insumos':
									require_once('insumos.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'estandares':
									require_once('estandares.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;

								case 'reportes':
									require_once('reportes.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'ordenes_trabajo':
									require_once('ordenes_trabajo.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								case 'hv_maquinas':
									require_once('hv_maquinas.php');
									$datos->actualizar_horometros_automatico();
									$datos->actualizar_fechas_ordenes();
								break;
								default:
									echo '<h1> HOLA '.$_SESSION["name"].' ¿QUE DESEAS HACER? </h1>';
							}
						}
						else{
							echo '<h1> NO SE DETECTA UNA SESION ABIERTA, POR FAVOR INICIE SESI&Oacute;N </h1>';
						}
	?>
					</td>
				</tr>

		</div>

	</div>

</div>
