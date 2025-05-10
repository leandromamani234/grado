<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,3,4]);
include_once 'cabecera.php';
include_once 'menu.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Reporte</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.96);
            margin-top: 50px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            max-width: 600px;
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }
        .btn-primary {
            width: 100%;
            font-weight: bold;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Generar Reporte PDF</h2>
    <form action="generarReportePDF.php" method="POST" id="reporteForm">
        <div class="form-group">
            <label for="tipoReporte">Tipo de Reporte</label>
            <select class="form-control" id="tipoReporte" name="tipoReporte" required>
                <option value="">-- Selecciona una opción --</option>
                <option value="socios">Reporte de Socios</option>
                <option value="deudas">Reporte de Deudas</option>
                <option value="recibos">Reporte de Recibos</option>
            </select>
        </div>

        <div class="form-group" id="estadoDiv" style="display: none;">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado">
                <!-- Se llenará con JavaScript según el tipo de reporte -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">📄 Generar PDF</button>
    </form>
</div>

<script>
document.getElementById('tipoReporte').addEventListener('change', function () {
    const estadoDiv = document.getElementById('estadoDiv');
    const estadoSelect = document.getElementById('estado');
    const tipo = this.value;

    if (tipo === 'socios') {
        estadoDiv.style.display = 'block';
        estadoSelect.innerHTML = `
            <option value="">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
            <option value="Suspendido">Suspendido</option>
        `;
    } else if (tipo === 'deudas') {
        estadoDiv.style.display = 'block';
        estadoSelect.innerHTML = `
            <option value="">Todos</option>
            <option value="Pagado">Pagado</option>
            <option value="Anulado">Anulado</option>
        `;
    } else {
        estadoDiv.style.display = 'none';
        estadoSelect.innerHTML = '';
    }
});
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
