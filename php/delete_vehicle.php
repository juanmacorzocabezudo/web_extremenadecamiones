<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$response = array('success' => false, 'message' => '');

try {
    // Leer el JSON actual
    $json_file = '../data/vehicles.json';
    
    if (!file_exists($json_file)) {
        throw new Exception('Archivo de datos no encontrado');
    }
    
    // Obtener el ID del vehículo a eliminar
    $input = json_decode(file_get_contents('php://input'), true);
    $vehicleId = $input['id'] ?? null;
    
    if (!$vehicleId) {
        throw new Exception('ID de vehículo no especificado');
    }
    
    // Leer datos actuales
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
    
    if (!$data || !isset($data['vehicles'])) {
        throw new Exception('Error al leer los datos');
    }
    
    // Buscar y eliminar el vehículo
    $vehicleFound = false;
    $newVehicles = array_filter($data['vehicles'], function($v) use ($vehicleId, &$vehicleFound) {
        if ($v['id'] == $vehicleId) {
            $vehicleFound = true;
            return false;
        }
        return true;
    });
    
    if (!$vehicleFound) {
        throw new Exception('Vehículo no encontrado');
    }
    
    // Reindexar el array
    $data['vehicles'] = array_values($newVehicles);
    
    // Guardar cambios
    $result = file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        throw new Exception('Error al guardar los datos');
    }
    
    $response['success'] = true;
    $response['message'] = 'Vehículo eliminado correctamente';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
