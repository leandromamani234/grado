<?php
session_start();
require_once '../modelo/modelo_deudas.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$action = isset($_GET['action']) ? $_GET['action'] : '';
if (!$action) {
    echo "Error: Acción no definida.";
    exit();
}

$deudaModel = new ModeloDeudas();

// REGISTRAR DEUDA
if ($action === 'registrar') {
    $id_socio = isset($_POST['id_socio']) ? intval($_POST['id_socio']) : 0;
    $monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
    $fecha_deuda = isset($_POST['fecha_deuda']) ? trim($_POST['fecha_deuda']) : '';
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
    $tipo_deuda = isset($_POST['tipo_deuda']) ? trim($_POST['tipo_deuda']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($id_socio > 0 && $monto > 0 && $fecha_deuda && $estado && $tipo_deuda) {
        $deudaModel->asignar('id_socio', $id_socio);
        $deudaModel->asignar('monto', $monto);
        $deudaModel->asignar('fecha_deuda', $fecha_deuda);
        $deudaModel->asignar('estado', $estado);
        $deudaModel->asignar('tipo_deuda', $tipo_deuda);
        $deudaModel->asignar('observaciones', $observaciones);

        $resultado = $deudaModel->registrarDeuda();
        if ($resultado === true) {
            header("Location: ../vista/verDeudas.php?mensaje=Deuda registrada con éxito&tipo=exito");
        } else {
            echo "Error al registrar la deuda: $resultado";
        }
    } else {
        echo "Error: Debes completar todos los campos obligatorios.";
    }
    exit();
}

// EDITAR DEUDA
if ($action === 'editar') {
    $id_deuda = isset($_POST['id_deuda']) ? intval($_POST['id_deuda']) : 0;
    $id_socio = isset($_POST['id_socio']) ? intval($_POST['id_socio']) : 0;
    $monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
    $fecha_deuda = isset($_POST['fecha_deuda']) ? trim($_POST['fecha_deuda']) : '';
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
    $tipo_deuda = isset($_POST['tipo_deuda']) ? trim($_POST['tipo_deuda']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($id_deuda > 0 && $id_socio > 0 && $monto > 0 && $fecha_deuda && $estado && $tipo_deuda) {
        $deudaModel->asignar('id_deuda', $id_deuda);
        $deudaModel->asignar('id_socio', $id_socio);
        $deudaModel->asignar('monto', $monto);
        $deudaModel->asignar('fecha_deuda', $fecha_deuda);
        $deudaModel->asignar('estado', $estado);
        $deudaModel->asignar('tipo_deuda', $tipo_deuda);
        $deudaModel->asignar('observaciones', $observaciones);

        $resultado = $deudaModel->actualizarDeuda();
        if ($resultado === true) {
            header("Location: ../vista/verDeudas.php?mensaje=Deuda actualizada con éxito&tipo=exito");
        } else {
            echo "Error al actualizar la deuda: $resultado";
        }
    } else {
        echo "Error: Debes completar todos los campos obligatorios.";
    }
    exit();
}

// PAGAR DEUDA (actualiza el estado a 'Pagado')
if ($action === 'pagar') {
    $id_deuda = isset($_POST['id_deuda']) ? intval($_POST['id_deuda']) : 0;

    if ($id_deuda > 0) {
        $resultado = $deudaModel->pagarDeuda($id_deuda);
        if ($resultado === true) {
            header("Location: ../vista/verDeudas.php?mensaje=Deuda pagada con éxito&tipo=exito");
        } else {
            echo "Error al pagar la deuda: $resultado";
        }
    } else {
        echo "Error: ID de deuda no válido.";
    }
    exit();
}

// ANULAR DEUDA (actualiza el estado a 'Anulado')
if ($action === 'anular') {
    $id_deuda = isset($_POST['id_deuda']) ? intval($_POST['id_deuda']) : 0;

    if ($id_deuda > 0) {
        $resultado = $deudaModel->anularDeuda($id_deuda);
        if ($resultado === true) {
            header("Location: ../vista/verDeudas.php?mensaje=Deuda anulada con éxito&tipo=exito");
        } else {
            echo "Error al anular la deuda: $resultado";
        }
    } else {
        echo "Error: ID de deuda no válido.";
    }
    exit();
}
