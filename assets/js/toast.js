function showToast(msg) {
    const toastId = `toast-${Date.now()}`;
    const toastType = msg.success ? "toast-success" : "toast-fail";
    const icon = msg.success ? '✅' : '❌';
    const toastTitle = msg.success ? 'Éxito' : 'Error';
    const toastMessage = msg.message || (msg.success ? 'Operación completada' : 'Algo salió mal');

    // Estructura del toast
    const toastHTML = `
        <div id="${toastId}" class="toast ${toastType} align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <!--<span>${icon}</span>--><strong>${toastTitle}</strong>: ${toastMessage}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    const container = document.getElementById('toastContainer');
    container.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        delay: 3000 // Duración de 3 segundos
    });
    toast.show();
    
    // Auto-remove cuando se oculta
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Ejemplos de uso
document.getElementById('successBtn').addEventListener('click', () => {
    showToast({
        success: true,
        message: 'Los datos se guardaron correctamente'
    });
});

document.getElementById('errorBtn').addEventListener('click', () => {
    showToast({
        success: false,
        message: 'No se pudo conectar al servidor'
    });
});