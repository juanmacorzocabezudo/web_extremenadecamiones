<?php
function careerRespond($success, $message) {
    $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
    $requestedWith = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
    if (strpos($accept, 'application/json') === false && $requestedWith !== 'xmlhttprequest') {
        $status = $success ? 'enviada' : 'error';
        $location = '../trabaja-con-nosotros.html?candidatura=' . $status . '&mensaje=' . rawurlencode($message) . '#applicationForm';
        header('Location: ' . $location, true, 303);
        exit;
    }

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('success' => $success, 'message' => $message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    careerRespond(false, 'Método no permitido.');
}

$maxCvSize = 5 * 1024 * 1024;
$contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
$postLimit = careerIniBytes(ini_get('post_max_size'));
if ($postLimit > 0 && $contentLength > $postLimit) {
    careerRespond(false, 'El formulario supera el tamaño máximo permitido por el servidor.');
}

$honeypot = isset($_POST['website']) ? trim((string) $_POST['website']) : '';
if ($honeypot !== '') {
    careerRespond(true, 'Candidatura enviada correctamente. Gracias por tu interés.');
}

$privacyConsent = isset($_POST['privacy_consent']) ? trim((string) $_POST['privacy_consent']) : '';
if ($privacyConsent !== '1') {
    careerRespond(false, 'Debe aceptar la Política de Privacidad para enviar su candidatura.');
}

$configFile = dirname(__DIR__) . '/config.local.php';
if (!is_file($configFile)) {
    error_log('Candidatura web: falta config.local.php');
    careerRespond(false, 'El servicio de correo no está configurado.');
}

$config = require $configFile;
$requiredConfig = array('smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'mail_from');
foreach ($requiredConfig as $configKey) {
    if (empty($config[$configKey]) || $config[$configKey] === '***CONFIGURE_SMTP_PASSWORD***') {
        error_log('Candidatura web: configuración SMTP incompleta');
        careerRespond(false, 'El servicio de correo no está configurado.');
    }
}

if (empty($config['recaptcha_secret']) || $config['recaptcha_secret'] === 'TU_CLAVE_SECRETA_RECAPTCHA') {
    error_log('Candidatura web: falta la clave secreta de reCAPTCHA');
    careerRespond(false, 'El servicio de verificación de seguridad no está configurado.');
}

$recaptchaToken = isset($_POST['g-recaptcha-response']) ? trim((string) $_POST['g-recaptcha-response']) : '';
$recaptchaResult = verifyCareerRecaptcha($recaptchaToken, $config['recaptcha_secret']);
if ($recaptchaResult !== true) {
    $recaptchaMessages = array(
        'missing-input-response' => 'No se ha recibido la verificación de seguridad. Marque la casilla e inténtelo de nuevo.',
        'invalid-input-secret' => 'La clave secreta de reCAPTCHA no es válida. Contacte con el administrador.',
        'invalid-input-response' => 'Google ha rechazado la verificación. Marque la casilla de nuevo y compruebe que el dominio del sitio está autorizado en reCAPTCHA.',
        'timeout-or-duplicate' => 'La verificación ha caducado o ya se ha utilizado. Marque la casilla de nuevo.',
        'connection-failed' => 'El servidor no puede conectar con Google para validar reCAPTCHA. Inténtelo más tarde.',
        'invalid-response' => 'Google devolvió una respuesta no válida al verificar reCAPTCHA.'
    );
    $responseMessage = isset($recaptchaMessages[$recaptchaResult])
        ? $recaptchaMessages[$recaptchaResult]
        : 'No se ha podido validar la verificación de seguridad. Marque la casilla de nuevo e inténtelo otra vez.';
    careerRespond(false, $responseMessage);
}

$name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var(trim((string) $_POST['email']), FILTER_VALIDATE_EMAIL) : false;
$phone = isset($_POST['phone']) ? trim((string) $_POST['phone']) : '';
$position = isset($_POST['position']) ? trim((string) $_POST['position']) : '';
$message = isset($_POST['message']) ? trim((string) $_POST['message']) : '';

if ($name === '' || !$email || $phone === '') {
    careerRespond(false, 'Por favor, complete los campos obligatorios correctamente.');
}

if (strlen($name) > 200 || strlen($phone) > 50 || strlen($position) > 120 || strlen($message) > 3000) {
    careerRespond(false, 'Uno de los campos supera la longitud permitida.');
}

if (!isset($_FILES['cv']) || !is_array($_FILES['cv'])) {
    careerRespond(false, 'Adjunte su currículum en formato PDF, DOC o DOCX.');
}

$cv = $_FILES['cv'];
if ($cv['error'] !== UPLOAD_ERR_OK) {
    if ($cv['error'] === UPLOAD_ERR_INI_SIZE || $cv['error'] === UPLOAD_ERR_FORM_SIZE) {
        careerRespond(false, 'El CV supera el límite de carga configurado por el servidor.');
    }
    careerRespond(false, 'No se ha podido recibir el CV. Seleccione el archivo de nuevo.');
}

if ($cv['size'] < 1 || $cv['size'] > $maxCvSize || !is_uploaded_file($cv['tmp_name'])) {
    careerRespond(false, 'El CV debe ocupar menos de 5 MB.');
}

$extension = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));
$allowedMimeTypes = array(
    'pdf' => array('application/pdf'),
    'doc' => array('application/msword', 'application/x-ole-storage'),
    'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip')
);
if (!isset($allowedMimeTypes[$extension])) {
    careerRespond(false, 'El CV debe estar en formato PDF, DOC o DOCX.');
}

$stagingDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'career-uploads';
if (!is_dir($stagingDirectory) || !is_writable($stagingDirectory)) {
    error_log('Candidatura web: el directorio temporal protegido no está disponible');
    careerRespond(false, 'El servidor no tiene disponible el almacenamiento temporal seguro para el CV.');
}

$stagedCvPath = $stagingDirectory . DIRECTORY_SEPARATOR . bin2hex(random_bytes(24)) . '.upload';
if (!move_uploaded_file($cv['tmp_name'], $stagedCvPath)) {
    error_log('Candidatura web: no se pudo mover el CV al directorio temporal protegido');
    careerRespond(false, 'No se ha podido procesar el CV. Inténtelo de nuevo más tarde.');
}

if (!function_exists('finfo_open')) {
    @unlink($stagedCvPath);
    careerRespond(false, 'El servidor no tiene habilitada la validación del tipo de archivo.');
}

$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = $fileInfo ? @finfo_file($fileInfo, $stagedCvPath) : false;
if ($fileInfo !== false) {
    finfo_close($fileInfo);
}
$cvContents = @file_get_contents($stagedCvPath);
@unlink($stagedCvPath);

if ($mimeType === false || !in_array($mimeType, $allowedMimeTypes[$extension], true)) {
    careerRespond(false, 'El tipo de archivo del CV no es válido. Adjunte un PDF, DOC o DOCX.');
}
if ($cvContents === false) {
    careerRespond(false, 'No se ha podido leer el archivo del CV.');
}

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safePosition = htmlspecialchars($position !== '' ? $position : 'No especificado', ENT_QUOTES, 'UTF-8');
$safeMessage = $message !== '' ? nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) : 'Sin presentación';
$content = '<p>Se ha recibido una nueva candidatura desde el formulario de la web.</p>' .
    '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin-top:20px;">' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:140px;">Nombre</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeName . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:140px;">Email</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeEmail . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:140px;">Teléfono</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safePhone . '</td></tr>' .
    '<tr><th align="left" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:140px;">Puesto</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safePosition . '</td></tr>' .
    '<tr><th align="left" valign="top" style="padding:10px;border-bottom:1px solid #ddd;color:#666;width:140px;">Presentación</th><td style="padding:10px;border-bottom:1px solid #ddd;">' . $safeMessage . '</td></tr>' .
    '</table>';
$notificationHtml = buildCareerEmailHtml('Nueva candidatura', $content);
$originalFilename = str_replace('\\', '/', basename((string) $cv['name']));
$attachmentFilename = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalFilename);
if ($attachmentFilename === '' || $attachmentFilename === false) {
    $attachmentFilename = 'curriculum.' . $extension;
}

$mailSent = smtpSendCareerMail(
    $config,
    'cv@extremenadecamiones.es',
    'Nueva candidatura web: ' . $name,
    $notificationHtml,
    $email,
    $cvContents,
    $attachmentFilename,
    $mimeType
);

if (!$mailSent) {
    careerRespond(false, 'No se ha podido enviar la candidatura. Inténtelo de nuevo más tarde.');
}

$confirmationContent = '<p>Hola ' . $safeName . ':</p>' .
    '<p>Hemos recibido correctamente tu candidatura para trabajar con Extremeña de Camiones.</p>' .
    '<p>Nuestro equipo revisará tu perfil. Gracias por tu interés en formar parte de la empresa.</p>';
$confirmationHtml = buildCareerEmailHtml('Hemos recibido tu candidatura', $confirmationContent);
$confirmationSent = smtpSendCareerMail(
    $config,
    $email,
    'Hemos recibido tu candidatura',
    $confirmationHtml,
    $config['notification_to']
);

if (!$confirmationSent) {
    error_log('Candidatura web: candidatura recibida, pero no se pudo enviar la confirmación a ' . $email);
    careerRespond(true, 'Tu candidatura se ha enviado correctamente, pero no hemos podido enviar el correo de confirmación.');
}

careerRespond(true, 'Candidatura enviada correctamente. Hemos enviado una confirmación a ' . $email . '.');

function careerIniBytes($value) {
    $value = trim((string) $value);
    if ($value === '' || $value === '-1') {
        return PHP_INT_MAX;
    }
    $unit = strtolower(substr($value, -1));
    $bytes = (float) $value;
    if ($unit === 'g') {
        $bytes *= 1024;
        $unit = 'm';
    }
    if ($unit === 'm') {
        $bytes *= 1024;
        $unit = 'k';
    }
    if ($unit === 'k') {
        $bytes *= 1024;
    }
    return (int) $bytes;
}

function buildCareerEmailHtml($title, $content) {
    return '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title></head>' .
        '<body style="margin:0;background:#f4f4f4;font-family:Arial,sans-serif;color:#333;padding:30px 15px;">' .
        '<table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center">' .
        '<table role="presentation" width="100%" style="max-width:620px;background:#fff;" cellspacing="0" cellpadding="0">' .
        '<tr><td align="center" style="background:#fff;border-bottom:4px solid #e1000f;padding:18px 30px;"><img src="https://extremenadecamiones.es/images/logoextremena.png" alt="Extremeña de Camiones" width="190" style="display:block;margin:0 auto;width:190px;max-width:100%;height:auto;"></td></tr>' .
        '<tr><td style="padding:30px;line-height:1.6;"><h1 style="margin:0 0 20px;color:#1a1a1a;font-size:25px;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>' . $content . '</td></tr>' .
        '<tr><td style="background:#f4f4f4;padding:18px 30px;color:#777;font-size:12px;">PG. IND. EL PRADO. C/ ZARAGOZA, 11 · 06008 Mérida (Badajoz)<br>info@extremenadecamiones.es</td></tr>' .
        '</table></td></tr></table></body></html>';
}

function smtpSendCareerMail($config, $recipient, $subject, $html, $replyTo, $attachment = null, $filename = '', $mimeType = 'application/octet-stream') {
    $socket = @stream_socket_client(
        'ssl://' . $config['smtp_host'] . ':' . $config['smtp_port'],
        $errorNumber,
        $errorMessage,
        20,
        STREAM_CLIENT_CONNECT
    );
    if (!$socket) {
        error_log('Candidatura web: no se pudo conectar SMTP: ' . $errorMessage . ' (' . $errorNumber . ')');
        return false;
    }

    stream_set_timeout($socket, 20);
    try {
        smtpCareerExpect($socket, array(220));
        smtpCareerCommand($socket, 'EHLO extremenadecamiones.es', array(250));
        smtpCareerCommand($socket, 'AUTH LOGIN', array(334));
        smtpCareerCommand($socket, base64_encode($config['smtp_user']), array(334));
        smtpCareerCommand($socket, base64_encode($config['smtp_password']), array(235));
        smtpCareerCommand($socket, 'MAIL FROM:<' . $config['mail_from'] . '>', array(250));
        smtpCareerCommand($socket, 'RCPT TO:<' . $recipient . '>', array(250, 251));
        smtpCareerCommand($socket, 'DATA', array(354));

        $headers = array(
            'From: ' . careerMimeHeader('Extremeña de Camiones') . ' <' . $config['mail_from'] . '>',
            'To: ' . $recipient,
            'Reply-To: ' . $replyTo,
            'Subject: ' . careerMimeHeader($subject),
            'MIME-Version: 1.0',
            'X-Mailer: Extremeña de Camiones'
        );
        if ($attachment === null) {
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: base64';
            $body = chunk_split(base64_encode($html), 76, "\r\n");
        } else {
            $boundary = '=_extremena_' . bin2hex(random_bytes(18));
            $headers[] = 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';
            $body = '--' . $boundary . "\r\n" .
                "Content-Type: text/html; charset=UTF-8\r\n" .
                "Content-Transfer-Encoding: base64\r\n\r\n" .
                chunk_split(base64_encode($html), 76, "\r\n") .
                '--' . $boundary . "\r\n" .
                'Content-Type: ' . $mimeType . '; name="' . $filename . "\"\r\n" .
                "Content-Transfer-Encoding: base64\r\n" .
                'Content-Disposition: attachment; filename="' . $filename . "\"\r\n\r\n" .
                chunk_split(base64_encode($attachment), 76, "\r\n") .
                '--' . $boundary . '--';
        }
        $data = implode("\r\n", $headers) . "\r\n\r\n" . normalizeCareerSmtpBody($body) . "\r\n.";
        fwrite($socket, $data . "\r\n");
        smtpCareerExpect($socket, array(250));
        fwrite($socket, "QUIT\r\n");
        fclose($socket);
        return true;
    } catch (Exception $exception) {
        error_log('Candidatura web: fallo SMTP: ' . $exception->getMessage());
        fclose($socket);
        return false;
    }
}

function smtpCareerCommand($socket, $command, $expectedCodes) {
    fwrite($socket, $command . "\r\n");
    smtpCareerExpect($socket, $expectedCodes);
}

function smtpCareerExpect($socket, $expectedCodes) {
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

function normalizeCareerSmtpBody($body) {
    $body = str_replace(array("\r\n", "\r"), "\n", $body);
    $body = str_replace("\n", "\r\n", $body);
    return preg_replace('/^\./m', '..', $body);
}

function careerMimeHeader($value) {
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

function verifyCareerRecaptcha($token, $secret) {
    if ($token === '') {
        return 'missing-input-response';
    }

    $payload = http_build_query(array(
        'secret' => $secret,
        'response' => $token,
        'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
    ));
    $verificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $result = false;
    $curlError = '';

    if (function_exists('curl_init')) {
        $curl = curl_init($verificationUrl);
        curl_setopt_array($curl, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 10
        ));
        $result = curl_exec($curl);
        if ($result === false) {
            $curlError = 'cURL ' . curl_errno($curl) . ': ' . curl_error($curl);
            error_log('Candidatura web: error cURL al validar reCAPTCHA: ' . $curlError);
        }
    }

    if ($result === false) {
        $options = array('http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($payload) . "\r\n",
            'content' => $payload,
            'timeout' => 10
        ));
        $result = @file_get_contents($verificationUrl, false, stream_context_create($options));
    }

    if ($result === false) {
        error_log('Candidatura web: no se pudo validar reCAPTCHA');
        return 'connection-failed';
    }

    $verification = json_decode($result, true);
    if (!is_array($verification)) {
        return 'invalid-response';
    }
    if (!empty($verification['success'])) {
        return true;
    }

    $errorCodes = isset($verification['error-codes']) && is_array($verification['error-codes'])
        ? $verification['error-codes']
        : array();
    error_log('Candidatura web: reCAPTCHA rechazado: ' . implode(', ', $errorCodes));
    if (in_array('invalid-input-secret', $errorCodes, true)) {
        return 'invalid-input-secret';
    }
    if (in_array('timeout-or-duplicate', $errorCodes, true)) {
        return 'timeout-or-duplicate';
    }
    if (in_array('invalid-input-response', $errorCodes, true)) {
        return 'invalid-input-response';
    }
    return 'invalid-response';
}