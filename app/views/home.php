<?php
// $username = require_once __DIR__ . '/../controllers/ctlHome.php';
// echo __DIR__;
require __DIR__.'/../config/db.php';
?>

<!-- <h1>¡Bienvenido, < ?php echo $username ?>!</h1> -->

<h2 class="tittle-list-task">Tareas pendientes</h2>

<!-- <div class="container-list-task" style="border: 1px solid black;">
    < ?php for ($i = 0; $i < 2; $i++): ?>
        <div class="contenedor-hijo">
            < ?php include __DIR__ . '/list-task.html'; ?>
        </div>
    < ?php endfor; ?>
</div> -->

<?php include __DIR__ . '/list-task.html'; ?>
