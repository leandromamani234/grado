<?php
require_once 'conexion/conexionBase.php';

// Verifica si el usuario con el rol actual tiene permiso para una acción específica
function tienePermiso($entidad, $accion) {
    if (!isset($_SESSION['id_rol'])) return false;

    $idRol = $_SESSION['id_rol'];
    $con = new ConexionBase();
    $conn = $con->getConnection();

    $sql = "SELECT 1 FROM rol_permisos WHERE id_rol = ? AND entidad = ? AND accion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idRol, $entidad, $accion);
    $stmt->execute();
    $stmt->store_result();
    $permiso = $stmt->num_rows > 0;

    $stmt->close();
    $con->CloseConnection();
    return $permiso;
}

// Guarda el token para cambio de contraseña con expiración
function almacenarToken($id_usuario, $token) {
    $con = new ConexionBase();
    $conn = $con->getConnection();

    $sql = "UPDATE usuarios SET token_cambio = ?, token_expiracion = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $token, $id_usuario);
    $stmt->execute();
    $stmt->close();
    $con->CloseConnection();
}

// Verifica si el token es válido y no ha expirado
function verificarToken($token) {
    $con = new ConexionBase();
    $conn = $con->getConnection();

    $sql = "SELECT id_usuario FROM usuarios WHERE token_cambio = ? AND token_expiracion > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($id_usuario);
    $stmt->fetch();
    $stmt->close();
    $con->CloseConnection();

    return $id_usuario ?: false;
}

// Cambia la contraseña y limpia el token de recuperación
function cambiarContrasena($id_usuario, $nueva_contrasena) {
    $con = new ConexionBase();
    $conn = $con->getConnection();

    $hashed_pass = password_hash($nueva_contrasena, PASSWORD_BCRYPT);

    $sql = "UPDATE usuarios SET pass = ?, token_cambio = NULL, token_expiracion = NULL WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_pass, $id_usuario);
    $stmt->execute();
    $stmt->close();
    $con->CloseConnection();

    return true;
}
?>
