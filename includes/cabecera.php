<style type="text/css">
  #uno {
    display: table;
    height: 200px;
    border: 1px solid #f00;
    position: relative;
    width: 200px;
  }

  #dos {
    display: table-cell;
    vertical-align: middle;
  }

  #tres {
    border: 1px solid #0f0;
    position: relative;
  }
</style>
<div id="cabecera">
  <a href="/mantenimiento/index.php">
    <div id="logo" style="text-align:center"></div>
  </a>
  <?php
  if ($_SESSION["id_usuario"]) {
    require_once('menuppal.php');
  }
  ?>
  <div id="lder">
    <br>
    <div id="salir"><a href="/mantenimiento/form_submit_login.php?salir=1"><img src="/mantenimiento/imgs/salir.png" width="30" height="30"><br />Salir</a></div>
  </div>
  <div id="lder">
    <br>
    <div id="salir">
      <a href="http://172.16.96.23:810/rutas_pegasus">
        <img src="/mantenimiento/imgs/ppoee.png" width="30" height="30" style="right:30px" /></a>
    </div>
  </div>
</div>