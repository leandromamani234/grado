<?php
include_once "../includes/seguridad.php"; 
verificarRol([1]); // Solo el admin puede ver esta vista

include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/conexion/conexionBase.php';
require_once '../modelo/modelo_Usuarios.php';

$usuariosModel = new ModeloUsuario();
$usuarios = $usuariosModel->obtenerUsuariosConRoles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Usuarios</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imagen5.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .app-content {
            background: rgba(255, 255, 255, 0.9);
            padding: 4rem;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 1100px;
            width: 95%;
            margin: auto;
        }

        .app-title h1 {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .app-title p {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 1rem;
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
            text-align: center;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid #ccc;
        }

        thead {
            background-color: rgba(0, 0, 0, 0.1);
            font-weight: bold;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<main class="app-content">
    <div class="app-title">
        <h1><i class="bi bi-person-badge"></i> Ver Usuarios Registrados</h1>
        <p>Lista de usuarios registrados en el sistema</p>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-<?php echo $_GET['tipo'] == 'exito' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <div class="tile">
        <div class="tile-body">
            <table>
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre de Usuario</th>
                        <th>Correo</th>
                        <th>Nombre Completo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nick']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol'] ?? 'Sin Rol'); ?></td>
                                <td>
                                    <a href="editarUsuario.php?id_usuario=<?php echo $usuario['id_usuario']; ?>" class="btn btn-primary">Editar</a>
                                    <a href="../controlador/controlador_usuarios.php?action=eliminar&id_usuario=<?php echo $usuario['id_usuario']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
