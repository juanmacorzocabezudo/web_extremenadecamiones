<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = array('success' => false, 'title' => '', 'content' => '');

try {
    $json_file = '../data/quality-policy.json';

    if (!file_exists($json_file)) {
        throw new Exception('Archivo de datos no encontrado');
    }

    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);

    if ($data && isset($data['title']) && isset($data['content'])) {
        $response['success'] = true;
        $response['title'] = $data['title'];
        $response['content'] = $data['content'];
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
