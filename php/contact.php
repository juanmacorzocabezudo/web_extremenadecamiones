<?php
header('Content-Type: application/json; charset=UTF-8');

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

$configFile = dirname(__DIR__) . '/config.local.php';
if (!is_file($configFile)) {
    error_log('Contacto web: falta config.local.php');
    $response['message'] = 'El servicio de correo no está configurado.';
    echo json_encode($response);
    exit;
}

$config = require $configFile;
$requiredConfig = array('smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'mail_from', 'notification_to');
foreach ($requiredConfig as $configKey) {
    if (empty($config[$configKey]) || $config[$configKey] === '***CONFIGURE_SMTP_PASSWORD***') {
        error_log('Contacto web: configuración SMTP incompleta');
        $response['message'] = 'El servicio de correo no está configurado.';
        echo json_encode($response);
        exit;
    }
}

$name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var(trim((string) $_POST['email']), FILTER_VALIDATE_EMAIL) : false;
$phone = isset($_POST['phone']) ? trim((string) $_POST['phone']) : '';
$subject = isset($_POST['subject']) ? trim((string) $_POST['subject']) : 'general';
$message = isset($_POST['message']) ? trim((string) $_POST['message']) : '';

if ($name === '' || !$email || $phone === '' || $message === '') {
    $response['message'] = 'Por favor, complete todos los campos obligatorios.';
    echo json_encode($response);
    exit;
}

$subjectTranslations = array(
    'general' => 'Consulta general',
    'venta' => 'Venta de vehículo',
    'ocasion' => 'Vehículo de ocasión',
    'postventa' => 'Servicio postventa',
    'recambios' => 'Recambios',
    'otros' => 'Otros'
);
$subjectText = isset($subjectTranslations[$subject]) ? $subjectTranslations[$subject] : 'Consulta';

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$safeSubject = htmlspecialchars($subjectText, ENT_QUOTES, 'UTF-8');

$notificationHtml = buildEmailHtml(
    'Nuevo mensaje desde la web',
    '<p>Ha recibido una nueva consulta desde el <span style="white-space:nowrap;">formulario</span> de contacto.</p>' .
    '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin-top:20px;">' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:120px;">Nombre</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeName . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:120px;">Email</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeEmail . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:120px;">Teléfono</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safePhone . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:120px;">Asunto</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeSubject . '</td></tr>' .
    '<tr><th align="left" valign="top" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:120px;">Mensaje</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeMessage . '</td></tr>' .
    '</table>'
);

$confirmationHtml = buildEmailHtml(
    'Gracias por contactar con nosotros',
    '<p>Estimado/a ' . $safeName . ':</p>' .
    '<p><span style="white-space:nowrap;">Hemos</span> recibido correctamente su mensaje. En breve nos pondremos en contacto con usted.</p>' .
    '<p>Gracias por confiar en Extremeña de Camiones.</p>'
);

$notificationSent = smtpSendMail($config, $config['notification_to'], 'Contacto web: ' . $subjectText, $notificationHtml, $email, 'notificación interna');
$confirmationSent = smtpSendMail($config, $email, 'Hemos recibido su mensaje', $confirmationHtml, $config['notification_to'], 'confirmación al usuario');

if ($notificationSent && $confirmationSent) {
    $response['success'] = true;
    $response['message'] = 'Mensaje enviado correctamente. Nos pondremos en contacto con usted pronto.';
} else {
    $response['message'] = 'No se ha podido enviar el mensaje. Por favor, inténtelo de nuevo más tarde o contacte por teléfono.';
}

echo json_encode($response);

function buildEmailHtml($title, $content) {
    return '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title></head>' .
        '<body style="margin:0;background:#f4f4f4;font-family:Arial,sans-serif;color:#333;padding:30px 15px;">' .
        '<table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center">' .
        '<table role="presentation" width="100%" style="max-width:620px;background:#fff;border-radius:8px;overflow:hidden;" cellspacing="0" cellpadding="0">' .
        '<tr><td align="center" style="background:#fff;border-bottom:4px solid #e1000f;padding:18px 30px;"><img src="https://extremenadecamiones.es/images/logoextremena.png" alt="Extremeña de Camiones" width="190" style="display:block;margin:0 auto;width:190px;max-width:100%;height:auto;"></td></tr>' .
        '<tr><td style="padding:30px;line-height:1.6;"><h1 style="margin:0 0 20px;color:#1a1a1a;font-size:25px;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>' . $content . '</td></tr>' .
        '<tr><td style="background:#f4f4f4;padding:18px 30px;color:#777;font-size:12px;">Pol. Ind. El Prado, C/ Zaragoza, 21 · 06800 Mérida (Badajoz)<br>info@extremenadecamiones.es</td></tr>' .
        '</table></td></tr></table></body></html>';
}

function smtpSendMail($config, $recipient, $subject, $html, $replyTo, $mailType) {
    $socket = @stream_socket_client(
        'ssl://' . $config['smtp_host'] . ':' . $config['smtp_port'],
        $errorNumber,
        $errorMessage,
        20,
        STREAM_CLIENT_CONNECT
    );
    if (!$socket) {
        error_log('Contacto web: no se pudo conectar SMTP para ' . $mailType . ': ' . $errorMessage . ' (' . $errorNumber . ')');
        return false;
    }

    stream_set_timeout($socket, 20);
    try {
        smtpExpect($socket, array(220));
        smtpCommand($socket, 'EHLO extremenadecamiones.es', array(250));
        smtpCommand($socket, 'AUTH LOGIN', array(334));
        smtpCommand($socket, base64_encode($config['smtp_user']), array(334));
        smtpCommand($socket, base64_encode($config['smtp_password']), array(235));
        smtpCommand($socket, 'MAIL FROM:<' . $config['mail_from'] . '>', array(250));
        smtpCommand($socket, 'RCPT TO:<' . $recipient . '>', array(250, 251));
        smtpCommand($socket, 'DATA', array(354));

        $headers = array(
            'From: ' . mimeHeader('Extremeña de Camiones') . ' <' . $config['mail_from'] . '>',
            'To: ' . $recipient,
            'Reply-To: ' . $replyTo,
            'Subject: ' . mimeHeader($subject),
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            'X-Mailer: Extremeña de Camiones'
        );
        $data = implode("\r\n", $headers) . "\r\n\r\n" . normalizeSmtpBody($html) . "\r\n.";
        fwrite($socket, $data . "\r\n");
        smtpExpect($socket, array(250));
        fwrite($socket, "QUIT\r\n");
        fclose($socket);
        return true;
    } catch (Exception $exception) {
        error_log('Contacto web: fallo SMTP en ' . $mailType . ' para ' . $recipient . ': ' . $exception->getMessage());
        fclose($socket);
        return false;
    }
}

function smtpCommand($socket, $command, $expectedCodes) {
    fwrite($socket, $command . "\r\n");
    smtpExpect($socket, $expectedCodes);
}

function smtpExpect($socket, $expectedCodes) {
    $response = '';
    do {
        $line = fgets($socket, 515);
        if ($line === false) {
            throw new Exception('SMTP connection closed');
        }
        $response .= $line;
    } while (isset($line[3]) && $line[3] === '-');

    $code = (int) substr($response, 0, 3);
    if (!in_array($code, $expectedCodes, true)) {
        throw new Exception('Respuesta SMTP ' . $code . ': ' . trim(preg_replace('/\s+/', ' ', $response)));
    }
}

function normalizeSmtpBody($body) {
    $body = str_replace(array("\r\n", "\r"), "\n", $body);
    $body = str_replace("\n", "\r\n", $body);
    return preg_replace('/^\./m', '..', $body);
}

function mimeHeader($value) {
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}
