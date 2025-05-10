<?php
// Iniciar la sesión
session_start();
require_once 'conexion/conexionBase.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el formulario de inicio de sesión fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Conexión a la base de datos
    $con = new ConexionBase();
    $conn = $con->getConnection();

    // Consulta para obtener el id_usuario y el id_rol del usuario autenticado
    $sql = "SELECT id_usuario, id_rol FROM usuarios WHERE nick = ? AND pass = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Si el usuario existe, guardamos sus datos en la sesión
        $user = $result->fetch_assoc();
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['id_rol'] = $user['id_rol'];

        // Redirigir al usuario a la página principal o de inicio
        header("Location: inicio.php");
        exit();
    } else {
        // Si el usuario o la contraseña son incorrectos, mostramos un mensaje de error
        echo "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    $stmt->close();
    $con->CloseConnection();
} else {
    echo "Método de solicitud no permitido.";
}
?>
