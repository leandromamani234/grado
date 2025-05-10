<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../vista/css/password.css">
    <script>
        // Esta función se encarga de mostrar y ocultar el mensaje después de un tiempo
        function mostrarMensaje() {
            const mensaje = document.getElementById("mensaje");
            mensaje.style.display = "block"; // Mostrar el mensaje

            // Después de 3 segundos, ocultar el mensaje
            setTimeout(function() {
                mensaje.style.display = "none";
            }, 3000); // 3000 milisegundos = 3 segundos
        }

        // Ejecutamos la función cuando la página se cargue si hay un mensaje en la URL
        window.onload = function() {
            <?php if (isset($_GET['mensaje'])): ?>
                mostrarMensaje();
            <?php endif; ?>
        };

        // Función para deshabilitar el botón de enviar
        function deshabilitarBoton() {
            const boton = document.getElementById("btnEnviar");
            boton.disabled = true; // Deshabilitar el botón
        }

        // Función para habilitar el botón de enviar
        function habilitarBoton() {
            const boton = document.getElementById("btnEnviar");
            boton.disabled = false; // Habilitar el botón
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="../controlador/controlador_recuperar.php" method="POST" onsubmit="deshabilitarBoton()">
            <label for="email">Correo:</label>
            <input type="email" name="email" required placeholder="Ingresa tu correo">
            <button type="submit" id="btnEnviar">Enviar enlace</button>
        </form>
        
        <!-- Mensaje que se mostrará después de enviar el correo -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div id="mensaje" class="message"><?= htmlspecialchars($_GET['mensaje']) ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
