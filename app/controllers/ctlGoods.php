<?php

require_once 'app/models/Goods.php';

class ctlGoods {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            // Validaciones de la imagen (solo si se envió)
            if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024; // 2MB
                $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $mimePermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
                $mimeType = mime_content_type($imagen['tmp_name']);
                $fileSize = $imagen['size'];

                // Validar extensión y MIME
                if (!in_array($extension, $extensionesPermitidas) || !in_array($mimeType, $mimePermitidos)) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Formato de imagen no permitido.'
                    ]);
                    return;
                }

                // Validar tamaño
                if ($fileSize > $maxSize) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'La imagen excede el tamaño máximo permitido (2MB).'
                    ]);
                    return;
                }

                // Guardar imagen
                $fileName = uniqid('bien_') . '.' . $extension;
                $rutaDestino = 'assets/uploads/img/' . $fileName;

                if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'No se pudo guardar la imagen.'
                    ]);
                    return;
                }
            }

            // Guardar en la base de datos (con imagen o sin ella)
            require_once __DIR__ . '/../models/Goods.php';
            $goodsModel = new Goods();
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
            $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
            $mimeType = mime_content_type($imagen['tmp_name']);
    
            if (!in_array($extension, $permitidas) || strpos($mimeType, 'image/') !== 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido.']);
                return;
            }
    
            // Eliminar imagen anterior
            $rutaAnterior = $goodsModel->getImageById((int)$id);
            if ($rutaAnterior && file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
    
            $fileName = uniqid('bien_') . '.' . $extension;
            $nuevaRuta = 'assets/uploads/img/' . $fileName;
            if (!move_uploaded_file($imagen['tmp_name'], $nuevaRuta)) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'No se pudo guardar la nueva imagen.']);
                return;
            }
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
