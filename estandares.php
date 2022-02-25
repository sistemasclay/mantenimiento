<?php
 session_start();

$pagina = $pagina.'?seccion=estandares';
//echo $pagina;
    if($_GET["codigo_estandar"])
    {
    $datos_estandar = $datos->detalle_estandar2($_GET["codigo_estandar"]);
   // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
    }
	else
	{
		if($_POST["codigo_estandarb"])
		{
			$datos_estandar = $datos->detalle_estandar($_POST["codigo_estandarb"]);
		}
	}

?>
			<div id="titulo">
				<h1>ESTANDARES</h1>
				<h2>Aquí podrá ingresar la información general referente a los estándares que las máquinas utilizen</h2>
				<br>
			</div>
                <!--div principal-->
                <!--  overflow: scroll; -->
			<div id="contenido">
				<div id="datos" class="scrollstyle">

					<form class="forma" id="festandares" name="festandares" method="post" action="formsubmit_config.php">
						<input type="hidden" name="seccion" id="seccion" value="estandares"/>
<?php
						if($_GET["codigo_estandar"])
						{
							echo '<input type="hidden" name="codigo_estandar" id="codigo_estandar" value="'.$_GET["codigo_estandar"].'"/>';
						}

?>
						<table class="tforma" >
							<tr>
								<td class="etq">
									Máquina
								</td>
								<td>
									<select class="lista" name="codigo_proceso" id="codigo_proceso">
<?php
    if($_GET["codigo_estandar"])
    {
		$proceso = $datos->detalle_proceso($datos_estandar->fields['id_maquina']);
        echo "<option class=\"oplista\" value=\" ".$proceso->fields['id_maquina']." \">".$proceso->fields['nombre_maquina']."</option>";
    }

	$procesos = $datos->listar_procesos();

	while (!$procesos->EOF) {
		echo "<option class=\"oplista\" value=\" ".$procesos->fields['id_maquina']." \">".$procesos->fields['nombre_maquina']."</option>";
		$procesos->MoveNext();
	}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Sistema
								</td>
								<td>
									<select class="lista" name="codigo_sistema" id="codigo_sistema">
<?php
    if($_GET["codigo_estandar"])
    {
		$sistema = $datos->detalle_sistema($datos_estandar->fields['id_sistema']);
        echo "<option class=\"oplista\" value=\" ".$sistema->fields['id_sistema']." \">".$sistema->fields['nombre_sistema']."</option>";
    }

	$sistemas = $datos->listar_sistemas();

	while (!$sistemas->EOF) {
		echo "<option class=\"oplista\" value=\" ".$sistemas->fields['id_sistema']." \">".$sistemas->fields['nombre_sistema']."</option>";
		$sistemas->MoveNext();
	}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Sub-Sistema
								</td>
								<td>
									<select class="lista" name="codigo_sub_sistema" id="codigo_sub_sistema">
<?php
    if($_GET["codigo_estandar"])
    {
		$sub_sistema = $datos->detalle_sub_sistema($datos_estandar->fields['id_sub_sistema']);
        echo "<option class=\"oplista\" value=\" ".$sub_sistema->fields['id_sub_sistema']." \">".$sub_sistema->fields['nombre_sub_sistema']."</option>";
    }

	$sub_sistemas = $datos->listar_sub_sistemas();

	while (!$sub_sistemas->EOF) {
		echo "<option class=\"oplista\" value=\" ".$sub_sistemas->fields['id_sub_sistema']." \">".$sub_sistemas->fields['nombre_sub_sistema']."</option>";
		$sub_sistemas->MoveNext();
	}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Pieza
								</td>
								<td>
									<select class="lista" name="codigo_pieza" id="codigo_pieza">
<?php
    if($_GET["codigo_estandar"])
    {
		$pieza = $datos->detalle_pieza($datos_estandar->fields['id_pieza']);
        echo "<option class=\"oplista\" value=\" ".$pieza->fields['id_pieza']." \">".$pieza->fields['nombre_pieza']."</option>";
    }

	$actividades = $datos->listar_piezas();

	while (!$actividades->EOF) {
		echo "<option class=\"oplista\" value=\" ".$actividades->fields['id_pieza']." \">".$actividades->fields['nombre_pieza']."</option>";
		$actividades->MoveNext();
	}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Actvidad
								</td>
								<td>
									<select class="lista" name="codigo_actividad" id="codigo_actividad">
<?php
    if($_GET["codigo_estandar"])
    {
		$actividad = $datos->detalle_actividad($datos_estandar->fields['id_actividad']);
        echo "<option class=\"oplista\" value=\" ".$actividad->fields['id_actividad']." \">".$actividad->fields['nombre_actividad']."</option>";
    }

	$actividades = $datos->listar_actividades();

	while (!$actividades->EOF) {
		echo "<option class=\"oplista\" value=\" ".$actividades->fields['id_actividad']." \">".$actividades->fields['nombre_actividad']."</option>";
		$actividades->MoveNext();
	}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Tiempo Estandar(min):
								</td>
								<td>
									<input class="ctxt" type="text" name="tiempo_estandar" id="tiempo_estandar" value ="<?php  echo $datos_estandar->fields["std_mantenimiento"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Frecuencia:
								</td>
								<td>
									<input class="ctxt" type="text" name="frecuencia" id="frecuencia" value ="<?php  echo $datos_estandar->fields["frecuencia"];   ?>"/>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Unidad Frecuencia:
								</td>
								<td>
									<select class="lista" name="unidad_frecuencia" id="unidad_frecuencia">
<?php
    if($_GET["codigo_estandar"])
    {

		if( $datos_estandar->fields["unidad_frecuencia"] == 1){
			$unidad = 'Horas';
		}
		else{
			$unidad = 'Dias';
		}
        echo "<option class=\"oplista\" value=\" ".$datos_estandar->fields["unidad_frecuencia"]." \">".$unidad."</option>";
    }
?>
										<option class="oplista" value="1">Horas</option>
										<option class="oplista" value="2">Dias</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etq">
									Activo:
								</td>
								<td>
									<input type="Checkbox" name="estado" id="estado" <?php  if ($datos_estandar->fields["estado"]=="1") echo "checked" ?>  />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input class="btn" type="submit" name="Submit" value="Guardar" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="lista" class="scrollstyle">
					<table class="tinfo">
						<tr>
							<th>Máquina</th>
							<th>Sistema</th>
							<th>Sub-Sistema</th>
							<th>Pieza</th>
							<th>Actividad</th>
							<th>Estandar<br>(Minutos)</th>
							<th>Frecuencia</th>
							<th>Unid<br>Frecuencia</th>
							<th>Editar</th>
							<th>Eliminar</th>
						</tr>
<?php
		$recordSet=$datos->listar_estandares();
		//echo $recordSet;
		$color=array('impar','par');
		$c_estilo=0;

		while (!$recordSet->EOF) {
			$proceso = $datos->detalle_proceso($recordSet->fields['id_maquina']);
			$sistema = $datos->detalle_sistema($recordSet->fields['id_sistema']);
			$sub_sistema = $datos->detalle_sub_sistema($recordSet->fields['id_sub_sistema']);
			$actividad = $datos->detalle_actividad($recordSet->fields['id_actividad']);
			$pieza = $datos->detalle_pieza($recordSet->fields['id_pieza']);
			if($recordSet->fields['unidad_frecuencia']==1){
				$unidad = "Horas";
			}
			if($recordSet->fields['unidad_frecuencia']==2){
				$unidad = "Días";
			}


						if($c_estilo%2!=0)
								echo '<tr class="'.$color[0].'">';
						else
								echo '<tr class="'.$color[1].'">';
			echo "
			<td>".$proceso->fields['nombre_maquina']."</td>
			<td>".$sistema->fields['nombre_sistema']."</td>
			<td>".$sub_sistema->fields['nombre_sub_sistema']."</td>
			<td>".$pieza->fields['nombre_pieza']."</td>
			<td>".$actividad->fields['nombre_actividad']."</td>
			<td>".$recordSet->fields['std_mantenimiento']."</td>
			<td>".$recordSet->fields['frecuencia']."</td>
			<td>".$unidad."</td>
			<td><a href=$pagina&&codigo_estandar="
					.$recordSet->fields['id_estandar']."><img src=\"imagenes/edit1.png\"> </a></td>
				<td><a onclick='return confirm(\"¿Seguro que desea eliminar  ".$recordSet->fields['nombre_estandar']."?\")' href=formsubmit_config.php?seccion=eliminar_estandar&codigo_estandar="
					.$recordSet->fields['id_estandar']."><img src=\"imagenes/delete3.png\"> </a></td>
			</tr>
			";
			$c_estilo++;
			$recordSet->MoveNext();
		}
?>
					</table>
				</div>
			</div>
            <!-- fin div principal-->
