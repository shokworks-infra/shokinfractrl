<script type="text/javascript" >
function seleccionarRegiones(c){
   // cambia el SELECT anio al valor del SELECT nombre
    //alert('Seleccionada la cuenta: ' + document.getElementById('cboCuentas').value);
    var codcue        = getCheckedSelectId('cboCuentas');
    parametros = "codcue="+codcue;

    mostrarPagina('comboRegiones.php', 'divRegiones', parametros);
}
</script>
<?php

include_once 'funcionesBD/funcionesBD.php';
include_once 'config/conexion.php';

$sql = "select codcue, cuenta from cuentas order by cuenta;";

$result = ejecutarSQL($c, $sql); 


echo '<select id="cboCuentas" name="cboCuentas" onchange="javascript:seleccionarRegiones(this.value);" style="width: 350px;" >';

echo  '<option value="2000">Seleccione</option>';
echo  '<option value="1000">Todas</option>';

while ($fila = fetch($result)) {
	echo '<option value="' . $fila['codcue'] . '">' .$fila['cuenta'] . '</option>';	
}

echo "</select>";



?>
