// probar el ctlLogin.php usando fetch
// ctlLogin.php regresar un json
function getBaseUrl() {
    // Detecta si estás en un entorno local o en producción
    const isLocal = true; // Cambia esto a `false` si estás en producción
    return isLocal
        ? 'http://localhost/Inventario-Uniguajira' // URL base para desarrollo local
        : 'https://mi-servidor.com/Inventario-Uniguajira'; // URL base para producción
}

fetch(`${getBaseUrl()}/app/controllers/ctlLogin.php`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        username: 'testuser',
        password: 'testpassword'
    })
})
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => console.log(data)) // Mostrar los datos en la consola
    .catch(error => console.error('Error:', error)); // Manejar errores
