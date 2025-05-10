<?php
require_once '../modelo/modelo_recibos.php';

if (
    isset($_POST['id_recibo'], $_POST['id_propiedad'], $_POST['numero_serie'],
          $_POST['fecha_lectura'], $_POST['lectura_anterior'], $_POST['lectura_actual'],
          $_POST['monto_pagar'], $_POST['observaciones'])
) {
    // Obtener y sanitizar los datos
    $id_recibo = intval($_POST['id_recibo']);
    $id_propiedad = intval($_POST['id_propiedad']);
    $numero_serie = htmlspecialchars(trim($_POST['numero_serie']));
    $fecha_lectura = $_POST['fecha_lectura'];
    $lectura_anterior = floatval($_POST['lectura_anterior']);
    $lectura_actual = floatval($_POST['lectura_actual']);
    $consumo_agua = $lectura_actual - $lectura_anterior;
    $monto_pagar = floatval($_POST['monto_pagar']);
    $observaciones = htmlspecialchars(trim($_POST['observaciones']));

    // Instancia del modelo
    $reciboModel = new ModeloRecibos();

    // Llamada al método para actualizar
    $resultado = $reciboModel->actualizarRecibo(
        $id_recibo,
        $id_propiedad,
        $fecha_lectura,
        $lectura_anterior,
        $lectura_actual,
        $consumo_agua,
        $monto_pagar,
        $observaciones,
        $numero_serie
    );

    if ($resultado === true) {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Recibo actualizado con éxito") . "&tipo=exito");
    } else {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Error al actualizar el recibo: $resultado") . "&tipo=error");
    }
} else {
    header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Error: Faltan datos requeridos") . "&tipo=error");
}
?>
