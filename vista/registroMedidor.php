<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 3, 4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_medidor.php';
require_once '../modelo/modelo_registroPropiedades.php';

$propiedadModel = new ModeloPropiedad();
$medidorModel = new ModeloMedidor();

$propiedades = $propiedadModel->obtenerPropiedades();
$ocupadas = $medidorModel->obtenerPropiedadesConMedidor(); // <-- propiedad con medidor asignado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Medidor</title>
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
            background: rgba(255, 255, 255, 0.41);
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
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
            color: #333;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: bold;
            color: #222;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.7rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            width: 100%;
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

        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }

            .form-control {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-plus-circle"></i> Registrar Medidor</h1>
    <p>Complete los datos para registrar un nuevo medidor</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_medidor.php?action=registrar" method="POST">
        <!-- Solo números en serie -->
        <label for="serie" class="form-label">Serie del Medidor:</label>
        <input type="text" class="form-control" id="serie" name="serie" pattern="\d+" title="Solo números" required>

        <!-- Solo letras en marca -->
        <label for="marca" class="form-label">Marca del Medidor:</label>
        <input type="text" class="form-control" id="marca" name="marca" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" title="Solo letras" required>

        <label for="lectura_inicial" class="form-label">Lectura Inicial (m³):</label>
        <input type="number" step="0.01" class="form-control" id="lectura_inicial" name="lectura_inicial" required>

        <label for="id_propiedad" class="form-label">Número de Propiedad:</label>
        <select class="form-control" id="id_propiedad" name="id_propiedad" required>
            <option value="">Seleccione una Propiedad</option>
            <?php foreach ($propiedades as $propiedad): ?>
                <?php if (!in_array($propiedad['id_propiedades'], array_column($ocupadas, 'id_propiedades'))): ?>
                    <option value="<?php echo htmlspecialchars($propiedad['id_propiedades']); ?>">
                        <?php echo htmlspecialchars($propiedad['numero']); ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Registrar Medidor</button>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>