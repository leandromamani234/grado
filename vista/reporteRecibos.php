<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 3, 4]);

include_once "cabecera.php";
include_once "menu.php";
require_once '../controlador/controlador_Reporte.php';

$controladorReportes = new ControladorReportes();
$reportes = $controladorReportes->obtenerReportes();
$reporteRecibos = isset($reportes['recibos']) && is_array($reportes['recibos']) ? $reportes['recibos'] : [];

$busqueda = $_POST['busqueda'] ?? '';

// Filtro por búsqueda
if (!empty($busqueda)) {
    $reporteRecibos = array_filter($reporteRecibos, function($recibo) use ($busqueda) {
        return stripos($recibo['nombre'], $busqueda) !== false || 
               stripos($recibo['numero_casa'], $busqueda) !== false || 
               stripos($recibo['importe_bs'], $busqueda) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Recibos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 1200px;
        }
        h2 {
            text-align: center;
            color: #007bff;
            font-weight: bold;
        }
        .form-control {
            background-color: #000;
            color: #fff;
            border-radius: 8px;
            border: 1px solid #007bff;
        }
        .btn-primary, .btn-success {
            font-weight: bold;
            border-radius: 8px;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Reporte de Recibos Emitidos</h2>

    <form method="POST" class="mb-3 d-flex gap-3">
        <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre, número de casa o importe" value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <form action="generarReportePDF.php" method="POST" target="_blank" class="mb-4">
        <input type="hidden" name="tipoReporte" value="recibos">
        <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit" class="btn btn-success">📄 Exportar PDF</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Serie</th>
                <th>Fecha</th>
                <th>N° Casa</th>
                <th>Lectura Anterior</th>
                <th>Lectura Actual</th>
                <th>Consumo (m³)</th>
                <th>Importe (Bs)</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reporteRecibos)): ?>
                <?php foreach ($reporteRecibos as $recibo): ?>
                    <tr>
                        <td><?php echo $recibo['id_recibo']; ?></td>
                        <td><?php echo $recibo['numero_serie']; ?></td>
                        <td><?php echo $recibo['fecha_lectura']; ?></td>
                        <td><?php echo $recibo['numero_casa']; ?></td>
                        <td><?php echo $recibo['lectura_anterior']; ?></td>
                        <td><?php echo $recibo['lectura_actual']; ?></td>
                        <td><?php echo $recibo['consumo_m3']; ?></td>
                        <td><?php echo number_format($recibo['importe_bs'], 2, '.', ','); ?></td>
                        <td><?php echo $recibo['observaciones']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No se encontraron resultados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include_once "pie.php"; ?>
</body>
</html>
