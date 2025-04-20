/**
 * modal.js - Utilidades para manejo de ventanas modales
 * 
 * Este archivo contiene funciones para gestionar el comportamiento de modales en la aplicación.
 * Proporciona funcionalidades para mostrar y ocultar modales, así como para detectar clics fuera 
 * del contenido del modal para cerrarlos automáticamente. Incluye una solución especial para 
 * evitar que el modal se cierre accidentalmente cuando se selecciona texto dentro de un input.
 * 
 * @version 2.0
 * @date 2025-04-20
 */


// Variables para seguir el inicio del clic
let clickIniciadoEnModal = false;

// Mostrar el modal
function mostrarModal(selectorModal) {
    const modal = document.querySelector(selectorModal);
    modal.style.display = "flex";

    // Evento para detectar donde comienza el clic
    const mouseDownHandler = function(e) {
        clickIniciadoEnModal = modal.contains(e.target);
    };

    // Definimos la función y la asignamos solo una vez
    cerrarModalHandler = function(e) {
        // Solo cerramos el modal si el clic comenzó fuera del modal
        // y terminó fuera del modal (en el área oscura)
        if (e.target === modal && !clickIniciadoEnModal) {
            ocultarModal(selectorModal);
        }
        // Reiniciamos la variable para el próximo clic
        clickIniciadoEnModal = false;
    };

    // Agregamos los event listeners
    window.addEventListener("mousedown", mouseDownHandler);
    window.addEventListener("mouseup", cerrarModalHandler);
    
    // Guardamos el handler para poder eliminarlo después
    modal._mouseDownHandler = mouseDownHandler;
}

// Ocultar el modal
function ocultarModal(selectorModal) {
    const modal = document.querySelector(selectorModal);
    modal.style.display = "none";

    // Remueve los event listeners
    window.removeEventListener("mousedown", modal._mouseDownHandler);
    window.removeEventListener("mouseup", cerrarModalHandler);
}