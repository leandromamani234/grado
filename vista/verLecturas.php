<?php
include_once "../includes/seguridad.php"; 
verificarRol([1,4]); // Permitir acceso a admin (1), usuario (2) y externo (3)
include_once "cabecera.php"; 
include_once "menu.php"; 
include_once 'funciones.php';

require_once '../modelo/modelo_lecturas.php'; 

// Crear una instancia del modelo de lecturas
$lecturaModel = new ModeloLectura();

// Obtener todas las lecturas registradas
$lecturas = $lecturaModel->obtenerLecturas();
?>

<main class="app-content">
    <div class="app-title">
        <h1><i class="bi bi-eye"></i> Ver Lecturas Iniciales</h1>
    </div>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo'] == 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
    <?php endif; ?>

    <div class="tile">
        <div class="tile-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número de Casa</th>
                        <th>Lectura Inicial</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lecturas as $lectura): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lectura['id_lectura']); ?></td>
                            <td><?php echo htmlspecialchars($lectura['numero_casa']); ?></td>
                            <td><?php echo htmlspecialchars($lectura['lectura_inicial']); ?></td>
                            <td><?php echo htmlspecialchars($lectura['fecha_creacion']); ?></td>
                            <td>
                                <a href="editarLectura.php?id_lectura=<?php echo $lectura['id_lectura']; ?>" class="btn btn-warning">Editar</a>
                                <a href="../controlador/controladorLectura.php?action=eliminar&id_lectura=<?php echo $lectura['id_lectura']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta lectura?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include_once "pie.php"; ?>
