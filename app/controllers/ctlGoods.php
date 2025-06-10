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

public function batchCreate() {
    header('Content-Type: application/json');

    $logs = [];

    // Validate HTTP request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        $logs[] = 'Método no permitido: ' . $_SERVER['REQUEST_METHOD'];
        echo json_encode(['success' => false, 'message' => 'Método no permitido', 'logs' => $logs]);
        return;
    }

    // Validate if goods data exists in POST
    if (!isset($_POST['goods']) || !is_array($_POST['goods'])) {
        http_response_code(400);
        $logs[] = 'Datos inválidos: goods no está presente o no es un array';
        echo json_encode(['success' => false, 'message' => 'Datos inválidos', 'logs' => $logs]);
        return;
    }

    // Debug: Log received data
    $logs[] = 'POST data recibida: ' . print_r($_POST['goods'], true);
    $logs[] = 'FILES data recibida: ' . print_r(array_keys($_FILES), true);

    $processedGoods = [];

    foreach ($_POST['goods'] as $index => $good) {
        $nombre = isset($good['nombre']) ? trim($good['nombre']) : null;
        $tipo = isset($good['tipo']) ? (int)$good['tipo'] : null;
        
        // CORRECCIÓN PRINCIPAL: Usar la clave correcta para acceder a las imágenes
        $imagenKey = "goods_{$index}_imagen";
        $imagen = isset($_FILES[$imagenKey]) ? $_FILES[$imagenKey] : null;
        $rutaDestino = null;

        $logs[] = "Procesando bien índice $index: nombre='$nombre', tipo='$tipo', imagen_key='$imagenKey'";

        // Validate required fields
        if (!$nombre || !$tipo || !in_array($tipo, [1, 2])) {
            $logs[] = "Bien en índice $index omitido: campos requeridos faltantes o tipo inválido (nombre: '$nombre', tipo: '$tipo').";
            continue;
        }

        // Handle image upload
        if ($imagen) {
            $logs[] = "Imagen encontrada para índice $index: " . print_r($imagen, true);
            
            if ($imagen['error'] === UPLOAD_ERR_OK) {
                // Incluir el helper si no está incluido
                if (!function_exists('validarYGuardarImagen')) {
                    require_once 'imageHelper.php'; // Ajusta la ruta según tu estructura
                }
                
                $resultadoImagen = validarYGuardarImagen($imagen, 'assets/uploads/img/goods/', 2);

                if ($resultadoImagen['success']) {
                    $rutaDestino = $resultadoImagen['path'];
                    $logs[] = "Imagen para bien '$nombre' (índice $index) guardada correctamente en '$rutaDestino'.";
                } else {
                    $logs[] = "Error al guardar imagen para bien '$nombre' (índice $index): " . $resultadoImagen['message'];
                    // Continuar sin imagen en lugar de omitir todo el bien
                }
            } elseif ($imagen['error'] !== UPLOAD_ERR_NO_FILE) {
                $logs[] = "Error al subir imagen para bien '$nombre' (índice $index): código de error " . $imagen['error'];
            }
        } else {
            $logs[] = "Bien '$nombre' (índice $index) sin imagen adjunta.";
        }

        // Agregar el bien procesado (con o sin imagen)
        $processedGoods[] = [
            'nombre' => $nombre,
            'tipo' => $tipo,
            'imagen' => $rutaDestino
        ];
        
        $logs[] = "Bien '$nombre' agregado para procesamiento (imagen: " . ($rutaDestino ? "sí" : "no") . ")";
    }

    $logs[] = "Total de bienes a procesar: " . count($processedGoods);

    // Verificar que hay bienes para procesar
    if (empty($processedGoods)) {
        $logs[] = 'No hay bienes válidos para procesar.';
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No hay bienes válidos para procesar', 'logs' => $logs]);
        return;
    }

    // Call the model to insert goods
    try {
        $result = $this->goodsModel->batchInsert($processedGoods);

        if ($result) {
            $logs[] = 'Bienes creados exitosamente en la base de datos.';
            echo json_encode([
                'success' => true, 
                'message' => 'Bienes creados exitosamente', 
                'ids' => $result, 
                'logs' => $logs,
                'processed_count' => count($processedGoods)
            ]);
        } else {
            $logs[] = 'El modelo retornó false al intentar insertar los bienes.';
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear los bienes en la base de datos', 'logs' => $logs]);
        }
    } catch (Exception $e) {
        $logs[] = 'Excepción en batchInsert: ' . $e->getMessage();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage(), 'logs' => $logs]);
    }
}

}