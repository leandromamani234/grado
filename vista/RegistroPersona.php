<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,4]); // Permitir acceso a admin (1), usuario (2) y externo (4)
include_once "cabecera.php";
include_once "menu.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Persona</title>
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
        }
        .form-container {
            background: rgba(255, 255, 255, 0.41);
            padding: 2rem;
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
            margin-bottom: 1rem;
        }
        .form-container p {
            color: rgb(0, 0, 0);
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-container label {
            color: rgb(12, 12, 12);
            font-weight: bold;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.51);
            border: 1px solid #ffffff;
            color: rgb(0, 0, 0);
            margin-bottom: 1rem;
        }
        .form-control::placeholder {
            color: rgba(17, 17, 16, 0.63);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: rgb(15, 15, 13);
            font-weight: bold;
            width: 100%;
            padding: 0.75rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-person-plus"></i> Registro de Persona</h1>
    <p>Complete los datos de la persona</p>

    <!-- Bloque para mostrar el mensaje de error o éxito -->
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo '<div class="alert alert-' . ($_SESSION['tipo'] ?? 'info') . '" role="alert">';
        echo $_SESSION['mensaje'];
        echo '</div>';
        unset($_SESSION['mensaje'], $_SESSION['tipo']);
    }
    ?>

    <form action="../controlador/controlador_persona.php?action=registrar" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese nombre" required>
        </div>
        <div class="form-group">
            <label for="primer_apellido">Primer Apellido (opcional):</label>
            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" placeholder="Ingrese primer apellido">
        </div>
        <div class="form-group">
            <label for="segundo_apellido">Segundo Apellido (opcional):</label>
            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" placeholder="Ingrese segundo apellido">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese teléfono" required>
        </div>
        <div class="form-group">
            <label for="celular">Celular:</label>
            <input type="text" class="form-control" id="celular" name="celular" placeholder="Ingrese celular" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese dirección" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@email.com" required>
        </div>
        <div class="form-group">
            <label for="CI">CI:</label>
            <input type="text" class="form-control" id="CI" name="CI" placeholder="Ingrese carnet de identidad" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Persona</button>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
