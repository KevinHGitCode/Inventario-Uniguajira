<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/GoodsInventory.php';
require_once 'app/helpers/validate-http.php';

class ctlGoodInventory {
    private $goodsInventory;

    public function __construct() {
        $this->goodsInventory = new GoodsInventory();
    }

    /**
     * Crear un nuevo bien en el inventario
     */
    public function create() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['inventarioId', 'bien_id'])) {
            return;
        }

        $inventarioId = $_POST['inventarioId'];
        $bienId = $_POST['bien_id'];

        // Verificar qué tipo de bien es para procesar los datos correspondientes
        // Esta información debería venir del modelo de bienes o se infiere de los datos enviados
        $tipoResult = $this->goodsInventory->getTipoDeProducto($bienId);

        if (!$tipoResult) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo determinar el tipo de bien.']);
            return;
        }

        $tipoBien = $tipoResult['tipo'];

        if ($tipoBien === 'Cantidad') {
            // Para bienes de tipo cantidad
            if (!isset($_POST['cantidad']) || $_POST['cantidad'] < 1) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'La cantidad debe ser mayor a cero.']);
                return;
            }

            $cantidad = $_POST['cantidad'];
            $resultado = $this->goodsInventory->addQuantity($inventarioId, $bienId, $cantidad);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Bien de tipo cantidad agregado correctamente.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al agregar el bien de tipo cantidad.']);
            }
        } else if ($tipoBien === 'Serial') {
            // Para bienes de tipo serial
            $detalles = [
                'description' => $_POST['descripcion'] ?? '',
                'brand' => $_POST['marca'] ?? '',
                'model' => $_POST['modelo'] ?? '',
                'serial' => $_POST['serial'] ?? '',
                'state' => $_POST['estado'] ?? 'activo',
                'color' => $_POST['color'] ?? '',
                'technical_conditions' => $_POST['condicion_tecnica'] ?? '',
                'entry_date' => $_POST['fecha_ingreso'] ?? date('Y-m-d')
            ];

            // Validar que el serial no esté vacío
            if (empty($detalles['serial'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'El número de serial es obligatorio para este tipo de bien.']);
                return;
            }

            $resultado = $this->goodsInventory->addSerial($inventarioId, $bienId, $detalles);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Bien de tipo serial agregado correctamente.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al agregar el bien de tipo serial.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipo de bien no soportado.']);
        }
    }

    /**
     * Eliminar un bien del inventario
     */
    public function delete($id) {
        header('Content-Type: application/json');

        if (!validateHttpRequest('DELETE')) {
            return;
        }

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }

        $resultado = $this->goodsInventory->delete($id);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien eliminado del inventario exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el bien del inventario.']);
        }
    }

    /**
     * Actualizar la cantidad de un bien en el inventario
     */
    public function updateQuantity() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['bienId', 'cantidad'])) {
            return;
        }

        $bienId = $_POST['bienId'];
        $cantidad = $_POST['cantidad'];

        if ($cantidad < 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La cantidad no puede ser negativa.']);
            return;
        }

        $resultado = $this->goodsInventory->updateQuantity($bienId, $cantidad);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Cantidad actualizada exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la cantidad.']);
        }
    }

    /**
     * Mover un bien a otro inventario
     */
    public function moveGood() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['bienId', 'inventarioDestinoId'])) {
            return;
        }

        $bienId = $_POST['bienId'];
        $inventarioDestinoId = $_POST['inventarioDestinoId'];

        $resultado = $this->goodsInventory->moveGood($bienId, $inventarioDestinoId);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien movido exitosamente al nuevo inventario.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo mover el bien al inventario de destino.']);
        }
    }
}


/**
     * ====================================================================
     * ======================== CRUD Goods Inventory ======================
     * ====================================================================
     */

    /**
     * Método para agregar un bien al inventario
     */
    // public function addGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['inventarioId', 'nombre'])) {
    //         return;
    //     }

    //     $inventarioId = $_POST['inventarioId'];
    //     $nombre = $_POST['nombre'];
    //     $cantidad = $_POST['cantidad'] ?? 1;
    //     $imagen = $_POST['imagen'] ?? 'assets/img/default-good.png';

    //     $resultado = $this->goodsInventory->addGood($inventarioId, $nombre, $cantidad, $imagen);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien agregado exitosamente.']);
    //     } else {
    //         http_response_code(409);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo agregar el bien al inventario.']);
    //     }
    // }

    /**
     * Método para cambiar la cantidad de un bien
     */
    // public function updateQuantity() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'cantidad'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $nuevaCantidad = $_POST['cantidad'];

    //     $resultado = $this->goodsInventory->updateQuantityGood($bienId, $nuevaCantidad);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Cantidad actualizada exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la cantidad.']);
    //     }
    // }

    /**
     * Método para mover un bien de un inventario a otro
     */
    // TODO: Not implement yet
    // public function moveGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'inventario_destino_id'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $inventarioDestinoId = $_POST['inventario_destino_id'];

    //     $resultado = $this->goodsInventory->moveGood($bienId, $inventarioDestinoId);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien movido exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo mover el bien.']);
    //     }
    // }

    /**
     * Método para eliminar un bien del inventario
     */
    // TODO: Not implement yet
    // public function removeGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];

    //     $resultado = $this->goodsInventory->removeGood($bienId);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien eliminado exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el bien.']);
    //     }
    // }

    /**
     * Método para cambiar el estado de un bien
     */
    // TODO: Not implement yet
    // public function changeState() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'estado'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $nuevoEstado = $_POST['estado'];

    //     $resultado = $this->goodsInventory->updateState($bienId, $nuevoEstado);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Estado del bien actualizado exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del bien.']);
    //     }
    // }