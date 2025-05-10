<?php
require_once '../modelo/modelo_Usuarios.php';
require_once '../modelo/conexion/conexionBase.php';

session_start(); // Asegura que la sesión esté iniciada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $usuariosModel = new ModeloUsuario();
    $con = new ConexionBase();
    $conn = $con->getConnection();

    // 🚨 DEPURACIÓN: Confirmar ejecución
    error_log("📌 controlador_usuarios.php está ejecutándose. Acción: " . $action);

    // Acción: Iniciar sesión
    if ($action == 'login') {
        $nick = trim($_POST['nick']);
        $pass = trim($_POST['pass']);

        // Verificar credenciales
        $usuario = $usuariosModel->verificarCredenciales($nick, $pass);

        if ($usuario) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nick'] = $usuario['nick'];
            $_SESSION['rol'] = $usuario['rol'];

            // Obtener permisos del usuario desde la base de datos
            $id_usuario = $usuario['id_usuario'];

            $sql = "SELECT p.nombre 
                    FROM permisos p 
                    INNER JOIN rol_permisos rp ON p.id_permiso = rp.id_permiso
                    INNER JOIN usuario_rol ur ON rp.id_rol = ur.id_rol
                    WHERE ur.id_usuario = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();

            $permisos = [];
            while ($row = $result->fetch_assoc()) {
                $permisos[] = $row['nombre']; // Guardar nombres de los permisos
            }
            $stmt->close();

            // Guardar permisos en sesión
            $_SESSION['permisos'] = (!empty($permisos)) ? $permisos : [];

            // 🚨 DEPURACIÓN: Mostrar permisos obtenidos antes de redirigir
            error_log("✅ Permisos obtenidos: " . implode(", ", $_SESSION['permisos']));

            // Redirigir al panel de control
            header("Location: ../vista/home.php");
            exit;
        } else {
            header("Location: ../vista/login.php?mensaje=Credenciales incorrectas&tipo=error");
            exit;
        }
    }

    

    if ($action == 'registrar') {
        $nick = trim($_POST['nick']);
        $email = trim($_POST['email']);
        $pass = trim($_POST['pass']);
        $persona_id_persona = intval($_POST['persona_id_persona']);
        $id_rol = intval($_POST['id_rol']);
    
        // Validar que todos los campos estén completos
        if (empty($nick) || empty($email) || empty($pass) || empty($persona_id_persona) || empty($id_rol)) {
            header("Location: ../vista/registroUsuarios.php?mensaje=Por favor, completa todos los campos&tipo=error");
            exit;
        }
    
        // Hashear la contraseña
        $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
    
        // Llamar al modelo para registrar el usuario con rol
        $resultado = $usuariosModel->registrarUsuarioConRol($nick, $hashed_pass, $persona_id_persona, $email, $id_rol);
    
        // Verificar si el resultado es numérico, lo cual significa que se registró correctamente
        if (is_numeric($resultado)) {
            header("Location: ../vista/verUsuarios.php?mensaje=Usuario registrado y rol asignado con éxito&tipo=exito");
        } else {
            header("Location: ../vista/registroUsuarios.php?mensaje=" . urlencode($resultado) . "&tipo=error");
        }
        exit;
    }

    // Acción: Editar usuario
    if ($action == 'editar' && isset($_GET['id_usuario'])) {
        $id_usuario = intval($_GET['id_usuario']);
        $nick = trim($_POST['nick']);
        $email = trim($_POST['email']);
        $pass = trim($_POST['pass']);
        $id_rol = intval($_POST['id_rol']);
    
        // Si el campo de contraseña está vacío, no la actualiza
        $nuevaContrasena = !empty($pass) ? $pass : null;
    
        // Llamada corregida al método actualizarUsuario
        $actualizarUsuario = $usuariosModel->actualizarUsuario($id_usuario, $nick, $email, $nuevaContrasena, $id_rol);
    
        if ($actualizarUsuario) {
            header("Location: ../vista/verUsuarios.php?mensaje=Usuario actualizado con éxito&tipo=exito");
        } else {
            header("Location: ../vista/editarUsuario.php?id_usuario=$id_usuario&mensaje=Error al actualizar el usuario&tipo=error");
        }
        exit;
    }
    
}

// Acción: Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'eliminar') {
    $id_usuario = intval($_GET['id_usuario']);

    $usuariosModel = new ModeloUsuario();
    $resultado = $usuariosModel->eliminarUsuario($id_usuario);

    if ($resultado) {
        header("Location: ../vista/verUsuarios.php?mensaje=Usuario eliminado con éxito&tipo=exito");
    } else {
        header("Location: ../vista/verUsuarios.php?mensaje=Error al eliminar el usuario&tipo=error");
    }
    exit;
}
?>
