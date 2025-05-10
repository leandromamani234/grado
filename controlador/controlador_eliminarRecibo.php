<?php
require_once '../modelo/modelo_recibos.php';

if (isset($_GET['id'])) {
    $id_recibo = intval($_GET['id']); // Asegura que es un número entero
    $reciboModel = new ModeloRecibos();
    $resultado = $reciboModel->eliminarRecibo($id_recibo);

    if ($resultado) {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Recibo eliminado con éxito") . "&tipo=exito");
    } else {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Error al eliminar el recibo") . "&tipo=error");
    }
} else {
    header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("ID de recibo no especificado") . "&tipo=error");
}
