<?php
include_once "../includes/seguridad.php"; 
verificarRol([1]); // Permitir acceso a admin (1), usuario (2) y externo (3)

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/conexion/conexionBase.php';
require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/modelo_registroRol.php';

// Verificar que el parámetro id_usuario exista en la URL
if (isset($_GET['id_usuario'])) {
    $id_usuario = intval($_GET['id_usuario']);
} else {
    header("Location: verUsuarios.php?mensaje=ID de usuario no proporcionado&tipo=error");
    exit;
}

// Instancia del modelo para obtener los datos del usuario
$usuariosModel = new ModeloUsuario(); 
$usuario = $usuariosModel->obtenerUsuarioPorId($id_usuario); 

// Verificar que el usuario exista
if (!$usuario) {
    header("Location: verUsuarios.php?mensaje=Usuario no encontrado&tipo=error");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Editar Usuario</title>
    <style>
        body {
            background-image: url('images/imagen5.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .app-content {
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="bi bi-person-badge"></i> Editar Usuario</h1>
                <p>Modifique los datos del usuario</p>
            </div>
        </div>

        <!-- Formulario para editar usuario -->
        <form action="../controlador/controlador_usuarios.php?action=editar&id_usuario=<?php echo $id_usuario; ?>" method="POST">
            <div class="form-group">
                <label for="nick">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="nick" name="nick" value="<?php echo htmlspecialchars($usuario['nick']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="pass">Nueva Contraseña (opcional):</label>
                <input type="password" class="form-control" id="pass" name="pass">
                <small>Dejar en blanco si no deseas cambiar la contraseña.</small>
            </div>

            <div class="form-group">
                <label for="id_rol">Asignar Rol:</label>
                <select class="form-control" id="id_rol" name="id_rol" required>
                    <option value="" disabled>Seleccione un rol</option>
                    <?php
                    $rolesModel = new ModeloRegistroRol();
                    $roles = $rolesModel->obtenerRoles();
                    foreach ($roles as $rol) {
                        $selected = $rol['id_rol'] == $usuario['id_rol'] ? 'selected' : '';
                        echo "<option value='{$rol['id_rol']}' $selected>" . htmlspecialchars($rol['nombre_rol']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </main>

    <?php include_once "pie.php"; ?>
</body>
</html>
