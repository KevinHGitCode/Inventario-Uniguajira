<?php
require_once '../../app/models/Reports.php';

// Crear una instancia única del modelo Reports
$reports = new Reports();

$database = Database::getInstance();
$database->setCurrentUser();

// Crear una instancia del runner
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'folderId' => null,
    'reportId' => null
];

// PRUEBAS DE LECTURA
$runner->registerTest('obtener_carpetas', function() use ($reports) {
    echo "<p>Testing getAllFolders()...</p>";
    
    $allFolders = $reports->getAllFolders();
    if (is_array($allFolders)) {
        renderTable($allFolders);
        return true;
    }
    echo "<p>Error: No se pudieron obtener las carpetas como array</p>";
    return false;
});

// PRUEBAS DE CREACIÓN DE CARPETAS
$runner->registerTest('crear_carpeta_valida', function() use (&$testData, $reports) {
    $nombre = "Carpeta Temporal " . time();
    $descripcion = "Descripción de prueba";
    
    echo "<p>Testing createFolder('$nombre', '$descripcion')...</p>";
    $folderId = $reports->createFolder($nombre, $descripcion);

    if ($folderId !== false) {
        echo "<p>Carpeta creada con ID: $folderId</p>";
        $testData['folderId'] = $folderId;
        return true;
    }
    echo "<p>Error al crear la carpeta</p>";
    return false;
});

$runner->registerTest('crear_carpeta_nombre_duplicado', function() use (&$testData, $reports, $database) {
    if (!isset($testData['folderId'])) {
        echo "<p>Error: Primero debe ejecutarse 'crear_carpeta_valida'</p>";
        return false;
    }

    $stmt = $database->getConnection()->prepare("SELECT nombre FROM carpetas_reportes WHERE id = ?");
    $stmt->bind_param("i", $testData['folderId']);
    $stmt->execute();
    $nombreExistente = $stmt->get_result()->fetch_assoc()['nombre'];
    
    echo "<p>Testing createFolder('$nombreExistente', 'descripción')...</p>";
    $result = $reports->createFolder($nombreExistente, "descripción");

    if ($result === false) {
        echo "<p>Correcto: No se creó carpeta con nombre duplicado</p>";
        return true;
    }
    
    echo "<p>Error: Se permitió crear carpeta duplicada</p>";
    $reports->deleteFolder($result); // Limpieza
    return false;
});

// PRUEBAS DE CREACIÓN DE REPORTES
$runner->registerTest('crear_reporte_valido', function() use (&$testData, $reports) {
    if (!isset($testData['folderId'])) {
        echo "<p>Error: Primero debe ejecutarse 'crear_carpeta_valida'</p>";
        return false;
    }

    $nombre = "Reporte Temporal " . time();
    $descripcion = "Descripción del reporte";
    
    echo "<p>Testing createReport('$nombre', {$testData['folderId']}, '$descripcion')...</p>";
    $reportId = $reports->createReport($nombre, $testData['folderId'], $descripcion);

    if ($reportId !== false) {
        echo "<p>Reporte creado con ID: $reportId</p>";
        $testData['reportId'] = $reportId;
        return true;
    }
    echo "<p>Error al crear el reporte</p>";
    return false;
});

$runner->registerTest('crear_reporte_carpeta_inexistente', function() use ($reports) {
    $idInexistente = 999999;
    echo "<p>Testing createReport con carpeta inexistente...</p>";
    
    $result = $reports->createReport("Test", $idInexistente, "desc");
    if ($result === false) {
        echo "<p>Correcto: No se creó reporte en carpeta inexistente</p>";
        return true;
    }
    echo "<p>Error: Se permitió crear reporte en carpeta inexistente</p>";
    return false;
});

// PRUEBAS DE ACTUALIZACIÓN
$runner->registerTest('renombrar_reporte_valido', function() use (&$testData, $reports) {
    if (!isset($testData['reportId'])) {
        echo "<p>Error: Primero debe ejecutarse 'crear_reporte_valido'</p>";
        return false;
    }

    $nuevoNombre = "Reporte Actualizado " . time();
    echo "<p>Testing renameReport({$testData['reportId']}, '$nuevoNombre')...</p>";
    
    if ($reports->renameReport($testData['reportId'], $nuevoNombre)) {
        echo "<p>Reporte renombrado correctamente</p>";
        return true;
    }
    echo "<p>Error al renombrar el reporte</p>";
    return false;
});

// PRUEBAS DE ELIMINACIÓN
$runner->registerTest('eliminar_reporte', function() use (&$testData, $reports) {
    if (!isset($testData['reportId'])) {
        echo "<p>Error: Primero debe ejecutarse 'crear_reporte_valido'</p>";
        return false;
    }

    echo "<p>Testing eliminar reporte temporal...</p>";
    if ($reports->deleteReport($testData['reportId'])) {
        echo "<p>Reporte eliminado correctamente</p>";
        $testData['reportId'] = null;
        return true;
    }
    echo "<p>Error al eliminar el reporte</p>";
    return false;
});

$runner->registerTest('eliminar_carpeta_con_reportes', function() use (&$testData, $reports, $database) {
    // Buscar una carpeta con reportes
    $sql = "SELECT cr.id FROM carpetas_reportes cr 
            INNER JOIN reportes r ON cr.id = r.carpeta_id 
            GROUP BY cr.id HAVING COUNT(r.id) > 0 LIMIT 1";
    $result = $database->getConnection()->query($sql);
    
    if ($result->num_rows === 0) {
        echo "<p>No hay carpetas con reportes para probar</p>";
        return true;
    }
    
    $folderIdWithReports = $result->fetch_assoc()['id'];
    echo "<p>Testing deleteFolder($folderIdWithReports)...</p>";
    
    if ($reports->deleteFolder($folderIdWithReports)) {
        echo "<p>Error: Se permitió eliminar carpeta con reportes</p>";
        return false;
    }
    echo "<p>Correcto: No se permitió eliminar carpeta con reportes</p>";
    return true;
});

// PRUEBA DE LIMPIEZA FINAL
$runner->registerTest('limpieza_final', function() use (&$testData, $reports) {
    $cleaned = true;

    if ($testData['reportId'] !== null) {
        echo "<p>Limpiando reporte temporal {$testData['reportId']}...</p>";
        if (!$reports->deleteReport($testData['reportId'])) {
            echo "<p>No se pudo eliminar el reporte temporal</p>";
            $cleaned = false;
        }
        $testData['reportId'] = null;
    }

    if ($testData['folderId'] !== null) {
        echo "<p>Limpiando carpeta temporal {$testData['folderId']}...</p>";
        if (!$reports->deleteFolder($testData['folderId'])) {
            echo "<p>No se pudo eliminar la carpeta temporal</p>";
            $cleaned = false;
        }
        $testData['folderId'] = null;
    }

    return $cleaned;
});

// Redirección si se accede directamente
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}