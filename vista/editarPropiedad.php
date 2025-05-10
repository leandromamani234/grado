<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,2,3,4]);
include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_registroPropiedades.php';
require_once '../modelo/modelo_registroSocios.php';

if (isset($_GET['id_propiedad'])) {
    $id_propiedad = intval($_GET['id_propiedad']);

    $modeloProp = new ModeloPropiedad();
    $propiedades = $modeloProp->obtenerPropiedades();

    $propiedad = null;
    foreach ($propiedades as $p) {
        if ($p['id_propiedades'] == $id_propiedad) {
            $propiedad = $p;
            break;
        }
    }

    if (!$propiedad) {
        echo "<p>Error: No se encontró la propiedad.</p>";
        exit();
    }

    $modeloSocio = new ModeloSocios();
    $socios = $modeloSocio->obtenerSocios();

    $socios_con_propiedad = [];
    foreach ($propiedades as $p) {
        if ($p['id_socio'] != $propiedad['id_socio']) {
            $socios_con_propiedad[] = $p['id_socio'];
        }
    }

    $numeros_ocupados = [];
    foreach ($propiedades as $p) {
        if ($p['numero'] != $propiedad['numero']) {
            $numeros_ocupados[] = (int)$p['numero'];
        }
    }
} else {
    echo "<p>Error: No se proporcionó un ID válido.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Propiedad</title>
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
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            width: 100%;
            padding: 0.75rem;
            margin-top: 10px;
            font-weight: bold;
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
    <h1><i class="bi bi-house"></i> Editar Propiedad</h1>
    <p>Modifique los datos de la propiedad</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <form action="../controlador/controlador_propiedades.php?action=editar" method="POST">
        <input type="hidden" name="id_propiedad" value="<?= htmlspecialchars($propiedad['id_propiedades']) ?>">

        <div class="form-group">
            <label for="manzano">Manzano:</label>
            <select class="form-control" id="manzano" name="manzano" required>
                <option value="">Seleccione un manzano</option>
                <?php foreach (range('A', 'Z') as $letra): ?>
                    <option value="<?= $letra ?>" <?= ($propiedad['manzano'] == $letra) ? 'selected' : '' ?>><?= $letra ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="numero">Número:</label>
            <select class="form-control" id="numero" name="numero" required>
                <option value="">Seleccione un número</option>
                <?php for ($i = 1; $i <= 215; $i++): ?>
                    <?php if (!in_array($i, $numeros_ocupados) || $i == $propiedad['numero']): ?>
                        <option value="<?= $i ?>" <?= ($i == $propiedad['numero']) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_persona">Socio:</label>
            <select class="form-control" id="id_persona" name="id_persona" required>
                <option value="">Seleccione un socio</option>
                <?php foreach ($socios as $socio): ?>
                    <?php if ($socio['id_persona'] == $propiedad['id_socio'] || !in_array($socio['id_persona'], $socios_con_propiedad)): ?>
                        <option value="<?= $socio['id_persona'] ?>" <?= ($socio['id_persona'] == $propiedad['id_socio']) ? 'selected' : '' ?>>
                            <?= $socio['nombre'] . ' ' . $socio['primer_apellido'] . ' ' . $socio['segundo_apellido'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Propiedad</button>
        <a href="verPropiedades.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<?php include_once "pie.php"; ?>
</body>
</html>
