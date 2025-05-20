<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 3, 4]);

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_recibos.php';

$reciboModel = new ModeloRecibos();
$mensaje = '';
$recibos = [];

if (isset($_GET['id_propiedad']) && is_numeric($_GET['id_propiedad'])) {
    $id_propiedad = intval($_GET['id_propiedad']);
    $recibos = $reciboModel->obtenerRecibosPorPropiedad($id_propiedad);
    $mensaje = empty($recibos)
        ? "Esta propiedad no tiene recibos registrados."
        : "Se encontraron " . count($recibos) . " recibos para esta propiedad.";
} elseif (isset($_GET['id_socio']) && is_numeric($_GET['id_socio'])) {
    $id_socio = intval($_GET['id_socio']);
    $recibos = $reciboModel->obtenerRecibosPorSocio($id_socio);
    $mensaje = empty($recibos)
        ? "Este socio no tiene ningún recibo registrado."
        : "Se encontraron " . count($recibos) . " recibos para este socio.";
} else {
    $recibos = $reciboModel->obtenerTodosLosRecibos();
    $mensaje = "Mostrando todos los recibos registrados.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Recibos</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            max-width: 1300px;
            margin: 2rem auto;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            font-size: 2rem;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            border: 1px solid #333;
            text-align: center;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        .alert {
            padding: 1rem;
            text-align: center;
            margin-top: 1rem;
            border-radius: 5px;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .btn-accion {
            margin: 0 4px;
            padding: 6px 10px;
            font-size: 0.85rem;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-editar {
            background-color: #ffc107;
            color: black;
        }
        .btn-eliminar {
            background-color: #dc3545;
            color: white;
        }
        .btn-imprimir {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="bi bi-receipt"></i> Recibos</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert <?php echo $_GET['tipo'] === 'exito' ? 'alert-info' : 'alert-warning'; ?>">
            <?php echo htmlspecialchars(urldecode($_GET['mensaje'])); ?>
        </div>
    <?php else: ?>
        <div class="alert <?php echo empty($recibos) ? 'alert-warning' : 'alert-info'; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($recibos)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>N° Serie</th>
                    <th>Fecha Lectura</th>
                    <th>N° Casa</th>
                    <th>Lectura Anterior</th>
                    <th>Lectura Actual</th>
                    <th>Consumo (m³)</th>
                    <th>Importe (Bs)</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recibos as $recibo): ?>
                    <tr>
                        <td><?php echo $recibo['id_recibo']; ?></td>
                        <td><?php echo $recibo['numero_serie']; ?></td>
                        <td><?php echo $recibo['fecha_lectura']; ?></td>
                        <td><?php echo $recibo['numero_casa']; ?></td>
                        <td><?php echo $recibo['lectura_anterior']; ?></td>
                        <td><?php echo $recibo['lectura_actual']; ?></td>
                        <td><?php echo $recibo['consumo_m3']; ?></td>
                        <td><?php echo $recibo['importe_bs']; ?></td>
                        <td><?php echo $recibo['observaciones']; ?></td>
                        <td>
                            <a href="editarRecibo.php?id_recibo=<?php echo $recibo['id_recibo']; ?>" class="btn-accion btn-editar"><i class="bi bi-pencil-square"></i></a>
                            <a href="../controlador/controlador_eliminarRecibo.php?id=<?php echo $recibo['id_recibo']; ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este recibo?');"><i class="bi bi-trash-fill"></i></a>
                            <a href="imprimirRecibo.php?id=<?php echo $recibo['id_recibo']; ?>" class="btn btn-success" target="_blank">
  🖨️ RawBT
</a>


                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include_once "pie.php"; ?>
</body>
</html>
