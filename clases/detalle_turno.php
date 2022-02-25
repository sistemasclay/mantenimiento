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
      
class detalle_turno {
        
                function traer_turno_log($proceso,$turno)
        {                 
            		include("conexion.php");
			$result = $conexion_pg->Execute("SELECT * FROM turno_log WHERE id_proceso=".$proceso." and id_turno=".$turno." order by id_turnolog");
                        //$result = $db->Execute("SELECT * FROM usuarios as b INNER JOIN lista_paises as a ON (b.pais= a.id) INNER JOIN lista_estados as c ON (b.estado= c.id) WHERE aceptado = 0 ORDER BY b.id_usuario DESC LIMIT $inicio, $registros");
                        if ($result === false) die("fallo consulta");
			 $conexion_pg->Close();
                        $i=0;
			return $result;
        }

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
