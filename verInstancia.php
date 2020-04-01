<head>
   <script language="JavaScript" type="text/javascript" src="js/datos.js"></script>
   <script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>
   <link rel="stylesheet" href="css/estilo.css" type="text/css">
</head>

<hr>
<center>
<button  class='boton' onclick="
   document.getElementById('divInstancia').style.display = 'none';
   document.getElementById('divResultados').style.display = 'block';"> Volver a instancias </button>
</center>
<hr>
<?php

   include_once('funcionesBD/funcionesBD.php');
   include_once('config/conexion.php');

   // $c = conectar('mysql', $host, $base, $usuario, $password );

   $instanceid = $_POST['instanceid'];


   $sql  = "Select c.cuenta, r.region, "; 
   $sql .= "(select valor from tags where instanceid = i.instanceid and tag = 'Name') as name, i.* ";
   $sql .= "from instancias i inner join cuentas c on c.codcue = i.codcue ";
   $sql .= "inner join regiones r on r.codreg = i.codreg where instanceid = '$instanceid';";
   
   $result = ejecutarSQL($c, $sql);
   $nc     = numeroCampos($result);

   $fila = fetch($result);

   $cuenta = $fila['cuenta'];
   $nombre = $fila['name'];

   echo "<table class='tablaform' style=' margin-left:auto; margin-right:auto;'>";

   echo "<tr style='font-size:20px; height: 40px;'>";
   echo "<th colspan='11'>Cuenta <strong><b> $cuenta </b> </strong>  Instancia $nombre </th>";
   echo "</tr>";

   for ($i = 0 ; $i < $nc; $i++) {

   	   echo "<tr><th>". nombreCampo($result, $i) . "</th><td>" . $fila[$i] . "</td></tr>";
   }

   echo "</table>";
 
?>
<hr>
