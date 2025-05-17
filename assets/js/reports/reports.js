// Objeto que almacenará los datos de grupos e inventarios
const datosInventarios = {
    grupos: [],
    inventariosPorGrupo: {}
};

// Función para cargar los grupos cuando se abre el modal
function cargarGrupos() {
    // Hacer una petición AJAX para obtener los grupos
    fetch('/api/groups/getAll')
        .then(response => response.json())
        .then(data => {
            datosInventarios.grupos = data;
            
            // Limpiar y rellenar el select de grupos
            const selectGrupos = document.getElementById('grupoSeleccionado');
            selectGrupos.innerHTML = '<option value="">Seleccione un grupo</option>';
            
            data.forEach(grupo => {
                const option = document.createElement('option');
                option.value = grupo.id;
                option.textContent = grupo.nombre;
                selectGrupos.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar los grupos:', error);
            alert('Error al cargar los grupos. Por favor, inténtelo de nuevo.');
        });
}

// Función para cargar los inventarios de un grupo específico
function cargarInventariosPorGrupo(grupoId) {
    // Si ya tenemos los inventarios de este grupo en caché, los usamos
    if (datosInventarios.inventariosPorGrupo[grupoId]) {
        actualizarSelectInventarios(datosInventarios.inventariosPorGrupo[grupoId]);
        return;
    }
    
    // Si no, hacemos la petición para obtenerlos
    fetch(`/api/inventories/getByGroupId/${grupoId}`)
        .then(response => response.json())
        .then(data => {
            // Guardar en caché
            datosInventarios.inventariosPorGrupo[grupoId] = data;
            
            // Actualizar el select
            actualizarSelectInventarios(data);
        })
        .catch(error => {
            console.error('Error al cargar los inventarios:', error);
            alert('Error al cargar los inventarios. Por favor, inténtelo de nuevo.');
        });
}

// Función para actualizar el select de inventarios
function actualizarSelectInventarios(inventarios) {
    const selectInventarios = document.getElementById('inventarioSeleccionado');
    
    // Habilitar el select si hay inventarios
    if (inventarios && inventarios.length > 0) {
        selectInventarios.disabled = false;
        
        // Limpiar opciones anteriores
        selectInventarios.innerHTML = '<option value="">Seleccione un inventario</option>';
        
        // Añadir nuevas opciones
        inventarios.forEach(inventario => {
            const option = document.createElement('option');
            option.value = inventario.id;
            option.textContent = inventario.nombre;
            selectInventarios.appendChild(option);
        });
    } else {
        // Si no hay inventarios, deshabilitar y mostrar mensaje
        selectInventarios.disabled = true;
        selectInventarios.innerHTML = '<option value="">No hay inventarios disponibles para este grupo</option>';
    }
}

// Configurar event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Event listener para cuando se abre el modal
    const abrirModalBtn = document.getElementById('btn-abrir-modal-reporte');
    if (abrirModalBtn) {
        abrirModalBtn.addEventListener('click', function() {
            cargarGrupos();
            mostrarModal('#modalCrearReporteDeUnInventario');
        });
    }
    
    // Event listener para cuando cambia el grupo seleccionado
    const selectGrupo = document.getElementById('grupoSeleccionado');
    if (selectGrupo) {
        selectGrupo.addEventListener('change', function() {
            const grupoId = this.value;
            const selectInventarios = document.getElementById('inventarioSeleccionado');
            
            if (grupoId) {
                cargarInventariosPorGrupo(grupoId);
            } else {
                // Si no hay grupo seleccionado, deshabilitar el select de inventarios
                selectInventarios.disabled = true;
                selectInventarios.innerHTML = '<option value="">Primero seleccione un grupo</option>';
            }
        });
    }
    
    // Event listener para el envío del formulario
    const formReporte = document.getElementById('formReporteDeUnInventario');
    if (formReporte) {
        formReporte.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Obtener los valores seleccionados
            const nombreReporte = document.getElementById('nombreReporte').value;
            const grupoId = document.getElementById('grupoSeleccionado').value;
            const inventarioId = document.getElementById('inventarioSeleccionado').value;
            
            // Validar que se seleccionaron grupo e inventario
            if (!grupoId || !inventarioId) {
                alert('Por favor, seleccione un grupo y un inventario.');
                return;
            }
            
            // Enviar la solicitud para generar el reporte
            fetch('/api/reports/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nombreReporte: nombreReporte,
                    grupoId: grupoId,
                    inventarioId: inventarioId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reporte generado con éxito');
                    ocultarModal('#modalCrearReporteDeUnInventario');
                    // Aquí podrías redirigir al PDF generado
                    if (data.pdfUrl) {
                        window.open(data.pdfUrl, '_blank');
                    }
                } else {
                    alert('Error al generar el reporte: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        });
    }
});


//Como hago que todo este js funcione para ambos modales, ya que son iguales pero con campo menos