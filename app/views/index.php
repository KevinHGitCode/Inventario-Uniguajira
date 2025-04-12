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
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/toast.css">
    <link rel="stylesheet" href="assets/css/userMenu.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/goods.css">
    <link rel="stylesheet" href="assets/css/inventory.css">
    <link rel="stylesheet" href="assets/css/responsive/responsive.css">
    <link rel="stylesheet" href="assets/css/responsive/goodsResponsive.css">

</head>
    
<body>
    
    <?php include 'app/views/navbar.html'; ?>

    <?php include 'app/views/sidebar.html'; ?>


    <!-- -------------------------------------------------------------------
        main: Las opciones del sidebar están vinculadas 
        a una función de JavaScript que actualiza dinámicamente 
        el contenido del elemento <main> utilizando innerHTML.
    ------------------------------------------------------------------- -->
    <main id="main">
        <!-- Contenedor de Toasts -->
        <div id="toastContainer" class="toast-container"></div>

        <!-- Descomentar para probar los botones -->
        <!-- <div class="container mt-5 text-center">
            <h1>Mini Experimento - Toast</h1>
            <button id="successBtn" class="btn btn-success mt-4 me-2">Éxito</button>
            <button id="errorBtn" class="btn btn-danger mt-4">Error</button>
        </div> -->

        <div id="main-content"></div>
    </main>

    <!-- TODO: Crear un modal que para usar innerHTML -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/goods.js"></script>
    <script src="assets/js/inventory.js"></script>
</body>
</html>