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
    $goodsInventory = new ReportsPDF(); // Instantiate GoodsInventory here
    $dataGoodsInventory = $goodsInventory->getInventoryWithGoods(37);
    return $dataGoodsInventory;
}

function getInfo() {
    $infoInventory = new ReportsPDF(); // Instantiate GoodsInventory here
    $info = $infoInventory->getInfoInventory(37);
    return $info;
}


/**
 * Generar el HTML para el reporte de inventario
 */
function generateInventoryReportHtml() {
    $dataGoodsInventory = getInventoryItems();
    $info = getInfo();
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

// Generar el HTML para el reporte
$reportHtml = generateInventoryReportHtml();

// Inicializar el generador de PDF
$pdfGenerator = new PdfGenerator();

// Generar y mostrar el PDF
// Usar esta línea para mostrar o descargar directamente desde el navegador:
$pdfGenerator->generateAndStreamPdf($reportHtml, 'reporte_inventario.pdf');

// Alternativamente, para guardar el PDF en un archivo:
// $outputPath = __DIR__ . '/../pdfs/reporte_inventario_' . date('Y-m-d') . '.pdf';
// $pdfGenerator->generateAndSavePdf($reportHtml, $outputPath);
// echo "PDF generado y guardado en: " . $outputPath;