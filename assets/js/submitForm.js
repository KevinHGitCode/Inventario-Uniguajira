/**
 * Este script se encarga de enviar formularios mediante AJAX.
 * Se utiliza para evitar el actualizacion de la pagina al enviar un formulario.
 * Se utiliza la API Fetch para enviar el formulario.
 * 
 * Instrucciones:
 * 1. Agregar la clase "FormularioAjax" al formulario que se desea enviar mediante AJAX.
 * 2. Agregar el atributo "method" al formulario con el valor "POST" o "GET".
 * 3. Agregar el atributo "action" con la 'ruta' a la que se desea enviar el formulario.
 * 
 * Llamado: 
 * - Al seleccionar una opcion del sidebar se carga un html
 * - Este html puede tener formularios
 * - En el metodo loadContent se debe poner un condicional
 * - Si es la opcion seleccionada entonces:
 *    - Se llama a la funcion inicializarFormularioAjax()
 * 
 */

const formularios_ajax=document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
    e.preventDefault();

    let enviar=confirm("Quieres enviar el formulario");

    if(enviar==true){

        let data= new FormData(this);
        let method=this.getAttribute("method");
        let action=this.getAttribute("action");

        let encabezados= new Headers();

        let config={
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        fetch(action,config)
        .then(respuesta => respuesta.text())
        .then(respuesta =>{ 
            alert(respuesta); 
        });
    }

}

// Asignar el evento submit a cada formulario con la clase FormularioAjax
function inicializarFormularioAjax() {
    formularios_ajax.forEach(formularios => {
        formularios.addEventListener("submit",enviar_formulario_ajax);
    });
}