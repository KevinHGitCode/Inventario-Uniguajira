<?php
require_once __DIR__ . '/../controllers/ctlHome.php';
$userName = ctlHome::getUserNameById(1);
?>
<h1>¡Bienvenido, <?php echo htmlspecialchars($userName); ?>!</h1>

<h2 class="tittle-list-task">Tareas pendientes</h2>

<!-- <div class="container-list-task" style="border: 1px solid black;">
    < ?php for ($i = 0; $i < 2; $i++): ?>
        <div class="contenedor-hijo">
            < ?php include __DIR__ . '/list-task.html'; ?>
        </div>
    < ?php endfor; ?>
</div> -->

<?php include __DIR__ . '/list-task.html'; ?>