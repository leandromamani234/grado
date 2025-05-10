<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vista/librerias/PHPMailer/src/Exception.php';
require_once '../vista/librerias/PHPMailer/src/PHPMailer.php';
require_once '../vista/librerias/PHPMailer/src/SMTP.php';

function enviarCorreo($para, $asunto, $mensajeHTML) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'le1059583@gmail.com'; // Cambia esto
        $mail->Password = 'wbfvgwswyvwmfuqm'; // Cambia esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Cambiado de 465 a 587 para STARTTLS

        // Configuración del mensaje
        $mail->setFrom('no-reply@tusitio.com', 'Soporte');
        $mail->addAddress($para);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensajeHTML;

        // Intento de envío
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Error al enviar correo: " . $mail->ErrorInfo; // Muestra el error
        return false;
    }
}
