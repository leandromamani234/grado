<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,3,4]);
include_once 'cabecera.php';
include_once 'menu.php';
require_once '../controlador/controlador_Reporte.php';

$controladorReportes = new ControladorReportes();
$reporteSocios = $controladorReportes->obtenerReportes()['socios'];

$busqueda = '';
if (isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];
    $reporteSocios = array_filter($reporteSocios, function($socio) use ($busqueda) {
        return stripos($socio['nombre_completo'], $busqueda) !== false || 
               stripos($socio['estado'], $busqueda) !== false || 
               stripos($socio['nombre_otb'], $busqueda) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Socios</title>
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 900px;
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
            border-radius: 8px;
            font-weight: bold;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Reporte de Socios</h2>

    <div class="filters mb-4">
        <form method="POST" class="flex-grow-1">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por Nombre, Estado u OTB" value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" class="btn btn-primary mt-2">Buscar</button>
        </form>

        <!-- Exportar PDF sin descarga (abre en pestaña nueva) -->
        <form action="generarReportePDF.php" method="POST" target="_blank">
            <input type="hidden" name="tipoReporte" value="socios">
            <label class="font-weight-bold text-dark mt-2">Estado:</label>
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <option value="Suspendido">Suspendido</option>
            </select>
            <button type="submit" class="btn btn-success mt-2">📄 Exportar PDF</button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>Estado</th>
                <th>OTB</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($reporteSocios) > 0) {
                $n = 1;
                foreach ($reporteSocios as $socio) {
                    echo "<tr>
                            <td>{$n}</td>
                            <td>{$socio['nombre_completo']}</td>
                            <td>{$socio['estado']}</td>
                            <td>{$socio['nombre_otb']}</td>
                          </tr>";
                    $n++;
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No se encontraron socios.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include_once "pie.php"; ?>
</body>
</html>
