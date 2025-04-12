<?php
function validarYGuardarImagen($archivo, $directorioDestino, $maxSizeMB) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir la imagen.'];
    }

    $maxSize = $maxSizeMB * 1024 * 1024; // Convertir MB a bytes
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $mimePermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $mimeType = mime_content_type($archivo['tmp_name']);
    $fileSize = $archivo['size'];

    if (!in_array($extension, $extensionesPermitidas) || !in_array($mimeType, $mimePermitidos)) {
        return ['success' => false, 'message' => 'Formato de imagen no permitido.'];
    }

    if ($fileSize > $maxSize) {
        return ['success' => false, 'message' => 'La imagen excede el tamaño máximo permitido (' . $maxSizeMB . 'MB).'];
    }

    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    $fileName = uniqid('img_') . '.' . $extension;
    $rutaDestino = rtrim($directorioDestino, '/') . '/' . $fileName;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return ['success' => false, 'message' => 'No se pudo guardar la imagen.'];
    }

    return ['success' => true, 'path' => $rutaDestino];
}
