<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 4]); // Permitir acceso a admin (1), usuario (2) y externo (3)

include_once "cabecera.php";
include_once "menu.php";
require_once '../modelo/modelo_deudas.php'; // Asegúrate de que la ruta sea correcta
require_once '../modelo/modelo_registroPersona.php'; // Para obtener las personas

// Crear instancia del modelo de deudas y personas
$deudasModel = new ModeloDeudas();
$personasModel = new ModeloPersona();

// Obtener el ID de la deuda desde la URL
$id_deuda = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar si se proporcionó un ID válido
if ($id_deuda > 0) {
    // Obtener la información de la deuda a editar
    $deuda = $deudasModel->obtenerDeudaPorId($id_deuda); // Método que debe existir en tu modelo
    $personas = $personasModel->obtenerPersonas(); // Obtener todas las personas registradas

    // Verificar si la deuda existe
    if (!$deuda) {
        echo "Error: Deuda no encontrada.";
        exit();
    }
} else {
    echo "Error: ID de deuda no válido.";
    exit();
}
?>

<style>
    body {
        background-image: url('images/imag.jpg'); /* Ruta a tu imagen */
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        margin: 0;
        color: black; /* Color de texto negro */
    }

    .app-content {
        background: rgba(255, 255, 255, 0.9); /* Fondo semitransparente para la caja */
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        width: 90%; /* Aumentar el ancho de la caja */
        max-width: 800px; /* Ajusta el ancho máximo según sea necesario */
        margin: auto; /* Centra el contenido */
        margin-top: 5%; /* Añadir espacio superior */
    }

    .form-label {
        font-weight: bold; /* Negrita para las etiquetas */
    }

    .btn {
        border-radius: 5px; /* Bordes redondeados para los botones */
    }
</style>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="bi bi-pencil"></i> Editar Deuda</h1>
            <p>Actualice los datos de la deuda</p>
        </div>
    </div>

    <div class="tile">
        <div class="tile-body">
            <!-- Mostrar mensaje de éxito o error -->
            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert alert-<?php echo $_GET['tipo'] === 'exito' ? 'success' : 'danger'; ?>">
                    <?php echo htmlspecialchars($_GET['mensaje']); ?>
                </div>
            <?php endif; ?>
            
            <form action="../controlador/controlador_deudas.php?action=editar" method="POST">
                <!-- Campo oculto para el ID de la deuda -->
                <input type="hidden" name="id_deuda" value="<?php echo htmlspecialchars($deuda['id_deuda']); ?>">

                <!-- Select para seleccionar el usuario (persona) -->
                <div class="mb-3">
                    <label for="id_persona" class="form-label">Persona:</label>
                    <select class="form-control" id="id_persona" name="id_persona" required>
                        <?php foreach ($personas as $persona): ?>
                            <option value="<?php echo htmlspecialchars($persona['id_persona']); ?>"
                                <?php echo ($persona['id_persona'] == $deuda['id_persona']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($persona['nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Monto -->
                <div class="mb-3">
                    <label for="monto" class="form-label">Monto (Bs):</label>
                    <input type="number" step="0.01" class="form-control" id="monto" name="monto" value="<?php echo htmlspecialchars($deuda['monto']); ?>" required>
                </div>

                <!-- Fecha de deuda -->
                <div class="mb-3">
                    <label for="fecha_deuda" class="form-label">Fecha de Deuda:</label>
                    <input type="date" class="form-control" id="fecha_deuda" name="fecha_deuda" value="<?php echo htmlspecialchars($deuda['fecha_deuda']); ?>" required>
                </div>

                <!-- Estado -->
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="Pago al día" <?php echo ($deuda['estado'] == 'Pago al día') ? 'selected' : ''; ?>>Pago al día</option>
                        <option value="En Mora" <?php echo ($deuda['estado'] == 'En Mora') ? 'selected' : ''; ?>>En Mora</option>
                        <option value="En plan de pagos" <?php echo ($deuda['estado'] == 'En plan de pagos') ? 'selected' : ''; ?>>En plan de pagos</option>
                        <option value="Pasible a corte" <?php echo ($deuda['estado'] == 'Pasible a corte') ? 'selected' : ''; ?>>Pasible a corte</option>
                        <option value="En corte" <?php echo ($deuda['estado'] == 'En corte') ? 'selected' : ''; ?>>En corte</option>
                    </select>
                </div>

                <!-- Tipo de deuda -->
                <div class="mb-3">
                    <label for="tipo_deuda" class="form-label">Tipo de Deuda:</label>
                    <select class="form-control" id="tipo_deuda" name="tipo_deuda" required>
                        <option value="Consumo Regular" <?php echo ($deuda['tipo_deuda'] == 'Consumo Regular') ? 'selected' : ''; ?>>Consumo Regular</option>
                        <option value="Multa + Consumo" <?php echo ($deuda['tipo_deuda'] == 'Multa + Consumo') ? 'selected' : ''; ?>>Multa + Consumo</option>
                        <option value="Reconexión + Consumo" <?php echo ($deuda['tipo_deuda'] == 'Reconexión + Consumo') ? 'selected' : ''; ?>>Reconexión + Consumo</option>
                        <option value="Deuda Acumulada" <?php echo ($deuda['tipo_deuda'] == 'Deuda Acumulada') ? 'selected' : ''; ?>>Deuda Acumulada</option>
                        <option value="Ajuste" <?php echo ($deuda['tipo_deuda'] == 'Ajuste') ? 'selected' : ''; ?>>Ajuste</option>
                    </select>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($deuda['observaciones']); ?></textarea>
                </div>

                <!-- Botones de acción -->
                <button type="submit" class="btn btn-primary">Actualizar Deuda</button>
                <a href="verDeudas.php" class="btn btn-secondary">Cancelar</a> <!-- Botón para cancelar -->
            </form>
        </div>
    </div>
</main>

<?php include_once "pie.php"; ?>
