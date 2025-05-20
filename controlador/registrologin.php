<?php
session_start(); // Iniciar sesión

require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/conexion/configDb.php'; // Para acceder al objeto $pdo

if (!empty($_POST['usuario']) && !empty($_POST['pass'])) {
    $usuario = trim($_POST['usuario']);
    $pass = trim($_POST['pass']);

    // Crear instancia del modelo de usuarios
    $usuariosModel = new ModeloUsuario();
    $usuarioAutenticado = $usuariosModel->verificarCredenciales($usuario, $pass);

    if ($usuarioAutenticado) {
        // Guardar datos del usuario en la sesión
        $_SESSION['usuario'] = $usuarioAutenticado['nick'];
        $_SESSION['id_usuario'] = $usuarioAutenticado['id_usuario'];
        $_SESSION['id_rol'] = $usuarioAutenticado['id_rol'];

        // ✅ Agregar mensaje de bienvenida genérico
        $_SESSION['mensaje_bienvenida'] = "👋 ¡Bienvenido al sistema!";

        // Redirigir al panel principal
        header("Location: ../vista/home.php");
        exit();
    } else {
        // Usuario o contraseña incorrectos
        header("Location: ../vista/index.php?error=" . urlencode("Credenciales incorrectas"));
        exit();
    }
} else {
    // Si no se enviaron los datos del formulario, redirigir al login
    header("Location: ../vista/index.php");
    exit();
}
?>
