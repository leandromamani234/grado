<?php
require_once "../includes/seguridad.php";
verificarRol([1, 2]);

require_once '../modelo/modelo_recibos.php';
$reciboModel = new ModeloRecibos();

$id_recibo = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_recibo === 0) {
    echo "Error: ID no válido.";
    exit();
}

$recibo = $reciboModel->obtenerReciboPorId($id_recibo);
if (!$recibo) {
    echo "Error: Recibo no encontrado.";
    exit();
}

// Encabezado RawBT
header("Content-Type: text/plain; charset=utf-8");

echo "================================\n";
echo " JUNTA VECINAL \"BARRIO FABRIL\"  \n";
echo "  PAPELETA DE CONSUMO DE AGUA   \n";
echo "================================\n";

echo "Nombre:          " . $recibo['nombre_completo'] . "\n";
echo "N° de Casa:      " . $recibo['numero_casa'] . "\n";
echo "Mes:             " . date("F Y", strtotime($recibo['fecha_lectura'])) . "\n";
echo "Fecha Lectura:   " . $recibo['fecha_lectura'] . "\n";
echo "--------------------------------\n";
echo "Lectura Anterior: " . str_pad($recibo['lectura_anterior'], 4, "0", STR_PAD_LEFT) . "\n";
echo "Lectura Actual:   " . str_pad($recibo['lectura_actual'], 4, "0", STR_PAD_LEFT) . "\n";
echo "Consumo (m³):     " . number_format($recibo['consumo_m3'], 2) . "\n";
echo "Monto a Pagar:    " . number_format($recibo['importe_bs'], 2) . " Bs\n";
echo "--------------------------------\n";
echo "Observaciones:    " . ($recibo['observaciones'] ?? 'Ninguna') . "\n";
echo "================================\n";
echo "     ¡Gracias por su pago!      \n";
echo "================================\n";
?>
