<?php
include("../../clases/graficos_oee_producto.php");
$repor= new reportes_graficos();
$pagina="graficos_oee_producto.php";

//echo $_POST["persona"];
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

         $inf= "De ".$_POST["fechai"]." a ".$_POST["fechaf"];
    }

	if($_POST["opcion"]=="turno")
    {
		if($_POST["tini"] > $_POST["tfin"])
		{
			echo "El Batch de inicio debe ser menor al BAtch final";
			$_POST["tipo"]=100;
			echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
		}
		if($_POST["tini"]=="")
		{
			echo "Debe seleccionar interbalo de Batchs";
			$_POST["tipo"]=100;
			echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
		}
		if($_POST["tfin"]=="")
		{
			echo "Debe seleccionar interbalo de Batchs";
			$_POST["tipo"]=100;
			echo "<meta http-equiv=\"refresh\" content=\"2;URL=".$pagina."\">";
		}
		$inf= "De ".$_POST["tini"]." a ".$_POST["tfin"];
    }

	$sql="";
	if($_POST["opcion"]=="turno")
	{
		$sql="SELECT p.nombre_producto nombre,
					a.indicador_1,
					a.indicador_2,
					a.indicador_3,
					a.tiempo_turno,
					a.unidades_conteo
				FROM turnos a
					INNER JOIN productos p ON p.id_producto = a.id_producto
				WHERE p.id_producto = '".$_POST["producto"]."'
					AND a.id_proceso='".$_POST["id_proceso"]."' 
					AND a.id_turno<='".$_POST["tfin"]."' 
					AND a.id_turno>='".$_POST["tini"]."'"; 
		// order by id_turno
	}
	else
	{
		if($_POST["opcion"]=="fecha")
		{
		$sql="SELECT p.nombre_producto nombre,
					a.indicador_1,
					a.indicador_2,
					a.indicador_3,
					a.tiempo_turno,
					a.unidades_conteo
				FROM turnos a
					INNER JOIN productos p ON p.id_producto = a.id_producto
				WHERE p.id_producto = '".$_POST["producto"]."'
					AND a.id_proceso='".$_POST["id_proceso"]."' 
					AND a.fecha_inicio<='".$_POST["fechaf"]."' 
					AND a.fecha_inicio>='".$_POST["fechai"]."'";
		}
	}
//echo $sql;
	$repor->pdf_grafico_oee_producto($sql,$inf);

        ?>

