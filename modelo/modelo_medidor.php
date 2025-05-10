<?php
require_once 'conexion/conexionBase.php';

class ModeloMedidor {
    private $serie;
    private $marca;
    private $id_propiedad;
    private $lectura_inicial;
    private $con;

    public function __construct() {
        $this->serie = "";
        $this->marca = "";
        $this->id_propiedad = 0;
        $this->lectura_inicial = 0;
        $this->con = new ConexionBase();
    }

    public function asignar($nombre, $valor) {
        $this->$nombre = $valor;
    }

    public function registrarMedidor() {
        if (!$this->con->CreateConnection()) return "Error al conectar a la base de datos.";
        $conn = $this->con->getConnection();

        $sql = "INSERT INTO medidor (serie, marca, id_propiedad, lectura_inicial) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return "Error en la preparación de la consulta: " . $conn->error;

        $stmt->bind_param('ssii', $this->serie, $this->marca, $this->id_propiedad, $this->lectura_inicial);
        if ($stmt->execute()) {
            $stmt->close(); $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close(); $this->con->CloseConnection();
            return "Error al registrar el medidor: " . $error;
        }
    }

    public function actualizarMedidor($id_medidor) {
        if (!$this->con->CreateConnection()) return "Error al conectar a la base de datos.";
        $conn = $this->con->getConnection();

        $sql = "UPDATE medidor SET serie = ?, marca = ?, id_propiedad = ?, lectura_inicial = ? WHERE id_medidor = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return "Error en la preparación de la consulta: " . $conn->error;

        $stmt->bind_param('ssiii', $this->serie, $this->marca, $this->id_propiedad, $this->lectura_inicial, $id_medidor);
        if ($stmt->execute()) {
            $stmt->close(); $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close(); $this->con->CloseConnection();
            return "Error al actualizar el medidor: " . $error;
        }
    }

    public function eliminarMedidor($id_medidor) {
        if (!$this->con->CreateConnection()) return "Error al conectar a la base de datos.";
        $conn = $this->con->getConnection();

        $sql = "DELETE FROM medidor WHERE id_medidor = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return "Error en la preparación de la consulta: " . $conn->error;

        $stmt->bind_param('i', $id_medidor);
        if ($stmt->execute()) {
            $stmt->close(); $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close(); $this->con->CloseConnection();
            return "Error al eliminar el medidor: " . $error;
        }
    }

    public function obtenerMedidores() {
        $this->con->CreateConnection();
        $sql = "SELECT m.id_medidor, m.serie, m.marca, p.manzano, p.numero, m.lectura_inicial
                FROM medidor m
                INNER JOIN propiedades p ON m.id_propiedad = p.id_propiedades";
        $result = $this->con->ExecuteQuery($sql);
        if ($result === false) return "Error al ejecutar la consulta: " . $this->con->getConnection()->error;

        $medidores = [];
        while ($row = $this->con->GetArrayResults($result)) {
            $medidores[] = $row;
        }

        $this->con->CloseConnection();
        return $medidores;
    }

    public function obtenerMedidorPorId($id_medidor) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
        $sql = "SELECT m.id_medidor, m.serie, m.marca, p.id_propiedades, p.manzano, p.numero, m.lectura_inicial
                FROM medidor m
                INNER JOIN propiedades p ON m.id_propiedad = p.id_propiedades
                WHERE m.id_medidor = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return "Error en la preparación de la consulta: " . $conn->error;

        $stmt->bind_param('i', $id_medidor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $medidor = $result->fetch_assoc();
            $stmt->close(); $this->con->CloseConnection();
            return $medidor;
        } else {
            $stmt->close(); $this->con->CloseConnection();
            return null;
        }
    }

    public function obtenerPropiedades() {
        $this->con->CreateConnection();
        $sql = "SELECT id_propiedades, manzano, numero FROM propiedades";
        $result = $this->con->ExecuteQuery($sql);
        if ($result === false) return "Error al ejecutar la consulta: " . $this->con->getConnection()->error;

        $propiedades = [];
        while ($row = $this->con->GetArrayResults($result)) {
            $propiedades[] = $row;
        }

        $this->con->CloseConnection();
        return $propiedades;
    }

    public function obtenerPropiedadesConMedidor() {
        if (!$this->con->CreateConnection()) return [];
        $sql = "SELECT id_propiedad AS id_propiedades FROM medidor";
        $result = $this->con->ExecuteQuery($sql);

        $ocupadas = [];
        while ($row = $this->con->GetArrayResults($result)) {
            $ocupadas[] = $row;
        }

        $this->con->CloseConnection();
        return $ocupadas;
    }

    public function verificarSerieExistente($serie, $excluir_id = null) {
        if (!$this->con->CreateConnection()) return true;

        $conn = $this->con->getConnection();
        $sql = "SELECT id_medidor FROM medidor WHERE serie = ?" . ($excluir_id ? " AND id_medidor != ?" : "");
        $stmt = $conn->prepare($sql);

        if ($excluir_id) {
            $stmt->bind_param('si', $serie, $excluir_id);
        } else {
            $stmt->bind_param('s', $serie);
        }

        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        $this->con->CloseConnection();

        return $existe;
    }

    public function obtenerLecturaAnteriorPorPropiedad($id_propiedad) {
        if (!$this->con->CreateConnection()) return null;
    
        $conn = $this->con->getConnection();
    
        // 1. Buscar la última lectura_actual registrada en recibos
        $sql = "SELECT lectura_actual FROM recibos 
                WHERE id_propiedad = ? 
                ORDER BY id_recibo DESC 
                LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;
    
        $stmt->bind_param("i", $id_propiedad);
        $stmt->execute();
        $stmt->bind_result($lectura_actual);
    
        if ($stmt->fetch()) {
            $stmt->close();
            $this->con->CloseConnection();
            return $lectura_actual;
        }
    
        $stmt->close();
    
        // 2. Si no hay recibo anterior, buscar lectura inicial del medidor
        $sql2 = "SELECT lectura_inicial FROM medidor WHERE id_propiedad = ?";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) return null;
    
        $stmt2->bind_param("i", $id_propiedad);
        $stmt2->execute();
        $stmt2->bind_result($lectura_inicial);
    
        $resultado = $stmt2->fetch() ? $lectura_inicial : null;
    
        $stmt2->close();
        $this->con->CloseConnection();
    
        return $resultado;
    }
    
    
    
}
?>
