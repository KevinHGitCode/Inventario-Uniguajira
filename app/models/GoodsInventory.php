<?php
require_once __DIR__ . '/../config/db.php';

class GoodsInventory extends Database {

    /**
     * Constructor de la clase GoodsInventory.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }


    /**
     * Obtener información de un bien en inventario por su ID.
     * 
     * @param int $id ID del bien en inventario.
     * @return array|false Arreglo asociativo con la información del bien en inventario o false si no existe.
     */
    public function getGoodInventoryById($id) {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                bien_id, 
                inventario_id
            FROM bienes_inventarios 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        return $result->fetch_assoc();
    }

    /**
     * Obtener todos los bienes de un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes del inventario.
     */
    public function getAllGoodsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                inventario_id, 
                bien_id, 
                inventario, 
                bien, 
                imagen, 
                tipo,
                cantidad
            FROM vista_cantidades_bienes_inventario 
            WHERE inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener todos los bienes de tipo serial de un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes de tipo serial.
     */
    public function getAllSerialGoodsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                inventario_id, 
                bien_id, 
                inventario, 
                bien, 
                bienes_inventarios_id,
                bienes_equipos_id,
                descripcion,
                marca,
                modelo,
                serial,
                estado,
                color,
                condiciones_tecnicas,
                fecha_ingreso,
                fecha_salida
            FROM vista_bienes_serial_inventario 
            WHERE inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Añadir un bien de tipo cantidad a un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @param int $goodId ID del bien.
     * @param int $quantity Cantidad a añadir.
     * @return int|false ID del bien_inventario creado si fue exitoso, False si hubo un error.
     */
    public function addQuantity($inventoryId, $goodId, $quantity) {
        // Primero verificamos si el bien ya existe en el inventario
        $bienInventarioId = $this->getBienInventarioId($goodId, $inventoryId);
        
        if (!$bienInventarioId) {
            // Si no existe, creamos el registro en bienes_inventarios
            $stmt = $this->connection->prepare("
                INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                VALUES (?, ?)
            ");
            $stmt->bind_param("ii", $goodId, $inventoryId);
            if (!$stmt->execute()) {
                return false;
            }
            
            $bienInventarioId = $this->connection->insert_id;
        }
        
        // Ahora añadimos o actualizamos la cantidad
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");
        $stmt->bind_param("ii", $bienInventarioId, $quantity);
        $result = $stmt->execute();
        
        return $result ? $bienInventarioId : false;
    }

    /**
     * Añadir un bien de tipo serial a un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @param int $goodId ID del bien.
     * @param array $details Detalles del bien.
     * @return int|false ID del bien_equipo creado si fue exitoso, False si hubo un error.
     */
    public function addSerial($inventoryId, $goodId, $details) {
        $bienInventarioId = $this->getBienInventarioId($goodId, $inventoryId);

        if (!$bienInventarioId) {
            $stmt = $this->connection->prepare("
                INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                VALUES (?, ?)
            ");
            $stmt->bind_param("ii", $goodId, $inventoryId);
            if (!$stmt->execute()) {
                return false;
            }
            $bienInventarioId = $this->connection->insert_id;
        }

        $stmt = $this->connection->prepare("
            INSERT INTO bienes_equipos (
                bien_inventario_id, 
                descripcion, 
                marca, 
                modelo, 
                serial, 
                estado, 
                color, 
                condiciones_tecnicas, 
                fecha_ingreso
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssss", 
            $bienInventarioId, 
            $details['description'], 
            $details['brand'], 
            $details['model'], 
            $details['serial'], 
            $details['state'], 
            $details['color'], 
            $details['technical_conditions'], 
            $details['entry_date']
        );
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Obtener el ID de bien_inventario para un bien y un inventario.
     * Obtener la relacion
     * 
     * @param int $goodId ID del bien.
     * @param int $inventoryId ID del inventario.
     * @return int|false ID del bien_inventario o false si no existe.
     */
    private function getBienInventarioId($goodId, $inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT id FROM bienes_inventarios 
            WHERE bien_id = ? AND inventario_id = ?
        ");
        $stmt->bind_param("ii", $goodId, $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    /**
     * Eliminar un bien de un inventario.
     * 
     * @param int $id ID del bien en inventario.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        // Determinar si es bien de tipo cantidad o serial
        $stmt = $this->connection->prepare("
            SELECT tipo FROM bienes b
            JOIN bienes_inventarios bi ON b.id = bi.bien_id
            WHERE bi.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        $tipo = $row['tipo'];
        
        // Iniciar transacción
        $this->connection->begin_transaction();
        
        // Eliminar según el tipo
        if ($tipo === 'Cantidad') {
            // Eliminar registro de bienes_cantidad
            $stmt = $this->connection->prepare("
                DELETE FROM bienes_cantidad WHERE bien_inventario_id = ?
            ");
            $stmt->bind_param("i", $id);
            $result1 = $stmt->execute();
            
            if (!$result1) {
                $this->connection->rollback();
                return false;
            }
        } else if ($tipo === 'Serial') {
            // Eliminar registros de bienes_equipos
            $stmt = $this->connection->prepare("
                DELETE FROM bienes_equipos WHERE bien_inventario_id = ?
            ");
            $stmt->bind_param("i", $id);
            $result1 = $stmt->execute();
            
            if (!$result1) {
                $this->connection->rollback();
                return false;
            }
        }
        
        // Eliminar registro de bienes_inventarios
        $stmt = $this->connection->prepare("
            DELETE FROM bienes_inventarios WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $result2 = $stmt->execute();
        
        if (!$result2) {
            $this->connection->rollback();
            return false;
        }
        
        // Confirmar transacción
        $this->connection->commit();
        return true;
    }

    /**
     * Actualizar la cantidad de un bien en el inventario.
     * 
     * @param int $bienId ID del bien en inventario.
     * @param int $cantidad Nueva cantidad.
     * @return bool Resultado de la operación.
     */
    public function updateQuantity($bienId, $cantidad) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_cantidad 
            SET cantidad = ? 
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("ii", $cantidad, $bienId);
        return $stmt->execute();
    }

    /**
     * Mover un bien a otro inventario.
     * 
     * @param int $bienId ID del bien en inventario.
     * @param int $inventarioDestinoId ID del inventario destino.
     * @return bool Resultado de la operación.
     */
    public function moveGood($bienId, $inventarioDestinoId) {
        // Obtener información del bien actual
        $stmt = $this->connection->prepare("
            SELECT bien_id, inventario_id FROM bienes_inventarios 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $bienId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        $goodId = $row['bien_id'];
        
        // Verificar si ya existe el bien en el inventario destino
        $bienInventarioDestinoId = $this->getBienInventarioId($goodId, $inventarioDestinoId);
        
        // Iniciar transacción
        $this->connection->begin_transaction();
        
        // Obtener tipo de bien
        $stmt = $this->connection->prepare("
            SELECT tipo FROM bienes 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $goodId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tipoBien = $result->fetch_assoc()['tipo'];
        
        if ($tipoBien === 'Cantidad') {
            // Obtener cantidad actual
            $stmt = $this->connection->prepare("
                SELECT cantidad FROM bienes_cantidad 
                WHERE bien_inventario_id = ?
            ");
            $stmt->bind_param("i", $bienId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cantidad = $result->fetch_assoc()['cantidad'];
            
            // Si existe en destino, sumar cantidad
            if ($bienInventarioDestinoId) {
                $stmt = $this->connection->prepare("
                    UPDATE bienes_cantidad 
                    SET cantidad = cantidad + ? 
                    WHERE bien_inventario_id = ?
                ");
                $stmt->bind_param("ii", $cantidad, $bienInventarioDestinoId);
                $result1 = $stmt->execute();
                
                if (!$result1) {
                    $this->connection->rollback();
                    return false;
                }
            } else {
                // Crear nuevo registro en inventario destino
                $stmt = $this->connection->prepare("
                    INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                    VALUES (?, ?)
                ");
                $stmt->bind_param("ii", $goodId, $inventarioDestinoId);
                $result1 = $stmt->execute();
                
                if (!$result1) {
                    $this->connection->rollback();
                    return false;
                }
                
                $bienInventarioDestinoId = $this->connection->insert_id;
                
                // Insertar cantidad
                $stmt = $this->connection->prepare("
                    INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
                    VALUES (?, ?)
                ");
                $stmt->bind_param("ii", $bienInventarioDestinoId, $cantidad);
                $result2 = $stmt->execute();
                
                if (!$result2) {
                    $this->connection->rollback();
                    return false;
                }
            }
            
            // Eliminar bien del inventario original
            $stmt = $this->connection->prepare("
                DELETE FROM bienes_cantidad 
                WHERE bien_inventario_id = ?
            ");
            $stmt->bind_param("i", $bienId);
            $result3 = $stmt->execute();
            
            if (!$result3) {
                $this->connection->rollback();
                return false;
            }
            
        } else if ($tipoBien === 'Serial') {
            // Para bienes de tipo serial, actualizar el bien_inventario_id
            if (!$bienInventarioDestinoId) {
                // Crear el registro en inventario destino
                $stmt = $this->connection->prepare("
                    INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                    VALUES (?, ?)
                ");
                $stmt->bind_param("ii", $goodId, $inventarioDestinoId);
                $result1 = $stmt->execute();
                
                if (!$result1) {
                    $this->connection->rollback();
                    return false;
                }
                
                $bienInventarioDestinoId = $this->connection->insert_id;
            }
            
            // Actualizar los equipos asociados
            $stmt = $this->connection->prepare("
                UPDATE bienes_equipos 
                SET bien_inventario_id = ? 
                WHERE bien_inventario_id = ?
            ");
            $stmt->bind_param("ii", $bienInventarioDestinoId, $bienId);
            $result2 = $stmt->execute();
            
            if (!$result2) {
                $this->connection->rollback();
                return false;
            }
        }
        
        // Eliminar el registro de bienes_inventarios original
        $stmt = $this->connection->prepare("
            DELETE FROM bienes_inventarios 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $bienId);
        $result4 = $stmt->execute();
        
        if (!$result4) {
            $this->connection->rollback();
            return false;
        }
        
        // Confirmar transacción
        $this->connection->commit();
        return true;
    }
}
?>