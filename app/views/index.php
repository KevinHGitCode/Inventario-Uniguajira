<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Uniguajira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/userMenu.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/goods.css">
    <link rel="stylesheet" href="assets/css/inventory.css">
    <link rel="stylesheet" href="assets/css/tasks.css">
    <link rel="stylesheet" href="assets/css/responsive/responsive.css">
    <link rel="stylesheet" href="assets/css/responsive/goodsResponsive.css">
</head>
    
<body>
    <?php include 'app/views/navbar.html'; ?>
    <?php include 'app/views/sidebar.html'; ?>

    <main id="main">
        <!-- Contenido cargado dinámicamente -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/goods.js"></script>
    <script src="assets/js/inventory.js"></script>
    <script src="assets/js/tasks.js"></script>
    
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