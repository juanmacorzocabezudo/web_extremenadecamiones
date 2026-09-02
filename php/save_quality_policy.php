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

    $json_file = '../data/quality-policy.json';

    if (file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
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
