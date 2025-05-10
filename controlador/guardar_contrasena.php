<?php
require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/modelo_recuperar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nueva = $_POST['nueva_contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];  // Aquí recogemos el campo de confirmación

    // Comprobamos si la contraseña y la confirmación son iguales
    if ($nueva !== $confirmar) {
        header('Location: ../vista/cambiar_contrasena.php?mensaje=Las contraseñas no coinciden&token=' . $token);
        exit;
    }

    $recuperarModel = new ModeloRecuperar();
    $id_usuario = $recuperarModel->verificarToken($token);

    if ($id_usuario) {
        $usuarioModel = new ModeloUsuario();
        $usuarioModel->actualizarContrasenas($id_usuario, $nueva);
        $recuperarModel->eliminarToken($token);
        header('Location: ../vista/index.php?mensaje=Contraseña cambiada exitosamente');
    } else {
        header('Location: ../vista/cambiar_contrasena.php?mensaje=Token inválido o expirado');
    }
}

