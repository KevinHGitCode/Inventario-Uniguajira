<?php

require_once 'app/models/Goods.php';
require_once 'app/helpers/imageHelper.php';

class ctlGoods {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $tipo = (int) $_POST['tipo'];
            $imagen = $_FILES['imagen'] ?? null;
            $rutaDestino = null;
    
            $goodsModel = new Goods();
    
            // Validar si ya existe un bien con el mismo nombre
            if ($goodsModel->existsByName($nombre)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Ya existe un bien con ese nombre.']);
                return;
            }
    
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
    
            $resultado = $goodsModel->create($nombre, $tipo, $rutaDestino);
    
            if ($resultado) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Bien creado exitosamente.'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al guardar el bien en la base de datos.'
                ]);
            }
            exit();
        } else {
            http_response_code(405);
            echo "Método no permitido.";
        }
    }
    


    public function delete($id) {
        header('Content-Type: application/json');
    
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID no válido.']);
            return;
        }
    
        $goodsModel = new Goods();
        $cantidad = $goodsModel->getQuantityById((int)$id);

        if ($cantidad === 0) {
            $rutaImagen = $goodsModel->getImageById((int)$id);
            if ($rutaImagen && file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
            $resultado = $goodsModel->delete((int)$id);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar el bien porque su cantidad es mayor a 0.']);
            return;
        }

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien eliminado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el bien.']);
        }
    }
    
    

    public function update() {
        header('Content-Type: application/json');
    
        $id = $_POST['id'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $imagen = $_FILES['imagen'] ?? null;
    
        if (!is_numeric($id) || $nombre === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            return;
        }
    
        $goodsModel = new Goods();
    
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
            $rutaAnterior = $goodsModel->getImageById((int)$id);
            if ($rutaAnterior && file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
    
            $nuevaRuta = $resultadoImagen['path'];
        }
    
        // Actualizar en la base de datos
        $resultado = $goodsModel->update($id, $nombre, $nuevaRuta);
    
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Bien actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el bien.']);
        }
    }

}
