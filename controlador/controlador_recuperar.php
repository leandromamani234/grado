<?php
require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/modelo_Recuperar.php';
require_once '../includes/correo.php';
    

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        header('Location: ../vista/recuperar_contrasena.php?mensaje=Correo inválido');
        exit;
    }

    $usuarioModel = new ModeloUsuario();
    $recuperarModel = new ModeloRecuperar();
    $usuario = $usuarioModel->buscarPorEmail($email);

    if (!$usuario) {
        header('Location: ../vista/recuperar_contrasena.php?mensaje=Correo no registrado');
        exit;
    }

    $token = bin2hex(random_bytes(32));
    $recuperarModel->guardarToken($usuario['id_usuario'], $token);

    $enlace = "http://localhost/REGISTROS/vista/cambiar_contrasena.php?token=$token";
    $asunto = "Restablecimiento de usuario";
    $mensaje = "Haz clic en el siguiente enlace para cambiar tu contraseña:<br><a href='$enlace'>$enlace</a><br><br>Este enlace caducará en 3 minutos.";

    if (enviarCorreo($email, $asunto, $mensaje)) {
        header('Location: ../vista/recuperar_contrasena.php?mensaje=Correo enviado');
    } else {
        header('Location: ../vista/recuperar_contrasena.php?mensaje=Error al enviar correo');
    }
}
