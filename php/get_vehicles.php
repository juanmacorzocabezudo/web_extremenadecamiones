<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = array('success' => false, 'vehicles' => array());

try {
    // Leer archivo JSON de vehículos
    $json_file = '../data/vehicles.json';
    
    if (!file_exists($json_file)) {
        throw new Exception('Archivo de datos no encontrado');
    }
    
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
    
    if ($data && isset($data['vehicles'])) {
        $response['success'] = true;
        $response['vehicles'] = $data['vehicles'];
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
