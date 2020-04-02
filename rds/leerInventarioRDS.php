<?php

error_reporting(E_ALL);
include_once '../funcionesBD/funcionesBD.php';
include_once '../config/conexion.php';
include_once '../funcionesBD/funciones.php';

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

if (!$c ) {
  echo "No se conecto";
  exit;
}

// Leer las cuentas registradas.
//$sql    = "select * from cuentas where codcue = 7;";
$sql         = "select * from cuentas order by cuenta;";
$result      = ejecutarSQL($c, $sql);
$fecha       = date("Y-m-d");

//echo "Antes del while <br>";
// echo "$sql <hr>";

$sqlDel    = "Delete from instancias_rds;";
$resultDel = ejecutarSQL($c, $sqlDel);

while ($fila = fetch($result)) {

  $codcue    = $fila['codcue'];
  $cuenta    = $fila['cuenta'];

  // Leer las regiones
  //$sqlReg    = "select * from regiones where  codreg = 12;";
  $sqlReg    = "select * from regiones;";
  $resultReg = ejecutarSQL($c, $sqlReg);

  echo "Cuenta $cuenta <br>";

  while ($region = fetch($resultReg)) {

      // echo "Iniciando..<br>";

      $codreg = $region['codreg'];
      $region = $region['region'];

      echo "$cuenta $region \n";
      $archTemp = "${cuenta}_${region}_RDS_${fecha}.rpt";
      // $archTemp = "shokworks_us-east-1.rpt";
      $comando  = "aws rds describe-db-instances --profile $cuenta --region $region > $archTemp";
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
         exec("chcp 850");
      }
      system($comando);

      if (count(file($archTemp)) < 4 ) {
        unlink($archTemp);
        continue;
      }

      $json = file_get_contents($archTemp);
      $data = json_decode($json, true);
      
      $instancias = $data['DBInstances'];

      for ($i = 0 ; $i < count($instancias); $i++) {

          $instRDS              = $instancias[$i];
          $DBInstanceIdentifier = $instRDS['DBInstanceIdentifier'];
          $endPointAddress      = $instRDS['Endpoint']['Address'];
          $endPointPuerto       = $instRDS['Endpoint']['Port'];
          $endPointHostedZoneId = $instRDS['Endpoint']['HostedZoneId'];
          
          $contador    = 1;
          $campos      = "";
          $valores     = "";
          // Estado de la instancia running, stop, etc.
          foreach ($instRDS as $key => $value) {

            if (!is_array($value)) {
              // Aqui se procesan los datos iniciales de la instancia
              $key      = strtolower($key);
              if ($key != "dbinstanceidentifier") {
                $value    = ajustarCampo($key, $value) ;
                $campos  .= $key . ", ";
                $valores .= "'$value', ";
              }

            }

          }

          $campos   = quitarComaFinal($campos);
          $valores  = quitarComaFinal($valores);
          
          $sqlIns  = "REPLACE INTO instancias_rds (codcue, codreg, dbinstanceidentifier, endpointaddress, endpointpuerto, endpointhostedzoneid, $campos) "; 
          $sqlIns .= "values ( '$codcue', '$codreg', '$DBInstanceIdentifier', '$endPointAddress', '$endPointPuerto', '$endPointHostedZoneId',  $valores); ";
          echo "<br>";
          echo $sqlIns ; 
          echo "<br>";

          $resultInst = ejecutarSQL($c, $sqlIns);
          // exit; 
          //  Procesar los datos quq vienen en arrays.
          // $resultTags  = ejecutarSql($c, Tags($seccion['Tags']));

      } // for ($i = 0 ; $i < count($instancias); $i++)

  } // while ($region = fetch($resultReg))

} // while ($cuenta = )

?>
