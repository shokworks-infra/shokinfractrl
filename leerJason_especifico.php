<?php

error_reporting(E_ALL);
include_once 'funcionesBD/funcionesBD.php';
include_once 'funcionesBD/funciones.php';
include_once 'config/conexion.php';

// Variables.

$instanceId = "";
$nivel = 0;

$camposInstancia = ""; 
$valoresInstancia = "";
$camposTags = "";
$valoresTags = "";
$camposCpu = "";
$valoresCpu = "";

$sql_cpuOptions = "";

////////////////////////////////////////////// Programa Principal  //////////////////////////////////////////////

// Modificar para conectar a la base correcta

if (!$c ) {
  echo "No se conecto";
  exit;
}


$codcue    = 4;
$cuenta    = 'kinesis-group';
$codreg    = 9 ;
$region    = 'ap-southeast-1';
$archTemp  = 'kinesis-group_ap-southeast-1_2019-12-30.rpt';   

$json       = file_get_contents($archTemp);
$data       = json_decode($json, true);
$instancias = $data['Reservations'];

$contador = 1;

echo "\n";

for ($i = 0 ; $i < count($instancias); $i++) {
 
 $seccion     = $instancias[$i]['Instances'][0];
 $instanceId  = $seccion['InstanceId']; 
 $campos      = "";
 $valores     = ""; 

 foreach ($seccion as $key => $value) {
   
   if (!is_array($value)) {
     // Aqui se procesan los datos iniciales de la instancia
     $key      = strtolower($key);

     if ($key != "instanceid") {
        $value    = ajustarCampo($key, $value) ;
        $campos  .= $key . ", ";
        $valores .= "'$value', ";  
     }

   }
      
 }

 $campos   = quitarComaFinal($campos);
 $valores  = quitarComaFinal($valores);
 $sqlIns   =  "REPLACE INTO instancias (codcue, codreg, instanceid,  $campos) values ( '$codcue', '$codreg', '$instanceId', $valores); "; 

 if ($instanceId == "i-0fa772ce05bd95e6c" || $instanceId == "i-07ecf72cf13ecabc1" ) {
 	 echo $sqlIns . "\n";
 } else {
 	echo "$instanceId \n";
 }
 
 //  Procesar los datos quq vienen en arrays.
 $resultInst = ejecutarSQL($c, $sqlIns); 
 $resultTags = ejecutarSql($c, Tags($seccion['Tags'])); 

} // for ($i = 0 ; $i < count($instancias); $i++) 

?>
