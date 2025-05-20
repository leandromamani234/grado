<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,3,4]);
include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroPropiedades.php';
require_once '../modelo/modelo_recibos.php';

$propiedadesModel = new ModeloPropiedad(); 
$propiedades = $propiedadesModel->obtenerPropiedades(); 

$reciboModel = new ModeloRecibos();
$numeroSerie = $reciboModel->obtenerSiguienteNumeroSerie(); // Este método ya lo tienes en tu modelo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Recibo</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-attachment: fixed;
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
        h1 {
            text-align: center;
            color: #222;
        }
        label {
            font-weight: bold;
            margin-top: 1rem;
        }
        .form-control {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
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
    </style>
</head>
<body>

<div class="form-container">
    <h1><i class="bi bi-receipt"></i> Registrar Recibo</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert <?php echo $_GET['tipo'] === 'exito' ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo htmlspecialchars(urldecode($_GET['mensaje'])); ?>
        </div>
    <?php endif; ?>

    <form action="../controlador/controlador_recibos.php" method="POST">
        <!-- N° de Serie (solo lectura) -->
        <label for="numero_serie">N° de Serie:</label>
        <input type="text" class="form-control" id="numero_serie" name="numero_serie" value="<?php echo $numeroSerie; ?>" readonly required>

        <!-- Propiedad -->
        <label for="numero_propiedad">Propiedad:</label>
        <select class="form-control" id="numero_propiedad" name="id_propiedad" required onchange="obtenerLecturaAnterior(this.value)">
            <option value="" disabled selected>Seleccione una propiedad</option>
            <?php foreach ($propiedades as $propiedad): ?>
                <option value="<?php echo $propiedad['id_propiedades']; ?>">
                    <?php echo 'N°: ' . $propiedad['numero'] . ' | Propietario: ' . ($propiedad['propietario'] ?? 'Desconocido'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Campos de lectura -->
        <label for="fecha_lectura">Fecha de Lectura:</label>
        <input type="date" class="form-control" id="fecha_lectura" name="fecha_lectura" required value="<?php echo date('Y-m-d'); ?>">


        <label for="lectura_anterior">Lectura Anterior:</label>
<input type="number" step="0.01" class="form-control" id="lectura_anterior" value="0" readonly>


        <label for="lectura_actual">Lectura Actual:</label>
        <input type="number" step="0.01" class="form-control" id="lectura_actual" name="lectura_actual" required>

        <label for="consumo_agua">Consumo de Agua (m³):</label>
        <input type="number" step="0.01" class="form-control" id="consumo_agua" name="consumo_agua" readonly required>

        <label for="monto_pagar">Monto a Pagar (Bs):</label>
        <input type="number" step="0.01" class="form-control" id="monto_pagar" name="monto_pagar" required>

        <label for="observaciones">Observaciones:</label>
        <select class="form-control" id="observaciones" name="observaciones">
            <option value="Ninguna">Ninguna</option>
            <option value="Medidor manchado">Medidor manchado</option>
            <option value="Medidor en retroceso">Medidor en retroceso</option>
            <option value="Medidor sin funcionamiento">Medidor sin funcionamiento</option>
            <option value="Llave de paso en mal estado">Llave de paso en mal estado</option>
            <option value="Deuda pendiente anterior mes">Deuda pendiente anterior mes</option>
            <option value="Pasible a corte por deuda de 3 meses">Pasible a corte por deuda de 3 meses</option>
        </select>

        <button type="submit" class="btn btn-primary">Registrar Recibo</button>
    </form>
</div>

<script>
function obtenerLecturaAnterior(idPropiedad) {
    if (idPropiedad) {
        fetch(`../controlador/controlador_medidor.php?action=obtenerLectura&id_propiedad=${idPropiedad}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('lectura_anterior').value = data.lectura_anterior;
                calcularConsumoYMonto();
            } else {
                alert('No se pudo obtener la lectura anterior');
            }
        })
        .catch(error => console.log('Error:', error));
    }
}

function calcularConsumoYMonto() {
    let lecturaAnterior = parseFloat(document.getElementById('lectura_anterior').value);
    let lecturaActual = parseFloat(document.getElementById('lectura_actual').value);
    let consumoMaximoPermitido = 10;
    let costoBase = 15;
    let costoExcedentePorCubo = 2;

    if (!isNaN(lecturaAnterior) && !isNaN(lecturaActual) && lecturaActual >= lecturaAnterior) {
        let consumoAgua = lecturaActual - lecturaAnterior;
        document.getElementById('consumo_agua').value = consumoAgua.toFixed(2);

        let montoPagar = costoBase;
        if (consumoAgua > consumoMaximoPermitido) {
            let excedente = consumoAgua - consumoMaximoPermitido;
            montoPagar += excedente * costoExcedentePorCubo;
        }
        document.getElementById('monto_pagar').value = montoPagar.toFixed(2);
    } else {
        document.getElementById('consumo_agua').value = 0;
        document.getElementById('monto_pagar').value = costoBase.toFixed(2);
    }
}

document.getElementById('lectura_actual').addEventListener('input', calcularConsumoYMonto);
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
