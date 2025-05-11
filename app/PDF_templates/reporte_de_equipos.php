<?php
// Incluir el autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Si la clase no se encuentra automáticamente, incluirla directamente
require_once __DIR__ . '/../helpers/PdfGenerator.php';

require __DIR__ . '/../models/ReportsPDF.php';

// Usar el namespace correcto
use app\PdfGenerator;

/**
 * Función para obtener datos del inventario (esto debería adaptarse a tu sistema actual)
 * Esta es solo una función de ejemplo que deberías reemplazar con tu lógica real
 */
function getInventoryItems() {
    $goodsSerial = new ReportsPDF();
    $dataGoodsSerial = $goodsSerial->getAllSerialGoods(); // Get all goods from the model
    return $dataGoodsSerial;
}

/**
 * Generar el HTML para el reporte de inventario
 */
function generateInventoryReportHtml() {
    $dataGoodsSerial = getInventoryItems();
    date_default_timezone_set('America/Bogota');
    $date = date('d/m/Y');
    
    // Comenzar a construir el HTML
    $html = '
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de todos los Equipos</title>
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
                <h1>Reporte de Equipos Uniguajira Maicao</h1>
                <p>Fecha de generación: ' . $date . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Descripcion</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Serial</th>
                        <th>Estado</th>
                        <th>Condicion</th>
                    </tr>
                </thead>
                <tbody>';

                // Agregar una fila por cada bien en el inventario
                foreach ($dataGoodsSerial as $goodSerial) {
                    $html .= '
                        <tr>
                            <td>' . htmlspecialchars($goodSerial['bien']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['descripcion']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['marca']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['modelo']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['serial']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['estado']) . '</td>
                            <td>' . htmlspecialchars($goodSerial['condiciones_tecnicas']) . '</td>

                        </tr>';
                }

                // Cerrar la tabla y el documento HTML
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

// Generar el HTML para el reporte
$reportHtml = generateInventoryReportHtml();

// Inicializar el generador de PDF
$pdfGenerator = new PdfGenerator();

// Generar y mostrar el PDF
// Usar esta línea para mostrar o descargar directamente desde el navegador:
$pdfGenerator->generateAndStreamPdf($reportHtml, 'reporte_equipos.pdf');

// Alternativamente, para guardar el PDF en un archivo:
// $outputPath = __DIR__ . '/../pdfs/reporte_inventario_' . date('Y-m-d') . '.pdf';
// $pdfGenerator->generateAndSavePdf($reportHtml, $outputPath);
// echo "PDF generado y guardado en: " . $outputPath;