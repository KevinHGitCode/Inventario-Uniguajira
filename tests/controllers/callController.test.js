// Realiza una solicitud a la URI y muestra la respuesta en la consola
fetch('http://inventario.test/api/login')
    .then(response => response.text()) // Convierte la respuesta a texto
    .then(text => console.log('Respuesta del servidor:', text)) // Muestra la respuesta en la consola
    .catch(error => console.error('Error al realizar la solicitud:', error)); // Maneja errores