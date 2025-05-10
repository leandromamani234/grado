<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,3,4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_medidor.php';
$medidorModel = new ModeloMedidor();
$medidores = $medidorModel->obtenerMedidores();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Medidores</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1200px;
            margin: auto;
        }

        .form-container h1 {
            font-size: 2rem;
            text-align: center;
            color: #222;
            margin-bottom: 0.5rem;
        }

        .form-container p {
            text-align: center;
            color: #555;
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background-color: #007bff;
            color: black;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid #333;
            background-color: rgba(255, 255, 255, 0.95);
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            border-radius: 5px;
            margin: 2px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #000;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }

            th, td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-list"></i> Ver Medidores</h1>
    <p>Lista de todos los medidores registrados</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Medidor</th>
                    <th>Serie</th>
                    <th>Marca</th>
                    <th>Número de Casa</th>
                    <th>Lectura Inicial (m³)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($medidores)): ?>
                    <?php foreach ($medidores as $medidor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medidor['id_medidor']); ?></td>
                            <td><?php echo htmlspecialchars($medidor['serie']); ?></td>
                            <td><?php echo htmlspecialchars($medidor['marca']); ?></td>
                            <td><?php echo htmlspecialchars($medidor['numero']); ?></td>
                            <td><?php echo rtrim(rtrim($medidor['lectura_inicial'], '0'), '.'); ?></td>
                            <td>
                                <a href="editarMedidor.php?id_medidor=<?php echo $medidor['id_medidor']; ?>" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="../controlador/controlador_medidor.php?action=eliminar&id_medidor=<?php echo $medidor['id_medidor']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este medidor?');">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay medidores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
