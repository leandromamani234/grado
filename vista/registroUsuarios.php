<?php
include_once "../includes/seguridad.php"; 
verificarRol([1]); // Solo el admin puede registrar usuarios

include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/conexion/conexionBase.php';
require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/modelo_registroRol.php';
require_once '../modelo/modelo_registroPersona.php';

$personasModel = new ModeloPersona();
$rolesModel = new ModeloRegistroRol();

$personas = $personasModel->obtenerPersonas();
$roles = $rolesModel->obtenerRoles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            width: 100%;
        }
        .form-container h1 {
            font-size: 1.9em;
            color: rgba(6, 6, 6, 0.7);
            text-align: center;
        }
        .form-container p {
            color: #000;
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group label {
            color: #000;
            font-weight: bold;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
            border: 1px solid #ccc;
            padding: 0.75rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            border-radius: 4px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
        }
        .btn-verificar {
            margin-left: 10px;
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
    </style>
</head>
<body>
<main class="form-container">
    <h1><i class="bi bi-person-plus"></i> Registro de Usuario</h1>
    <p>Complete los datos del nuevo usuario</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] == 'exito' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_usuarios.php?action=registrar" method="POST">
        <div class="form-group">
            <label for="nick">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="nick" name="nick" required>
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <div style="display: flex; gap: 10px;">
                <input type="email" class="form-control" id="email" name="email" required>
                <button type="button" class="btn btn-primary btn-verificar" onclick="verificarCorreo()">Verificar</button>
            </div>
            <small id="resultadoCorreo" style="display: block; margin-top: 5px;"></small>
        </div>

        <div class="form-group">
            <label for="pass">Contraseña:</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>

        <div class="form-group">
            <label for="persona_id_persona">Persona Asociada:</label>
            <select class="form-control" id="persona_id_persona" name="persona_id_persona" required>
                <option value="">Seleccione una persona</option>
                <?php foreach ($personas as $persona): ?>
                    <option value="<?php echo $persona['id_persona']; ?>">
                        <?php echo htmlspecialchars($persona['nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_rol">Asignar Rol:</label>
            <select class="form-control" id="id_rol" name="id_rol" required>
                <option value="">Seleccione un rol</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>">
                        <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Usuario</button>
    </form>
</main>

<script>
function verificarCorreo() {
    const email = document.getElementById('email').value.trim();
    const resultado = document.getElementById('resultadoCorreo');

    if (!email) {
        resultado.textContent = "⚠️ Ingrese un correo electrónico.";
        resultado.style.color = "orange";
        return;
    }

    fetch('../controlador/enviar_verificacion.php?email=' + encodeURIComponent(email))
        .then(response => response.json())
        .then(data => {
            resultado.textContent = data.mensaje;
            resultado.style.color = data.status === 'ok' ? 'green' : 'red';
        })
        .catch(() => {
            resultado.textContent = "⚠️ Error al verificar.";
            resultado.style.color = "orange";
        });
}
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
