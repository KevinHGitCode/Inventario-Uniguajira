<?php

require_once 'app/models/Goods.php';

class ctlGoods {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $tipo = (int) $_POST['tipo'];
            $imagen = $_FILES['imagen'];
    
            // Validar tipo ENUM
            if (!in_array($tipo, [1, 2])) {
                http_response_code(400);
                echo "Tipo de bien no válido.";
                return;
            }
    
            // Validaciones de la imagen
            if ($imagen['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024; // 2MB
                $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $mimePermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
                $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
                $mimeType = mime_content_type($imagen['tmp_name']);
                $fileSize = $imagen['size'];
    
                // Validar extensión y MIME
                if (!in_array($extension, $extensionesPermitidas) || !in_array($mimeType, $mimePermitidos)) {
                    http_response_code(400);
                    echo "Formato de imagen no permitido.";
                    return;
                }
    
                // Validar tamaño
                if ($fileSize > $maxSize) {
                    http_response_code(400);
                    echo "La imagen excede el tamaño máximo permitido (2MB).";
                    return;
                }
    
                // Guardar imagen
                $fileName = uniqid('bien_') . '.' . $extension;
                $rutaDestino = 'assets/uploads/img/' . $fileName;
    
                if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    http_response_code(500);
                    echo "No se pudo guardar la imagen.";
                    return;
                }
            } else {
                http_response_code(400);
                echo "No se subió una imagen válida.";
                return;
            }
    
            // Guardar en la base de datos
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
    
}
