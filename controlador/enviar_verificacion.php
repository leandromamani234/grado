<?php
require_once '../modelo/modelo_Usuarios.php';

if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Correo no proporcionado']);
    exit;
}

$correo = trim($_GET['email']);
$modelo = new ModeloUsuario();

if ($modelo->correoExiste($correo)) {
    echo json_encode(['status' => 'existe', 'mensaje' => '❌ Esta cuenta no está disponible. Por favor, ingrese otra.']);
} else {
    echo json_encode(['status' => 'disponible', 'mensaje' => '✅ Este correo está disponible.']);
}
