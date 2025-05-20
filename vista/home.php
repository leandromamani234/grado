<?php
include_once "cabecera.php";
include_once "menu.php";
require_once '../controlador/controlador_home.php';

$controladorHome = new ControladorHome();
$estadisticas = $controladorHome->obtenerEstadisticas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Panel de Control</title>
    <style>
        body {
            background-image: url('images/imag.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card {
            text-align: center;
            padding: 20px;
            margin: 10px;
            color: white;
            border-radius: 10px;
        }
        .socios { background-color: #ff6666; }
        .recibos { background-color: #9933ff; }
        .deudas { background-color: #33cc33; }
        .lecturas { background-color: #ffcc00; }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 30px;
        }

        .alert-bienvenida {
            text-align: center;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 20px;
            background-color: #198754;
            color: white;
            padding: 12px;
            border-radius: 8px;
            animation: fadeOut 1s ease-in-out forwards;
            animation-delay: 3s;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <!-- ✅ MENSAJE DE BIENVENIDA PERSONALIZADO -->
    <?php if (isset($_SESSION['mensaje_bienvenida'])): ?>
        <div class="alert-bienvenida" id="bienvenida">
            <?php echo htmlspecialchars($_SESSION['mensaje_bienvenida']); ?>
        </div>
        <?php unset($_SESSION['mensaje_bienvenida']); ?>
    <?php endif; ?>

    <h1 class="text-center mt-3">HOME</h1>

    <div class="row text-center mt-4">
        <div class="col-md-3">
            <div class="card socios">
                <h2>Socios</h2>
                <h3><?php echo $estadisticas['total_socios']; ?></h3>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card recibos">
                <h2>Recibos</h2>
                <h3><?php echo $estadisticas['total_recibos']; ?></h3>
                <p>Monto: S/. <?php echo number_format($estadisticas['total_monto_recibos'], 2); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card deudas">
                <h2>Deudas</h2>
                <h3><?php echo $estadisticas['total_deudas']; ?></h3>
                <p>Monto: S/. <?php echo number_format($estadisticas['total_monto_deudas'], 2); ?></p>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="deudasChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('deudasChart').getContext('2d');
    var deudasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Deudas Acumuladas - 2024',
                data: [1200, 1900, 3000, 500, 2000, 3000, 4000, 2500, 3200, 1500, 800, 2300],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
