<?php require_once 'app/controllers/sessionCheck.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Uniguajira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Manrope:wght@400;600&family=Plus+Jakarta+Sans:wght@400;600&family=Space+Grotesk:wght@400;500&display=swap">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/toast.css">
    <link rel="stylesheet" href="assets/css/userMenu.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/goods.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/tasks.css">
    <link rel="stylesheet" href="assets/css/responsive/responsive.css">
    <link rel="stylesheet" href="assets/css/responsive/goodsResponsive.css">
    <link rel="stylesheet" href="assets/css/responsive/inventoryResponsive.css">

</head>

<body>

    <?php include 'app/views/navbar.php'; ?>

    <?php include 'app/views/sidebar.html'; ?>


    <!-- -------------------------------------------------------------------
        main: Las opciones del sidebar están vinculadas 
        a una función de JavaScript que actualiza dinámicamente 
        el contenido del elemento <main> utilizando innerHTML.
    ------------------------------------------------------------------- -->
    <main id="main">
        <!-- Contenedor de Toasts -->
        <div id="toastContainer" class="toast-container"></div>

        <div id="main-content"></div>
    </main>

    <!-- TODO: Crear un modal que para usar innerHTML -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/tasks.js"></script>
    <script src="assets/js/goods.js"></script>
    <script src="assets/js/user.js"></script>
    <script src="assets/js/inventory.js"></script>

    <script>
         // Asegurar que las funciones estén disponibles globalmente
         document.addEventListener('DOMContentLoaded', () => {
             if (window.taskFunctions) {
                 window.showTaskModal = window.taskFunctions.showTaskModal;
                 window.hideTaskModal = window.taskFunctions.hideTaskModal;
                 window.createTask = window.taskFunctions.createTask;
                 window.toggleTask = window.taskFunctions.toggleTask;
                 window.deleteTask = window.taskFunctions.deleteTask;
                 window.showNotification = window.taskFunctions.showNotification;
             }
         });
     </script>
</body>

</html>