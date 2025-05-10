<?php
session_start(); // Iniciar sesión

require_once '../modelo/modelo_Usuarios.php';

if (!empty($_POST['usuario']) && !empty($_POST['pass'])) {
    $usuario = trim($_POST['usuario']);
    $pass = trim($_POST['pass']);

    // Crear una instancia del modelo de usuarios
    $usuariosModel = new ModeloUsuario();

    // Verificar si el usuario existe y las credenciales son correctas
    $usuarioAutenticado = $usuariosModel->verificarCredenciales($usuario, $pass);

    if ($usuarioAutenticado) {
        // Iniciar sesión con los datos del usuario
        $_SESSION['usuario'] = $usuarioAutenticado['nick'];
        $_SESSION['id_usuario'] = $usuarioAutenticado['id_usuario'];
        $_SESSION['id_rol'] = $usuarioAutenticado['id_rol']; // ✅ Ahora sí está bien
    

        // Redirigir al panel principal o página protegida
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
