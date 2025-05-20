<?php
require_once '../modelo/modelo_registroSocios.php';
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$socioModel = new ModeloSocios();

if ($action === 'eliminar') {
    $id_persona = isset($_POST['id_persona']) ? intval($_POST['id_persona']) : 0;

    if ($id_persona > 0) {
        $resultado = $socioModel->eliminarSocio($id_persona);

        $_SESSION['mensaje'] = $resultado === true 
            ? "Socio eliminado con éxito."
            : $resultado;
        $_SESSION['tipo'] = $resultado === true ? "success" : "danger";
    } else {
        $_SESSION['mensaje'] = "ID de socio no válido para eliminar.";
        $_SESSION['tipo'] = "danger";
    }

    header("Location: ../vista/verSocios.php");
    exit();
}


// Acción: Registrar socio (sin matrícula y OTB fija)
if ($action === 'registrar') {
    if (isset($_POST["id_persona"], $_POST["estado"])) {
        $id_persona = intval($_POST["id_persona"]);
        $estado = htmlspecialchars(trim($_POST["estado"]));
        $id_otb = 1; // OTB fija: Barrio Fabril

        if ($id_persona <= 0) {
            $_SESSION['mensaje'] = "Error: No se seleccionó una persona válida.";
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroSocio.php");
            exit();
        }

        $resultado = $socioModel->registrarSocio($id_persona, $estado, $id_otb);

        if ($resultado === true) {
            $_SESSION['mensaje'] = "Socio registrado con éxito.";
            $_SESSION['tipo'] = "success";
            header("Location: ../vista/verSocios.php");
            exit();
        } else {
            $_SESSION['mensaje'] = $resultado;
            $_SESSION['tipo'] = "danger";
            header("Location: ../vista/registroSocio.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "Faltan datos para registrar el socio.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroSocio.php");
        exit();
    }
}

// Acción: Editar socio (se mantiene la OTB fija)
if ($action === 'editar') {
    if (isset($_POST["id_persona_actual"], $_POST["id_persona_nueva"])) {
        $id_persona_actual = intval($_POST["id_persona_actual"]);
        $id_persona_nueva = intval($_POST["id_persona_nueva"]);
        $id_otb = 1; // OTB fija

        $resultado = $socioModel->actualizarSocio($id_persona_actual, $id_persona_nueva, $id_otb);

        $_SESSION['mensaje'] = $resultado === true 
            ? "Socio actualizado con éxito."
            : $resultado;
        $_SESSION['tipo'] = $resultado === true ? "success" : "danger";

        header("Location: ../vista/verSocios.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: Faltan datos para actualizar el socio.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/verSocios.php");
        exit();
    }
}

// Acción: Cambiar estado
if ($action === 'cambiar_estado') {
    if (isset($_POST['id_persona'], $_POST['estado'])) {
        $id_persona = intval($_POST['id_persona']);
        $estado = $_POST['estado'];

        $resultado = $socioModel->cambiarEstado($id_persona, $estado);

        if ($resultado === true) {
            switch ($estado) {
                case 'Activo':
                    $_SESSION['mensaje'] = "✅ Este socio está activo.";
                    break;
                case 'Inactivo':
                    $_SESSION['mensaje'] = "🔴 Este socio ha sido inactivado.";
                    break;
                case 'Suspendido':
                    $_SESSION['mensaje'] = "⚪ Este socio fue suspendido.";
                    break;
                default:
                    $_SESSION['mensaje'] = "Estado actualizado.";
            }
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = $resultado;
            $_SESSION['tipo'] = "danger";
        }

        header("Location: ../vista/verSocios.php");
        exit();
    }
}

// Si no se pasó ninguna acción válida
header("Location: ../vista/verSocios.php");
exit();
?>
