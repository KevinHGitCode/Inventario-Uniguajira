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
     * ====================================================================
     * ======================== CRUD Goods Inventory ======================
     * ====================================================================
     */

    /**
     * Crear un nuevo bien en el inventario
     */
    public function create() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['inventarioId', 'bien_id', 'bien_tipo'])) {
            return;
        }

        $inventarioId = $_POST['inventarioId'];
        $bienId = $_POST['bien_id'];
        $bienTipo = $_POST['bien_tipo'];

        if ($bienTipo == 1) {  // tipo cantidad
            $this->handleCantidadType($inventarioId, $bienId);
        } else if ($bienTipo == 2) {  // tipo serial
            $this->handleSerialType($inventarioId, $bienId);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipo de bien no soportado."' . $bienTipo . '"']);
        }
    }

    private function handleCantidadType($inventarioId, $bienId) {
        if (!validateHttpRequest('POST', ['cantidad'])) {
            return;
        }

        $cantidad = $_POST['cantidad'];

        if ($cantidad < 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cantidad inválida.']);
            return;
        }

        $resultado = $this->goodsInventory->addQuantity($inventarioId, $bienId, $cantidad);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien de tipo cantidad agregado exitosamente.', 'id' => $resultado]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo agregar el bien de tipo cantidad.']);
        }
    }

    private function handleSerialType($inventarioId, $bienId) {
        if (!validateHttpRequest('POST', ['serial'])) {
            return;
        }

        $serial = $_POST['serial'];

        $details = [
            'description' => $_POST['descripcion'] ?? '',
            'brand' => $_POST['marca'] ?? '',
            'model' => $_POST['modelo'] ?? '',
            'serial' => $serial,
            'state' => $_POST['estado'] ?? 'activo',
            'color' => $_POST['color'] ?? '',
            'technical_conditions' => $_POST['condiciones_tecnicas'] ?? '',
            'entry_date' => $_POST['fecha_ingreso'] ?? date('Y-m-d')
        ];

        $resultado = $this->goodsInventory->addSerial($inventarioId, $bienId, $details);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien de tipo serial agregado exitosamente.', 'id' => $resultado]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo agregar el bien de tipo serial.']);
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