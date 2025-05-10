<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,3,4]);
include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroPropiedades.php';
require_once '../modelo/modelo_recibos.php';

$id_recibo = isset($_GET['id_recibo']) ? intval($_GET['id_recibo']) : 0;
if ($id_recibo === 0) {
    echo "<div class='alert alert-danger'>Error: No se proporcionó un ID de recibo válido.</div>";
    include_once "pie.php";
    exit();
}

$reciboModel = new ModeloRecibos();
$recibo = $reciboModel->obtenerReciboPorId($id_recibo);

$propiedadesModel = new ModeloPropiedad(); 
$propiedades = $propiedadesModel->obtenerPropiedades(); 

if (!$recibo) {
    echo "<div class='alert alert-danger'>Error: No se encontró el recibo solicitado.</div>";
    include_once "pie.php";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Recibo</title>
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
            background: rgba(255, 255, 255, 0.9);
            padding: 3rem;
            max-width: 600px;
            margin: 3rem auto;
            border-radius: 10px;
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
            margin-bottom: 10px;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1><i class="bi bi-pencil-square"></i> Editar Recibo</h1>
    <form action="../controlador/controlador_editarRecibo.php" method="POST">
        <input type="hidden" name="id_recibo" value="<?php echo htmlspecialchars($recibo['id_recibo']); ?>">

        <label for="numero_serie">Nº de Serie:</label>
        <input type="text" class="form-control" id="numero_serie" name="numero_serie" value="<?php echo htmlspecialchars($recibo['numero_serie']); ?>" readonly required>

        <label for="numero_propiedad">Propiedad:</label>
        <select class="form-control" id="numero_propiedad" name="id_propiedad" required>
            <option value="" disabled>Seleccione una propiedad</option>
            <?php foreach ($propiedades as $prop): ?>
                <option value="<?php echo htmlspecialchars($prop['id_propiedades']); ?>"
                    <?php echo ($prop['id_propiedades'] == $recibo['id_propiedad']) ? 'selected' : ''; ?>>
                    <?php echo 'Nº: ' . htmlspecialchars($prop['numero']) . ' | Propietario: ' . ($prop['propietario'] ?? 'Desconocido'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_lectura">Fecha de Lectura:</label>
        <input type="date" class="form-control" id="fecha_lectura" name="fecha_lectura" value="<?php echo htmlspecialchars($recibo['fecha_lectura']); ?>" required>

        <label for="lectura_anterior">Lectura Anterior:</label>
        <input type="number" step="0.01" class="form-control" id="lectura_anterior" name="lectura_anterior" value="<?php echo htmlspecialchars($recibo['lectura_anterior']); ?>" required>

        <label for="lectura_actual">Lectura Actual:</label>
        <input type="number" step="0.01" class="form-control" id="lectura_actual" name="lectura_actual" value="<?php echo htmlspecialchars($recibo['lectura_actual']); ?>" required>

        <label for="consumo_agua">Consumo de Agua (m³):</label>
        <input type="number" step="0.01" class="form-control" id="consumo_agua" name="consumo_agua" value="<?php echo htmlspecialchars($recibo['consumo_m3']); ?>" readonly required>

        <label for="monto_pagar">Monto a Pagar (Bs):</label>
        <input type="number" step="0.01" class="form-control" id="monto_pagar" name="monto_pagar" value="<?php echo htmlspecialchars($recibo['importe_bs']); ?>" required>

        <label for="observaciones">Observaciones:</label>
        <select class="form-control" id="observaciones" name="observaciones">
            <?php
            $obs = [
                "Ninguna",
                "Medidor manchado",
                "Medidor en retroceso",
                "Medidor sin funcionamiento",
                "Llave de paso en mal estado",
                "Deuda pendiente anterior mes",
                "Pasible a corte por deuda de 3 meses"
            ];
            foreach ($obs as $item) {
                echo "<option value=\"$item\"" . ($recibo['observaciones'] === $item ? ' selected' : '') . ">$item</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn-primary">Actualizar Recibo</button>
        <a href="verRecibos.php" class="btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function calcularConsumo() {
    let lecturaAnterior = parseFloat(document.getElementById('lectura_anterior').value);
    let lecturaActual = parseFloat(document.getElementById('lectura_actual').value);
    if (!isNaN(lecturaAnterior) && !isNaN(lecturaActual) && lecturaActual >= lecturaAnterior) {
        let consumoAgua = lecturaActual - lecturaAnterior;
        document.getElementById('consumo_agua').value = consumoAgua.toFixed(2);
    } else {
        document.getElementById('consumo_agua').value = 0;
    }
}

document.getElementById('lectura_actual').addEventListener('input', calcularConsumo);
document.getElementById('lectura_anterior').addEventListener('input', calcularConsumo);
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
