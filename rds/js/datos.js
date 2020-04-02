function mostrarDatos(){

 var divFormulario = document.getElementById('divResultados');
 var codcue        = getCheckedSelectId('cboCuentas');
 var codreg        = getCheckedSelectId('cboRegiones');
 var nombre        = document.getElementById('nombre').value;
 var instancetype  = document.getElementById('instancetype').value;
 var orden         = 0;
 var personalDir   = 1;

 // document.getElementById('divInstancia').style.display = 'none';
 /*
 if (document.getElementById('chkDir').checked) {
    personalDir=1;
 } else {
   personalDir=0;
 }
 */

 //alert(document.getElementById("cboCuentas").value);

 miAnd = 0;
 parametros="";
 
 parametros = "codcue="+codcue;

// if (codreg < 1000 ) {
 parametros = "&codreg=" + codreg; 
 //}  
 
 miAnd = 1
 if (nombre.length > 0 ) {
     parametros = "&nombre=" + nombre;
 }

 if (instancetype.length > 0) {

       parametros = parametros + "&instancetype=" + instancetype;
 }
 
 parametros = parametros + "&orden=" + orden+"&codcue="+codcue;

 // alert('parametros: ' + parametros);

 ajax=nuevoAjax();

 //document.getElementById('imgCargando').style.display = 'block';
 actualizaDiv('divResultados', '<center>Procesando... Espere por favor</center>' ) ;  
 
 ajax.open("POST", "datosProceso.php");
 ajax.onreadystatechange=function() {
  if (ajax.readyState==4) {
   //mostrar resultados en esta capa
   //document.getElementById('imgCargando').style.display = 'none';
   divFormulario.innerHTML = ajax.responseText;
   divFormulario.style.display="block";
  }
 }

 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

 ajax.send(parametros);
}
