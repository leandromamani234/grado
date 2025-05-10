<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../modelo/funciones.php'; // Asegúrate de ajustar la ruta
    $correo = $_POST['correo'];
    
    // Verifica si el correo existe en la base de datos
    $usuario = buscarUsuarioPorCorreo($correo); // Suponiendo que tienes una función para buscar el usuario por correo
    
    if ($usuario) {
        // Generar un token y almacenarlo temporalmente en la base de datos
        $token = bin2hex(random_bytes(50)); // Generar un token seguro
        almacenarToken($usuario['id_usuario'], $token); // Almacena el token en la base de datos con el id del usuario
        
        // Enviar el correo con el enlace de restablecimiento
        $enlace = "http://tusitio.com/cambiar_contrasena.php?token=" . $token;
        mail($correo, "Cambio de contraseña", "Haz clic en el siguiente enlace para cambiar tu contraseña: $enlace");
        
        echo "Revisa tu correo electrónico para cambiar tu contraseña.";
    } else {
        echo "El correo no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cambio de Contraseña</title>
</head>
<body>
    <form method="POST" action="solicitar_cambio_contrasena.php">
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" id="correo" required>
        <button type="submit">Enviar enlace de restablecimiento</button>
    </form>
</body>
</html>
