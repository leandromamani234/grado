<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_deudas.php';
require_once '../modelo/modelo_registroSocios.php'; // CORREGIDO

$deudasModel = new ModeloDeudas();
$sociosModel = new ModeloSocios(); // CORREGIDO
$socios = $sociosModel->obtenerSocios(); // CORREGIDO
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Deuda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background: rgba(255, 255, 255, 0.41);
            padding: 5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
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
            color: #555;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            padding: 0.6rem;
            font-size: 1rem;
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

        .btn-primary {
            width: 100%;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-cash-stack"></i> Registrar Deuda</h1>
    <p>Complete los datos de la deuda</p>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-<?php echo $_GET['tipo'] === 'exito' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <form action="../controlador/controlador_deudas.php?action=registrar" method="POST">
        <div class="mb-3">
            <label for="id_socio" class="form-label">Socio:</label>
            <select class="form-control" id="id_socio" name="id_socio" required>
                <option value="">Seleccione un Socio</option>
                <?php foreach ($socios as $socio): ?>
                    <option value="<?php echo htmlspecialchars($socio['id_persona']); ?>">
                        <?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['primer_apellido'] . ' ' . $socio['segundo_apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="monto" class="form-label">Monto (Bs):</label>
            <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
        </div>

        <div class="mb-3">
            <label for="fecha_deuda" class="form-label">Fecha de Deuda:</label>
            <input type="date" class="form-control" id="fecha_deuda" name="fecha_deuda" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado:</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="">Seleccione el estado</option>
                <option value="Pago al día">Pago al día</option>
                <option value="En Mora">En Mora</option>
                <option value="En plan de pagos">En plan de pagos</option>
                <option value="Pasible a corte">Pasible a corte</option>
                <option value="En corte">En corte</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo_deuda" class="form-label">Tipo de Deuda:</label>
            <select class="form-control" id="tipo_deuda" name="tipo_deuda" required>
                <option value="">Seleccione el tipo de deuda</option>
                <option value="Consumo Regular">Consumo Regular</option>
                <option value="Multa + Consumo">Multa + Consumo</option>
                <option value="Reconexión + Consumo">Reconexión + Consumo</option>
                <option value="Deuda Acumulada">Deuda Acumulada</option>
                <option value="Ajuste">Ajuste</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones:</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Deuda</button>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
