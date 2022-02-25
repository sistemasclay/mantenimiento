<?php session_start(); ?>
<?php

        include("clases/configuracion_aplicacion.php");
        $datos = new configuracion_aplicacion();
        //$datos->remplazar_etiqueta($id_formula, $formula);
        if($_POST["id_formula"])
            {
            //echo "Modificar Formula: ".$_POST["id_formula"];
            $formula=$datos->detalle_formula($_POST["id_formula"]);
          //  echo  $formula->fields['formula'];
            }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="estilos/myc_gral.css"/>
  <title>Monitoreo y Control</title>
</head>

<body>
<div id="contenedor">
  <?php require_once('includes\cabecera.php'); ?>
  <div id="contenido">
  	<h1>EDITOR DE FORMULAS</h1>

<div>
            <form class="forma" action="formulas.php" method="post">
				<table class="tforma" align="center">
					<tr>
						<td>
							<select class="lista" name="id_formula" id="id_formula"   >
<?php
$eti_formula=array();
$recordSet=$datos->listar_formulas();
while (!$recordSet->EOF) {
echo "<option class=\"oplista\" value=\" ".$recordSet->fields['id_formula']." \">".$recordSet->fields['etiqueta']."</option>";
$eti_formula[]=$recordSet->fields['etiqueta'];
$recordSet->MoveNext();
}
?>
							</select>
						</td>
						<td> <input type="submit" name="Submit" value="Editar" /></td>
					</tr>
				</table>
				<br/>
				<br/>
<?php
				if($_POST["id_formula"])
				{
					echo  "<H2>".$eti_formula[$formula->fields['id_formula']-1]." = ".$formula->fields['formula']."</H2>";
				}
				else
				{
					echo "<H1>"."Debe seleccionar una formula"."</H1>";
				}
?>
			</form>
        </div>


        <br/>

        <div>
<FORM name="Keypad" method="post" action="formsubmit_config.php"/>
 <input type="hidden" name="codigo_formula" id="codigo_formula" value="<?php  echo $_POST["id_formula"]  ?>"/>
 <input type="hidden" name="seccion" id="seccion" value="formulas"/>
<B>
    EDITOR
    <br/>
<TABLE border=2 width=50 height=60 cellpadding=1 cellspacing=5  >
 <!--   <tr>
    <TD colspan=3 align=middle>
    <input name="ReadOut1" type="Text"  size=50 value="0" width=100%  >
    </TD>
    </tr> -->
<TR>
<TD colspan=3 align=middle>
    <input name="ReadOut" type="Text"  size=90 value="<?php
    $etiqueta=$datos->remplazar_variable($formula->fields['formula']);
    echo $etiqueta;
            ?>" width=100% />
</TD>

<TD>
<input name="btnClear" type="Button" value=" C " onclick="Clear()" />
</TD>
<TD><input name="btnborrarc" type="Button" value=" B " onclick="BorrarCaracter()"  />
</TD>
</TR>
<TR>
<TD>
<input name="btnSeven" type="Button" value=" 7 " onclick="NumPressed(7)"  />
</TD>
<TD>
<input name="btnEight" type="Button" value=" 8 " onclick="NumPressed(8)"  />
</TD>
<TD>
<input name="btnNine" type="Button" value=" 9 " onclick="NumPressed(9)"  />
</TD>

<TD>
<input name="paren1" type="Button" value="(" onclick="NumPressed('(')"  />
</TD>
<TD>
<input name="paren2" type="Button" value=")" onclick="NumPressed(')')"  />
</TD>
</TR>
<TR>
<TD>
<input name="btnFour" type="Button" value=" 4 " onclick="NumPressed(4)"  />
</TD>
<TD>
<input name="btnFive" type="Button" value=" 5 " onclick="NumPressed(5)"  />
</TD>
<TD>
<input name="btnSix" type="Button" value=" 6 " onclick="NumPressed(6)"  />
</TD>

<TD align=middle><input name="btnPlus" type="Button" value=" + " onclick="NumPressed('+')"  />
</TD>
<TD align=middle><input name="btnMinus" type="Button" value=" - " onclick="NumPressed('-')"  />
</TD>
</TR>
<TR>
<TD>
<input name="btnOne" type="Button" value=" 1 " onclick="NumPressed(1)"  >
</TD>
<TD>
<input name="btnTwo" type="Button" value=" 2 " onclick="NumPressed(2)"  >
</TD>
<TD>
<input name="btnThree" type="Button" value=" 3 " onclick="NumPressed(3)"  >

</TD> <TD align=middle><input name="btnMultiply" type="Button" value=" * " onclick="NumPressed('*')"  >
</TD>
<TD align=middle><input name="btnDivide" type="Button" value=" / " onclick="NumPressed('/')"  >
</TD>
</TR>
<TR>
<TD>
<input name="btnZero" type="Button" value=" 0 " onclick="NumPressed(0)"  >
</TD>
<TD>
<input name="btnDecimal" type="Button" value=" . " onclick="NumPressed('.')"  >
</TD>
<TD colspan=3>

</TD>
</TR>

<!-- uno de 6  -->
<?php
$variables = $datos->listar_etiquetas();
?>
<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
</TR>

<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
</TR>

<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')" />
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  />
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  />
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  />
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  />
</TD>
</TR>

<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
</TR>

<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
</TR>

<TR>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta']; $variables->MoveNext(); ?>')"  >
</TD>
<TD>
<input name="uno" type="Button" value="<?php  echo $variables->fields['etiqueta'] ?>" onclick="NumPressed('<?php  echo $variables->fields['etiqueta'];  ?>')"  >
</TD>
<TD>

</TD>
</TR>
<tr>
    <td colspan="5" >
         <div align="center">
           <input type="submit" name="Submit" value="Modificar" />
              </div></td>
</tr>
</TABLE>
</B>
</FORM>
</div>

<script LANGUAGE="JavaScript">
<!-- Este script y muchos mas estan disponibles en -->
<!-- Galeria de javaScript http://www16.brinkster.com/galeriajs -->
<!-- Begin
var FKeyPad = document.Keypad;
var Accum = 0;
var FlagNewNum = false;
var FlagNewNum1 = false;
var PendingOp = "";

function NumPressed (Num) {
if (FlagNewNum) {
FKeyPad.ReadOut.value = Num;
FlagNewNum = false;
}
else {
if (FKeyPad.ReadOut.value == "0")
FKeyPad.ReadOut.value = Num;
else
FKeyPad.ReadOut.value += Num;
}
}

function NumPressed1 (Num) {
if (FlagNewNum1) {
FKeyPad.ReadOut1.value = Num;
FlagNewNum1 = false;
}
else {
if (FKeyPad.ReadOut1.value == "0")
FKeyPad.ReadOut1.value = Num;
else
FKeyPad.ReadOut1.value += Num;
}
}


function ClearEntry () {
FKeyPad.ReadOut.value = "";
FKeyPad.ReadOut1.value = "";
FlagNewNum = true;
FlagNewNum1 = true;
}

function Clear () {
Accum = 0;
PendingOp = "";
ClearEntry();
}

function copiar()
{
FKeyPad.ReadOut1.value=FKeyPad.ReadOut.value;
}

function BorrarCaracter(){
        var letras = 0;
	letras=FKeyPad.ReadOut.value.length;
       // FKeyPad.ReadOut.value += letras;
	//FKeyPad.ReadOut.length > 0;
	FKeyPad.ReadOut.value = left(FKeyPad.ReadOut.value, letras-1)
}

// End -->
</script>


  </div>
  <?php require_once('includes\piep.php'); ?>
</div>




</body>
</html>
