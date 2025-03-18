<?php
require_once __DIR__ . '/../controllers/ctlHome.php';
$userName = ctlHome::getUserNameById(1);
?>
<h1>¡Bienvenido, <?php echo htmlspecialchars($userName); ?>!</h1>