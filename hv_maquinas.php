<?php


$pagina = $pagina . '?seccion=hv_maquinas';
//echo $pagina;
if ($_GET["codigo_proceso"]) {
  $datos_maquina = $datos->detalle_proceso($_GET["codigo_proceso"]);
  // echo $datos_proceso->fields["nombre"]."-".$datos_proceso->fields["id_proceso"];
} else {
  if ($_POST["codigo_procesob"]) {
    $datos_maquina = $datos->detalle_proceso($_POST["codigo_procesob"]);
  }
}
?>
<div id="titulo">
  <h1>HV MAQUINAS</h1>
  <h2>Aquí prodra acceder a la Hoja de vida de todas las maquinas de la planta</h2>
</div>
<!--div principal-->
<!--  overflow: scroll; -->
<div class="ui container">
  <div class="ui two column grid">
    <div class="ui eigth wide column">
      <div id="">
        <div id="lista3" class="scrollstyle">
          <table class="ui inverted yellow celled table">
            <tr>
              <th>
                <div class="tooltip">Codigo</div>
              </th>
              <th>
                <div class="tooltip">Nombre</div>
              </th>
              <th>
                <div class="tooltip"><?php if (!$_SESSION["grade"] >= 2) echo "Ver hoja de vida";
                                      else echo "Registrar en <br> hoja de vida"; ?></div>
              </th>
            </tr>
            <?php
            $recordSet = $datos->listar_procesos();
            //echo $recordSet;
            $color = array('impar', 'par');
            $c_estilo = 0;

            while (!$recordSet->EOF) {

              if ($c_estilo % 2 != 0)
                echo '<tr class="' . $color[0] . '">';
              else
                echo '<tr class="' . $color[1] . '">';
              echo "
      		<td>" . $recordSet->fields['id_maquina'] . "</td>
      		<td>" . $recordSet->fields['nombre_maquina'] . "</td>
      		<td><a href=$pagina&&codigo_proceso="
                . $recordSet->fields['id_maquina'] . "><img src=\"imagenes/edit1.png\"> </a></td>
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
    <div class="ui eigth wide column">
      <?php if ($_GET["codigo_proceso"]) { ?>
        <div id="datos2" class="scrollstyle">

          <form class="ui form" id="fprocesos" name="fprocesos" method="post" action="formsubmit_config.php">
            <input type="hidden" name="seccion" id="seccion" value="hv_maquinas" />
            <input type="hidden" name="maquina" id="maquina" value="<?php echo $datos_maquina->fields["id_maquina"] ?>" />
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"] ?>" />
            <table class="ui  yellow table">
              <tr>
                <th class="">
                  Observaciones para la hoja de vida de la máquina <b><?php echo $datos_maquina->fields["nombre_maquina"]; ?></b>:
                </th>
              </tr>
              <tr>
                <td>
                  <textarea class="ctxt" cols=50 rows=15 name="obs_hv" id="obs_hv" style="resize: both;"></textarea>
                </td>
              </tr>
              <th>
                <input class="ui yellow button" type="submit" name="Submit" value="Guardar" />
              </th>
              </tr>
            </table>
          </form>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- fin div principal-->