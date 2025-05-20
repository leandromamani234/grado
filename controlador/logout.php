<?php
session_start(); // Iniciar la sesión

// Limpiar variables y destruir sesión
session_unset(); // ✅ Limpia todas las variables de sesión
$_SESSION = array(); // Refuerza el vaciado
session_destroy();   // Destruye la sesión

// Evitar que el navegador use caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Redirigir al login
header("Location: ../vista/index.php");
exit();
?>
