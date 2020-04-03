<?php

function ajustarCampo($campo, $valor) {
global $cuenta, $region, $tipoDato, $tamano, $campos, $linea;

$tamano   = 50;
$tipoDato = " varchar($tamano)";

   // $campo = str_replace("-", "_", $campo);
   // $campo = str_replace(":", "_", $campo);

   $campo  = strtolower($campo); 

   switch ($campo) {

    case "iniciocuenta":
        $campo  = "cuenta";
        $cuenta = $valor;
        break;
    case "inicioregion":
        $campo = "region";
        $region = $valor; 
        break;
    case 'attachtime'; 
    case 'launchtime';
    case 'latestrestorabletime';
    case 'instancecreatetime': 
        $valor = str_replace("T", " ", $valor);
        $valor = str_replace("Z", "", $valor);
        $valor = str_replace("t", " ", $valor);
        $valor = str_replace("z", "", $valor);
        $tipoDato = " datetime ";
        break;        
    case 'ebsoptimized';
    case 'multiaz':
        if (strlen(trim($valor)) == 0 ) {
        	$valor = 0;
        } else {
        	$valor = 1; 
	}
	break;
  }

  # Hay valores boolean que vienen como string
  if ( strtolower($valor)  ==  "true") {
     $valor = "1";
  } elseif ( strtolower($valor) == "false" ) {
     $valor = "0";
  }

  return $valor;
} // Fin ajustarCampo

///////////////////////////////////////////////////////////////////////////////
function quitarComaFinal ($string) {

	return substr($string, 0, strlen($string) - 2);
}


///////////////////////////////////////////////////////////////////////////////
function Tags($aDatos) {
global $c, $instanceId, $camposTags, $valoresTags, $sqlTags;

$sqlTags = ""; 
$valor = "";
   
  foreach ($aDatos as $key => $value) {

    if (is_array($value)) {

      $paso = 0;
      foreach ($value as $key1 => $value1) {

        if ($key1 == "Key" ) {
           $paso ++; 
           $tag = $value1; 
        } elseif ($key1 == "Value") {
           $paso++; 
           $valor = $value1;
        }
      }

      $sqlTags = "Replace into tags (instanceId, tag, valor) values ('$instanceId', '$tag', '$valor'); "; 
      $res = ejecutarSql($c, $sqlTags);
            
    } 

  }

  return $sqlTags; 

}  // Fin function Tags

?>
