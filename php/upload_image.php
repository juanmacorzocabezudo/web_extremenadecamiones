<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', '0');

$response = array('success' => false, 'message' => '', 'filename' => '');

// Validar método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

try {
    // Verificar que se subió un archivo
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se recibió ninguna imagen válida');
    }
    
    $file = $_FILES['image'];
    $uploadDir = __DIR__ . '/../images/vehicles/';
    
    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new Exception('No se pudo crear la carpeta de imágenes');
        }
    }

    if (!is_writable($uploadDir)) {
        throw new Exception('La carpeta de imágenes no tiene permisos de escritura');
    }
    
    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception('Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG, GIF y WebP');
    }
    
    // Validar tamaño (máximo 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('La imagen es demasiado grande. Máximo 5MB');
    }
    
    // Obtener extensión
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Generar nombre único
    $filename = 'vehicle_' . time() . '_' . uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $filename;
    
    // Mover archivo
    if (!@move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Error al guardar la imagen');
    }
    
    // Redimensionar imagen para optimizar (opcional)
    // Aquí podrías agregar código para redimensionar con GD o ImageMagick
    
    $response['success'] = true;
    $response['message'] = 'Imagen subida correctamente';
    $response['filename'] = 'images/vehicles/' . $filename;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
