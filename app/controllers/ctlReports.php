<?php

require_once __DIR__ . '/../models/Reports.php';

class ReportsController
{
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
            return [
                'status' => 'success',
                'data' => $reports
            ];
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
    public function renameFolder($folderId, $newName)
    {
        try {
            $success = $this->reportModel->renameFolder($folderId, $newName);

            if ($success) {
                return [
                    'status' => 'success',
                    'message' => 'Folder renamed successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Folder could not be renamed or name is unchanged.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error renaming folder: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Manejar la eliminación de una carpeta.
     *
     * @param int $folderId ID de la carpeta.
     * @return array Respuesta con el resultado de la eliminación.
     */
    public function deleteFolder($folderId)
    {
        try {
            $success = $this->reportModel->deleteFolder($folderId);

            if ($success) {
                return [
                    'status' => 'success',
                    'message' => 'Folder deleted successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Folder could not be deleted. It may contain reports.'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error deleting folder: ' . $e->getMessage()
            ];
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
