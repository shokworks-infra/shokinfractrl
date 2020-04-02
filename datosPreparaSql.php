<?php

   // preparar sql

   $like = ""; $like1 = ""; $like2 = "";$like3 = ""; $like4 = ""; $like5 = ""; $like6 = "";
   $and = "";
   if (isset($_POST['codcue'])) {

       $codcue = $_POST['codcue'];
       if ($codcue != 1000) {
          $like1  = " codcue =  $codcue ";
          $and = " and ";
       }
   }

   if (isset($_POST['codreg'])) {

       $codreg = $_POST['codreg'];
       if ($codreg != 1000) {
          $like2  = " $and r.codreg =  $codreg ";
          $and = " and ";
       }
   }

   // Filtros
   if (isset($_POST['nombre'])) {

       $nombre = $_POST['nombre'];
       //$like3 = " $and Name like '%" . strtoupper($nombre) . "%' ";
       //$and = " and ";
    }

    if (isset($_POST['instanceid'])) {

      $instanceid = $_POST['instanceid'];
      $like4 = " $and instanceid like '%" . strtoupper($instanceid) . "%' ";
      $and = " and ";
    }

    if (isset($_POST['instancetype'])) {

       $instancetype = $_POST['instancetype'];
       $like5 = " $and instancetype  like '%" . trim($instancetype) . "%' ";
       $and = " and ";
    }

    if (isset($_POST['statusEC2'])) {
      $statusEC2 = $_POST['statusEC2'];
      switch ($statusEC2) {
        case 'corriendo':
          $like6 = " $and i.state = 'running' ";
          break;
        case 'detenidas':
          $like6 = " $and i.state = 'stopped' ";
          break;
        default:
          $like6 = "";
          break;
      }
    }

    $like = $like1 . " " . $like2 . " "  . $like3 . " " . $like4 . " " . $like5 . " " . $like6;

    if ($codcue != 1000 ) {
       $sql  = "select r.region, r.descripcion, i.instanceid, ";
       $sql .= "(select valor from tags t where t.tag = 'Name' and t.instanceid = i.instanceid ) Name, ";
       $sql .= "(select valor from tags t where t.tag = 'env' and t.instanceid = i.instanceid ) Ambiente, ";
       $sql .= "i.instancetype, i.publicipaddress, i.publicdnsname, i.privateipaddress, i.privatednsname, i.vpcid, i.codsta, i.state ";
       $sql .= "from instancias i ";
       $sql .= "inner join regiones r on r.codreg = i.codreg ";
       if (strlen(trim($like)) > 0) {
          $sql .= "where $like ";
       }
       $sql .= "order by r.region, i.state, Name;";

    } else {
       $sql  = "select c.cuenta, r.region, r.descripcion, i.instanceid, ";
       $sql .= "(select valor from tags t where t.tag = 'Name' and t.instanceid = i.instanceid ) Name, ";
       $sql .= "(select valor from tags t where t.tag = 'env' and t.instanceid = i.instanceid ) Ambiente, ";
       $sql .= "i.instancetype, i.publicipaddress, i.publicdnsname, i.privateipaddress, i.privatednsname, i.vpcid, i.codsta, i.state ";
       $sql .= "from instancias i ";
       $sql .= "inner join regiones r on r.codreg = i.codreg ";
       $sql .= "inner join cuentas c on c.codcue = i.codcue ";
       if (strlen(trim($like)) > 0) {
          $sql .= "where $like ";
       }

       $sql .= "order by c.cuenta, r.region, i.state, Name;";
    }
    // echo "<br>codcue $codcue codreg $codcue<br>";
    // echo "<br>$sql<hr>";

?>
