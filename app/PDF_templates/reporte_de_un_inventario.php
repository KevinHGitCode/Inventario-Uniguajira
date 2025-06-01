<?php
// Incluir el autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Si la clase no se encuentra automáticamente, incluirla directamente
require_once __DIR__ . '/../helpers/PdfGenerator.php';
require_once __DIR__ . '/../models/ReportsPDF.php';

// Usar el namespace correcto
use app\PdfGenerator;

/**
 * Clase para generar reportes PDF de un inventario específico
 */
class InventoryReportGenerator {
    private $reportsPDF;
    private $pdfGenerator;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->reportsPDF = new ReportsPDF();
        $this->pdfGenerator = new PdfGenerator();
    }
    
    /**
     * Obtiene los artículos de un inventario específico
     * 
     * @param int $inventoryId ID del inventario
     * @return array Lista de artículos del inventario
     */
    public function getInventoryItems($inventoryId) {
        return $this->reportsPDF->getInventoryWithGoods($inventoryId);
    }
    
    /**
     * Obtiene información general del inventario
     * 
     * @param int $inventoryId ID del inventario
     * @return array Información del inventario
     */
    public function getInventoryInfo($inventoryId) {
        return $this->reportsPDF->getInfoInventory($inventoryId);
    }
    
    /**
     * Genera el HTML para el reporte del inventario
     * 
     * @param int $inventoryId ID del inventario
     * @return string HTML del reporte
     */
    public function generateInventoryReportHtml($inventoryId) {
        $dataGoodsInventory = $this->getInventoryItems($inventoryId);
        $info = $this->getInventoryInfo($inventoryId);
        date_default_timezone_set('America/Bogota');
        $date = date('d/m/Y');
        
        $html = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Reporte de Inventario ' . htmlspecialchars($info['nombre']) . '</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 10px;
                    }
                    .header h1 {
                        margin: 0;
                        color: #333;
                    }
                    .info {
                        text-align: center;
                        margin-bottom: 20px;
                        color: #555;
                    }
                    .footer {
                        text-align: center;
                        font-size: 10px;
                        margin-top: 30px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="logo">
                        <img src="http://' . $_SERVER['HTTP_HOST'] . '/Inventario-Uniguajira/assets/images/logoUniguajira.png" width="300">
                    </div>  
                    <h1>Reporte de Inventario: ' . htmlspecialchars($info['nombre']) . '</h1>
                    <p>Fecha de generación: ' . $date . '</p>
                </div>
                <div class="info">
                    <p><strong>Estado de conservación del inventario:</strong> ' . htmlspecialchars($info['estado_conservacion']) . '</p>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Bien</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>';

                    foreach ($dataGoodsInventory as $good) {
                        $html .= '
                            <tr>
                                <td>' . htmlspecialchars($good['bien']) . '</td>
                                <td>' . htmlspecialchars($good['tipo']) . '</td>
                                <td>' . htmlspecialchars($good['cantidad']) . '</td>
                            </tr>';
                    }

                    $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Este documento es un reporte generado automáticamente por el sistema de Inventario Uniguajira sede Maicao.</p>
                </div>
            </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Genera y muestra el PDF del reporte de inventario
     * 
     * @param int $inventoryId ID del inventario
     * @param string $filename Nombre del archivo PDF
     */
    public function generateAndStreamReport($inventoryId, $filename = 'reporte_inventario.pdf') {
        $reportHtml = $this->generateInventoryReportHtml($inventoryId);
        $this->pdfGenerator->generateAndStreamPdf($reportHtml, $filename);
    }
    
    /**
     * Genera y guarda el PDF del reporte de inventario
     * 
     * @param int $inventoryId ID del inventario
     * @param string $outputPath Ruta donde guardar el archivo PDF
     * @return string Ruta completa donde se guardó el archivo
     */
    public function generateAndSaveReport($inventoryId, $outputPath = null) {
        if (!$outputPath) {
            $inventoryInfo = $this->getInventoryInfo($inventoryId);
            $safeInventoryName = preg_replace('/[^a-z0-9]/i', '_', $inventoryInfo['nombre']);
            $outputPath = 'assets/storage/pdfs/reporte_inventario_salon_'. $safeInventoryName . '_' . date('Y-m-d') . '.pdf';
        }
        
        $reportHtml = $this->generateInventoryReportHtml($inventoryId);
        $this->pdfGenerator->generateAndSavePdf($reportHtml, $outputPath);
        return $outputPath;
    }
}

// Script principal para generar el reporte (si se usa directamente este archivo)
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    // Crear instancia del generador
    $reportGenerator = new InventoryReportGenerator();
    
    // ID del inventario (podría venir de un parámetro GET)
    $inventoryId = isset($_GET['id']) ? (int)$_GET['id'] : 37; // Valor por defecto como en el código original
    
    // Generar y mostrar el PDF
    // $reportGenerator->generateAndStreamReport($inventoryId);
    
    // Alternativamente, para guardar el PDF en un archivo:
    $outputPath = $reportGenerator->generateAndSaveReport($inventoryId);
    // echo "PDF generado y guardado en: " . $outputPath;
}
