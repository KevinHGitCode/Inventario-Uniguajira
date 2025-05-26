<?php
// Incluir el autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Si la clase no se encuentra automáticamente, incluirla directamente
require_once __DIR__ . '/../helpers/PdfGenerator.php';
require_once __DIR__ . '/../models/ReportsPDF.php';

// Usar el namespace correcto
use app\PdfGenerator;

/**
 * Clase para generar reportes PDF de todos los bienes
 */
class AllGoodsReportGenerator {
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
     * Obtiene todos los bienes
     * 
     * @return array Lista de todos los bienes
     */
    public function getAllGoods() {
        return $this->reportsPDF->getAllGoods();
    }
    
    /**
     * Genera el HTML para el reporte de todos los bienes
     * 
     * @return string HTML del reporte
     */
    public function generateAllGoodsReportHtml() {
        $dataGoodsInventory = $this->getAllGoods();
        date_default_timezone_set('America/Bogota');
        $date = date('d/m/Y');
        
        $html = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Reporte de todos los bienes</title>
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
                        margin-bottom: 20px;
                    }
                    .header h1 {
                        margin: 0;
                        color: #333;
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
                    <h1>Reporte de Bienes Uniguajira Maicao</h1>
                    <p>Fecha de generación: ' . $date . '</p>
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
                                <td>' . htmlspecialchars($good['tipo_bien']) . '</td>
                                <td>' . htmlspecialchars($good['total_cantidad']) . '</td>
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
     * Genera y muestra el PDF del reporte de todos los bienes
     * 
     * @param string $filename Nombre del archivo PDF
     */
    public function generateAndStreamReport($filename = 'reporte_bienes.pdf') {
        $reportHtml = $this->generateAllGoodsReportHtml();
        $this->pdfGenerator->generateAndStreamPdf($reportHtml, $filename);
    }
    
    /**
     * Genera y guarda el PDF del reporte de todos los bienes
     * 
     * @param string $outputPath Ruta donde guardar el archivo PDF
     * @return string Ruta completa donde se guardó el archivo
     */
    public function generateAndSaveReport($outputPath = null) {
        if (!$outputPath) {
            $outputPath = 'assets/storage/pdfs/reporte_bienes_' . date('Y-m-d') . '.pdf';
        }
        
        $reportHtml = $this->generateAllGoodsReportHtml();
        $this->pdfGenerator->generateAndSavePdf($reportHtml, $outputPath);
        return $outputPath;
    }
}

// Script principal para generar el reporte (si se usa directamente este archivo)
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    // Crear instancia del generador
    $reportGenerator = new AllGoodsReportGenerator();
    
    // Generar y mostrar el PDF
    // $reportGenerator->generateAndStreamReport();
    
    // Alternativamente, para guardar el PDF en un archivo:
    $outputPath = $reportGenerator->generateAndSaveReport();
    // echo "PDF generado y guardado en: " . $outputPath;
}