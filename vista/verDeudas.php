<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 4]); 

include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_deudas.php';
require_once '../modelo/modelo_registroSocios.php';

$deudasModel = new ModeloDeudas();
$sociosModel = new ModeloSocios();

if (isset($_GET['id_persona'])) {
    $id_persona = intval($_GET['id_persona']);
    $deudas = $deudasModel->obtenerDeudasPorPersona($id_persona);
} else {
    $deudas = $deudasModel->obtenerDeudas();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Deudas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .app-content {
            padding: 4rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            margin: 2rem auto;
            max-width: 1200px;
        }
        .app-title h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }
        .app-title p {
            color: #555;
            text-align: center;
            margin-bottom: 2rem;
        }
        .alert {
            font-size: 1rem;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .alert-warning { background-color: #fff3cd; color: #856404; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        th, td {
            padding: 0.75rem;
            text-align: center;
            border: 1px solid #333;
        }
        .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            margin: 2px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-success { background-color: #28a745; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger  { background-color: #dc3545; color: white; }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        @media screen and (min-width: 768px) {
            .actions {
                flex-direction: row;
                justify-content: center;
            }
        }
        .fila-pagado td, .fila-anulado td {
            background-color: #f8d7da !important;
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body>

<main class="app-content">
    <div class="app-title">
        <h1><i class="bi bi-cash-stack"></i> Lista de Deudas</h1>
        <p><?php echo isset($id_persona) ? "Deudas del socio ID: " . $id_persona : "Deudas registradas en el sistema"; ?></p>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-<?php echo $_GET['tipo'] === 'exito' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <div class="tile">
        <div class="tile-body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Monto (Bs)</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($deudas)): ?>
                        <?php foreach ($deudas as $deuda): ?>
                            <?php
                                $estado = strtolower($deuda['estado']);
                                $inactiva = $estado === 'pagado' || $estado === 'anulado';
                                $clase = $inactiva ? 'fila-' . $estado : '';
                            ?>
                            <tr class="<?php echo $clase; ?>">
                                <td><?php echo $deuda['id_deuda']; ?></td>
                                <td><?php echo $deuda['nombre_completo']; ?></td>
                                <td><?php echo $deuda['monto']; ?></td>
                                <td><?php echo $deuda['fecha_deuda']; ?></td>
                                <td><?php echo ucfirst($deuda['estado']); ?></td>
                                <td><?php echo $deuda['tipo_deuda']; ?></td>
                                <td><?php echo $deuda['observaciones']; ?></td>
                                <td>
                                    <div class="actions">
                                        <?php if (!$inactiva): ?>
                                            <form action="../controlador/controlador_deudas.php?action=pagar" method="POST">
                                                <input type="hidden" name="id_deuda" value="<?php echo $deuda['id_deuda']; ?>">
                                                <button type="submit" class="btn btn-success">Pagar</button>
                                            </form>
                                            <a href="editarDeuda.php?id=<?php echo $deuda['id_deuda']; ?>" class="btn btn-warning">Editar</a>
                                            <form action="../controlador/controlador_deudas.php?action=anular" method="POST">
                                                <input type="hidden" name="id_deuda" value="<?php echo $deuda['id_deuda']; ?>">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Deseas anular esta deuda?');">Anular</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #dc3545;">Sin acciones</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="alert alert-warning">No se encontraron deudas registradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
