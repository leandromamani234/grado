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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Agua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .recibo {
            border: 1px solid #000;
            padding: 20px;
        }

        h2, h3 {
            text-align: center;
        }

        .info, .lecturas {
            width: 100%;
            margin-top: 20px;
        }

        .info td, .lecturas td {
            padding: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-style: italic;
        }

        .linea {
            border-top: 1px solid #000;
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="recibo">
    <h2>JUNTA VECINAL “BARRIO FABRIL”</h2>
    <h3>RECIBO DE CONSUMO DE AGUA POTABLE</h3>
    <div class="linea"></div>

    <table class="info">
        <tr>
            <td><strong>Nombre:</strong></td>
            <td><?= htmlspecialchars($recibo['nombre_completo']) ?></td>
        </tr>
        <tr>
            <td><strong>N° de Casa:</strong></td>
            <td><?= htmlspecialchars($recibo['numero_casa']) ?></td>
        </tr>
        <tr>
            <td><strong>Mes:</strong></td>
            <td><?= date("F Y", strtotime($recibo['fecha_lectura'])) ?></td>
        </tr>
        <tr>
            <td><strong>Fecha de Lectura:</strong></td>
            <td><?= $recibo['fecha_lectura'] ?></td>
        </tr>
    </table>

    <table class="lecturas">
        <tr>
            <td><strong>Lectura Anterior:</strong></td>
            <td><?= $recibo['lectura_anterior'] ?> m³</td>
        </tr>
        <tr>
            <td><strong>Lectura Actual:</strong></td>
            <td><?= $recibo['lectura_actual'] ?> m³</td>
        </tr>
        <tr>
            <td><strong>Consumo:</strong></td>
            <td><?= number_format($recibo['consumo_m3'], 2) ?> m³</td>
        </tr>
        <tr>
            <td><strong>Monto a Pagar:</strong></td>
            <td><strong><?= number_format($recibo['importe_bs'], 2) ?> Bs</strong></td>
        </tr>
        <tr>
            <td><strong>Observaciones:</strong></td>
            <td><?= $recibo['observaciones'] ?? 'Ninguna' ?></td>
        </tr>
    </table>

    <div class="linea"></div>
    <div class="footer">
        ¡Gracias por su pago!
    </div>
</div>

<script>
    window.print(); // Imprime automáticamente al abrir
</script>

</body>
</html>
