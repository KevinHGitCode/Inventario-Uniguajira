<?php require_once 'app/controllers/sessionCheck.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Uniguajira</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive/responsive.css">
    <link rel="stylesheet" href="assets/css/userMenu.css">
    <link rel="stylesheet" href="assets/css/home.css">
    
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
        
    </main>

    <!-- TODO: Crear un modal que para usar innerHTML -->

    <!-- TODO: Crear un notificacion toast para usar innerHTML  -->

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/navbar.js"></script>
</body>
</html>