<?php
// Incluir el autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Si la clase no se encuentra automáticamente, incluirla directamente
require_once __DIR__ . '/../helpers/PdfGenerator.php';

require __DIR__ . '/../models/ReportsPDF.php';

// Usar el namespace correcto
use app\PdfGenerator;

// ... resto de tu código ...
// Aquí deberías incluir tu conexión a la base de datos o cualquier otra dependencia necesaria
// require_once __DIR__ . '/../config/database.php';

/**
 * Función para obtener datos del inventario (esto debería adaptarse a tu sistema actual)
 * Esta es solo una función de ejemplo que deberías reemplazar con tu lógica real
 */
function getInventoryItems() {
    // En un caso real, esta función consultaría tu base de datos
    // Ejemplo: $items = $db->query("SELECT * FROM inventario ORDER BY fecha_registro DESC LIMIT 100");
    
    $goodsSerial = new ReportsPDF();
    $dataGoodsSerial = $goodsSerial->getAllSerialGoods(); // Get all goods from the model
    return $dataGoodsSerial;
    
    // Este es un ejemplo de datos:
    // return [
    //     ['id' => 1, 'nombre' => 'Monitor Dell 24"', 'cantidad' => 15, 'ubicacion' => 'Laboratorio A', 'estado' => 'Activo'],
    //     ['id' => 2, 'nombre' => 'Teclado Logitech K120', 'cantidad' => 30, 'ubicacion' => 'Almacén Central', 'estado' => 'Activo'],
    //     ['id' => 3, 'nombre' => 'Mouse Óptico HP', 'cantidad' => 25, 'ubicacion' => 'Almacén Central', 'estado' => 'Activo'],
    //     ['id' => 4, 'nombre' => 'Proyector Epson PowerLite', 'cantidad' => 8, 'ubicacion' => 'Sala de Conferencias', 'estado' => 'Mantenimiento'],
    //     ['id' => 5, 'nombre' => 'Laptop HP Elitebook', 'cantidad' => 12, 'ubicacion' => 'Oficina Administrativa', 'estado' => 'Activo'],
    // ];
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