<?php

require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Goods.php';
require_once 'app/helpers/imageHelper.php';
require_once 'app/helpers/validate-http.php';

class ctlGoods {
    
    private $goodsModel;
    
    /**
     * Constructor de la clase.
     * Inicializa el modelo de bienes.
     */
    public function __construct() {
        $this->goodsModel = new Goods();
    }

    public function getJson() {
        header('Content-Type: application/json');
        $allGoods = $this->goodsModel->getAllGoods();
        
        // Filtrar solo id, bien (nombre) y tipo
        $filteredGoods = array_map(function($good) {
            return [
                'id' => $good['id'],
                'bien' => $good['nombre'],
                'tipo' => $good['tipo']
            ];
        }, $allGoods);
        
        echo json_encode($filteredGoods);
    }

    public function create() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['nombre', 'tipo'])) {
            return;
        }
        
        $nombre = trim($_POST['nombre']);
        $tipo = (int) $_POST['tipo'];
        $imagen = $_FILES['imagen'] ?? null;
        $rutaDestino = null;

        // Validar tipo ENUM
        if (!in_array($tipo, [1, 2])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Tipo de bien no válido.'
            ]);
            return;
        }

        // Validaciones de imagen (opcional)
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $resultadoImagen = validarYGuardarImagen($imagen, 'assets/uploads/img/goods/', 2); // 2MB para bienes

            if (!$resultadoImagen['success']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $resultadoImagen['message']]);
                return;
            }

            $rutaDestino = $resultadoImagen['path'];
        }

        $resultado = $this->goodsModel->create($nombre, $tipo, $rutaDestino);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Bien creado exitosamente.',
                'bienId' => $resultado
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar el bien. El nombre podría estar duplicado.'
            ]);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
    
        if (!validateHttpRequest('DELETE')) {
            return;
        }
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID no válido.']);
            return;
        }
    
        $cantidad = $this->goodsModel->getQuantityById((int)$id);

        if ($cantidad === 0) {
            $rutaImagen = $this->goodsModel->getImageById((int)$id);
            if ($rutaImagen && file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
            $resultado = $this->goodsModel->delete((int)$id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Bien eliminado correctamente.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el bien.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar el bien porque su cantidad es mayor a 0.']);
        }
    }
    
    public function update() {
        header('Content-Type: application/json');
    
        if (!validateHttpRequest('POST', ['id', 'nombre'])) {
            return;
        }
    
        $id = (int)$_POST['id'];
        $nombre = trim($_POST['nombre']);
        $imagen = $_FILES['imagen'] ?? null;
    
        // Procesar imagen si se subió una nueva
        $nuevaRuta = null;
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $resultadoImagen = validarYGuardarImagen($imagen, 'assets/uploads/img/goods/', 2); // 2MB para bienes
    
            if (!$resultadoImagen['success']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $resultadoImagen['message']]);
                return;
            }
    
            // Eliminar imagen anterior si existe
            $rutaAnterior = $this->goodsModel->getImageById($id);
            if ($rutaAnterior && file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
    
            $nuevaRuta = $resultadoImagen['path'];
        }
    
        // Actualizar en la base de datos
        $resultado = $this->goodsModel->update($id, $nombre, $nuevaRuta);
    
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien actualizado correctamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el bien o no hubo cambios.']);
        }
    }
}