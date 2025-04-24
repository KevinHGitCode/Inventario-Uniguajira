<?php
/**
 * Valida que la solicitud sea del método HTTP especificado y verifica campos requeridos
 * 
 * @param string $method El método HTTP a validar ('GET', 'POST', 'PUT', 'DELETE', etc.)
 * @param array $requiredFields Los campos requeridos (opcional)
 * @param bool $returnMissingFields Si es true, retorna los campos que fallaron la validación
 * @return bool|array True si pasa la validación, false si falla, o array de campos faltantes
 */
function validateHttpRequest($method, $requiredFields = [],) {
    // Validar el método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => "Método no permitido. Se esperaba $method."]);
        return false;
    }
    
    // Si no hay campos requeridos, retornamos true directamente
    if (empty($requiredFields)) {
        return true;
    }
    
    // Obtener los datos adecuados según el método
    $data = [];
    switch (strtoupper($method)) {
        case 'GET':
            $data = $_GET;
            break;
        case 'POST':
            $data = $_POST;
            break;
        case 'PUT':
        case 'PATCH':
            parse_str(file_get_contents('php://input'), $data);
            break;
        default:
            $data = $_REQUEST;
    }
    
    // Validar campos requeridos
    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $missingFields[] = $field;
        }
    }
    
    // Si hay campos faltantes, retornamos un error
    if (!empty($missingFields)) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'Los siguientes campos son requeridos: ' . implode(', ', $missingFields)
        ]);

        return false;
    }
    
    return true;
}