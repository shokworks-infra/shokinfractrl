<?php

error_reporting(E_ALL);
include_once 'funcionesBD/funcionesBD.php';
include_once 'config/conexion.php';
include_once 'funcionesBD/funciones.php';

function Monitoring () {

}

function ProductCodes() {

}

function CpuOptions($aDatos) {
global $c, $instanceId, $camposCpu, $valoresCpu, $sql_cpuOptions;

  echo "<h1>EN CpuOptions</h1>";

  foreach ($aDatos as $key => $value) {

    if (is_array($value)) {
      echo "<br>value es un array <br>";

      $paso = 0;
      foreach ($value as $key1 => $value1) {

        echo "<br>En el foreach<br>";

        if ($key1 == "Value" ) {
           $paso = 1;
           $valor = $value1;
        } else {
           $paso = 0;
           $cpu = $value1;
           $sql_cpuOptions = "Replace into Cpus (instanceId, tag, valor) values ('$instanceId', '$cpu', '$valor'); ";
        }

      }

    } else {
       $paso = 3;
       $camposCpu .= $key . ", ";
       $valoresCpu .= "'" . $value . "', ";
    }


  }

  echo "<h2>Saliendo con paso = $paso </h2><br>";
  if ($paso == 3 ) {
     $camposCpu  = strtolower(substr($camposCpu, 0, strlen($camposCpu) - 2));
     $valoresCpu = substr($valoresCpu, 0, strlen($valoresCpu) - 2);
     $sql_cpuOptions = "Replace into cpus (instanceId, $camposCpu) values ('$instanceId', $valoresCpu); ";
     //echo $sql_cpuOptions ."<br>";
  }

} // Fin CpuOptions

function SecurityGroups() {

}
function NetworkInterfaces() {

}
function Placement() {

}
function BlockDeviceMappings() {

}
function IamInstanceProfile() {

}


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
echo "Conectado\n";

// Eliminar las Tags de las instancia, porque cuando son eliminandas o cambiadas de region no se actualiza la base.
$sqlDel      = "Delete from tags;";
$resultDel   = ejecutarSQL($c, $sqlDel);
// Eliminar las instancias, porque cuando son eliminandas o cambiadas de region no se actualiza la base.
$sqlDel     = "Delete from instancias;";
$resultDel   = ejecutarSQL($c, $sqlDel);

// Leer las cuentas registradas.
//$sql    = "select * from cuentas where codcue = 7;";
$sql         = "select * from cuentas where activa = 1;";
$result      = ejecutarSQL($c, $sql);
$fecha       = date("Y-m-d");

//echo "Antes del while <br>";
echo "$sql <hr>";

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
      $archTemp = "${cuenta}_${region}_${fecha}.rpt";
      //$archTemp = "kinesis-dns_eu-west-1_2020-01-31.rpt";
      $comando  = "aws ec2 describe-instances --profile $cuenta --region $region  > $archTemp";
      echo $comando;
      echo "<br>";

      system($comando);

      if (count(file($archTemp)) < 4 ) {
        unlink($archTemp);
        continue;
      }

      $json = file_get_contents($archTemp);
      $data = json_decode($json, true);
      $instancias = $data['Reservations'];

      for ($i = 0 ; $i < count($instancias); $i++) {
          echo "<br>Entrando<br>";
          $seccion     = $instancias[$i]['Instances'][0];
          //var_dump($seccion['State']);
          $instanceId  = $seccion['InstanceId'];

          $contador    = 1;
          $campos      = "";
          $valores     = "";
          // Estado de la instancia running, stop, etc.
          $codsta = $seccion['State']['Code'];
          $state  = $seccion['State']['Name'];

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

          $sqlIns  = "REPLACE INTO instancias (codcue, codreg, instanceid, codsta, state,  $campos) ";
          $sqlIns .= "values ( '$codcue', '$codreg', '$instanceId', '$codsta', '$state', $valores); ";


          $resultInst = ejecutarSQL($c, $sqlIns);
          if (!$resultInst) {
            echo "<br><h2>";
            echo $sqlIns ;
            echo "</h2><br>";
            exit;
          }

          //  Procesar los datos quq vienen en arrays.
          $resultTags  = ejecutarSql($c, Tags($seccion['Tags']));

      } // for ($i = 0 ; $i < count($instancias); $i++)

  } // while ($region = fetch($resultReg))

} // while ($cuenta = )

?>
