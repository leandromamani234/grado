<?php
require_once 'conexion/conexionBase.php';

class ModeloRecuperar {
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    public function guardarToken($id_usuario, $token) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $stmt = $conn->prepare("INSERT INTO tokens_recuperacion (id_usuario, token) VALUES (?, ?)");
        $stmt->bind_param("is", $id_usuario, $token);
        $stmt->execute();
        $stmt->close();

        $this->con->CloseConnection();
    }

    public function verificarToken($token) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
    
        $stmt = $conn->prepare("SELECT id_usuario FROM tokens_recuperacion WHERE token = ? AND fecha_creacion >= NOW() - INTERVAL 3 MINUTE");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
    
        $stmt->close();
        $this->con->CloseConnection();
    
        return $fila ? $fila['id_usuario'] : false;
    }
    

    public function eliminarToken($token) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $stmt = $conn->prepare("DELETE FROM tokens_recuperacion WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();

        $this->con->CloseConnection();
    }
}
