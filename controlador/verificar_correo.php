<?php
require_once '../modelo/conexion/conexionBase.php';

header('Content-Type: application/json');

$email = $_GET['email'] ?? '';
$email = trim($email);

if (empty($email)) {
    echo json_encode(['error' => 'Correo vacío']);
    exit;
}

$con = new ConexionBase();
$con->CreateConnection();
$conn = $con->getConnection();

$stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$existe = $result->num_rows > 0;

$stmt->close();
$con->CloseConnection();

echo json_encode(['existe' => $existe]);
