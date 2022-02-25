<?php


$pagina = $pagina.'?seccion=insumos';
//echo $pagina;
    if($_GET["codigo_insumo"])
    {
    $datos_insumo = $datos->detalle_insumo($_GET["codigo_insumo"]);
   // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
    }
	else
	{
		if($_POST["codigo_insumob"])
		{
			$datos_insumo = $datos->detalle_insumo($_POST["codigo_insumob"]);
		}
	}
?>
			<div id="titulo">
				<h1>INSUMOS</h1>
				<h2>Aquí podra ingresar la información general referente los insumos que se usen en los mantenimientos</h2>
				<br>
				<form class="ui form" action="<?php $pagina?>" method="post" name="busqueda">
						<table class="tforma">
							<tr>
								<td  class="etq">
									Busqueda Rapida
								</td>
								<td>
									<input class="ctxt" name="codigo_insumob" type="text" id="codigo_insumob" />
								</td>
								<td colspan="2">
									<input class="ui yellow button" type="submit" />
								</td>
							</tr>
						</table>
					</form>
			</div>
      <br>
                <!--div principal-->
                <!--  overflow: scroll; -->

      <div class="ui container">
        <div class="ui two column grid">
          <div class="ui six wide column">
            <div id="datos" class="scrollstyle">

    					<form class="ui form" id="finsumos" name="finsumos" method="post" action="formsubmit_config.php">
    						<input type="hidden" name="seccion" id="seccion" value="insumos"/>
    						<table class="ui table" >
    							<tr>
    								<td class="etq">
    									Codigo:
    								</td>
    								<td>
    									<input class="ctxt" type="text" <?php  if($datos_insumo->fields["id_insumo"]!=""){ echo "readonly=\"readonly\""; } ?> name="codigo_insumo" id="codigo_insumo" value ="<?php  echo $datos_insumo->fields["id_insumo"];   ?>"/>
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Nombre:
    								</td>
    								<td>
    									<input class="ctxt" type="text" name="nombre_insumo" id="nombre_insumo" value ="<?php  echo $datos_insumo->fields["nombre_insumo"];?>" />
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Activo:
    								</td>
    								<td>
    									<input type="Checkbox" name="estado" id="estado" <?php  if ($datos_insumo->fields["estado"]=="1") echo "checked" ?>  />
    								</td>
    							</tr>
    							<tr>
    								<td colspan="2">
    									<input class="ui yellow button" type="submit" name="Submit" value="Guardar" />
    								</td>
    							</tr>
    						</table>
    					</form>
    				</div>
          </div>
          <div class="ui ten wide column" style="overflow:scroll; height:600px; overflow-x:hidden;">
            <div id="lista" class="scrollstyle">
    					<table class=" ui inverted yellow celled table">
    						<tr>
    							<th><div class="tooltip">Codigo</div></th>
    							<th><div class="tooltip">Nombre</div></th>
    							<th>Editar</th>
    							<th>Eliminar</th>
    						</tr>
    <?php
    		$recordSet=$datos->listar_insumos();
    		//echo $recordSet;
    		$color=array('impar','par');
    		$c_estilo=0;

    		while (!$recordSet->EOF) {

    							if($c_estilo%2!=0)
    							echo '<tr class="'.$color[0].'">';
    					else
    							echo '<tr class="'.$color[1].'">';
    		echo "
    		<td>".$recordSet->fields['id_insumo']."</td>
    		<td>".$recordSet->fields['nombre_insumo']."</td>
    		<td><a href=$pagina&&codigo_insumo="
    				.$recordSet->fields['id_insumo']."><img src=\"imagenes/edit1.png\"> </a></td>
    			<td><a onclick='return confirm(\"¿Seguro que desea eliminar  ".$recordSet->fields['nombre_insumo']."?\")' href=formsubmit_config.php?seccion=eliminar_insumo&codigo_insumo="
    				.$recordSet->fields['id_insumo']."><img src=\"imagenes/delete3.png\"> </a></td>
    		</tr>
    		";
    		$c_estilo++;
    		$recordSet->MoveNext();
    		}
    ?>
    					</table>
    				</div>
          </div>
        </div>
      </div>
			<div id="">

        <br>

			</div>
            <!-- fin div principal-->
