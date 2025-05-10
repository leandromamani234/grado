<?php
require_once '../modelo/modelo_medidor.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'] ?? '';
$medidorModel = new ModeloMedidor();

function esSoloNumeros($valor) {
    return preg_match('/^\d+$/', $valor);
}

function esSoloLetras($valor) {
    return preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/u', $valor);
}

// Registrar medidor
if ($action === 'registrar') {
    if (isset($_POST['serie'], $_POST['marca'], $_POST['id_propiedad'], $_POST['lectura_inicial'])) {
        $serie = trim($_POST['serie']);
        $marca = trim($_POST['marca']);
        $id_propiedad = intval($_POST['id_propiedad']);
        $lectura_inicial = floatval($_POST['lectura_inicial']);

        // Validaciones
        if (!esSoloNumeros($serie)) {
            $_SESSION['mensaje'] = "La serie debe contener solo números.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroMedidor.php");
            exit();
        }

        if (!esSoloLetras($marca)) {
            $_SESSION['mensaje'] = "La marca debe contener solo letras.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroMedidor.php");
            exit();
        }

        if ($medidorModel->verificarSerieExistente($serie)) {
            $_SESSION['mensaje'] = "La serie ya está registrada con otro medidor. Ingrese una serie única.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroMedidor.php");
            exit();
        }

        $ocupadas = $medidorModel->obtenerPropiedadesConMedidor();
        if (in_array($id_propiedad, array_column($ocupadas, 'id_propiedades'))) {
            $_SESSION['mensaje'] = "Esta propiedad ya tiene un medidor registrado.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroMedidor.php");
            exit();
        }

        // Asignar y registrar
        $medidorModel->asignar('serie', $serie);
        $medidorModel->asignar('marca', $marca);
        $medidorModel->asignar('id_propiedad', $id_propiedad);
        $medidorModel->asignar('lectura_inicial', $lectura_inicial);

        $resultado = $medidorModel->registrarMedidor();
        $_SESSION['mensaje'] = $resultado === true ? "Medidor registrado con éxito." : "Error al registrar: $resultado";
        $_SESSION['tipo'] = $resultado === true ? "success" : "danger";

        // Redirige según resultado
        header("Location: ../vista/" . ($resultado === true ? "verMedidores.php" : "registroMedidor.php"));
        exit();
    } else {
        $_SESSION['mensaje'] = "Faltan datos para registrar el medidor.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroMedidor.php");
        exit();
    }
}

// Eliminar medidor
if ($action === 'eliminar') {
    $id_medidor = intval($_GET['id_medidor'] ?? 0);
    if ($id_medidor > 0 && $medidorModel->eliminarMedidor($id_medidor)) {
        $_SESSION['mensaje'] = "Medidor eliminado con éxito.";
        $_SESSION['tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el medidor.";
        $_SESSION['tipo'] = "danger";
    }
    header("Location: ../vista/verMedidores.php");
    exit();
}

// Editar medidor
if ($action === 'editar') {
    if (isset($_POST['id_medidor'], $_POST['serie'], $_POST['marca'], $_POST['lectura_inicial'], $_POST['id_propiedad'])) {
        $id_medidor = intval($_POST['id_medidor']);
        $serie = trim($_POST['serie']);
        $marca = trim($_POST['marca']);
        $lectura_inicial = floatval($_POST['lectura_inicial']);
        $id_propiedad = intval($_POST['id_propiedad']);

        if (!esSoloNumeros($serie)) {
            $_SESSION['mensaje'] = "La serie debe contener solo números.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarMedidor.php?id_medidor=$id_medidor");
            exit();
        }

        if (!esSoloLetras($marca)) {
            $_SESSION['mensaje'] = "La marca debe contener solo letras.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarMedidor.php?id_medidor=$id_medidor");
            exit();
        }

        if ($medidorModel->verificarSerieExistente($serie, $id_medidor)) {
            $_SESSION['mensaje'] = "No se puede editar. La serie ya está registrada con otro medidor.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/editarMedidor.php?id_medidor=$id_medidor");
            exit();
        }

        $medidorModel->asignar('serie', $serie);
        $medidorModel->asignar('marca', $marca);
        $medidorModel->asignar('lectura_inicial', $lectura_inicial);
        $medidorModel->asignar('id_propiedad', $id_propiedad);

        $resultado = $medidorModel->actualizarMedidor($id_medidor);
        $_SESSION['mensaje'] = $resultado === true ? "Medidor actualizado con éxito." : "Error al actualizar.";
        $_SESSION['tipo'] = $resultado === true ? "success" : "danger";
        header("Location: ../vista/verMedidores.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Faltan datos para actualizar el medidor.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/verMedidores.php");
        exit();
    }
}

// Obtener lectura anterior (AJAX)
if ($action === 'obtenerLectura') {
    $id_propiedad = intval($_GET['id_propiedad'] ?? 0);
    if ($id_propiedad > 0) {
        $lectura = $medidorModel->obtenerLecturaAnteriorPorPropiedad($id_propiedad);
        echo json_encode([
            'success' => $lectura !== null,
            'lectura_anterior' => $lectura ?? 0,
            'message' => $lectura !== null ? '' : 'No se encontró lectura anterior'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de propiedad no válido']);
    }
    exit();
}
?>
