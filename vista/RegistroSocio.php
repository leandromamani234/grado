<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,3,4]);

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroPersona.php';
require_once '../modelo/modelo_registroSocios.php';

// Instancias de modelos
$personaModel = new ModeloPersona();
$socioModel = new ModeloSocios();

// Obtener todas las personas
$personas = $personaModel->obtenerPersonas();

// Obtener personas que ya son socios
$socios = $socioModel->obtenerSocios();
$idsSocios = array_column($socios, 'id_persona');

// Filtrar personas que aún no son socios
$personas = array_filter($personas, function($persona) use ($idsSocios) {
    return !in_array($persona['id_persona'], $idsSocios);
});

// Ordenar personas alfabéticamente
usort($personas, function($a, $b) {
    return strcmp($a['nombre'], $b['nombre']);
});

// OTB fija: Barrio Fabril
$otbUnica = [
    'id_otb' => 1,
    'nombre' => 'Barrio Fabril'
];

// Mensajes desde la sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo = $_SESSION['tipo'];
    unset($_SESSION['mensaje'], $_SESSION['tipo']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Socio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estilos -->
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <h1><i class="bi bi-person-plus"></i> Registro de Socio</h1>
    <p>Complete los datos del socio</p>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <form action="../controlador/controlador_socios.php?action=registrar" method="POST">
        <div class="form-group">
            <label for="id_persona">Persona:</label>
            <select class="form-control" id="id_persona" name="id_persona" required>
                <option value="">Seleccione una persona</option>
                <?php foreach ($personas as $p): ?>
                    <option value="<?php echo $p['id_persona']; ?>">
                        <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['primer_apellido'] . ' ' . $p['segundo_apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="estado">Estado:</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="">Seleccione un estado</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <option value="Suspendido">Suspendido</option>
            </select>
        </div>

        <input type="hidden" name="id_otb" value="<?php echo $otbUnica['id_otb']; ?>">
        <div class="form-group">
            <label>OTB:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($otbUnica['nombre']); ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Socio</button>
    </form>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_persona').select2({
            placeholder: "Seleccione una persona...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
