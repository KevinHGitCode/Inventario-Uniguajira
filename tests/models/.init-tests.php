<?php
// /test - Punto de entrada para el sistema de pruebas
require_once '.tableHelper.php';
require_once '.TestRunner.php';

// Definir lista de pruebas disponibles
$availableTestSuites = [
    'Goods' => 'Goods.test.php',
    'Groups' => 'Groups.test.php',
    'User' => 'User.test.php',
    'Inventory' => 'Inventory.test.php',
    'GoodsInventory' => 'GoodsInventory.test.php',
    'Tasks' => 'Tasks.test.php',
];

// Obtener estilo CSS del TestRunner
$testRunner = new TestRunner();
$header = $testRunner->getWebHeader();
$footer = $testRunner->getWebFooter();

// Verificar si se ha solicitado una suite de pruebas específica
if (isset($_GET['suite']) && array_key_exists($_GET['suite'], $availableTestSuites)) {
    $testSuite = $_GET['suite'];
    $testFile = $availableTestSuites[$testSuite];
    
    // Incluir y ejecutar el archivo de prueba seleccionado
    include_once($testFile);

    $runner->handleWebRequest();
} else {

    echo $header;  // Mostrar el encabezado HTML

    // Mostrar el índice principal
    echo "<h2>Sistema de Pruebas Automatizadas</h2>";
    echo "<p>Seleccione una suite de pruebas para ejecutar:</p>";
    
    echo "<div class='test-suites-grid'>";
    foreach ($availableTestSuites as $name => $file) {
        echo "<div class='test-suite-card'>";
        echo "<h3>$name</h3>";
        echo "<p>Archivo: $file</p>";
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
