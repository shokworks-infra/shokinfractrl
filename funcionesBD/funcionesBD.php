<?php
if (!isset($_SESSION)) {
  session_start();
}

//if (function_exists('conectar'))   {

   function conectar($manejador, $host, $base, $user, $pass ) {


     $dsn = "$manejador:host=$host;dbname=$base";


     if (strlen(trim($base)) == 0 ){
      $base = "information_schema";
     }

     try {

        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $c =  new PDO($dsn, $user, $pass, $opciones);
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $c->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);


        return $c;

    } catch (PDOException $e) {
      $_SESSION['errorConexion'] = $e->getMessage();
      echo "<br>host $host usuario $user: " .$e->getMessage() . "<br>";
      return false;
    }

   } // Fin conectar
//}


function ejecutarSQL($c, $sql) {

  try {
      $result = $c->prepare($sql);
      if (!$result) {
        echo "\nPDO::errorInfo():\n";
        print_r($dbh->errorInfo());
        echo "<br>";
      }
      $result->execute();

  } catch (PDOException $e) {
      echo "<br><h1>Fallo la consulta: <br>" . $e->getMessage() . "<br>";
      return false;

  }

  return $result;

} // fin fetch

function fetch($result) {

  return  $result->fetch(PDO::FETCH_BOTH);
}

function numeroCampos($result) {

   return $result->columnCount();

} // fin numeroCampos

function numeroFilas($result) {

   return  $result->rowCount();

} // fin numeroFilas

function nombreCampo($result, $posicion) {
    $meta = $result->getColumnMeta($posicion);

    return $meta['name'];
} // nombreCampo

function nombresCampos($result) {
  /* Devuelve un array con los nombres de campo existentes
     en un array devuelto por  $result->fetch(PDO::FETCH_BOTH)
  */
  $totalRegistros = $result->rowCount();
  if ($totalRegistros == 0) {
     return false;
  }

  $numCampos = $result->columnCount();
  $aCampos = "";

  for ($i = 0; $i < $numCampos; $i++) {
    $meta = $result->getColumnMeta($i);
    $campo[] = $meta['name'];
  }
  return $campo;
} // fin nombresCampos


/*
    CONTROLADOR
*/

function existeControlador($controlador) {
  /*
     comprueba si esta instalado en el equipo el controlador PDO
  */
  $aControladores = (PDO::getAvailableDrivers());

  if (in_array($controlador, $aControladores)) {

    return true;
  }
  return false;
}

?>
