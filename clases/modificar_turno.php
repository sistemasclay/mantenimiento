<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of configuracion_aplicacion
 * esta clase esta asociada a las configuracion de la informacion del
 * sistema.
 * @author Juan Pablo Giraldo
 */
      include("adodb5/adodb-exceptions.inc.php");
      include("adodb5/adodb.inc.php");
      
class modificar_turno {
        


   function listar_etiquetas()
    {
        include("conexion.php");
       // $sql =	"SELECT * FROM ordenes_produccion";
       $sql = "SELECT * FROM variables order by id_variable";
       $result=$conexion_pg->Execute($sql);
	if ($result === false)
            {
            echo 'error al listar: '.$conexion_pg->ErrorMsg().'<BR>';
            }
            $conexion_pg->Close();
            $i=0;
        while (!$result->EOF)
	{
			$ar[$i]["etiqueta"]=$result->fields["etiqueta"];
			$result->MoveNext();
			$i++;
	}
         return $ar;
    }

}

?>
