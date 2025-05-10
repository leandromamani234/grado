<?php
session_start();
// Si no hay usuario autenticado, redirigir al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/index.php");
    exit;
}

// Incluir la configuración de la base de datos
require_once "../modelo/conexion/configDb.php"; // Ajustar la ruta correctamente

// Función para verificar si el usuario tiene uno de los roles permitidos
function verificarRol($roles_permitidos) {
    global $pdo; // Conexión global

    // Obtener el rol del usuario desde la tabla usuario_rol
    $id_usuario = $_SESSION['id_usuario'];

    // Consultar el rol del usuario en la tabla usuario_rol
    $query = "SELECT id_rol FROM usuario_rol WHERE id_usuario = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id_usuario]);
    $user_rol = $stmt->fetch();

    if ($user_rol) {
        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($user_rol['id_rol'], $roles_permitidos)) {
            header("Location: ../vista/no_permisos.php"); // Redirige si no tiene permiso
            exit;
        }
    } else {
        header("Location: ../vista/no_permisos.php"); // Redirige si no tiene rol asignado
        exit;
    }
}
