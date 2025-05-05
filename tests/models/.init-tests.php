<?php
// /test - Punto de entrada para el sistema de pruebas
require_once '.tableHelper.php';
require_once '.TestRunner.php';

// Definir lista de pruebas disponibles
$availableTestSuites = [
    'Goods' => ['file' => 'Goods.test.php', 'checked' => true],
    'Groups' => ['file' => 'Groups.test.php', 'checked' => true],
    'User' => ['file' => 'User.test.php', 'checked' => true],
    'Inventory' => ['file' => 'Inventory.test.php', 'checked' => true],
    'GoodsInventory' => ['file' => 'GoodsInventory.test.php', 'checked' => true],
    'Tasks' => ['file' => 'Tasks.test.php', 'checked' => true],
    'Reports' => ['file' => 'Reports.test.php', 'checked' => true],
];

// Iniciar sesión y establecer el usuario actual si no existe
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// Obtener estilo CSS del TestRunner
$testRunner = new TestRunner();
$header = $testRunner->getWebHeader();
$footer = $testRunner->getWebFooter();

// Verificar si se ha solicitado una suite de pruebas específica
if (isset($_GET['suite']) && array_key_exists($_GET['suite'], $availableTestSuites)) {
    $testSuite = $_GET['suite'];
    $testFile = $availableTestSuites[$testSuite]['file'];
    
    // Incluir y ejecutar el archivo de prueba seleccionado
    include_once($testFile);

    $runner->handleWebRequest();
} else {

    echo $header;  // Mostrar el encabezado HTML

    // Mostrar el índice principal
    echo "<h2>Sistema de Pruebas Automatizadas</h2>";
    echo "<p>Seleccione una suite de pruebas para ejecutar:</p>";
    
    echo "<div class='test-suites-grid'>";
    foreach ($availableTestSuites as $name => $data) {
        $checked = $data['checked'] ? "<span class='checkmark'>&#10003;</span>" : "";
        echo "<div class='test-suite-card'>";
        echo "<h3>$name $checked</h3>";
        echo "<p>Archivo: {$data['file']}</p>";
        echo "<a href='.init-tests.php?suite=$name' class='btn'>Ejecutar pruebas</a>";
        echo "</div>";
    }
    echo "</div>";
    
    // Añadir algunas estadísticas o información adicional
    echo "<div class='summary'>";
    echo "<h3>Información del Sistema de Pruebas:</h3>";
    echo "<p>Total de suites de prueba disponibles: " . count($availableTestSuites) . "</p>";
    echo "</div>";

    // Mostrar el pie de página HTML
    echo $footer;
}
