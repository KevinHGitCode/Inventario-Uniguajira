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
    public function createFolder($name, $description = '')
    {
        try {
            $folderId = $this->reportModel->createFolder($name, $description);

            if ($folderId) {
                return [
                    'status' => 'success',
                    'message' => 'Folder created successfully.',
                    'folderId' => $folderId
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Folder name already exists or an error occurred.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error creating folder: ' . $e->getMessage()
            ];
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
    public function createReport($name, $folderId, $description = '')
    {
        try {
            $reportId = $this->reportModel->createReport($name, $folderId, $description);

            if ($reportId) {
                return [
                    'status' => 'success',
                    'message' => 'Report created successfully.',
                    'reportId' => $reportId
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Report could not be created. Folder might not exist.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error creating report: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Manejar el renombrado de un reporte.
     *
     * @param int $reportId ID del reporte.
     * @param string $newName Nuevo nombre del reporte.
     * @return array Respuesta con el resultado del renombrado.
     */
    public function renameReport($reportId, $newName)
    {
        try {
            $success = $this->reportModel->renameReport($reportId, $newName);

            if ($success) {
                return [
                    'status' => 'success',
                    'message' => 'Report renamed successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Report could not be renamed or name is unchanged.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error renaming report: ' . $e->getMessage()
            ];
        }
    }
}
