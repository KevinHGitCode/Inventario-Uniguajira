<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/GoodsInventory.php';
require_once 'app/models/Inventory.php'; // Añadido para obtener información del inventario
require_once 'app/helpers/validate-http.php';

class ctlGoodInventory {
    private $goodsInventory;
    private $inventory; // Añadido para consultar información del inventario

    public function __construct() {
        $this->goodsInventory = new GoodsInventory();
        $this->inventory = new Inventory(); // Inicializado
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

        $success = false;
        $resultado = null;

        if ($bienTipo == 1) {  // tipo cantidad
            $resultado = $this->handleCantidadType($inventarioId, $bienId);
            $success = ($resultado !== false);
        } else if ($bienTipo == 2) {  // tipo serial
            $resultado = $this->handleSerialType($inventarioId, $bienId);
            $success = ($resultado !== false);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipo de bien no soportado."' . $bienTipo . '"']);
            return;
        }

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Bien agregado exitosamente.', 'id' => $resultado]);
        }
    }

    private function handleCantidadType($inventarioId, $bienId) {
        if (!validateHttpRequest('POST', ['cantidad'])) {
            return false;
        }

        $cantidad = $_POST['cantidad'];

        if ($cantidad < 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cantidad inválida.']);
            return false;
        }

        return $this->goodsInventory->addQuantity($inventarioId, $bienId, $cantidad);
    }

    private function handleSerialType($inventarioId, $bienId) {
        if (!validateHttpRequest('POST', ['serial'])) {
            return false;
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

        return $this->goodsInventory->addSerial($inventarioId, $bienId, $details);
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
     * Eliminar un bien de tipo cantidad del inventario
     */
    public function deleteQuantity($idInventario, $idBien) {
        header('Content-Type: application/json');

        if (!validateHttpRequest('DELETE')) {
            return;
        }

        if (empty($idInventario) || empty($idBien)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de inventario y bien requeridos.']);
            return;
        }

        $resultado = $this->goodsInventory->deleteQuantityGood($idInventario, $idBien);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien eliminado del inventario exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el bien del inventario.']);
        }
    }

    /**
     * Eliminar un bien de tipo serial del inventario
     */
    public function deleteSerial($idBienSerial) {
        header('Content-Type: application/json');

        if (!validateHttpRequest('DELETE')) {
            return;
        }

        if (empty($idBienSerial)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID del equipo requeridos.']);
            return;
        }

        $resultado = $this->goodsInventory->deleteSerialGood($idBienSerial);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien serial eliminado del inventario exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el bien serial del inventario.']);
        }
    }

    /**
     * Actualizar la cantidad de un bien en el inventario
     */    public function updateQuantity() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['bienId', 'inventarioId', 'cantidad'])) {
            return;
        }

        $bienId = $_POST['bienId'];
        $inventarioId = $_POST['inventarioId'];
        $cantidad = $_POST['cantidad'];

        if ($cantidad < 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La cantidad no puede ser negativa.']);
            return;
        }

        $resultado = $this->goodsInventory->updateQuantity($bienId, $inventarioId, $cantidad);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Cantidad actualizada exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la cantidad.']);
        }
    }

    /**
     * Actualizar los detalles de un bien serial
     */
    public function updateSerial() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['bienEquipoId', 'serial'])) {
            return;
        }

        $bienEquipoId = $_POST['bienEquipoId'];
        
        $details = [
            'descripcion' => $_POST['descripcion'] ?? '',
            'marca' => $_POST['marca'] ?? '',
            'modelo' => $_POST['modelo'] ?? '',
            'serial' => $_POST['serial'],
            'estado' => $_POST['estado'] ?? 'activo',
            'color' => $_POST['color'] ?? '',
            'condiciones_tecnicas' => $_POST['condiciones_tecnicas'] ?? '',
            'fecha_ingreso' => $_POST['fecha_ingreso'] ?? date('Y-m-d')
        ];

        try {
            $resultado = $this->goodsInventory->updateSerial($bienEquipoId, $details);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Bien serial actualizado exitosamente.']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el bien serial.']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
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