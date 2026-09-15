<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', '0');

$response = array('success' => false, 'message' => '');

// Validar método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

try {
    // Obtener datos JSON del cuerpo de la petición
    $json_input = file_get_contents('php://input');
    $vehicle_data = json_decode($json_input, true);
    
    if (!is_array($vehicle_data) || !$vehicle_data) {
        throw new Exception('Datos inválidos');
    }

    if (!isset($vehicle_data['id']) || $vehicle_data['id'] === '') {
        throw new Exception('El vehículo no tiene un identificador válido');
    }
    
    // Leer archivo JSON existente
    $json_file = __DIR__ . '/../data/vehicles.json';
    
    if (!file_exists($json_file)) {
        // Crear archivo si no existe
        $data = array('vehicles' => array());
    } else {
        if (!is_readable($json_file)) {
            throw new Exception('El archivo de vehículos no se puede leer');
        }

        $json_content = file_get_contents($json_file);
        $data = json_decode($json_content, true);
        
        if (!$data || !isset($data['vehicles'])) {
            $data = array('vehicles' => array());
        }
    }
    
    // Buscar si el vehículo existe (para actualizar)
    $found = false;
    foreach ($data['vehicles'] as $index => $vehicle) {
        if ($vehicle['id'] == $vehicle_data['id']) {
            $data['vehicles'][$index] = $vehicle_data;
            $found = true;
            break;
        }
    }
    
    // Si no existe, añadir nuevo
    if (!$found) {
        $data['vehicles'][] = $vehicle_data;
    }
    
    // Guardar archivo
    if (!is_writable($json_file) && (file_exists($json_file) || !is_writable(dirname($json_file)))) {
        throw new Exception('El archivo de vehículos no tiene permisos de escritura');
    }

    $json_output = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json_output === false) {
        throw new Exception('No se pudieron preparar los datos del vehículo');
    }

    if (@file_put_contents($json_file, $json_output, LOCK_EX) !== false) {
        $response['success'] = true;
        $response['message'] = 'Vehículo guardado correctamente';
    } else {
        throw new Exception('Error al escribir el archivo');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
