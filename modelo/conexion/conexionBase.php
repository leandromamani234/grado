<?php
class ConexionBase {
    private $host;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct() {
        // Cargar los detalles de conexión desde configDb.php
        require_once "configDb.php";  // Asegúrate de que esta ruta es correcta
        $this->host = HOST;
        $this->user = USER;
        $this->password = PASSWORD;
        $this->database = DATABASE;
    }

    public function CreateConnection() {
        // Crear la conexión a MySQL
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->conn->connect_errno) {
            throw new Exception("Error de conexión a la base de datos: " . $this->conn->connect_error);
        }
        return true; // Retorna true si la conexión fue exitosa
    }

    public function CloseConnection() {
        // Cerrar la conexión si está abierta
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function ExecuteQuery($sql) {
        // Verifica si la conexión fue establecida correctamente antes de ejecutar el query
        if (!$this->conn) {
            throw new Exception("No hay conexión activa a la base de datos.");
        }

        // Ejecuta la consulta SQL
        $result = $this->conn->query($sql);
        if ($this->conn->error) {
            throw new Exception("Error en la consulta SQL: " . $this->conn->error);
        }
        return $result;
    }

    public function GetCountAffectedRows() {
        // Retorna la cantidad de filas afectadas por la última consulta
        return $this->conn->affected_rows;
    }

    public function GetArrayResults($result) {
        // Retorna un arreglo asociativo con los resultados de la consulta
        return $result->fetch_assoc();
    }

    public function SetFreeResult($result) {
        // Libera la memoria del resultado
        if ($result) {
            $result->free_result();
        }
    }

    public function getConnection() {
        // Retorna la conexión actual
        return $this->conn;
    }

    public function PrepareStatement($sql) {
        // Prepara una consulta SQL y retorna el objeto statement
        $stmt = $this->conn->prepare($sql);
        if ($this->conn->error) {
            throw new Exception("Error preparando la consulta: " . $this->conn->error);
        }
        return $stmt;
    }
}
?>
