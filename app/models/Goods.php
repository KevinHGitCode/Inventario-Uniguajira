<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Goods
 * 
 * Esta clase gestiona las operaciones relacionadas con los bienes en la base de datos.
 * Utiliza el patrón Singleton de Database para la conexión a la base de datos.
 */
class Goods {
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
    public function getAll() {
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
     * Obtener la lista de todos los bienes desde la tabla 'bienes'.
     * 
     * @return array Lista de bienes con los campos 'id', 'nombre', 'tipo' e 'imagen'.
     */
    public function getAllGoods() {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                tipo, 
                imagen 
            FROM bienes
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Crear un nuevo bien.
     * 
     * @param string $nombre Nombre del bien.
     * @param int $tipo Tipo del bien (representado como un entero).
     * @param string $imagenRuta Ruta de la imagen asociada al bien.
     * @return int|false ID del bien creado si fue exitoso, False en caso contrario.
     */
    public function create($nombre, $tipo, $imagenRuta) {
        // Verificar si el nombre ya existe
        if ($this->existsByName($nombre)) {
            return false;
        }

        $stmt = $this->connection->prepare("INSERT INTO bienes (nombre, tipo, imagen) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $nombre, $tipo, $imagenRuta);
        
        if ($stmt->execute()) {
            return $stmt->insert_id; // Retornar el ID del bien recién creado
        }
        
        return false;
    }
    
    /**
     * Modificar el nombre y/o la imagen de un bien.
     * 
     * @param int $id ID del bien a modificar.
     * @param string $nombre Nuevo nombre del bien.
     * @param string|null $nuevaRuta Nueva ruta de la imagen (opcional).
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public function update($id, $nombre, $nuevaRuta = null) {
        if ($nuevaRuta) {
            $stmt = $this->connection->prepare("UPDATE bienes SET nombre = ?, imagen = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre, $nuevaRuta, $id);
        } else {
            $stmt = $this->connection->prepare("UPDATE bienes SET nombre = ? WHERE id = ?");
            $stmt->bind_param("si", $nombre, $id);
        }
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar un bien.
     * 
     * Nota: Solo se puede eliminar si el bien no tiene relaciones.
     * 
     * @param int $id ID del bien a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public function delete($id) {
        // Verificar que la cantidad del bien sea 0 antes de eliminarlo
        if ($this->getQuantityById($id) > 0) {
            return false; // No se puede eliminar si la cantidad es mayor a 0
        }

        $stmt = $this->connection->prepare("DELETE FROM bienes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Obtener la cantidad total de un bien por su ID.
     * 
     * @param int $id ID del bien.
     * @return int Cantidad total del bien. Retorna 0 si no se encuentra.
     */
    public function getQuantityById($id) {
        $stmt = $this->connection->prepare("SELECT total_cantidad FROM vista_total_bienes_sistema WHERE bien_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return (int)$row['total_cantidad'];
        }
        
        return 0; // Si no encontró nada, asumimos cantidad 0
    }

    /**
     * Obtener la ruta de la imagen de un bien por su ID.
     * 
     * @param int $id ID del bien.
     * @return string|null Ruta de la imagen o null si no existe.
     */
    public function getImageById($id) {
        $stmt = $this->connection->prepare("SELECT imagen FROM bienes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['imagen'] ?? null;
    }

    /**
     * Verificar si existe un bien con un nombre específico.
     * 
     * @param string $nombre Nombre del bien.
     * @return bool True si el bien existe, False en caso contrario.
     */
    public function existsByName($nombre) {
        $stmt = $this->connection->prepare("SELECT id FROM bienes WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    /**
     * Inserta múltiples bienes en la base de datos de manera eficiente.
     * 
     * @param array $goods Array de bienes a insertar, donde cada bien es un array con 'nombre', 'tipo' e 'imagen'.
     * @return array|false Array de IDs de los bienes insertados si fue exitoso, False en caso contrario.
     */
    public function batchInsert($goods) {
        $placeholders = [];
        $values = [];

        foreach ($goods as $good) {
            $placeholders[] = '(?, ?, ?)';
            $values[] = $good['nombre'];
            $values[] = $good['tipo'];
            $values[] = $good['imagen'] ?? null;
        }

        $query = "INSERT INTO bienes (nombre, tipo, imagen) VALUES " . implode(', ', $placeholders);
        $stmt = $this->connection->prepare($query);

        $stmt->bind_param(str_repeat('sis', count($goods)), ...$values);

        if ($stmt->execute()) {
            $insertedIds = [];
            for ($i = 0; $i < $stmt->affected_rows; $i++) {
                $insertedIds[] = $this->connection->insert_id + $i;
            }
            return $insertedIds;
        }

        return false;
    }
}
?>