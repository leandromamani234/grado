<?php
require_once '../modelo/conexion/conexionBase.php';

if (isset($_GET['id_propiedad'])) {
    $idPropiedad = $_GET['id_propiedad'];

    $conexion = new ConexionBase();
    if (!$conexion->CreateConnection()) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión']);
        exit;
    }

    $conn = $conexion->getConnection();

    // 1. Buscar el último recibo
    $sqlRecibo = "SELECT lectura_actual FROM recibos WHERE id_propiedades = ? ORDER BY fecha_lectura DESC LIMIT 1";

    $stmt = $conn->prepare($sqlRecibo);
    $stmt->bind_param("i", $idPropiedad);
    $stmt->execute();
    $result = $stmt->get_result();

    $lectura_anterior = 0;

    if ($row = $result->fetch_assoc()) {
        // Hay recibo, usamos lectura actual del último
        $lectura_anterior = $row['lectura_actual'];
    } else {
        // No hay recibo → vamos a buscar la lectura inicial del medidor
        $sqlMedidor = "SELECT lectura_inicial FROM medidor WHERE id_propiedad = ? LIMIT 1";
        $stmt = $conn->prepare($sqlMedidor);
        $stmt->bind_param("i", $idPropiedad);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $lectura_anterior = $row['lectura_inicial'];
        }
    }

    echo json_encode(['success' => true, 'lectura_anterior' => $lectura_anterior]);
    $conexion->CloseConnection();
}
