<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,3,4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_registroSocios.php';
$socioModel = new ModeloSocios();
$socios = $socioModel->obtenerSocios();

if ($socios === false) {
    echo "<p>Error: No se pudo obtener la lista de socios.</p>";
    include_once "pie.php";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Socios</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .form-container {
            background: rgba(255,255,255,0.9);
            padding: 2rem;
            max-width: 1300px;
            margin: 2rem auto;
            border-radius: 10px;
        }

        .form-container h1 {
            text-align: center;
            font-size: 2.5rem;
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

        tr.activo {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        tr.inactivo {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        tr.suspendido {
            background-color: #ffffff;
            color: black;
            font-weight: bold;
        }

        .btn-estado {
            padding: 6px 12px;
            border-radius: 5px;
            border: 1px solid #00000011;
            cursor: pointer;
            margin: 0 2px;
            background-color: #e0e0e0;
        }

        .acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: center;
        }

        .acciones a, .acciones form {
            display: inline-block;
        }

        .acciones i {
            font-size: 1rem;
            color: white;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-deuda { background-color: #17a2b8; }
        .btn-recibo { background-color: #6f42c1; }
        .btn-editar { background-color: #007bff; }
        .btn-eliminar { background-color: #dc3545; }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-list"></i> Ver Socios</h1>

    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>OTB</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($socios as $socio): ?>
                <tr class="<?php echo strtolower($socio['estado']); ?>">
                    <td><?php echo $socio['id_persona']; ?></td>
                    <td><?php echo $socio['nombre'] . ' ' . $socio['primer_apellido'] . ' ' . $socio['segundo_apellido']; ?></td>
                    <td>
                        <form action="../controlador/controlador_socios.php?action=cambiar_estado" method="POST">
                            <input type="hidden" name="id_persona" value="<?php echo $socio['id_persona']; ?>">
                            <button type="submit" name="estado" value="Activo" class="btn-estado">Activo</button>
                            <button type="submit" name="estado" value="Inactivo" class="btn-estado">Inactivo</button>
                            <button type="submit" name="estado" value="Suspendido" class="btn-estado">Suspendido</button>
                        </form>
                    </td>
                    <td>Barrio Fabril</td>
                    <td>
                        <div class="acciones">
                            <a href="verDeudas.php?id_persona=<?php echo $socio['id_persona']; ?>" class="btn btn-deuda" title="Ver Deudas">
                                <i class="bi bi-cash"></i>
                            </a>
                            <a href="editarSocio.php?id_persona=<?php echo $socio['id_persona']; ?>" class="btn btn-editar" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="../controlador/controlador_socios.php?action=eliminar" method="POST" onsubmit="return confirm('¿Deseas eliminar este socio?');">
                                <input type="hidden" name="id_persona" value="<?php echo $socio['id_persona']; ?>">
                                <button type="submit" class="btn btn-eliminar" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
