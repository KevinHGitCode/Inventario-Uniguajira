
function iniciarBusqueda() {
    const searchInput = document.getElementById('searchUserInput');
    if (!searchInput) {
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll(".bien-card");

        cards.forEach(item => {
            const text = item.querySelector("h3").textContent.toLowerCase();
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}
