<?php

require_once '../../app/models/Reports.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

$runner->registerTest('getAllFolders', 
    function() {
        $reports = new Reports();
        echo "<p>Testing getAllFolders()...</p>";
        
        $allFolders = $reports->getAllFolders();
        if (is_array($allFolders)) {
            renderTable($allFolders);
            return true;
        } else {
            echo "<p>No se pudo obtener las carpetas como un array</p>";
            return false;
        }
    }, 
    []  // Sin parámetros
);

$runner->registerTest('getReportsByFolder', 
    function($folderId) {
        $reports = new Reports();
        echo "<p>Testing getReportsByFolder() para carpeta ID $folderId...</p>";
        
        $folderReports = $reports->getReportsByFolder($folderId);
        if (is_array($folderReports)) {
            renderTable($folderReports);
            return true;
        } else {
            echo "<p>No se pudo obtener los reportes como un array</p>";
            return false;
        }
    }, 
    [1]  // Valor predeterminado para folderId
);

$runner->registerTest('createFolder', 
    function($name, $description) {
        $reports = new Reports();
        echo "<p>Testing createFolder() con nombre: '$name', descripción: '$description'</p>";
        
        $result = $reports->createFolder($name, $description);
        if ($result !== false) {
            echo "<p>Se creó la carpeta correctamente con ID: $result</p>";
            return true;
        } else {
            echo "<p>No se pudo crear la carpeta</p>";
            return false;
        }
    }, 
    [
        "Carpeta de Prueba",     // nombre
        "Esta es una carpeta de prueba" // descripción
    ]
);

$runner->registerTest('renameFolder', 
    function($id, $newName) {
        $reports = new Reports();
        echo "<p>Testing renameFolder() para carpeta ID $id con nuevo nombre: '$newName'</p>";
        
        if ($reports->renameFolder($id, $newName)) {
            echo "<p>Se renombró la carpeta correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo renombrar la carpeta</p>";
            return false;
        }
    }, 
    [
        1,                      // id
        "Carpeta Renombrada"    // newName
    ]
);

$runner->registerTest('deleteFolder', 
    function($id) {
        $reports = new Reports();
        echo "<p>Testing deleteFolder() para carpeta ID $id</p>";
        
        if ($reports->deleteFolder($id)) {
            echo "<p>Se eliminó la carpeta correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar la carpeta (puede tener reportes asociados)</p>";
            return false;
        }
    }, 
    [
        2  // id de la carpeta a eliminar
    ]
);

$runner->registerTest('createReport', 
    function($name, $folderId, $description) {
        $reports = new Reports();
        echo "<p>Testing createReport() con nombre: '$name', carpeta ID: $folderId</p>";
        
        $result = $reports->createReport($name, $folderId, $description);
        if ($result !== false) {
            echo "<p>Se creó el reporte correctamente con ID: $result</p>";
            return true;
        } else {
            echo "<p>No se pudo crear el reporte</p>";
            return false;
        }
    }, 
    [
        "Reporte de Prueba",     // nombre
        1,                       // folderId
        "Este es un reporte de prueba" // descripción
    ]
);

$runner->registerTest('renameReport', 
    function($id, $newName) {
        $reports = new Reports();
        echo "<p>Testing renameReport() para reporte ID $id con nuevo nombre: '$newName'</p>";
        
        if ($reports->renameReport($id, $newName)) {
            echo "<p>Se renombró el reporte correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo renombrar el reporte</p>";
            return false;
        }
    }, 
    [
        1,                      // id
        "Reporte Renombrado"    // newName
    ]
);

$runner->registerTest('getInventoryDetails', 
    function($inventoryId) {
        $reports = new Reports();
        echo "<p>Testing getInventoryDetails() para inventario ID $inventoryId...</p>";
        
        $details = $reports->getInventoryDetails($inventoryId);
        if ($details !== false) {
            renderTable([$details]); // Convertir a array para usar renderTable
            return true;
        } else {
            echo "<p>No se pudo obtener los detalles del inventario</p>";
            return false;
        }
    }, 
    [1]  // inventoryId predeterminado
);

$runner->registerTest('getGoodsCountByInventory', 
    function($inventoryId) {
        $reports = new Reports();
        echo "<p>Testing getGoodsCountByInventory() para inventario ID $inventoryId...</p>";
        
        $goodsCount = $reports->getGoodsCountByInventory($inventoryId);
        if (is_array($goodsCount)) {
            renderTable($goodsCount);
            return true;
        } else {
            echo "<p>No se pudo obtener el conteo de bienes</p>";
            return false;
        }
    }, 
    [1]  // inventoryId predeterminado
);

$runner->registerTest('getSerialGoodsDetailsByInventory', 
    function($inventoryId) {
        $reports = new Reports();
        echo "<p>Testing getSerialGoodsDetailsByInventory() para inventario ID $inventoryId...</p>";
        
        $serialGoods = $reports->getSerialGoodsDetailsByInventory($inventoryId);
        if (is_array($serialGoods)) {
            renderTable($serialGoods);
            return true;
        } else {
            echo "<p>No se pudo obtener los detalles de bienes seriales</p>";
            return false;
        }
    }, 
    [1]  // inventoryId predeterminado
);

// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}