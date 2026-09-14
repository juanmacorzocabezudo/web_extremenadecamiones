<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

try {
    $json_input = file_get_contents('php://input');
    $policy_data = json_decode($json_input, true);

    if (!$policy_data || !isset($policy_data['title']) || !isset($policy_data['content'])) {
        throw new Exception('Datos inválidos');
    }

    $data = array(
        'title' => $policy_data['title'],
        'content' => $policy_data['content']
    );

    $data_directory = dirname(__DIR__) . '/data';
    $json_file = $data_directory . '/quality-policy.json';

    if (!is_dir($data_directory) || !is_writable($data_directory)) {
        throw new Exception('El servidor no tiene permiso de escritura en la carpeta data');
    }

    if (file_exists($json_file) && !is_writable($json_file)) {
        throw new Exception('El servidor no tiene permiso de escritura en quality-policy.json');
    }

    if (file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
        $response['success'] = true;
        $response['message'] = 'Política de calidad guardada correctamente';
    } else {
        throw new Exception('Error al escribir el archivo');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
