<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Reports
 * 
 * Esta clase maneja las operaciones relacionadas con los reportes y carpetas de reportes en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class ReportsPDF {
    protected $connection;

    /**
     * Constructor de la clase Goods.
     * Obtiene la conexión a la base de datos desde la instancia Singleton de Database.
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    /**
     * Obtener la lista de todos los bienes desde la vista del sistema.
     * 
     * @return array Lista de bienes con los campos 'bien_id', 'bien', 'tipo_bien', 'total_cantidad' e 'imagen'.
     */
    public function getAllGoods() {
        $stmt = $this->connection->prepare("
            SELECT 
                bien_id, 
                bien,
                tipo_bien, 
                total_cantidad, 
                imagen 
            FROM vista_total_bienes_sistema
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener todos los bienes de un inventario específico junto con su estado de conservación.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes del inventario y su estado de conservación.
     */
    public function getInventoryWithGoods($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                bien,
                tipo,
                cantidad
            FROM 
                vista_cantidades_bienes_inventario
            WHERE
                inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener el nombre y el estado de conservación de un inventario específico.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con el nombre y el estado de conservación.
     */
    public function getInfoInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT
                nombre,
                estado_conservacion
            FROM 
                inventarios
            WHERE
                id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return [
            'nombre' => $result['nombre'],
            'estado_conservacion' => $result['estado_conservacion']
        ];
    }



    /**
     * Obtiene todos los bienes de tipo serial (equipos) con su información completa
     * 
     * @return array Array asociativo con todos los bienes de tipo serial y sus detalles
     */
    public function getAllSerialGoods() {
        $stmt = $this->connection->prepare("
            SELECT 
                vbsi.bien,
                vbsi.descripcion,
                vbsi.marca,
                vbsi.modelo,
                vbsi.serial,
                vbsi.estado,
                vbsi.condiciones_tecnicas,
                vbsi.bienes_inventarios_id,
                bi.inventario_id,
                i.nombre as nombre_inventario
            FROM 
                vista_bienes_serial_inventario vbsi
            LEFT JOIN 
                bienes_inventarios bi ON vbsi.bienes_inventarios_id = bi.id
            LEFT JOIN 
                inventarios i ON bi.inventario_id = i.id
        ");
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // /**
    //  * Obtener todos los bienes de un inventario específico.
    //  * 
    //  * @param int $inventoryId ID del inventario.
    //  * @return array Arreglo asociativo con los bienes del inventario.
    //  */
    // public function getAllGoodsByInventory($inventoryId) {
    //     $stmt = $this->connection->prepare("
    //         SELECT 
    //             b.id,
    //             b.nombre as bien,
    //             b.tipo,
    //             bi.cantidad,
    //         FROM bienes_inventarios bi
    //         JOIN bienes b ON bi.bien_id = b.id
    //         WHERE bi.inventario_id = ?
    //         ORDER BY b.nombre
    //     ");
    //     $stmt->bind_param("i", $inventoryId);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // }

    /**
     * Obtiene información de un grupo específico
     * 
     * @param int $groupId ID del grupo
     * @return array Información del grupo
     */
    public function getInfoGroup($groupId) {
        $stmt = $this->connection->prepare("
            SELECT 
                id,
                nombre
            FROM 
                grupos 
            WHERE 
                id = ?
        ");
        
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return [
                'id' => $groupId,
                'nombre' => 'Grupo no encontrado',
                'estado_conservacion' => 'N/A'
            ];
        }
    }
    
    /**
     * Obtiene los inventarios relacionados con un grupo específico
     * 
     * @param int $groupId ID del grupo
     * @return array Lista de inventarios
     */
    public function getInventoriesByGroup($groupId) {
        $stmt = $this->connection->prepare("
            SELECT 
                id,
                nombre,
                estado_conservacion,
                fecha_modificacion,
                grupo_id
            FROM 
                inventarios 
            WHERE 
                grupo_id = ?
            ORDER BY 
                id
        ");
        
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene todos los grupos
     * 
     * @return array Lista de todos los grupos
     */
    public function getAllGroups() {
        $stmt = $this->connection->prepare("
            SELECT 
                id,
                nombre 
            FROM 
                grupos
            ORDER BY 
                nombre
        ");
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene detalles específicos de un bien por su ID
     * 
     * @param int $goodId ID del bien
     * @return array|null Detalles del bien o null si no existe
     */
    public function getGoodDetails($goodId) {
        $stmt = $this->connection->prepare("
            SELECT 
                b.id,
                b.nombre,
                b.tipo,
                b.imagen,
                be.descripcion,
                be.marca,
                be.modelo,
                be.serial,
                be.estado,
                be.color,
                be.condiciones_tecnicas,
                be.fecha_ingreso,
                be.fecha_salida
            FROM 
                bienes b
            LEFT JOIN 
                bienes_equipos be ON b.id = be.bien_inventario_id
            WHERE 
                b.id = ?
        ");
        
        $stmt->bind_param("i", $goodId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}
?>