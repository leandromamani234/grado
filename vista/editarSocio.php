<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 3, 4]);

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroSocios.php';
require_once '../modelo/modelo_registroPersona.php';

if (!isset($_GET['id_persona'])) {
    echo "<p>Error: No se proporcionó un ID de socio válido.</p>";
    exit();
}

$id_persona = intval($_GET['id_persona']);
$socioModel = new ModeloSocios();
$socio = $socioModel->obtenerSocioPorId($id_persona);

if (!$socio) {
    echo "<p>Error: No se encontró información del socio con el ID proporcionado.</p>";
    exit();
}

$personaModel = new ModeloPersona();
$personasNoSocias = $personaModel->buscarPersonasNoSocias("");
$personasNoSocias[] = [
    'id_persona' => $socio['id_persona'],
    'nombre_completo' => $socio['nombre'] . ' ' . $socio['primer_apellido'] . ' ' . $socio['segundo_apellido']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Socio</title>
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
        }
        .form-container {
            background: rgba(255, 255, 255, 0.41);
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            margin: auto;
        }
        .form-container h1 {
            font-size: 1.9em;
            color: rgba(6, 6, 6, 0.7);
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .form-container p {
            color: rgb(0, 0, 0);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-container label {
            color: rgb(12, 12, 12);
            font-weight: bold;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid #ffffff;
            color: rgb(0, 0, 0);
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: rgb(15, 15, 13);
            font-weight: bold;
            width: 100%;
            padding: 0.75rem;
            margin-top: 1rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            display: inline-block;
            text-align: center;
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            text-decoration: none;
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
    <h1><i class="bi bi-person-plus"></i> Editar Socio</h1>
    <p>Modifique solo el nombre asociado al socio</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_socios.php?action=editar" method="POST">
        <input type="hidden" name="id_persona_actual" value="<?php echo htmlspecialchars($socio['id_persona']); ?>">

        <div class="form-group">
            <label for="id_persona_nueva">Persona:</label>
            <select class="form-control" id="id_persona_nueva" name="id_persona_nueva" required>
                <?php foreach ($personasNoSocias as $persona): ?>
                    <option value="<?php echo $persona['id_persona']; ?>"
                        <?php echo ($persona['id_persona'] == $socio['id_persona']) ? 'selected' : ''; ?>>
                        <?php echo $persona['nombre_completo']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Barrio fijo -->
        <input type="hidden" name="id_otb" value="1">
        <div class="form-group">
            <label>OTB:</label>
            <input type="text" class="form-control" value="Barrio Fabril" readonly>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Actualizar Socio</button>
            <a href="verSocios.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
