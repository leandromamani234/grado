<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,3,4]);
include_once 'cabecera.php';
include_once 'menu.php';
require_once '../controlador/controlador_Reporte.php';

$controladorReportes = new ControladorReportes();
$reportes = $controladorReportes->obtenerReportes();
$reporteDeudas = isset($reportes['deudas']) && is_array($reportes['deudas']) ? $reportes['deudas'] : [];

$busqueda = $_POST['busqueda'] ?? '';
$estadoFiltro = $_POST['estado'] ?? 'todos';

$reporteDeudas = array_filter($reporteDeudas, function($deuda) use ($busqueda, $estadoFiltro) {
    $coincide = stripos($deuda['id_deuda'], $busqueda) !== false ||
                stripos($deuda['nombre_completo'], $busqueda) !== false ||
                stripos($deuda['monto'], $busqueda) !== false ||
                stripos($deuda['fecha_deuda'], $busqueda) !== false ||
                stripos($deuda['estado'], $busqueda) !== false ||
                stripos($deuda['tipo_deuda'], $busqueda) !== false ||
                stripos($deuda['observaciones'], $busqueda) !== false;

    $estadoCoincide = $estadoFiltro === 'todos' || $deuda['estado'] === $estadoFiltro;
    return $coincide && $estadoCoincide;
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Deudas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 1100px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            font-weight: bold;
        }
        .form-control {
            background-color: #000;
            color: #fff;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: bold;
        }
        .btn-exportar {
            background-color: #28a745;
            color: white;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .fila-inactiva td {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Reporte de Deudas</h2>

    <!-- Formulario de filtros -->
    <form method="POST" class="mb-4">
        <div class="form-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>">
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado">
                <?php
                $estados = ["todos", "Pagado", "Anulado"];
                foreach ($estados as $estado) {
                    $selected = $estadoFiltro === $estado ? 'selected' : '';
                    echo "<option value=\"$estado\" $selected>$estado</option>";
                }
                ?>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Botón de exportación -->
    <form method="POST" action="generarReportePDF.php" target="_blank">
        <input type="hidden" name="tipoReporte" value="deudas">
        <input type="hidden" name="estado" value="<?php echo htmlspecialchars($estadoFiltro); ?>">
        <button type="submit" class="btn btn-exportar mb-3">Exportar PDF</button>
    </form>

    <!-- Tabla de resultados -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Socio</th>
                <th>Monto (Bs)</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reporteDeudas) > 0): ?>
                <?php foreach ($reporteDeudas as $deuda): ?>
                    <?php
                        $clase = in_array(strtolower($deuda['estado']), ['pagado', 'anulado']) ? 'fila-inactiva' : '';
                    ?>
                    <tr class="<?php echo $clase; ?>">
                        <td><?php echo $deuda['id_deuda']; ?></td>
                        <td><?php echo $deuda['nombre_completo']; ?></td>
                        <td><?php echo number_format($deuda['monto'], 2, '.', ','); ?></td>
                        <td><?php echo $deuda['fecha_deuda']; ?></td>
                        <td><?php echo $deuda['estado']; ?></td>
                        <td><?php echo $deuda['tipo_deuda']; ?></td>
                        <td><?php echo $deuda['observaciones']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No se encontraron resultados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include_once "pie.php"; ?>
</body>
</html>