<?php
namespace App;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    private $dompdf;
    private $options;

    /**
     * Constructor: inicializa DOMPDF con opciones predeterminadas
     */
    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('isRemoteEnabled', true); // Permite carga de imágenes remotas
        $this->options->set('isFontSubsettingEnabled', true);
        $this->options->set('defaultFont', 'Helvetica');
        
        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * Genera un PDF a partir de HTML y lo devuelve como string
     * 
     * @param string $html Contenido HTML a convertir
     * @param string $paperSize Tamaño del papel (A4, letter, etc)
     * @param string $orientation Orientación (portrait o landscape)
     * @return string Contenido del PDF generado
     */
    public function generatePdf($html, $paperSize = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paperSize, $orientation);
        $this->dompdf->render();
        
        return $this->dompdf->output();
    }

    /**
     * Genera un PDF y lo guarda en un archivo
     * 
     * @param string $html Contenido HTML a convertir
     * @param string $outputPath Ruta donde guardar el archivo PDF
     * @param string $paperSize Tamaño del papel (A4, letter, etc)
     * @param string $orientation Orientación (portrait o landscape)
     * @return bool True si se guardó correctamente
     */
    public function generateAndSavePdf($html, $outputPath, $paperSize = 'A4', $orientation = 'portrait')
    {
        $pdfContent = $this->generatePdf($html, $paperSize, $orientation);
        
        // Asegurarse de que el directorio existe
        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return file_put_contents($outputPath, $pdfContent) !== false;
    }

    /**
     * Genera un PDF y lo envía al navegador para descarga
     * 
     * @param string $html Contenido HTML a convertir
     * @param string $filename Nombre del archivo para descarga
     * @param string $paperSize Tamaño del papel (A4, letter, etc)
     * @param string $orientation Orientación (portrait o landscape)
     */
    public function generateAndStreamPdf($html, $filename = 'document.pdf', $paperSize = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paperSize, $orientation);
        $this->dompdf->render();
        
        $this->dompdf->stream($filename, [
            'Attachment' => true // true para forzar descarga, false para mostrar en navegador
        ]);
    }

    /**
     * Configura opciones adicionales para DOMPDF
     * 
     * @param array $options Opciones a configurar
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            $this->options->set($key, $value);
        }
        
        // Recrear el objeto DOMPDF con las nuevas opciones
        $this->dompdf = new Dompdf($this->options);
    }

    
}