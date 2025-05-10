<?php
require_once '../modelo/conexion/conexionBase.php';

$conexion = new ConexionBase();
$conexion->CreateConnection();
$conn = $conexion->getConnection();

$usuario = 'le1059583@gmail.com';
$sql = "SELECT id_usuario, email FROM usuarios WHERE nick = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "El usuario existe en la base de datos.";
} else {
    echo "El usuario NO existe en la base de datos.";
}

$stmt->close();
$conexion->CloseConnection();
?>
