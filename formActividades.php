<?php

require 'clases/configuracion_aplicacion.php';
$logicaTurno = new configuracion_aplicacion();
$datos = new configuracion_aplicacion();


if($_POST["estado"]){
  $codigoRegistro = $_POST["codigo_registro"];
  $usuario = $_POST["usuario"];
  $orden = $_POST["codigoOrden"];
  $maquina = $_POST["maquina"];
  $sub_sistema=$_POST["sub_sistema"];
  $pieza = $_POST["pieza"];



  $fecha_programada= $datos->traerFechaOrdenTrabajo($orden);

  $datos->actualizar_fechaFin_orden($orden);


  $ordenCerrar = $datos->traer_orden_registro($_POST["codigo_registro"]);
    if($_GET["elOrigen"]){
      $datos->cerrarTurnoManufactura($ordenCerrar);
    }
  $sistemas = $logicaTurno->listar_sistemas();
  $i=0;
  while(!$sistemas->EOF){

    $info = $sistemas->fields["nombre_sistema"];

    $actividades = $logicaTurno->listar_actividades_cerrar2($info);
    while (!$actividades->EOF) {
      // code...

  $sistemaPOST =$sistemas->fields["id_sistema"];
  $enviarPOST =$actividades->fields["nombre_actividad"];
      if($_POST[$enviarPOST]){
        // echo $orden = $_POST["codigoOrden"]." -orden";
        // echo $maquina = $_POST["maquina"]." -maquina";
        // echo $sistemas->fields["id_sistema"]." - sistema";
        // echo $sub_sistema=$_POST["sub_sistema"]." -subs";
        // echo $pieza = $_POST["pieza"]." -pieza";
        // echo $_POST[$enviarPOST]."- activi";
        // echo $usuario = $_POST["usuario"]." -usuarios";

        $sql = "INSERT INTO registros(id_maquina,id_sistema,id_sub_sistema,id_pieza,id_actividad,fecha_programada,id_orden_trabajo,id_usuario)values($maquina,$sistemaPOST,$sub_sistema,$pieza,$_POST[$enviarPOST],'$fecha_programada',$orden,$usuario)";



          	$logicaTurno->actualizar_fechaFin_orden($orden);
             $logicaTurno->registro_mantenimiento_automatico23($sql);


      }else{

      }



      $actividades->MoveNext();
    }
    $i++;
    $sistemas->MoveNext();
  }


  $datos->cerrar_mantenimiento2($orden,$codigo_registro);


  echo "La orden de Trabajo ".$codigoRegistro." se ha cerrado exitosamente";

    echo "<meta http-equiv=\"refresh\" content=\"2;URL=principal.php?seccion=ordenes_trabajo\">";
    //echo "<meta http-equiv=\"refresh\" content=\"1;URL=registros.php?codigo_orden=".$orden."\">";



}






 ?>
