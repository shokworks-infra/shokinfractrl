<?php
if (!isset($_SESSION)) {
  session_start();
}
   // procesar y mostrar datos
   include_once('funcionesBD/funcionesBD.php');
   include_once('config/conexion.php');
   include_once('datosPreparaSql.php');

      // NOmbre de cuenta.
   if ($codcue != 1000) {
      $sql1   = "select cuenta from cuentas where codcue = $codcue; ";
      $result = ejecutarSql($c, $sql1);
      $fila   = fetch($result);
      $cuenta = $fila['cuenta'];
   } else {
      $cuenta = "<b><strong>Todas: </strong><b>";
   }

   //echo "<hr>$sql<hr>";
   // exit;
   // Buscar los datos para la cuenta
   $result = ejecutarSql($c, $sql);
   $n      = numeroCampos($result);
   $nr     = numeroFilas($result);

   //echo "<hr>Devueltas $nr filas con $n campos cada una <hr>";

   //echo "<font size=10>";
   echo "<table class='lista' align='center'>";
   echo "<tr style='font-size:20px; height: 40px;'>";
   echo "<th colspan='" . ($n + 2) . "'>Servidor $host - Base $base</th>";
   echo "</tr>";

   echo "<tr style='font-size:20px; height: 40px;'>";
   // Se suman 2 a $n por el contador de instancias y la columna accion
   echo "<th colspan='" . ($n + 2) . "'>Cuenta <strong><b> $cuenta </b> </strong>  $nr Instancias EC2 </th>";
   echo "</tr>";

   $j         = 0;
   $desRegion = ''; // Descripcion de la region

   echo "<tr style='font-size:15px; height: 30px;'>";
   echo "<th>N°</th>";
   for ($i=0; $i<$n; $i++) {
       $titCol = strtoupper(nombreCampo($result, $i));

      if ($titCol == 'DESCRIPCION') {
        continue;
      }

       $style="";
       if ($titCol == "CUENTA" || $titCol == 'REGION') {
           $style = "style='width: 130px;'";
       }
       echo "<th $style >$titCol</th>";
   }
   echo "<th>Acción</th></tr>";

   $contador=1;

   while ($fila = fetch($result) ) {

        $desRegion  = $fila['descripcion']; // Descripcion de la region
        $instanceid = $fila['instanceid'];
        $estado     = $fila['state'];
        $codsta     = $fila['codsta'];

        $fontColor    = "";
        $finFontColor = "";

        if ($codsta == 80 ) {
           $fontColor = "<font color='red'>";
           $finFontColor = "</font>";
        }

        $j++;
        $color=($j%2)? "odd":"even";
        echo "<tr class=\"$color\">";
        echo "<td>" . $contador++ . "</td>";
        for ($i=0; $i<$n; $i++) {
               $align=" align='left' ";
               if (nombreCampo($result, $i) == "descripcion") {
                  continue;
               }
               if (nombreCampo($result, $i) == "region") {
                 echo "<td $align title = 'Región: " . $fila['descripcion']  . "' >$fontColor" . $fila[$i] .  "$finFontColor</td>" ;
               } else {
                 echo "<td $align title = '" . nombreCampo($result, $i) . "' >$fontColor" . $fila[$i] . "$finFontColor</td>" ;
               }
        }  // for ($i=0; $i<$n; $i++)


        ?>

           <td><input type="button" class="boton" value="Ver"
                   onclick="
                   document.getElementById('divResultados').style.display = 'none';
                   mostrarPagina('verInstancia.php','divInstancia','instanceid=<?php echo $instanceid; ?>');
                   " ></td>
        <?php
        echo "</tr>";
   } // while

   echo "</table>";

   echo "</font>";
// else del if principal

?>
