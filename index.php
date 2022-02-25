<?php include_once('layout.php') ?>

<div id="contenido1">
  <?php
  if ($_SESSION["name"]) {
    echo '<h1> HOLA ' . $_SESSION["name"] . ' ¿QUE DESEAS HACER? </h1>';
    $pagina = "principal.php";
    echo "<meta http-equiv=\"refresh\" content=\"1;URL=" . $pagina . "\">";
  } else {
  ?>
    <h1>INICIO SESIÓN</h1>

    <form class="ui form" action="form_submit_login.php" method="post">
      <table align="center" width="20%" height="220" class="ui table">
        <tr>
          <td><input class="text" name="usuario" type="number" pattern="^[9|8|7|6]\d{8}$" id="usuario" autocomplete="off" placeholder="Usuario" /></td>

        </tr>
        <tr>
          <td><input class="text" name="pass" type="password" id="pass" placeholder="Contraseña" /></td>
        </tr>

        <tr>
          <td>
            <div class="field">
              <input class="ui yellow button" type="submit" name="Submit" value="Enviar" />
            </div>

          </td>
        </tr>
      </table>
      <p>&nbsp;</p>

    </form>
  <?php } ?>
</div>
<?php require_once('includes/piep.php'); ?>

<!-- <script  languaje= "JavaScript" >
    var f1 = new LiveValidation('usuario');
    f1.add( Validate.Presence );
    f1.add( Validate.Numericality, { onlyInteger: true } );
    var f2 = new LiveValidation('pass');
    f2.add( Validate.Presence );
  </script> -->