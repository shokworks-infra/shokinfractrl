<head>
   <script language="JavaScript" type="text/javascript" src="js/datos.js"></script>
   <script language="JavaScript" type="text/javascript" src="../js/ajax.js"></script>
   <link rel="stylesheet" href="../css/estilo.css" type="text/css">
</head>

<table class="tablaform" align='center' width="100%">

<tr><th align='center'  colspan=9>AWS INFRAESTRUCTURA DEVELOP</th></tr>

   <tr><th colspan="2">Cuentas AWS</th><td colspan="5">
   <?php
      include_once '../comun/comboCuentas.php';
   ?>
</td></tr>

<tr>
     <tH>Name</tH> 
     <th>Región</th>
     <th>Instance Type</th>
</tr>
<tr>
     <td>
         <input type='text' id='nombre' name='nombre' 
               onchange='nombre=document.getElementById(\"nombre\").value;' >
     </td> 
     <td>
        <div id='divRegiones' style="margin: auto;">
            <select id="cboRegiones" name="cboRegiones" style="width: 350px;">
               <option value="1000">Todas</option>
            </select>
        </div>
     </td>
     
     <td>
         <input type='text' id='instancetype' name='ubicacion' 
                onchange='instancetype=document.getElementById(\"instancetype\").value;'>
     </td>
</tr>
<tr>
   <td colspan='7' align='center'>
      <input type='button' class='boton' value='Buscar' onclick='javascript:mostrarDatos();document.getElementById("divInstancia").style.display = "none";'>
      <input type='button' class='boton' value='Volver' onclick='window.location = "http://localhost/shokinfractrl";'>
   </td>
</tr>

</table>

<div id="divResultados">

</div>

<div id="divInstancia" style="display:none;">
  
</div>
