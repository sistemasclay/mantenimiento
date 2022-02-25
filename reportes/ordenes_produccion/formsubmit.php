<?php
include("../../clases/reportes.php");
$repor= new reportes();
$pagina="escritos.php";

//echo $_POST["id_proceso"];

if($_POST["opcion"]=="fecha")
    {
            if($_POST["fechai"] > $_POST["fechaf"])
        {
           echo "La fecha de inicio debe ser menor al fecha final";
           $_POST["tipo"]=100;
           echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
        }
				$_POST["fechai"]=$_POST["fechai"]." 00:00:00";
		$_POST["fechaf"]=$_POST["fechaf"]." 23:59:59";

    }

if($_POST["opcion"]=="turno")
    {
        if($_POST["tini"] > $_POST["tfin"])
        {
           echo "La OP de inicio debe ser menor al Batch final";
           $_POST["tipo"]=100;
        //   echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
        }
            if($_POST["tini"]=="")
        {
          echo "Debe seleccionar interbalo de OPs";
           $_POST["tipo"]=100;
        //   echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
        }
       if($_POST["tfin"]=="")
        {
          echo "Debe seleccionar interbalo de OPs";
           $_POST["tipo"]=100;
       //    echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
        }
    }
       
              if($_POST["opcion"]=="turno")
              {
               $sql = "SELECT id_orden_produccion
                FROM ordenes_produccion 
                WHERE  id_orden_produccion<='".$_POST["tfin"]."' AND id_orden_produccion>='".$_POST["tini"]."'
                order by id_orden_produccion";
              }
              else
                  {
                      if($_POST["opcion"]=="fecha")
                      {
                       $sql = "SELECT id_orden_produccion
                FROM ordenes_produccion
                        WHERE  fecha_inicio>='".$_POST["fechai"]."' AND fecha_inicio<='".$_POST["fechaf"]."'
                         order by id_orden_produccion";
                      }
                  }
               // WHERE b.id_proceso='".$_POST["id_proceso"]."' AND fecha_inicio BETWEEN CAST ('".$_POST["fechai"]."' AS DATE) AND CAST ('".$_POST["fechaf"]."' AS DATE)  order by id_turno";
//  WHERE b.id_proceso='".$_POST["id_proceso"]."' AND fecha_inicio>='".$_POST["fechai"]."' AND fecha_inicio<='".$_POST["fechaf"]."'  order by id_turno";
//  echo $sql;
                    $repor->pdf_ordenes_produccion($sql);



        ?>

