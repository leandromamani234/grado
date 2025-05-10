<?php
require_once '../modelo/modelo_registroRol.php'; // Asegúrate de que la ruta es correcta
session_start();

// Crear instancia del modelo ModeloRegistroRol
$rolModel = new ModeloRegistroRol();

// Verificar qué acción se está solicitando (registro, edición o eliminación)
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Manejar eliminación de rol
if ($action == 'eliminar') {
    $id_rol = intval($_GET['id_rol']);
    
    // Primero eliminamos los permisos asociados
    $rolModel->eliminarPermisos($id_rol);
    
    // Luego eliminamos el rol
    $resultado = $rolModel->eliminarRol($id_rol);

    if ($resultado === "Rol eliminado con éxito.") {
        header("Location: ../vista/verRoles.php?mensaje=" . urlencode($resultado) . "&tipo=success");
    } else {
        header("Location: ../vista/verRoles.php?mensaje=" . urlencode($resultado) . "&tipo=error");
    }
    exit();
}

// Manejar edición de rol
if ($action == 'editar') {
    $id_rol = intval($_POST['id_rol']);
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $permisos = $_POST['permisos'];

    // Asignar los valores
    $rolModel->asignar('nombre', $nombre);

    // Llamar al método para actualizar el rol con los permisos
    $resultado = $rolModel->actualizarRol($id_rol, $permisos);

    if ($resultado === "Rol actualizado con éxito.") {
        // Enviar un mensaje de éxito cuando se actualiza correctamente el rol
        header("Location: ../vista/verRoles.php?mensaje=" . urlencode($resultado) . "&tipo=success");
    } else {
        // Enviar un mensaje de error si la actualización falla
        header("Location: ../vista/editarRol.php?id_rol=$id_rol&mensaje=" . urlencode($resultado) . "&tipo=error");
    }
    exit();
}

// Manejar registro de nuevo rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action == '') {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $permisos = $_POST['permisos'];

    // Asignar el nombre del rol
    $rolModel->asignar('nombre', $nombre);

    // Llamar al método para registrar el rol con los permisos
    $resultado = $rolModel->registrar($permisos);

    if ($resultado === "Rol registrado con éxito.") {
        header("Location: ../vista/verRoles.php?mensaje=" . urlencode($resultado) . "&tipo=success");
    } else {
        header("Location: ../vista/registroRol.php?mensaje=" . urlencode($resultado) . "&tipo=error");
    }
    exit();
}
?>
