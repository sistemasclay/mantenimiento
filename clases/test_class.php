<?php

/**
 * Description of configuracion_aplicacion
 * esta clase esta asociada a las configuracion de la informacion del
 * sistema.
 * @author Juan Pablo salazar
 */
      include("adodb5/adodb-exceptions.inc.php");

      include("adodb5/adodb.inc.php");
class test_class {

	function ejecutar_sql($sql){
		include("conexion.php");
		$result=$conexion_pg->Execute($sql);
		if ($result === false)
            {
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
            }
         $conexion_pg->Close();
         return $result;
	}
    
}
?>