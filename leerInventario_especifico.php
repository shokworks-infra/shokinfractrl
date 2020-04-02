<?php


error_reporting(E_ALL);
include_once 'funcionesBD/funcionesBD.php';
include_once 'funcionesBD/funciones.php';
include_once 'config/conexion.php';


function Monitoring () {

}
function State($aDatos) {

  echo "Seccion: State<br>";
  var_dump($aDatos);
  echo "<br>Fin sección State  ===================<br>";


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

// Modificar para conectar a la base correcta

if (!$c ) {
  echo "No se conecto";
  exit;
}

// Leer las cuentas registradas.
$sql    = "select * from cuentas where codcue = 11;";
$result = ejecutarSQL($c, $sql);
$fecha  = date("Y-m-d");

while ($fila = fetch($result)) {

  $codcue    = $fila['codcue']; 
  $cuenta    = $fila['cuenta'];

  // Leer las regiones 
  $sqlReg    = "select * from regiones";
  $resultReg = ejecutarSQL($c, $sqlReg);

  // echo "Cuenta $cuenta <br>";

  while ($region = fetch($resultReg)) {

      $codreg = $region['codreg'];
      $region = $region['region'];

      echo "$cuenta $region \n";
      $archTemp = "${cuenta}_${region}_${fecha}.rpt";
      //  $archTemp = "aliantpay_us-east-1_2020-01-14.rpt";
      $comando  = "aws ec2 describe-instances --profile $cuenta --region $region  > $archTemp";

      system($comando);
      echo "Procesndo el archivo $archTemp \n";

      if (count(file($archTemp)) < 4 ) {
        unlink($archTemp); 
        continue;
      }

      $json = file_get_contents($archTemp);
      $data = json_decode($json, true);
      $instancias = $data['Reservations'];

      for ($i = 0 ; $i < count($instancias); $i++) {

          $seccion     = $instancias[$i]['Instances'][0];
          $instanceId  = $seccion['InstanceId']; 
          $contador    = 1;
          $campos      = "";
          $valores     = ""; 

          foreach ($seccion as $key => $value) {

            if (!is_array($value)) {
              // Aqui se procesan los datos iniciales de la instancia
              $key      = strtolower($key);
              echo "\nProcesando $key \n";
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
          echo "\n \n $sqlIns \n \n";
          $resultInst = ejecutarSQL($c, $sqlIns); 
          if (!$resultInst) {
             echo "\nError ejecutando $sqlIns\n";
          }

          //  Procesar los datos quq vienen en arrays.
          if (array_search('Tags', $seccion)) {
             $resultTags = ejecutarSql($c, Tags($seccion['Tags'])); 
          } else {
             echo "La instancia $instanceId no tiene Tags \n";
          }

      } // for ($i = 0 ; $i < count($instancias); $i++) 

  } // while ($region = fetch($resultReg)) 

} // while ($cuenta = )

?>
