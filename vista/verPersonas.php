<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 4]); // Permitir acceso a admin, usuario y externo
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_registroPersona.php';
$personaModel = new ModeloPersona();
$personas = $personaModel->obtenerPersonas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Personas</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
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
            background: rgba(4, 109, 207, 0.41);
            padding: 4rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1300px;
            margin: auto;
        }

        .form-container h1 {
            font-size: 2rem;
            color: #222;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .form-container p {
            color: #333;
            text-align: center;
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
            color:rgb(51, 114, 28);
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
            background-color: rgba(255, 255, 255, 0.9);
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(to bottom right, rgb(69, 120, 255), rgb(255, 69, 69));
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            margin: 2px;
            transition: transform 0.3s ease-in-out;
        }

        .button:hover {
            transform: scale(1.1);
        }

        .button i {
            color: white;
            font-size: 1.1rem;
        }

        .table-responsive {
            overflow-x: auto;
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
    <h1><i class="bi bi-list"></i> Lista de Personas</h1>
    <p>Visualización de todas las personas registradas en el sistema</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] == 'exito' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>CI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($personas)): ?>
                    <?php foreach ($personas as $persona): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($persona['id_persona']); ?></td>
                            <td><?php echo htmlspecialchars($persona['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($persona['primer_apellido'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($persona['segundo_apellido'] ?? '-'); ?></td>
                            <td><?php echo !empty($persona['telefono']) ? htmlspecialchars($persona['telefono']) : '-'; ?></td>
                            <td><?php echo !empty($persona['celular']) ? htmlspecialchars($persona['celular']) : '-'; ?></td>
                            <td><?php echo !empty($persona['direccion']) ? htmlspecialchars($persona['direccion']) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($persona['email']); ?></td>
                            <td><?php echo !empty($persona['CI']) ? htmlspecialchars($persona['CI']) : '-'; ?></td>
                            <td>
                                <a href="editarPersona.php?id_persona=<?php echo $persona['id_persona']; ?>" class="button" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="../controlador/controlador_persona.php?action=eliminar&id_persona=<?php echo $persona['id_persona']; ?>" class="button" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta persona?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No se encontraron personas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
