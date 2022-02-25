
<script type="text/javascript">
	function alertaInconsitente() {
		swal({
			title: "Datos Inconsitentes",
			text: "Intente de Nuevo",
			icon: "warning",
		});
	}

function alertaInicioCorrecto() {
	swal({
		title: "Inicio Sesion",
		text: "Exitoso!",
		icon: "success",
	});
};
function alertaFinSesion() {
	swal({
		title: "Fin Sesion",
		text: "Hasta Pronto",
		icon: "success",
	});

};
function alertaIngreseDatos() {
	swal({
		title: "Ingrese los datos",
		text: "",
		icon: "error",
	});
}
</script>
<?php
session_start();
include_once('layout.php');
include("clases/acceso.php");
$datos= new datos_persona();

echo "<h1>INICIO SESIÓN</h1>

 <form class='ui form' action='form_submit_login.php' method='post'>
	 <table align='center' width='20%' height='220' class='ui table'>
		 <tr>
		 <td><input class='text' name='usuario' type='number' pattern='^[9|8|7|6]\d{8}$' id='usuario' autocomplete='off' placeholder='Usuario'/></td>

		 </tr>
		 <tr>
			 <td><input class='text' name='pass' type='password' id='pass' placeholder='Contraseña'/></td>
		 </tr>

		 <tr>
			 <td >
					<div class='field'>
						<input class='ui yellow button'  type='submit' name='Submit' value='Enviar' />
					</div>

				</td>
		</tr>
	 </table>
 <p>&nbsp;</p>

 </form>";



	if($_GET['salir'])
    {
					Session_destroy();
					echo "<script>";
					echo "alertaFinSesion();";
						echo "</script>";

          $pagina="login.php";
         	echo "<meta http-equiv=\"refresh\" content=\"1;URL=".$pagina."\">";
    }
    else
    {
				if ($POST["usuario"]=="") {
					// code...
					echo "<script>";
					echo "alertaIngreseDatos();";
					echo "</script>";

				}
         $datos_r=$datos->credencial($_POST["usuario"],$_POST["pass"]);

                    $i=0;

        while (!$datos_r->EOF) {

            $i++;
            $datos_r->MoveNext();
        }
         $datos_r->MoveFirst();

             if($i==1)
            {

              $_SESSION[user]=$datos_r->fields["id_user"];
              $_SESSION[name]=$datos_r->fields["user_name"];
              $_SESSION[grade]=$datos_r->fields["grade"];
              		echo "<script>";
						echo "alertaInicioCorrecto();";
						echo "</script>";

            $pagina="principal.php";
            echo "<meta http-equiv=\"refresh\" content=\"1;URL=".$pagina."\">";

            }
            else
                {
                $pagina="login.php";
								echo "<script>";
								echo "alertaInconsitente();";
								echo "</script>";


                echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
                }

	}
//print_r($datos);


?>
