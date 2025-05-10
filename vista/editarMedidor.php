<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,3,4]);
include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_medidor.php';

$medidorModel = new ModeloMedidor();
$id_medidor = isset($_GET['id_medidor']) ? intval($_GET['id_medidor']) : 0;

if ($id_medidor > 0) {
    $medidor = $medidorModel->obtenerMedidorPorId($id_medidor);
    if (!$medidor) {
        echo "<p>Error: No se encontró el medidor con el ID proporcionado.</p>";
        include_once "pie.php";
        exit();
    }
} else {
    echo "<p>Error: No se proporcionó un ID de medidor válido.</p>";
    include_once "pie.php";
    exit();
}

$propiedades = $medidorModel->obtenerPropiedades();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Medidor</title>
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
            font-family: Arial, sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.41);
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
            margin: 5% auto;
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

        .btn-primary, .btn-secondary {
            border: none;
            padding: 0.7rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
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
    <h1><i class="bi bi-pencil-square"></i> Editar Medidor</h1>
    <p>Modifique los datos del medidor seleccionado</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_medidor.php?action=editar" method="POST">
        <!-- ID oculto -->
        <input type="hidden" name="id_medidor" value="<?php echo htmlspecialchars($id_medidor); ?>">

        <!-- Serie (solo números) -->
        <label for="serie" class="form-label">Serie del Medidor:</label>
        <input type="text" class="form-control" id="serie" name="serie"
               value="<?php echo htmlspecialchars($medidor['serie']); ?>"
               pattern="\d+" title="Solo números" required>

        <!-- Marca (solo letras) -->
        <label for="marca" class="form-label">Marca del Medidor:</label>
        <input type="text" class="form-control" id="marca" name="marca"
               value="<?php echo htmlspecialchars($medidor['marca']); ?>"
               pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" title="Solo letras" required>

        <!-- Lectura inicial -->
        <label for="lectura_inicial" class="form-label">Lectura Inicial (m³):</label>
        <input type="number" step="0.01" class="form-control" id="lectura_inicial" name="lectura_inicial"
               value="<?php echo htmlspecialchars($medidor['lectura_inicial']); ?>" required>

        <!-- Propiedad asociada (solo número de casa) -->
        <label for="id_propiedad" class="form-label">Número de Casa:</label>
        <select class="form-control" id="id_propiedad" name="id_propiedad" required>
            <option value="">Seleccione una propiedad</option>
            <?php foreach ($propiedades as $propiedad): ?>
                <option value="<?php echo $propiedad['id_propiedades']; ?>"
                    <?php echo ($medidor['id_propiedades'] == $propiedad['id_propiedades']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($propiedad['numero']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Botones -->
        <button type="submit" class="btn btn-primary">Actualizar Medidor</button>
        <a href="verMedidores.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
