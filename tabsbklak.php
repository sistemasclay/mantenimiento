<?php
include("clases/configuracion_aplicacion.php");
$datos = new configuracion_aplicacion();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- saved from url=(0032)http://www.ebideas.com/ -->
<html>
<title>M&C ETIQUETAS</title>

<head>
<link rel="stylesheet" type="text/css" href="tab/tabcontent.css"  />
<script type="text/javascript" src="tab/tabcontent.js"></script>
<script type="text/javascript" src="js/form-submit.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
</head>

<body>
<!-- main table start -->


<table border=0 cellPadding=5 cellSpacing=0 width="100%">
<tbody>
 <tr>
  <td valign=top>

	        <table border="0" align="left">
            <tr>
             <td valign="top">

              <ul id="countrytabs" class="shadetabs" style="list-style:none">
               <li><a href="#" rel="country1" class="selected">Generales</a></li>
               <li><a href="#" rel="country2">Batch</a></li>
               <li><a href="#" rel="country3">Productos</a></li>
               <li><a href="#" rel="country4">Paradas</a></li>
               <li><a href="#" rel="country5">Reportes</a></li>

              </ul>            </td>
            <td valign="top">
                <!--div principal-->
            <div style="border:3px solid gray; width:600px; height:500px; margin-bottom:auto; padding: 10px">



              <div id="country1" class="tabcontent" style="height:500px;">

 <div id="Ingreso_Planta" style="height:250px;" >
<form id="equipos" name="equipos" method="post" action="formsubmit_config.php">
 <input type="hidden" name="seccion" id="seccion" value="plantas">
 <br>
 <br>
  <table width="200" border="1" align="center">

  <tr>
  <td>
  Codigo:
  </td>
  <td>
      <input type="text" name="codigo_planta" id="codigo_planta" value="<?php  echo $datos_planta->fields["id_planta"]  ?>" />
  </td>
  </tr>

  <tr>
  <td>
  Nombre:
  </td>
  <td>
  <input type="text" name="nombre_planta" id="nombre_planta" value="<?php  echo $datos_planta->fields["nombre"] ?>" />
  </td>
  </tr>

    <tr>
  <td>
  Descripcion:
  </td>
  <td>
<input type="text" name="nombre_planta" id="nombre_planta" value="<?php  echo $datos_planta->fields["nombre"] ?>" />
  </td>
  </tr>

      <tr>
  <td>
  Activo:
  </td>
  <td>
     <input type="text" name="nombre_planta" id="nombre_planta" value="<?php  echo $datos_planta->fields["nombre"] ?>" />
  </td>
  </tr>

  <tr>
  <td colspan="2">
   <input type="submit" name="Submit" value="Guardar" />
  </td>
  </tr>
</table>
</form>
 </div>

              </div>


               <!-- imagenes -->

               <div id="country2" class="tabcontent" style="height:500px;">

 <div id="lista_procesos" style="overflow:scroll; height:250px; overflow-x:hidden;" >


<p align="center" class="style1">Procesos</p>


<table border=1 align=center>
<tr>
  <td ><b>Codigo</b></td>
  <td > <b> nombre </b></td>
  <td > <b> Descripcion </b></td>
  <td > <b> Estado </b></td>
  <td > <b> Editar</b></td>
  <td > <b> Eliminar</b></td>
</tr>

</table>
</div>
 <div id="procesos" style="height:250px; overflow:scroll; overflow-x:hidden;" >
<form id="procesos"  method="post" action="formsubmit_config.php">
 <input type="hidden" name="seccion" id="seccion" value="procesos">
  <br>
 <br>
  <table width="200" border="1" align="center">
  <tr>
  <td>
 Codigo
  </td>
  <td>
  <input type="text" name="codigo_proceso" id="codigo_proceso" value="<?php  echo $datos_proceso->fields["id_proceso"]  ?>"  />
  </td>
  </tr>

    <tr>
  <td>
 Nombre
  </td>
  <td>
  <input type="text" name="nombre_proceso" id="nombre_proceso" value="<?php  echo $datos_proceso->fields["nombre"]  ?>"/>
  </td>
  </tr>

   <tr>
  <td>
 Descripcion
  </td>
  <td>
      <TEXTAREA COLS=20 ROWS=2 name="descripcion_proceso">
<?php  echo $datos_proceso->fields["descripcion"]  ?>
      </TEXTAREA>
  </td>
  </tr>

     <tr>
  <td>
 Direccion IP
  </td>
  <td>
  <input type="text" name="ip_maquina" id="ip_maquina" value="<?php  echo $datos_proceso->fields["ip_maquina"]  ?>" />
  </td>
  </tr>

   <tr>
  <td>
 Max Horas
  </td>
  <td>
  <input type="text" name="max_horas" id="max_horas" value="<?php  echo $datos_proceso->fields["maximo_horas"]  ?>" />
  </td>
  </tr>

     <tr>
  <td>
 Planta
  </td>
  <td>
 <select name="planta_maquina" id="planta_maquina">
<?php
    if($_GET["codigo_proceso"])
    {
         $datos_planta = $datos->detalle_planta($datos_proceso->fields["id_planta"]);
        echo "<option value=\" ".$datos_planta->fields['id_planta']." \">".$datos_planta->fields['nombre']."</option>";
    }
$recordSet=$datos->listar_plantas();
while (!$recordSet->EOF) {
echo "<option value=\" ".$recordSet->fields['id_planta']." \">".$recordSet->fields['nombre']."</option>";
$recordSet->MoveNext();
}
?>
</select>
  </td>
  </tr>

       <tr>
  <td>
 Captura
  </td>
  <td>
<select name="captura" id="captura">
<?php
        if($_GET["codigo_proceso"])
    {
                if($datos_proceso->fields["captura"]==1)
                 {
                 echo "<option value=\"1\">Automatica</option>";
                 }
                 else
                 {
                   echo "<option value=\"0\">Manual</option>";
                 }
    }
?>
<option value="1">Automatica</option>
<option value="0">Manual</option>
</select>
  </td>
  </tr>

        <tr>
  <td>
  Activo:
  </td>
  <td>
      <input type="Checkbox" name="estado_proceso" id="estado_proceso" <?php  if ($datos_proceso->fields["estado"]=="1") echo "checked" ?>  />
  </td>
  </tr>

  <tr>
  <td colspan="2">
   <input type="submit" name="Submit" value="Guardar" />
  </td>
  </tr>
</table>
</form>
</div>
			                  </div>




              <!-- Planos -->

               <div id="country3" class="tabcontent" style="height:500px;">
			   <div id="lista_departamentos" style="overflow:scroll; height:250px;" >

<p align="center" class="style1">Productos</p>


<table border=1 align=center>
<tr>
  <td ><b>Codigo</b></td>
  <td > <b> nombre </b></td>
  <td > <b> Descripcion </b></td>
  <td > <b> Estado </b></td>
  <td > <b> Editar</b></td>
  <td > <b> Eliminar</b></td>
  <td > <b> Datos Asociados</b></td>
    <td > <b> Receta</b></td>
</tr>
</table>
</div>
 <div id="ingreso_productos"  style="height:250px; overflow:scroll; overflow-x:hidden;">
            <form id="area" name="area" method="post" action="formsubmit_config.php">
			 <input type="hidden" name="seccion" id="seccion" value="productos">
			 <br>
			  <br>
  <table width="200" border="1" align="center">
      <tr>
  <td>
 Codigo
  </td>
  <td>
  <input type="text" name="codigo_producto" id="codigo_producto" value="<?php  echo $datos_producto->fields["id_producto"] ?>" />
  </td>
  </tr>
        <tr>
  <td>
 Nombre
  </td>
  <td>
  <input type="text" name="nombre_producto" id="nombre_producto" value="<?php  echo $datos_producto->fields["nombre_producto"] ?>" />
  </td>
  </tr>
     <tr>
  <td>
 Descripcion
  </td>
  <td>
      <TEXTAREA COLS=20 ROWS=2 name="descripcion_producto">
<?php  echo $datos_producto->fields["descripcion"] ?>
      </TEXTAREA>
  </td>
  </tr>
          <tr>
  <td>
Dato Extra 1
  </td>
  <td>
  <input type="text" name="dato_extra1" id="dato_extra1" value="<?php  echo $datos_producto->fields["dato_extra1"] ?>"/>
  </td>
  </tr>
          <tr>
  <td>
 Dato Extra 2
  </td>
  <td>
  <input type="text" name="dato_extra2" id="dato_extra2" value="<?php  echo $datos_producto->fields["dato_extra2"] ?>" />
  </td>
  </tr>
          <tr>
  <td>
 Dato Extra 3
  </td>
  <td>
  <input type="text" name="dato_extra3" id="dato_extra3" value="<?php  echo $datos_producto->fields["dato_extra3"] ?>" />
  </td>
  </tr>
  <tr>
  <td>
  Activo:
  </td>
  <td>
  <input type="Checkbox" name="estado_producto" id="estado_producto" <?php  if ($datos_producto->fields["estado"]=="1") echo "checked" ?>  />
  </td>
  </tr>
  <tr>
  <td colspan="2">
   <input type="submit" name="Submit" value="Enviar" />
  </td>
  </tr>
  </table>
</form>
</div>
               </div>


<div id="country4" class="tabcontent" style="height:500px;">
<div id="lista_personal" style="overflow:scroll; height:250px; overflow-x:hidden;" >

<p align="center" class="style1">Personal</p>

<table border=1 align=center>
<tr>
  <td ><b>Codigo</b></td>
  <td > <b> nombre </b></td>
  <td > <b> Cargo </b></td>
  <td > <b> Nivel </b></td>
  <td > <b> Estado </b></td>
  <td > <b> Editar</b></td>
  <td > <b> Eliminar</b></td>
</tr>

</table>
</div>
 <div id="ingreso_personal" style="height:250px; overflow:scroll; overflow-x:hidden;" >
            <form id="fpersonal" name="fpersonal" method="post" action="formsubmit_config.php">
			 <input type="hidden" name="seccion" id="seccion" value="personal">
			 <br>
			  <br>
  <table width="200" border="1" align="center">
      <tr>
  <td>
 Codigo
  </td>
  <td>
  <input type="text" name="codigo_empleado" id="codigo_empleado" value ="<?php  echo $datos_empleado->fields["id_persona"];   ?>"/>
  </td>
  </tr>

  <tr>
  <td>
 Nombre
  </td>
  <td>
  <input type="text" name="nombre_empleado" id="nombre_empleado" value ="<?php  echo $datos_empleado->fields["nombre_persona"];?>" />
  </td>
  </tr>

  <tr>
  <td>
 Cargo
  </td>
  <td>
  <input type="text" name="cargo" id="cargo" value ="<?php  echo $datos_empleado->fields["cargo"];   ?>"/>
  </td>
  </tr>

   <tr>
  <td>
 Contrasena
  </td>
  <td>
  <input type="text" name="contra_empleado" id="contra_empleado" value ="<?php  echo $datos_empleado->fields["clave"];   ?>" />
  </td>
  </tr>

  <tr>
      <td>Nivel</td>
      <td>
          <select name="nivel_empleado" id="nivel_empleado">
<?php
        if($_GET["codigo_empleado"])
    {
        echo "<option value=\" ".$datos_empleado->fields["nivel"]." \">".$datos_empleado->fields["nivel"]."</option>";
    }
?>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
</select>
      </td>
  </tr>
        <tr>
  <td>
  Activo:
  </td>
  <td>
      <input type="Checkbox" name="estado_empleado" id="estado_empleado" <?php  if ($datos_empleado->fields["estado_persona"]=="1") echo "checked" ?>  />
  </td>
  </tr>
  <tr>
  <td colspan="2">
   <input type="submit" name="Submit" value="Enviar" />
  </td>
  </tr>
  </table>
</form>
</div>
               </div>
<div id="country5" class="tabcontent" style="overflow:scroll; height:500px; overflow-x:hidden; overflow-y:hidden;">
<div id="lista_paradas" style="overflow:scroll; height:250px;" >
<p align="center" class="style1">Paradas</p>
<table border=1 align=center>
<tr>

  <td align=center><b>Codigo</b></td>
  <td align=center><b>Nombre</b></td>
  <td align=center><b>Tipo</b></td>
  <td align=center><b>Grupo</b></td>
  <td align=center><b>Tiempo</b></td>
  <td align=center><b>Unidad</b></td>
   <td align=center><b>Estado</b></td>
    <td align=center><b>Editat</b></td>
     <td align=center><b>Borrar</b></td>
</tr>
</table>
</div>
 <div id="ingreso_instituciones" style="height:250px; overflow:scroll; overflow-x:hidden;" >
<form id="paradas" name="paradas" method="post" action="formsubmit_config.php" enctype="multipart/form-data">
<input type="hidden" name="seccion" id="seccion" value="paradas">
<!--	<input type="hidden" name="id_institucion" id="id_institucion" value=""> -->
<br>
<table width="200" border="1" align="center">
<tr>
<td>
codigo
</td>
<td>
    <input type="text" name="codigo_parada" id="codigo_parada" value="<?php  echo $datos_parada->fields['id_parada'] ?>" />
</td>
</tr>

<tr>
<td>
Nombre
</td>
<td>
<input type="text" name="nombre_parada" id="nombre_parada" value="<?php  echo $datos_parada->fields['nombre_parada'] ?>" />
</td>
</tr>

<tr>
<td>
Unidad Tiempo
</td>
<td>
<select name="unidad_tiempo" id="unidad_tiempo">
<?php
        if($_GET["codigo_parada"])
    {
        echo "<option value=\" ".$datos_parada->fields["unidad_tiempo"]." \">".$datos_parada->fields["unidad_tiempo"]."</option>";
    }
?>
<option value="1">Segundos</option>
<option value="2">Minutos</option>
<option value="3">Horas</option>
</select>
</td>
</tr>

<tr>
<td>
Tipo Parada
</td>
<td>
          <select name="tipo_parada" id="tipo_parada">
<?php
        if($_GET["codigo_parada"])
    {
        echo "<option value=\" ".$datos_parada->fields["tipo_parada"]." \">".$datos_parada->fields["tipo_parada"]."</option>";
    }
?>
<option value="1">Programada</option>
<option value="2">No Programada</option>

</select>
</td>
</tr>

<tr>
<td>
Grupo
</td>
<td><select name="grupo_parada" id="grupo_parada">
<?php
        if($_GET["codigo_parada"])
    {
        echo "<option value=\" ".$datos_parada->fields["grupo_parada"]." \">".$datos_parada->fields["grupo_parada"]."</option>";
    }
?>
<option value="1">Averias</option>
<option value="2">Cuadre y ajuste</option>
<option value="3">Peque&ntilde;as Paradas</option>
</select>
</td>
</tr>

           <tr>
                <td>Tiempo Estimado:</td>
                <td><input type="text" name="tiempo_parada" id="tiempo_parada" value="<?php  echo $datos_parada->fields['tiempo_programado'] ?>" /></td>
            </tr>
        <tr>
  <td>
  Activo:
  </td>
  <td>
      <input type="Checkbox" name="estado_parada" id="estado_parada" <?php  if ($datos_parada->fields["estado_parada"]=="1") echo "checked" ?>  />
  </td>
  </tr>

<tr>
<td>
<input type="submit" name="Submit" value="Enviar" />
</td>
</tr>
</table>
</form>
</div>
               </div>

            <!-- fin div principal-->
           </div>


            </td>
            </tr>
           </table>



           	<script type="text/javascript">
           	var countries=new ddtabcontent("countrytabs")
           	countries.setpersist(true)
           	countries.setselectedClassTarget("link") //"link" or "linkparent"
           	countries.init()
			</script>

      </td>
	</tr>
   </tbody>
  </table>
  <!-- main table finish -->

</body>
</html>
