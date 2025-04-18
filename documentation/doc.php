<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación del Proyecto</title>
    <link rel="preload" href="assets/images/fondounigua.jpeg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/doc.css">
</head>
<body>
    <header>
        <h1>Documentación del Proyecto</h1>
    </header>
    <main>
        <!-- Indice -->
        <nav>
            <h2>Índice</h2>
            <ul>
                <li><a href="#controladores">Controladores</a></li>
                <li><a href="#rutas">Rutas</a></li>
                <li><a href="#modelos">Modelos</a></li>
                <li><a href="#javascript">JavaScript</a></li>
                <li><a href="#flujo">Flujo de Rutas</a></li>
                <li><a href="#css-classes">Clases CSS</a></li>
            </ul>
        </nav>

        <div class="section-container">
           
        <?php include_once 'documentation/doc-controllers/controllers.doc.html'; ?>
        <?php include_once 'documentation/doc-rutas/rutas.doc.html'; ?>
        <?php include_once 'documentation/doc-models/models.doc.html'; ?>
        <?php include_once 'documentation/doc-javascript/javascript.doc.html'; ?>
        <?php include_once 'documentation/doc-flujo/flujo.doc.html'; ?>
        <?php include_once 'documentation/doc-styles/styles.doc.html'; ?>
      
        </div>
    </main>
</body>
</html>