<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 2, 3, 4]);
include_once "cabecera.php";
include_once "menu.php";

require_once '../modelo/modelo_registroPropiedades.php';
require_once '../modelo/modelo_registroPersona.php';

$propiedadModel = new ModeloPropiedad();
$personasModel = new ModeloPersona();

$propiedades = $propiedadModel->obtenerPropiedades(); 
$personas = $personasModel->obtenerPersonas(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Propiedades</title>
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
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1300px;
            margin: auto;
        }

        .form-container h1 {
            font-size: 2rem;
            color: #222;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .form-container p {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background-color: #007bff;
            color: black;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid #333;
            background-color: rgba(255, 255, 255, 0.9);
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 2px;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #000;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert {
            text-align: center;
            padding: 1rem;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .form-control {
                font-size: 0.9rem;
            }

            th, td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

<main class="form-container">
    <h1><i class="bi bi-list"></i> Ver Propiedades</h1>
    <p>Lista de todas las propiedades registradas</p>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <div class="form-group">
        <label for="buscar_nombre" class="form-label">Buscar por Nombre:</label>
        <input type="text" class="form-control" id="buscar_nombre" placeholder="Buscar por propietario..." onkeyup="filtrarPropiedades()">
    </div>

    <div class="form-group">
        <label for="manzano" class="form-label">Selecciona Manzano:</label>
        <select class="form-control" id="manzano" onchange="mostrarPropiedadesPorManzano(this.value)">
            <option value="">Seleccione un manzano</option>
            <?php 
                $manzanos = array_unique(array_column($propiedades, 'manzano'));
                foreach ($manzanos as $manzano): ?>
                <option value="<?php echo htmlspecialchars($manzano); ?>"><?php echo htmlspecialchars($manzano); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="table-responsive">
        <table id="tabla_propiedades">
            <thead>
                <tr>
                    <th>ID Propiedad</th>
                    <th>Manzano</th>
                    <th>Número</th>
                    <th>Socio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propiedades as $propiedad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($propiedad['id_propiedades']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['manzano']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['numero']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['propietario']); ?></td>
                        <td>
                            <a href="editarPropiedad.php?id_propiedad=<?php echo $propiedad['id_propiedades']; ?>" class="btn btn-warning">Editar</a>
                            <a href="../controlador/controlador_propiedades.php?action=eliminar&id_propiedad=<?php echo $propiedad['id_propiedades']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                            <a href="verRecibos.php?id_propiedad=<?php echo $propiedad['id_propiedades']; ?>" class="btn btn-primary">Ver Recibos</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function mostrarPropiedadesPorManzano(manzano) {
    const propiedades = <?php echo json_encode($propiedades); ?>;
    const tabla = document.getElementById('tabla_propiedades').getElementsByTagName('tbody')[0];
    tabla.innerHTML = '';

    propiedades.forEach(prop => {
        if (prop.manzano === manzano || manzano === '') {
            const row = `
                <tr>
                    <td>${prop.id_propiedades}</td>
                    <td>${prop.manzano}</td>
                    <td>${prop.numero}</td>
                    <td>${prop.propietario}</td>
                    <td>
                        <a href="editarPropiedad.php?id_propiedad=${prop.id_propiedades}" class="btn btn-warning">Editar</a>
                        <a href="../controlador/controlador_propiedades.php?action=eliminar&id_propiedad=${prop.id_propiedades}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                        <a href="verRecibos.php?id_propiedad=${prop.id_propiedades}" class="btn btn-primary">Ver Recibos</a>
                    </td>
                </tr>`;
            tabla.innerHTML += row;
        }
    });

    if (tabla.innerHTML === '') {
        tabla.innerHTML = `<tr><td colspan="5">No hay propiedades en este manzano.</td></tr>`;
    }
}

function filtrarPropiedades() {
    const input = document.getElementById('buscar_nombre');
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll('#tabla_propiedades tbody tr');

    filas.forEach(fila => {
        const nombre = fila.cells[3].textContent.toLowerCase();
        fila.style.display = nombre.includes(filtro) ? '' : 'none';
    });
}
</script>

<?php include_once "pie.php"; ?>
</body>
</html>
