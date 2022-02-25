<?php


$pagina = $pagina.'?seccion=usuarios';
//echo $pagina;
    if($_GET["codigo_empleado"])
    {
    $datos_empleado = $datos->detalle_usuario($_GET["codigo_empleado"]);
   // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
    }
	else
	{
		if($_POST["codigo_empleadob"])
		{
			$datos_empleado = $datos->detalle_usuario($_POST["codigo_empleadob"]);
		}
	}
?>
			<div id="titulo">
				<h1>USUARIOS</h1>
				<h2>Aquí podra ingresar al personal y los permisos que estos tengan en la aplicación</h2>
				<br>
				<form class="ui form" action="<?php $pagina?>" method="post" name="busqueda">
						<table class="tforma">
							<tr>
								<td  class="etq">
									Busqueda Rapida
								</td>
								<td>
									<input class="ctxt" name="codigo_empleadob" type="text" id="codigo_empleadob" />
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

    					<form class="ui form" id="fpersonal" name="fpersonal" method="post" action="formsubmit_config.php">
    						<input type="hidden" name="seccion" id="seccion" value="usuarios"/>
    						<table class="ui table" >
    							<tr>
    								<td class="etq">
    									Codigo:
    								</td>
    								<td>
    									<input class="ctxt" type="text" <?php  if($datos_empleado->fields["id_user"]!=""){ echo "readonly=\"readonly\""; } ?> name="codigo_empleado" id="codigo_empleado" value ="<?php  echo $datos_empleado->fields["id_user"];   ?>"/>
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Nombre:
    								</td>
    								<td>
    									<input class="ctxt" type="text" name="user_name" id="user_name" value ="<?php  echo $datos_empleado->fields["user_name"];?>" />
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Apellido:
    								</td>
    								<td>
    									<input class="ctxt" type="text" name="user_lastname" id="user_lastname" value ="<?php  echo $datos_empleado->fields["user_lastname"];   ?>"/>
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Contraseña:
    								</td>
    								<td>
    									<input class="ctxt"  type="password" name="contra_empleado" id="contra_empleado" value ="<?php  echo $datos_empleado->fields["password"];   ?>" />
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Nivel:
    								</td>
    								<td>
    									<select class="lista" name="nivel_empleado" id="nivel_empleado">
    <?php
    	if($_GET["codigo_empleado"])
    	{
    		echo "<option value=\" ".$datos_empleado->fields["grade"]." \">".$datos_empleado->fields["grade"]."</option>";
    	}
    ?>
    										<option class="oplista" value="1">1</option>
    										<option class="oplista" value="2">2</option>
    										<option class="oplista" value="3">3</option>
    									</select>
    								</td>
    							</tr>
    							<tr>
    								<td class="etq">
    									Activo:
    								</td>
    								<td>
    									<input type="Checkbox" name="estado_empleado" id="estado_empleado" <?php  if ($datos_empleado->fields["estado"]=="1") echo "checked" ?>  />
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
          <div class="ui ten wide column">
            <div id="lista" class="scrollstyle">
              <table class="ui inverted yellow celled table">
              
                    <th style="width=20px"><div class="tooltip">CODIGO </div></th>
                  <th><div class="tooltip">NOMBRE </th>
                  <th><div class="tooltip">APELLIDO </th>
                  <th><div class="tooltip">NIVEL </th>
                  <th>Editar</th>
                  <th>Eliminar</th>
                </tr>
    <?php
        $recordSet=$datos->listar_usuarios();
        //echo $recordSet;
        $color=array('impar','par');
        $c_estilo=0;

        while (!$recordSet->EOF) {

                  if($c_estilo%2!=0)
                  echo '<tr class="'.$color[0].'">';
              else
                  echo '<tr class="'.$color[1].'">';
        echo "
        <td>".$recordSet->fields['id_user']."</td>
        <td>".$recordSet->fields['user_name']."</td>
        <td>".$recordSet->fields['user_lastname']."</td>
        <td>".$recordSet->fields['grade']."</td>
        <td><a href=$pagina&&codigo_empleado="
            .$recordSet->fields['id_user']."><img src=\"imagenes/edit1.png\"> </a></td>
          <td><a onclick='return confirm(\"¿Seguro que desea eliminar  ".$recordSet->fields['user_name']."?\")' href=formsubmit_config.php?seccion=eliminar_empleado&codigo_empleado="
            .$recordSet->fields['id_user']."><img src=\"imagenes/delete3.png\"> </a></td>
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
          </div>
        </div>
      </div>


            <!-- fin div principal-->
