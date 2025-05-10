<?php
// Definición de Constantes
define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DATABASE', 'proyect1');

try {
    // Crear una nueva conexión usando PDO
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
    
    // Configurar PDO para que arroje excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Deshabilitar la emulación de sentencias preparadas
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Mensaje de conexión exitosa
    // Este mensaje debe eliminarse en producción
    //echo "Conexión exitosa a la base de datos";

} catch (PDOException $e) {
    // Registrar el error en un archivo en lugar de mostrarlo al usuario en producción
    error_log("Error de conexión: " . $e->getMessage(), 3, '/var/log/proyect_errors.log');
    
    // Mostrar un mensaje genérico en producción
    echo "Error al conectar con la base de datos. Por favor, inténtalo más tarde.";
    
    // Detener el script
    exit();
}
?>
