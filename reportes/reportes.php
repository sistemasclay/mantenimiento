<?
 session_start();
include("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();
$fecha = time();
$pagina = $pagina.'?seccion=reportes';
?>
			<div id="titulo">
				<h1>REPORTES</h1>
<!--				<h2>Aquí podra ingresar la información general referente a los sub-sistemas que las máquinas usen</h2>	
				<form class="forma" action="<?//$pagina?>" method="post" name="busqueda">
						<table class="tforma">
							<tr>
								<td  class="etq">
									Busqueda Rapida
								</td>
								<td>
									<input class="ctxt" name="codigo_piezab" type="text" id="codigo_piezab" />
								</td>
								<td colspan="2">
									<input class="btn" type="submit" />
								</td>
							</tr>
						</table>
					</form>
-->
			</div>
                <!--div principal-->
			<div id="contenido">
				<form class="forma" action="formsubmit.php" target="_blank" method="POST" name="parametros">
<!--					<input type="hidden" value="<? echo $_POST["proceso"] ?>" name="id_proceso"/>-->
					<table class="tforma">
						<tr>
							<td class="etq">
							DESDE
							</td>
							<td class="etq">
							HASTA
							</td>
						</tr>
						<tr>
							<td>
								<input class="ctxt" type="text" name="fechai" id="fechai" onclick="displayCalendar(document.forms[1].fechai,'yyyy/mm/dd',this)" value="<? echo date("Y/m/d",$fecha);  ?>"/>
							</td>
							<td>
								<input class="ctxt" type="text" name="fechaf" id="fechaf" onclick="displayCalendar(document.forms[1].fechaf,'yyyy/mm/dd',this)" value="<? echo date("Y/m/d",$fecha);  ?>"  />
							</td>
						</tr>
						<tr>
							<td class="etq">
								Seleccione un reporte
							</td>
							<td colspan="2">
								<select class="lista" size="1" name="tipo" id="tipo">
								<option value="1" >Bitacora de Mantenimientos</option>
<!--								<option value="2" >Reporte Asistencia</option>
								<option value="3" >Reporte Paradas</option>-->
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input class="btn"  type="submit" value="Ver Reporte"/>
							</td>
						</tr>
					</table>
				</form>
			</div>
            <!-- fin div principal-->

  
<script  languaje= "JavaScript" >
//piezas
var f16 = new LiveValidation('codigo_pieza');
f16.add( Validate.Presence );
f16.add( Validate.Numericality, { onlyInteger: true } );

var f17 = new LiveValidation('codigo_piezab');
f17.add( Validate.Numericality, { onlyInteger: true } );

var f18 = new LiveValidation('nombre_pieza');
f18.add( Validate.Presence );

var f19 = new LiveValidation('descripcion_pieza');
f19.add( Validate.Presence );
</script>