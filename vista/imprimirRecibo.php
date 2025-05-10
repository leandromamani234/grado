<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2]);

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_recibos.php';

$reciboModel = new ModeloRecibos();

// ✅ CAMBIO: ahora se usa 'id' en lugar de 'id_recibo'
$id_recibo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_recibo === 0) {
    echo "Error: No se proporcionó un ID de recibo válido.";
    exit();
}

$recibo = $reciboModel->obtenerReciboPorId($id_recibo);

if (!$recibo) {
    echo "Error: No se encontró el recibo con el ID proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Recibo</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f9f9f9;
        }

        .receipt-container {
            width: 10cm;
            margin: auto;
            padding: 20px;
            border: 1px dashed #333;
            background: white;
            font-size: 13px;
        }

        h3 {
            text-align: center;
            margin: 0;
            padding-bottom: 10px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
        }

        .info {
            margin: 15px 0;
        }

        .info p {
            margin: 4px 0;
        }

        .info strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        table td {
            padding: 4px 0;
            border-bottom: 1px dotted #ccc;
        }

        .footer {
            margin-top: 10px;
            border-top: 1px dashed #999;
            padding-top: 10px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }

        .print-button {
            margin-top: 20px;
            display: block;
            width: fit-content;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: auto;
            margin-right: auto;
        }

        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <h3>JUNTA VECINAL "BARRIO FABRIL"<br>PAPELETA DE CONSUMO DE AGUA</h3>

    <div class="info">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($recibo['nombre_completo'] ?? 'N/A'); ?></p>
        <p><strong>N° de Casa:</strong> <?php echo htmlspecialchars($recibo['numero_casa'] ?? 'N/A'); ?></p>
        <p><strong>Mes:</strong> <?php echo date("F Y", strtotime($recibo['fecha_lectura'])); ?></p>
        <p><strong>Fecha de Lectura:</strong> <?php echo htmlspecialchars($recibo['fecha_lectura']); ?></p>
    </div>

    <table>
        <tr>
            <td><strong>Lectura Anterior:</strong></td>
            <td><?php echo str_pad(number_format($recibo['lectura_anterior'], 0), 4, '0', STR_PAD_LEFT); ?></td>
        </tr>
        <tr>
            <td><strong>Lectura Actual:</strong></td>
            <td><?php echo str_pad(number_format($recibo['lectura_actual'], 0), 4, '0', STR_PAD_LEFT); ?></td>
        </tr>
        <tr>
            <td><strong>Consumo m³:</strong></td>
            <td><?php echo number_format($recibo['consumo_m3'], 2); ?></td>
        </tr>
        <tr>
            <td><strong>Monto a Pagar:</strong></td>
            <td><strong><?php echo number_format($recibo['importe_bs'], 2); ?> Bs</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Observaciones:</strong> <?php echo htmlspecialchars($recibo['observaciones'] ?? 'Ninguna'); ?></p>
    </div>
</div>

<button class="print-button" onclick="window.print();">Imprimir</button>

</body>
</html>

