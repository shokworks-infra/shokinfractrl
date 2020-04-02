function mostrarPagina(url, idDiv, parametros=""){


 // alert(url + " " + idDiv + " " + parametros);
 divFormulario = document.getElementById(idDiv);

 ajax=nuevoAjax();

 ajax.open("POST", url);

 ajax.onreadystatechange=function() {
  if (ajax.readyState==4 ) {
   //mostrar resultados en esta capa
   divFormulario.innerHTML = ajax.responseText;
   divFormulario.style.display="block";
  }
 }
 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
 ajax.send(parametros);

}

function nuevoAjax() {

  if (window.XMLHttpRequest)
    {
        // Si es Mozilla, Safari etc
        Ajax = new XMLHttpRequest ();

    } else if (window.ActiveXObject)
    {
        // Si es IE
        try
        {
            Ajax = new ActiveXObject ("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            // en caso que sea una versión antigua
            try
            {
                Ajax = new ActiveXObject ("Microsoft.XMLHTTP");
            }
            catch (e)
            {
            }
        }
        
    }  else {
        return false;
    }
    
    return Ajax;

}

function peticionAjax (url, capa, parametros) {

    //alert('Llamando a: url: ' +  url + ' capa: ' + capa + ' parametros: ' + parametros );
    var Ajax = nuevoAjax();
    var ajaxObjhttp = new Ajax.Request(url, {
        method: 'POST',
        parameters: parametros,
        onCreate: function(transport) { //Método ejecutado cuando se inicie la petición

          document.getElementById(capa).innerHTML = '<img src="imagenes/cargandoDatos.html">';
        },
        onSuccess: function(transport) { //Método ejecutado cuando la petición es completada sin errores

             document.getElementById(capa).innerHTML = "";
             document.getElementById(capa).innerHTML += transport.responseText;
        },
        onFailure : function(response) { //Método ejecutado cuando la petición es completada con algún error
            document.getElementById(capa).innerHTML += "Ocurri&oacute; un error al acceder al servidor Web" + transport.responseText;
           // document.getElementById(capa).innerHTML = '<b>' + url + '<b>';
           //document.getElementById(capa).innerHTML += response.responseText;

        }
    })

}

function selObjParametrosCM(url, accion, valor) {
//parametros para Cambios Masivos (CM)
var obj = new Object();


//objeto=Form.serialize('agregar');

obj.accion = accion;
obj.valor = valor;
obj['nombre']='jose';


return obj

}

// Esta funcion se usa para subir archivos al servidor.
function jsUpload(upload_field)
{
    // this is just an example of checking file extensions
    // if you do not need extension checking, remove
    // everything down to line
    // upload_field.form.submit();

    var re_text = /\.csv|\.txt|\.xml|\.zip/i;
    var filename = upload_field.value;

    /* Checking file type */
    if (filename.search(re_text) == -1)
    {
        alert("El archivo no tiene la extensión (csv, txt, xml, zip)");
        upload_field.form.reset();
        return false;
    }

    upload_field.form.submit();
    document.getElementById('upload_status').value = "Cargando archivo....";
    upload_field.disabled = true;
    return true;
}

function actualizaDiv(capa, texto){

      //alert ('actualizaDiv capa ' + capa + ' texto ' + texto);
      document.getElementById(capa).innerHTML = texto;
      return true;
}

function mostrarDiv(nombreCapa){
//$(nombreCapa).style.display="block";
document.getElementById(nombreCapa).style.visibility="visible";
return;
}

function ocultarDiv(nombreCapa){
//$(nombreCapa).style.display="none";
document.getElementById(nombreCapa).style.visibility="hidden";
return;
}
function mostrarPaginaPosicion(url, capa, posicion, evaluaScript) {
/*
   Actualiza la pagina insertando el nuevo texto de acuerdo con la posicion
   indicada por la variable posicion:
   Puede ser Insertion.Before, Insertion.Top, Insertion.Bottom o Insertion.After
   Si no se especifica posicion, el texto anterior sera sustituido por el nuevo.
*/
/*
  evaluaScript: true si se desea que se evaluen los script que contine la pagina que se
  muestra, falso no se evaluan.
*/
    //Event.observe('resultado','click',procesaEvento,false);

    new Ajax.Updater(capa, url , { insertion:posicion, evalScript:evaluaScript, method: 'POST' });

}

function actualizarPagina(url, capa, posicion, evaluaScript, segundos) {
/*
   Esta funcion actualiza la pagina con informacion desde el servidor
   cada cierto tiempo indicado en 'segundos'
/*
   Actualiza la pagina insertando el nuevo texto de acuerdo con la posicion
   indicada por la variable posicion:
   Puede ser Insertion.Before, Insertion.Top, Insertion.Bottom o Insertion.After
   Si no se especifica posicion, el texto anterior sera sustituido por el nuevo.
*/
/*
  evaluaScript: true si se desea que se evaluen los script que contine la pagina que se
  muestra, falso no se evaluan.
*/
  new Ajax.PeriodicalUpdater(capa, url , { frequency:segundos, insertion:posicion });
}

function getCheckedRadioId(name) {
    var elements = document.getElementsByName(name);

    for (var i=0, len=elements.length; i<len; ++i)
        if (elements[i].checked) return elements[i].value;
}

function getCheckedSelectId(id) {

var ubi = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
return ubi;
}


function doesFileExist(urlToFile)
{
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}
