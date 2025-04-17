/**
 * Manejador de formularios para envío mediante AJAX
 * Esta función permite enviar cualquier formulario mediante AJAX y realizar acciones personalizadas
 * con la respuesta obtenida del servidor.
 * 
 * @param {string} formSelector - Selector CSS para identificar el/los formulario(s) (ej: ".FormularioAjax")
 * @param {Object} options - Opciones de configuración
 * @param {Function} options.beforeSubmit - Función llamada antes de enviar el formulario (retorna false para cancelar)
 * @param {Function} options.onSuccess - Función llamada cuando el servidor responde exitosamente
 * @param {Function} options.onError - Función llamada cuando ocurre un error
 * @param {Function} options.onComplete - Función llamada al finalizar (exitoso o con error)
 * @param {boolean} options.showConfirm - Mostrar diálogo de confirmación antes de enviar (default: false)
 * @param {string} options.confirmMessage - Mensaje de confirmación personalizado
 * @param {boolean} options.resetOnSuccess - Resetear el formulario después de éxito (default: false)
 * @param {boolean} options.closeModalOnSuccess - Cerrar modal asociado (requiere data-modal) (default: false)
 * @param {string|Function} options.redirectOnSuccess - URL o función que devuelve URL para redireccionar después de éxito
 */
function inicializarFormularioAjax(formSelector, options = {}) {
    // Opciones por defecto
    const defaultOptions = {
        beforeSubmit: null,
        onSuccess: response => showToast(response),
        onError: error => showToast({
            success: false,
            message: 'Error al procesar la solicitud: ' + (error.message || 'Error desconocido')
        }),
        onComplete: null,
        showConfirm: false,
        confirmMessage: '¿Estás seguro de enviar este formulario?',
        resetOnSuccess: false,
        closeModalOnSuccess: false,
        redirectOnSuccess: null
    };
    
    // Combinar opciones default con las proporcionadas
    const settings = { ...defaultOptions, ...options };
    
    // Obtener todos los formularios que coinciden con el selector
    const formularios = document.querySelectorAll(formSelector);
    
    if (formularios.length === 0) {
        console.warn(`No se encontraron formularios con el selector: ${formSelector}`);
        return;
    }
    
    // Asignar el evento submit a cada formulario
    formularios.forEach(formulario => {
        // Remover eventos anteriores si existieran (para evitar duplicados)
        formulario.removeEventListener('submit', formSubmitHandler);
        
        // Agregar el nuevo manejador de eventos
        formulario.addEventListener('submit', formSubmitHandler);
    });
    
    /**
     * Manejador del evento submit
     * @param {Event} e - Evento submit
     */
    function formSubmitHandler(e) {
        e.preventDefault();
        
        // Ejecutar función beforeSubmit si existe
        if (settings.beforeSubmit && settings.beforeSubmit(this) === false) {
            return; // Cancela el envío si beforeSubmit retorna false
        }
        
        // Mostrar confirmación si está habilitado
        if (settings.showConfirm) {
            if (!confirm(settings.confirmMessage)) {
                return; // El usuario canceló el envío
            }
        }
        
        // Crear objeto FormData con los datos del formulario
        const formData = new FormData(this);
        
        // Obtener método y acción del formulario
        const method = this.getAttribute('method') || 'POST';
        const action = this.getAttribute('action') || '';
        
        // Referencia al formulario para usar en promesas
        const form = this;
        
        // Configuración del fetch
        const fetchConfig = {
            method: method.toUpperCase(),
            body: formData,
            headers: new Headers(),
            mode: 'cors',
            cache: 'no-cache'
        };
        
        // Mostrar indicador de carga si existe
        toggleLoadingState(form, true);
        
        // Realizar la petición AJAX
        fetch(action, fetchConfig)
            .then(response => {
                // TODO: Se puede simplificar
                // Intentar parsear la respuesta como JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        // Si no es JSON, devolver como texto
                        return { success: response.ok, message: text };
                    }
                });
            })
            .then(responseData => {
                // Determinar si la respuesta fue exitosa
                const isSuccess = responseData.success !== false;
                
                if (isSuccess) {
                    // Acciones en caso de éxito
                    if (settings.onSuccess) {
                        settings.onSuccess(responseData, form);
                    }
                    
                    // Resetear formulario si está configurado
                    if (settings.resetOnSuccess) {
                        form.reset();
                    }
                    
                    // Cerrar modal asociado si está configurado
                    if (settings.closeModalOnSuccess && form.dataset.modal) {
                        const modalId = form.dataset.modal;
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.style.display = 'none';
                        }
                    }
                    
                    // Redireccionar si está configurado
                    if (settings.redirectOnSuccess) {
                        let redirectUrl = settings.redirectOnSuccess;
                        if (typeof redirectUrl === 'function') {
                            redirectUrl = redirectUrl(responseData);
                        }
                        if (redirectUrl) {
                            setTimeout(() => {
                                window.location.href = redirectUrl;
                            }, 500); // Pequeño retraso para que el usuario vea el mensaje
                        }
                    }
                } else {
                    // Acciones en caso de error
                    if (settings.onError) {
                        settings.onError(responseData, form);
                    }
                }
                
                // Acciones al completar (éxito o error)
                if (settings.onComplete) {
                    settings.onComplete(responseData, isSuccess, form);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                
                if (settings.onError) {
                    settings.onError(error, form);
                }
                
                if (settings.onComplete) {
                    settings.onComplete(error, false, form);
                }
            })
            .finally(() => {
                // Ocultar indicador de carga
                toggleLoadingState(form, false);
            });
    }
    
    /**
     * Alterna el estado de carga en el formulario
     * @param {HTMLFormElement} form - Formulario
     * @param {boolean} isLoading - Estado de carga
     */
    function toggleLoadingState(form, isLoading) {
        // Deshabilitar/habilitar los campos del formulario
        const formElements = form.querySelectorAll('input, select, textarea, button');
        formElements.forEach(element => {
            element.disabled = isLoading;
        });
        
        // Buscar un botón de envío para mostrar estado de carga
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            const originalText = submitButton.dataset.originalText || submitButton.innerHTML;
            
            if (isLoading) {
                // Guardar texto original si no está guardado
                if (!submitButton.dataset.originalText) {
                    submitButton.dataset.originalText = originalText;
                }
                // Cambiar a texto de carga
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            } else {
                // Restaurar texto original
                submitButton.innerHTML = originalText;
            }
        }
    }
    
    // Retornar la configuración actual para posibles usos externos
    return settings;
}


/**
 * Ejemplos de uso:
 * 
 * 1. Inicializar todos los formularios con la clase FormularioAjax de forma básica:
 * inicializarFormularioAjax('.FormularioAjax');
 * 
 * 2. Formulario de Login con redirección:
 * inicializarFormularioAjax('form[action="/api/login"]', {
 *     redirectOnSuccess: '/dashboard',
 *     onError: (response) => {
 *         showToast({success: false, message: 'Usuario o contraseña incorrectos'});
 *     }
 * });
 * 
 * 3. Formulario de creación con reset y cierre de modal:
 * inicializarFormularioAjax('#formCrearBien', {
 *     resetOnSuccess: true,
 *     closeModalOnSuccess: true,
 *     onSuccess: (response) => {
 *         showToast(response);
 *         setTimeout(() => loadContent('/goods'), 1500);
 *     }
 * });
 * 
 * 4. Formulario de eliminación con confirmación:
 * inicializarFormularioAjax('#formEliminar', {
 *     showConfirm: true,
 *     confirmMessage: '¿Estás seguro de eliminar este elemento? Esta acción no se puede deshacer.',
 *     onSuccess: (response) => {
 *         showToast(response);
 *         loadContent('/goods');
 *     }
 * });
 */