<?php

include_once 'funcionesBD/funcionesBD.php';
include_once 'config/conexion.php';


$codcue = $_POST['codcue'];

if ($codcue != 1000 ) {
   $sql  = "select r.codreg, r.region, r.descripcion, count(*) cant ";
   $sql .= "from regiones r ";
   $sql .= "inner join instancias i on i.codreg = r.codreg and i.codcue = $codcue ";
   $sql .= "group by r.codreg, r.region, r.descripcion ";
   $sql .= "order by r.region;";
} else {

   $sql  = "select r.codreg, r.region, r.descripcion, count(*) cant ";
   $sql .= "from regiones r ";
   $sql .= "inner join instancias i on i.codreg = r.codreg ";
   $sql .= "group by r.codreg, r.region, r.descripcion ";
   $sql .= "order by r.region;";	

}

$result = ejecutarSQL($c, $sql); 

echo '<select id="cboRegiones" name="cboRegiones" style="width: 350px;">';

echo  '<option value="1000">Todas</option>';

while ($fila = fetch($result)) {
	$mostrar = $fila['region'] . " [ " . $fila['descripcion'] . " ] ( " . $fila['cant'] . " )";

	echo '<option value="' . $fila['codreg'] . '">' . $mostrar . '</option>';	
}

echo "</select>";



?>
