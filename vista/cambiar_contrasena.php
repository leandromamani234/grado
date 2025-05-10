<?php $token = $_GET['token'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../vista/css/passwordreset.css">
   
</head>
<body>
<div class="container">
    <h2>Cambiar Contraseña</h2>
    <?php if (isset($_GET['mensaje'])): ?>
        <p class="<?= stripos($_GET['mensaje'], 'éxito') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </p>
    <?php endif; ?>

    <form action="../controlador/guardar_contrasena.php" method="POST" onsubmit="return validarContrasenas()">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <!-- Nueva Contraseña -->
        <div class="input-group">
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" name="nueva_contrasena" id="nueva_contrasena" required>
        </div>

        <!-- Confirmar Contraseña -->
        <div class="input-group">
            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required>
        </div>

        <!-- Botón para mostrar/ocultar contraseña -->
        <button type="button" class="toggle-button" onclick="mostrarOcultarContrasena()">Mostrar Contraseña</button>

        <!-- Botón de Enviar -->
        <div class="form-buttons">
            <button type="submit">Cambiar Contraseña</button>
        </div>
    </form>
</div>

<script>
    // Función para mostrar/ocultar las contraseñas
    function mostrarOcultarContrasena() {
        const nuevaContrasena = document.getElementById("nueva_contrasena");
        const confirmarContrasena = document.getElementById("confirmar_contrasena");
        const botonMostrar = document.querySelector(".toggle-button");

        // Si las contraseñas son ocultas, las mostramos; si están visibles, las ocultamos.
        if (nuevaContrasena.type === "password" && confirmarContrasena.type === "password") {
            nuevaContrasena.type = "text";
            confirmarContrasena.type = "text";
            botonMostrar.textContent = "Ocultar Contraseña";  // Cambiar texto del botón
        } else {
            nuevaContrasena.type = "password";
            confirmarContrasena.type = "password";
            botonMostrar.textContent = "Mostrar Contraseña";  // Cambiar texto del botón
        }
    }

    // Función para validar que las contraseñas coincidan
    function validarContrasenas() {
        const nuevaContrasena = document.getElementById("nueva_contrasena");
        const confirmarContrasena = document.getElementById("confirmar_contrasena");

        if (nuevaContrasena.value !== confirmarContrasena.value) {
            confirmarContrasena.classList.add("error-border"); // Agregar borde rojo si no coinciden
            alert("Las contraseñas no coinciden. Por favor, verifica.");
            return false; // Evitar el envío del formulario
        } else {
            confirmarContrasena.classList.remove("error-border"); // Quitar borde rojo si las contraseñas coinciden
            return true; // Permitir el envío del formulario
        }
    }
</script>
</body>
</html>
