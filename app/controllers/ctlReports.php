<?php

require_once __DIR__ . '/sessionCheck.php';
require_once __DIR__ . '/../models/Reports.php';
require_once 'app/helpers/validate-http.php';
require_once 'app/helpers/CacheManager.php'; // Include our new cache manager

class ctlReports
{
    private $cache;
    private $reportModel;

    public function __construct()
    {
        // Instancia del modelo Reports
        $this->reportModel = new Reports();
    }

    /**
     * Manejar la obtención de todas las carpetas de reportes.
     *
     * @return array Respuesta con las carpetas de reportes.
     */
    public function getAllFolders()
    {
        try {
            $folders = $this->reportModel->getAllFolders();
            return [
                'status' => 'success',
                'data' => $folders
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error fetching folders: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Manejar la obtención de reportes en una carpeta específica.
     *
     * @param int $folderId ID de la carpeta.
     * @return array Respuesta con los reportes de la carpeta.
     */
    public function getReportsByFolder($folderId)
    {
        try {
            $reports = $this->reportModel->getReportsByFolder($folderId);

            $dataIdFolder = $reports;
            require 'app/views/reports/reports.php';

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error fetching reports: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Manejar la creación de una nueva carpeta.
     *
     * @param string $name Nombre de la carpeta.
     * @param string $description Descripción de la carpeta.
     * @return array Respuesta con el ID de la carpeta creada o un mensaje de error.
     */
    public function createFolder(){
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['nombreCarpeta'])) {
            return;
        }

        $nombre = trim($_POST['nombreCarpeta']);

        $resultado = $this->reportModel->createFolder($nombre);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Carpeta creada exitosamente.',
                'bienCarpeta' => $resultado
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la carpeta. El nombre podría estar duplicado.'
            ]);
        }
    }

    /**
     * Manejar el renombrado de una carpeta.
     *
     * @param int $folderId ID de la carpeta.
     * @param string $newName Nuevo nombre de la carpeta.
     * @return array Respuesta con el resultado del renombrado.
     */
    public function renameFolder(){
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        $id = $_POST['folder_id'] ?? null;
        $newName = $_POST['nombre'] ?? '';
    
        if (empty($id) || empty($newName)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID y nuevo nombre son requeridos.']);
            return;
        }
    
        $resultado = $this->reportModel->renameFolder($id, $newName);
    
        if ($resultado) {
            // // Invalidate related caches
            // $this->cache->remove("all_groups");
            // $this->cache->invalidateEntity('group_' . $id);
            
            echo json_encode(['success' => true, 'message' => 'Carpeta actualizada exitosamente.']);
            return;
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la carpeta. Puede que la carpeta no exista o el nombre sea igual al anterior.']);
        }
    }

    /**
     * Manejar la eliminación de una carpeta.
     *
     * @param int $folderId ID de la carpeta.
     * @return array Respuesta con el resultado de la eliminación.
     */
    public function deleteFolder($folderId){
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        if (empty($folderId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }
    
        $resultado = $this->reportModel->deleteFolder($folderId);
    
        if ($resultado) {
            // Invalidate related caches
            // $this->cache->remove("all_groups");
            // $this->cache->invalidateEntity('group_' . $id);
            
            echo json_encode(['success' => true, 'message' => 'Carpeta eliminada exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la carpeta. Puede que no exista o tenga reportes asociados.']);
        }
    }

    /**
     * Manejar la creación de un nuevo reporte.
     *
     * @param string $name Nombre del reporte.
     * @param int $folderId ID de la carpeta.
     * @param string $description Descripción del reporte.
     * @return array Respuesta con el ID del reporte creado o un mensaje de error.
     */
    public function createReport(){
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['folder_id', 'nombreReporte' , 'tipoReporte'])) {
            return;
        }

        $folderId = $_POST['folder_id'];
        $nombreReporte = $_POST['nombreReporte'];
        $tipoReporte = $_POST['tipoReporte'];

        $success = false;

        if ($tipoReporte == 'inventario') {  // tipo cantidad
            $InventoryId = $_POST['inventarioSeleccionado'] ?? null;
            require_once 'app/PDF_templates/reporte_de_un_inventario.php';
            $reportGenerator = new InventoryReportGenerator();
            $outputPath = $reportGenerator->generateAndSaveReport($InventoryId);
            
        } else if ($tipoReporte == 'grupo') {  // tipo serial
            $GrupoId = $_POST['grupoSeleccionado'] ?? null;
            require_once 'app/PDF_templates/reporte_de_un_grupo.php';
            $reportGenerator = new InventoryGroupReportGenerator();
            $outputPath = $reportGenerator->generateAndSaveGroupReport($GrupoId);
            
        } else if ($tipoReporte == 'allInventories') {  // tipo serial
            require_once 'app/PDF_templates/reporte_de_todos_los_inventarios.php';
            $reportGenerator = new AllGroupsInventoryReportGenerator();
            $outputPath = $reportGenerator->generateAndSaveAllGroupsReport();
            
        } else if ($tipoReporte == 'goods') {  // tipo serial
            require_once 'app/PDF_templates/reporte_de_bienes.php';
            $reportGenerator = new AllGoodsReportGenerator();
            $outputPath = $reportGenerator->generateAndSaveReport();
            
        } else if ($tipoReporte == 'serial') {  // tipo serial
            require_once 'app/PDF_templates/reporte_de_equipos.php';
            $reportGenerator = new SerialGoodsReportGenerator();
            $outputPath = $reportGenerator->generateAndSaveReport();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipo de reporte no soportado."' . $tipoReporte . '"']);
            return;
        }

        $success = $this->reportModel->createReport($nombreReporte, $folderId, $outputPath);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Reporte generado exitosamente.']);
        }
    }

    /**
     * Manejar el renombrado de un reporte.
     *
     * @param int $reportId ID del reporte.
     * @param string $newName Nuevo nombre del reporte.
     * @return array Respuesta con el resultado del renombrado.
     */
    public function renameReport(){
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        $id = $_POST['report_id'] ?? null;
        $newName = $_POST['nombre'] ?? '';
    
        if (empty($id) || empty($newName)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID y nuevo nombre son requeridos.']);
            return;
        }
    
        $resultado = $this->reportModel->renameReport($id, $newName);
    
        if ($resultado) {
            // // Invalidate related caches
            // $this->cache->remove("all_groups");
            // $this->cache->invalidateEntity('group_' . $id);
            
            echo json_encode(['success' => true, 'message' => 'Reporte actualizado exitosamente.']);
            return;
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el reporte. Puede que el reporte no exista o el nombre sea igual al anterior.']);
        }
    }

    /**
     * Manejar la eliminación de un reporte.
     *
     * @param int $reportId ID del reporte.
     * @return void
     */
    public function deleteReport($reportId){
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        if (empty($reportId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }
    
        $resultado = $this->reportModel->deleteReport($reportId);
    
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Reporte eliminado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el reporte. Puede que no exista.']);
        }
    }

    /**
     * Función para descargar un reporte PDF
     * 
     * @param int $reportId ID del reporte a descargar
     * @param object $reportsModel Instancia del modelo de reportes
     * @return bool true si la descarga fue exitosa, false en caso contrario
     */
    public function downloadReport() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['report_id'])) {
            return;
        }
        
        $reportId = $_POST['report_id'];
        
        try {
            // Validar el ID del reporte
            if (!is_numeric($reportId) || $reportId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de reporte inválido']);
                return;
            }
            
            // Obtener los detalles del reporte
            $report = $this->reportModel->getReportById($reportId);
            
            if (!$report) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Reporte no encontrado']);
                return;
            }
            
            // Verificar que el archivo existe
            $filePath = $report['ruta_reporte'];
            
            // Si la ruta es relativa, construir la ruta completa
            if (!file_exists($filePath)) {
                // Intentar con diferentes rutas base
                $possiblePaths = [
                    $_SERVER['DOCUMENT_ROOT'] . '/' . $filePath,
                    dirname(__DIR__, 2) . '/' . $filePath, // Retroceder 2 niveles
                    dirname(__DIR__) . '/' . $filePath,     // Retroceder 1 nivel
                    '../' . $filePath,
                    './' . $filePath,
                    $filePath
                ];
                
                $fullPath = null;
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $fullPath = $path;
                        break;
                    }
                }
                
                if (!$fullPath) {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Archivo no encontrado en el servidor']);
                    return;
                }
                
                $filePath = $fullPath;
            }
            
            // Verificar que es un PDF
            $fileInfo = pathinfo($filePath);
            if (strtolower($fileInfo['extension']) !== 'pdf') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'El archivo no es un PDF válido']);
                return;
            }
            
            // Preparar el nombre del archivo para descarga
            $downloadName = $this->sanitizeFileName($report['nombre']) . '.pdf';
            
            // Cambiar headers para descarga de archivo
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $downloadName . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Expires: 0');
            
            // Limpiar el buffer de salida
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Enviar el archivo
            readfile($filePath);
            exit;
            
        } catch (Exception $e) {
            error_log("Error en descarga de reporte ID {$reportId}: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
            return;
        }
    }

    /**
     * Función auxiliar para sanitizar nombre de archivo
     * 
     * @param string $filename Nombre original del archivo
     * @return string Nombre sanitizado
     */
    private function sanitizeFileName($filename) {
        // Remover caracteres especiales y espacios múltiples
        $filename = preg_replace('/[^a-zA-Z0-9\s\-_.]/', '', $filename);
        $filename = preg_replace('/\s+/', '_', $filename);
        $filename = trim($filename, '_');
        
        // Si queda vacío, usar nombre por defecto
        if (empty($filename)) {
            $filename = 'reporte_' . date('Y-m-d_H-i-s');
        }
        
        return $filename;
    }
}
