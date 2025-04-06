function abrirInventario(id) {
    console.log('Abriendo inventario para el grupo con ID:', id);
    // Aquí puedes agregar lógica adicional para manejar la apertura del inventario
}

document.querySelectorAll('.group-card button[data-id]').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        console.log('ID del grupo:', id);
    });
});