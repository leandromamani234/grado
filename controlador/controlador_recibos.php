<?php
require_once '../modelo/modelo_recibos.php';
require_once '../modelo/modelo_registroPropiedades.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_propiedad = $_POST['id_propiedad'];
    $fecha_lectura = $_POST['fecha_lectura'];
    $lectura_actual = floatval($_POST['lectura_actual']);
    $monto_pagar = floatval($_POST['monto_pagar']);
    $observaciones = htmlspecialchars(trim($_POST['observaciones']));

    // Validar propiedad
    $propiedadModel = new ModeloPropiedad();
    $propiedades = $propiedadModel->obtenerPropiedades();
    $propiedadValida = false;
    foreach ($propiedades as $prop) {
        if ($prop['id_propiedades'] == $id_propiedad) {
            $propiedadValida = true;
            break;
        }
    }

    if (!$propiedadValida) {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("No se encontró la propiedad seleccionada") . "&tipo=error");
        exit;
    }

    // Obtener la lectura anterior desde el modelo
    $reciboModel = new ModeloRecibos();
    $lectura_anterior = $reciboModel->obtenerUltimaLecturaActual($id_propiedad);
    if ($lectura_anterior === null) {
        $lectura_anterior = 0; // si no hay registros previos
    }

    // Calcular consumo
    $consumo_m3 = $lectura_actual - $lectura_anterior;

    // Obtener número de serie
    $numero_serie = $reciboModel->obtenerSiguienteNumeroSerie();
    if ($numero_serie === null) {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("No hay números de serie disponibles") . "&tipo=error");
        exit;
    }

    // Registrar el recibo
    $resultado = $reciboModel->registrarRecibo(
        $id_propiedad,
        $fecha_lectura,
        $lectura_anterior,
        $lectura_actual,
        $consumo_m3,
        $monto_pagar,
        $observaciones,
        $numero_serie
    );

    if ($resultado === true) {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Recibo registrado con éxito") . "&tipo=exito");
    } else {
        header("Location: ../vista/verRecibos.php?mensaje=" . urlencode($resultado) . "&tipo=error");
    }
    exit;
} else {
    header("Location: ../vista/verRecibos.php?mensaje=" . urlencode("Error: No se enviaron todos los datos") . "&tipo=error");
    exit;
}
