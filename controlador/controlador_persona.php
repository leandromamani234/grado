<?php
session_start(); // Iniciar sesión para almacenar el mensaje
require_once '../modelo/modelo_registroPersona.php'; // Ruta correcta al modelo

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Crear instancia del modelo Persona
$personaModel = new ModeloPersona();

if ($action == 'registrar') {
    // Obtener y sanitizar los datos del formulario
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $primer_apellido = htmlspecialchars(trim($_POST["primer_apellido"]));
    $segundo_apellido = htmlspecialchars(trim($_POST["segundo_apellido"]));
    $telefono = htmlspecialchars(trim($_POST["telefono"]));
    $celular = htmlspecialchars(trim($_POST["celular"]));
    $direccion = htmlspecialchars(trim($_POST["direccion"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $CI = htmlspecialchars(trim($_POST["CI"]));

    // Validar letras
    $soloLetras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/";

    if (!preg_match($soloLetras, $nombre)) {
        $_SESSION['mensaje'] = "Error: El nombre solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }
    if (!empty($primer_apellido) && !preg_match($soloLetras, $primer_apellido)) {
        $_SESSION['mensaje'] = "Error: El primer apellido solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }
    if (!empty($segundo_apellido) && !preg_match($soloLetras, $segundo_apellido)) {
        $_SESSION['mensaje'] = "Error: El segundo apellido solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }

    // Validar números en teléfono y celular
    if (!preg_match('/^[0-9]+$/', $telefono)) {
        $_SESSION['mensaje'] = "Error: El teléfono solo debe contener números.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }
    if (!preg_match('/^[0-9]+$/', $celular)) {
        $_SESSION['mensaje'] = "Error: El celular solo debe contener números.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }

    // Verificar duplicados
    $validacion = $personaModel->validarDatosUnicos($telefono, $celular, $email, $CI);
    if ($validacion !== true) {
        $_SESSION['mensaje'] = $validacion;
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
        exit();
    }

    // Asignar
    $personaModel->asignar('nombre', $nombre);
    $personaModel->asignar('primer_apellido', $primer_apellido);
    $personaModel->asignar('segundo_apellido', $segundo_apellido);
    $personaModel->asignar('telefono', $telefono);
    $personaModel->asignar('celular', $celular);
    $personaModel->asignar('direccion', $direccion);
    $personaModel->asignar('email', $email);
    $personaModel->asignar('CI', $CI);

    // Registrar
    $resultado = $personaModel->registrar();
    if ($resultado === true) {
        $_SESSION['mensaje'] = "Persona registrada con éxito";
        $_SESSION['tipo'] = "success";
        header("Location: ../vista/verPersonas.php");
    } else {
        $_SESSION['mensaje'] = "Error al registrar la persona: $resultado";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/registroPersona.php");
    }
    exit();

} elseif ($action == 'actualizar') {
    $id_persona = intval($_POST['id_persona']);
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $primer_apellido = htmlspecialchars(trim($_POST["primer_apellido"]));
    $segundo_apellido = htmlspecialchars(trim($_POST["segundo_apellido"]));
    $telefono = htmlspecialchars(trim($_POST["telefono"]));
    $celular = htmlspecialchars(trim($_POST["celular"]));
    $direccion = htmlspecialchars(trim($_POST["direccion"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $CI = htmlspecialchars(trim($_POST["CI"]));

    // Validar letras
    $soloLetras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/";

    if (!preg_match($soloLetras, $nombre)) {
        $_SESSION['mensaje'] = "Error: El nombre solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
        exit();
    }
    if (!empty($primer_apellido) && !preg_match($soloLetras, $primer_apellido)) {
        $_SESSION['mensaje'] = "Error: El primer apellido solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
        exit();
    }
    if (!empty($segundo_apellido) && !preg_match($soloLetras, $segundo_apellido)) {
        $_SESSION['mensaje'] = "Error: El segundo apellido solo debe contener letras.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
        exit();
    }

    // Validar números
    if (!preg_match('/^[0-9]+$/', $telefono)) {
        $_SESSION['mensaje'] = "Error: El teléfono solo debe contener números.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
        exit();
    }
    if (!preg_match('/^[0-9]+$/', $celular)) {
        $_SESSION['mensaje'] = "Error: El celular solo debe contener números.";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
        exit();
    }

    // Asignar y actualizar
    $personaModel->asignar('nombre', $nombre);
    $personaModel->asignar('primer_apellido', $primer_apellido);
    $personaModel->asignar('segundo_apellido', $segundo_apellido);
    $personaModel->asignar('telefono', $telefono);
    $personaModel->asignar('celular', $celular);
    $personaModel->asignar('direccion', $direccion);
    $personaModel->asignar('email', $email);
    $personaModel->asignar('CI', $CI);

    $resultado = $personaModel->actualizarPersona($id_persona);
    if ($resultado === true) {
        $_SESSION['mensaje'] = "Persona actualizada con éxito";
        $_SESSION['tipo'] = "success";
        header("Location: ../vista/verPersonas.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la persona: $resultado";
        $_SESSION['tipo'] = "danger";
        header("Location: ../vista/editarPersona.php?id_persona=$id_persona");
    }
    exit();

} elseif ($action == 'eliminar') {
    $id_persona = intval($_GET['id_persona']);
    $resultado = $personaModel->eliminarPersona($id_persona);

    if ($resultado === true) {
        $_SESSION['mensaje'] = "Persona eliminada con éxito";
        $_SESSION['tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la persona: $resultado";
        $_SESSION['tipo'] = "danger";
    }
    header("Location: ../vista/verPersonas.php");
    exit();
}
?>
