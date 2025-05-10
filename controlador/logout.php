<?php
session_start(); // Iniciar la sesión

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Evitar que el navegador use el caché para mostrar páginas anteriores
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Redirigir al usuario a la página de inicio de sesión
header("Location: ../vista/index.php");
exit();
?>
