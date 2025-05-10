<?php 
include_once "../includes/seguridad.php"; 
verificarRol([1, 3, 4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_registroSocios.php';
require_once '../modelo/modelo_registroPropiedades.php';

$socio_modelo = new ModeloSocios();
$socios = $socio_modelo->obtenerSocios();

$propiedad_modelo = new ModeloPropiedad();
$propiedades_existentes = $propiedad_modelo->obtenerPropiedades();

$ocupadosGlobales = [];
$socios_con_propiedad = [];

foreach ($propiedades_existentes as $prop) {
    $ocupadosGlobales[] = (int)$prop['numero'];
    $socios_con_propiedad[] = $prop['id_socio'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Propiedad</title>
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
            color: rgba(6, 6, 6, 0.8);
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .form-container p {
            color: #000000;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-container label {
            color: #0c0c0c;
            font-weight: bold;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid #ffffff;
            color: #000000;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #0f0f0d;
            font-weight: bold;
            width: 100%;
            padding: 0.75rem;
            margin-top: 1rem;
        }
        .alert {
            text-align: center;
            padding: 1rem;
            border-radius: 5px;
            font-size: 1rem;
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
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-house"></i> Registro de Propiedad</h1>
    <p>Complete los datos de la propiedad</p>

    <!-- Bloque de mensajes de éxito o error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_propiedades.php?action=registrar" method="POST">
        <div class="form-group">
            <label for="manzano">Manzano:</label>
            <select class="form-control" id="manzano" name="manzano" required>
                <option value="">Seleccione un manzano</option>
                <?php foreach (range('A', 'Z') as $letra): ?>
                    <option value="<?php echo $letra; ?>"><?php echo $letra; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="numero">Número:</label>
            <select class="form-control" id="numero" name="numero" required>
                <option value="">Seleccione un número</option>
                <!-- Se llenará por JavaScript -->
            </select>
        </div>

        <div class="form-group">
            <label for="id_persona">Socio:</label>
            <select class="form-control" id="id_persona" name="id_persona" required>
                <option value="">Seleccione un socio</option>
                <?php foreach ($socios as $socio): ?>
                    <?php if (!in_array($socio['id_persona'], $socios_con_propiedad)): ?>
                        <option value="<?php echo $socio['id_persona']; ?>">
                            <?php echo $socio['nombre'] . ' ' . $socio['primer_apellido'] . ' ' . $socio['segundo_apellido']; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Propiedad</button>
    </form>
</main>

<script>
    // Números ya usados (sin importar manzano)
    const ocupadosGlobales = <?php echo json_encode($ocupadosGlobales); ?>;
    const numeroSelect = document.getElementById('numero');

    function llenarNumerosDisponibles() {
        numeroSelect.innerHTML = '<option value="">Seleccione un número</option>';
        for (let i = 1; i <= 215; i++) {
            if (!ocupadosGlobales.includes(i)) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                numeroSelect.appendChild(option);
            }
        }
    }

    // Llenar los números disponibles al cargar
    llenarNumerosDisponibles();
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
