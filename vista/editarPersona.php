<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2, 4]); // Permitir acceso a admin (1), usuario (2) y externo (3)
include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroPersona.php';

// Obtener el ID de la persona desde la URL
$id_persona = isset($_GET['id_persona']) ? intval($_GET['id_persona']) : 0;

if ($id_persona == 0) {
    header("Location: verPersonas.php?error=ID de persona no válido");
    exit();
}

// Obtener los datos de la persona
$personaModel = new ModeloPersona();
$persona = $personaModel->obtenerPersonaPorId($id_persona);

if (!$persona) {
    header("Location: verPersonas.php?error=Persona no encontrada");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Editar Persona</title>
    <style>
        /* Fondo y estilo de formulario similar al de registro */
        body {
            background-image: url('images/imag.jpg'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .app-content {
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            color: white;
            margin-top: 50px;
        }

        .app-title {
            margin-bottom: 20px;
        }

        h1 {
            color: white;
            background-color: rgba(0, 0, 51, 0.7); /* Fondo semitransparente */
            padding: 10px;
            border-radius: 5px;
            font-size: 1.8em;
            display: inline-block;
        }

        p {
            color: #cccccc;
            font-size: 1em;
            margin-top: 5px;
            display: inline-block;
            margin-left: 10px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <main class="app-content">
        <div class="app-title">
            <h1><i class="bi bi-pencil"></i> Editar Persona</h1>
            <p>Modifique los datos de la persona</p>
        </div>

        <form action="../controlador/controlador_persona.php?action=actualizar" method="POST">
            <input type="hidden" name="id_persona" value="<?php echo htmlspecialchars($persona['id_persona']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($persona['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="primer_apellido">Primer Apellido:</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($persona['primer_apellido']); ?>" required>
            </div>
            <div class="form-group">
                <label for="segundo_apellido">Segundo Apellido:</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($persona['segundo_apellido']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($persona['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="<?php echo htmlspecialchars($persona['celular']); ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($persona['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($persona['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="CI">CI:</label>
                <input type="text" class="form-control" id="CI" name="CI" value="<?php echo htmlspecialchars($persona['CI']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Persona</button>
            <a href="verPersonas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>

    <?php include_once "pie.php"; ?>
</body>
</html>
