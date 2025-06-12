<?php require_once 'app/controllers/sessionCheck.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=500, user-scalable=no">
    <title>Inventario Uniguajira</title>
    <!-- icono de la pestaña del navegador -->
    <link href="assets/images/favicon-uniguajira-32x32.webp" rel="icon" type="image/png" />

    <!-- Este font-awesome sirve para los iconos de inventario -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/.get.css">
    <link rel="stylesheet" href="assets/css/components/.get.css">
    <link rel="stylesheet" href="assets/css/responsive/.get.css">

</head>

<body>

    <?php include 'app/views/navbar.php'; ?>

    <?php include 'app/views/sidebar.php'; ?>


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

    <!-- Solucion temporal: cargar todos los modales una sola vez -->
    <?php // incluir los modales para el administrador
    if ($_SESSION['user_rol'] === 'administrador') {
        include __DIR__ . '/modals/modals.profile.php';
        include __DIR__ . '/modals/modals.task.php';
        include __DIR__ . '/modals/modals.good.php';
        include __DIR__ . '/modals/modals.inventory.php';
        include __DIR__ . '/modals/modals.user.php';
        include __DIR__ . '/modals/modals.reports.php';
        include __DIR__ . '/modals/modals.records.php';
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/helpers/submitForm.js"></script>
    <script src="assets/js/helpers/delete.js"></script>
    <script src="assets/js/helpers/search.js"></script>
    <script src="assets/js/helpers/toast.js"></script>
    <script src="assets/js/tasks.js"></script>

    <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
    <script src="assets/js/goods.js"></script>
    <script src="assets/js/goods-excel-upload.js"></script>
    <script src="assets/js/helpers/modal.js"></script>
    <script src="assets/js/helpers/selection.js"></script>
    <script src="assets/js/helpers/autocomplete.js"></script>
    <?php endif; ?>

    <script src="assets/js/user.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="assets/js/inventory/inventory.js"></script>
    <script src="assets/js/inventory/groups.js"></script>
    <script src="assets/js/inventory/goodsInventory.js"></script>
    <script src="assets/js/inventory/goods-inventory-loader.js"></script>
    <script src="assets/js/inventory/formGoodInventory.js"></script>
    <script src="assets/js/reports/folders.js"></script>
    <script src="assets/js/reports/reports.js"></script>
    <script src="assets/js/historial.js"></script>
    <script src="assets/js/onLoaded.js"></script>
</body>

</html>