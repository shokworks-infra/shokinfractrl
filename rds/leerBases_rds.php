<?php 
include_once '../funcionesBD/funcionesBD.php';
//include_once '../config/conexion.php';

echo "Preparando...<br>";

$host[] = "islagency.ctcnpt9ust9r.us-east-1.rds.amazonaws.com";
$user = 'islagencyadmin';
$pass = 'F*t.deff234as23fg';


for ($i = 0 ; $i < count($host); $i++ ) {

	// mysql -h islagency.ctcnpt9ust9r.us-east-1.rds.amazonaws.com -uislagencyadmin -pF*t.deff234as23fg

	$c = conectar('mysql', $host[$i], 'information_schema', $user, $pass);

	if (!$c) {
		echo "No se conecto<br>";
	}


	$excluir = "'information_schema', 'performance_schema', 'mysql' ";

	$sql = "SELECT TABLE_SCHEMA  FROM INFORMATION_SCHEMA.tables group by 1 having  TABLE_SCHEMA NOT IN ( $excluir );";
	echo $sql . "<br>";

	$result = ejecutarSQL($c, $sql);


	$numFilas  =  numeroFilas($result);
	$numCampos =  numeroCampos($result);


	echo "<table border='1'>";	
	while ($fila = fetch($result) ) {

		echo "<tr><td>" .$fila['TABLE_SCHEMA'] . "</td></tr>";

	}

	echo "</table>";

}

?>

