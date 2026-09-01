<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configuración
$to_email = "info@extremenadecamiones.es";
$response = array('success' => false, 'message' => '');

// Validar método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

// Obtener y limpiar datos del formulario
$name = isset($_POST['name']) ? htmlspecialchars(strip_tags(trim($_POST['name']))) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars(strip_tags(trim($_POST['phone']))) : '';
$subject = isset($_POST['subject']) ? htmlspecialchars(strip_tags(trim($_POST['subject']))) : 'general';
$message = isset($_POST['message']) ? htmlspecialchars(strip_tags(trim($_POST['message']))) : '';

// Validar campos obligatorios
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    $response['message'] = 'Por favor, complete todos los campos obligatorios.';
    echo json_encode($response);
    exit;
}

// Validar email
if (!$email) {
    $response['message'] = 'Por favor, introduzca un email válido.';
    echo json_encode($response);
    exit;
}

// Traducir asunto
$subject_translations = array(
    'general' => 'Consulta general',
    'venta' => 'Venta de vehículo',
    'ocasion' => 'Vehículo de ocasión',
    'postventa' => 'Servicio postventa',
    'recambios' => 'Recambios',
    'otros' => 'Otros'
);

$subject_text = isset($subject_translations[$subject]) ? $subject_translations[$subject] : 'Consulta';

// Construir email
$email_subject = "Contacto Web: $subject_text";
$email_body = "Ha recibido un nuevo mensaje desde el formulario de contacto de la web.\n\n";
$email_body .= "Detalles del contacto:\n\n";
$email_body .= "Nombre: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Teléfono: $phone\n";
$email_body .= "Asunto: $subject_text\n\n";
$email_body .= "Mensaje:\n$message\n";

// Headers del email
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Enviar email
if (mail($to_email, $email_subject, $email_body, $headers)) {
    $response['success'] = true;
    $response['message'] = 'Mensaje enviado correctamente. Nos pondremos en contacto con usted pronto.';
} else {
    $response['message'] = 'Error al enviar el mensaje. Por favor, inténtelo de nuevo más tarde o contacte por teléfono.';
}

echo json_encode($response);
?>
