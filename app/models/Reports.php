<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Reports
 * 
 * Esta clase maneja las operaciones relacionadas con los reportes y carpetas de reportes en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Reports extends Database {

    /**
     * Constructor de la clase Reports.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtener todas las carpetas de reportes.
     * 
     * @return array Arreglo asociativo con todas las carpetas y su número de reportes.
     */
    public function getAllFolders() {
        $stmt = $this->connection->prepare("
            SELECT 
                c.id as id, 
                c.nombre as nombre,
                c.descripcion as descripcion,
                c.fecha_creacion as fecha_creacion,
                COUNT(r.id) AS total_reportes
            FROM carpetas_reportes c
            LEFT JOIN reportes r ON c.id = r.carpeta_id
            GROUP BY c.id, c.nombre, c.descripcion, c.fecha_creacion
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener reportes de una carpeta específica.
     * 
     * @param int $folderId ID de la carpeta.
     * @return array Arreglo asociativo con los reportes de la carpeta.
     */
    public function getReportsByFolder($folderId) {
        $stmt = $this->connection->prepare("
            SELECT 
                r.id as id, 
                r.nombre as nombre, 
                r.fecha_creacion as fecha_creacion,
                r.descripcion as descripcion
            FROM reportes r
            WHERE r.carpeta_id = ?
            ORDER BY r.fecha_creacion DESC
        ");
        $stmt->bind_param("i", $folderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crear una nueva carpeta.
     * 
     * @param string $name Nombre de la carpeta.
     * @param string $description Descripción de la carpeta (opcional).
     * @return int|false ID de la carpeta creada si fue exitoso, False si el nombre ya existe o hubo un error.
     */
    public function createFolder($name, $description = '') {
        // Verificar si el nombre ya existe
        $checkStmt = $this->connection->prepare("SELECT id FROM carpetas_reportes WHERE nombre = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            return false; // El nombre ya existe
        }

        // Insertar la nueva carpeta
        $stmt = $this->connection->prepare("INSERT INTO carpetas_reportes (nombre, descripcion, fecha_creacion) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Retornar el ID de la carpeta recién creada
        }

        return false;
    }

    /**
     * Renombrar una carpeta.
     * 
     * @param int $id ID de la carpeta.
     * @param string $newName Nuevo nombre de la carpeta.
     * @return bool True si la carpeta fue actualizada exitosamente, False en caso contrario.
     */
    public function renameFolder($id, $newName) {
        // Verificar si la carpeta existe
        $checkStmt = $this->connection->prepare("SELECT nombre FROM carpetas_reportes WHERE id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows === 0) {
            return false; // La carpeta no existe
        }
    
        $row = $result->fetch_assoc();
        if ($row['nombre'] === $newName) {
            return false; // El nombre es el mismo, no hay cambios
        }
    
        // Actualizar el nombre de la carpeta
        $stmt = $this->connection->prepare("UPDATE carpetas_reportes SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar una carpeta si no tiene reportes asociados.
     * 
     * @param int $id ID de la carpeta.
     * @return bool True si la carpeta fue eliminada exitosamente, False si no se pudo eliminar.
     */
    public function deleteFolder($id) {
        // Verificar si la carpeta existe
        $checkFolderStmt = $this->connection->prepare("SELECT id FROM carpetas_reportes WHERE id = ?");
        $checkFolderStmt->bind_param("i", $id);
        $checkFolderStmt->execute();
        $folderResult = $checkFolderStmt->get_result();
    
        if ($folderResult->num_rows === 0) {
            return false; // La carpeta no existe
        }

        // Verificar si la carpeta tiene reportes asociados
        $checkStmt = $this->connection->prepare("SELECT id FROM reportes WHERE carpeta_id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows > 0) {
            return false; // No se puede eliminar porque tiene reportes asociados
        }
    
        // Eliminar la carpeta
        $stmt = $this->connection->prepare("DELETE FROM carpetas_reportes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    /**
     * Crear un nuevo reporte.
     * 
     * @param string $name Nombre del reporte.
     * @param int $folderId ID de la carpeta a la que pertenece.
     * @param string $description Descripción del reporte (opcional).
     * @return int|false ID del reporte creado si fue exitoso, False si hubo un error.
     */
    public function createReport($name, $folderId, $description = '') {
        // Verificar si la carpeta existe
        $checkStmt = $this->connection->prepare("SELECT id FROM carpetas_reportes WHERE id = ?");
        $checkStmt->bind_param("i", $folderId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            return false; // La carpeta no existe
        }

        // Insertar el nuevo reporte
        $stmt = $this->connection->prepare("INSERT INTO reportes (nombre, carpeta_id, descripcion, fecha_creacion) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sis", $name, $folderId, $description);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Retornar el ID del reporte recién creado
        }

        return false;
    }

    /**
     * Renombrar un reporte.
     * 
     * @param int $id ID del reporte.
     * @param string $newName Nuevo nombre del reporte.
     * @return bool True si el reporte fue actualizado exitosamente, False en caso contrario.
     */
    public function renameReport($id, $newName) {
        // Verificar si el reporte existe
        $checkStmt = $this->connection->prepare("SELECT nombre FROM reportes WHERE id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows === 0) {
            return false; // El reporte no existe
        }
    
        $row = $result->fetch_assoc();
        if ($row['nombre'] === $newName) {
            return false; // El nombre es el mismo, no hay cambios
        }
    
        // Actualizar el nombre del reporte
        $stmt = $this->connection->prepare("UPDATE reportes SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    /**
     * Obtener detalles de un inventario específico.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array|false Arreglo asociativo con los detalles del inventario o False si no existe.
     */
    public function getInventoryDetails($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                i.id,
                i.nombre,
                i.responsable,
                i.estado_conservacion,
                i.fecha_modificacion,
                g.nombre as grupo_nombre
            FROM inventarios i
            LEFT JOIN grupos g ON i.grupo_id = g.id
            WHERE i.id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        return $result->fetch_assoc();
    }

    /**
     * Obtener el conteo de bienes de un inventario específico.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes y sus cantidades.
     */
    public function getGoodsCountByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                b.id,
                b.nombre,
                b.tipo,
                COALESCE(bc.cantidad, 0) as cantidad
            FROM bienes_inventarios bi
            JOIN bienes b ON bi.bien_id = b.id
            LEFT JOIN bienes_cantidad bc ON bi.id = bc.bien_inventario_id
            WHERE bi.inventario_id = ?
            ORDER BY b.nombre
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener detalles de los bienes tipo serial de un inventario específico.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los detalles de los bienes tipo serial.
     */
    public function getSerialGoodsDetailsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                b.id,
                b.nombre,
                be.descripcion,
                be.marca,
                be.modelo,
                be.serial,
                be.estado,
                be.color,
                be.condiciones_tecnicas,
                be.fecha_ingreso,
                be.fecha_salida
            FROM bienes_inventarios bi
            JOIN bienes b ON bi.bien_id = b.id
            JOIN bienes_equipos be ON bi.id = be.bien_inventario_id
            WHERE bi.inventario_id = ? AND b.tipo = 'serial'
            ORDER BY b.nombre, be.serial
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>  