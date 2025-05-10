<?php
require_once 'conexion/conexionBase.php';

class ModeloRecibos {
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    public function registrarRecibo($id_propiedad, $fecha_lectura, $lectura_anterior, $lectura_actual, $consumo_m3, $monto_pagar, $observaciones, $numero_serie) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }
    
        $conn = $this->con->getConnection();
    
        $sql = "INSERT INTO recibos (id_propiedad, fecha_lectura, lectura_anterior, lectura_actual, consumo_m3, importe_bs, observaciones, numero_serie) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            $this->con->CloseConnection();
            return "Error en la preparación de la consulta: " . $conn->error;
        }
    
        $stmt->bind_param('issdddss', $id_propiedad, $fecha_lectura, $lectura_anterior, $lectura_actual, $consumo_m3, $monto_pagar, $observaciones, $numero_serie);
    
        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al registrar el recibo: " . $error;
        }
    }
    
    
    

    public function obtenerTodosLosRecibos() {
        if (!$this->con->CreateConnection()) return [];

        $sql = "SELECT r.*, p.numero AS numero_casa
                FROM recibos r
                INNER JOIN propiedades p ON r.id_propiedad = p.id_propiedades";

        $result = $this->con->ExecuteQuery($sql);
        $recibos = [];

        while ($row = $this->con->GetArrayResults($result)) {
            $recibos[] = $row;
        }

        $this->con->CloseConnection();
        return $recibos;
    }

    public function obtenerReciboPorId($id_recibo) {
        if (!$this->con->CreateConnection()) return null;
    
        $conn = $this->con->getConnection();
    
        $sql = "SELECT r.*, 
                       p.numero AS numero_casa,
                       CONCAT(per.nombre, ' ', per.primer_apellido, ' ', per.segundo_apellido) AS nombre_completo
                FROM recibos r
                INNER JOIN propiedades p ON r.id_propiedad = p.id_propiedades
                INNER JOIN socios s ON p.id_socio = s.id_persona
                INNER JOIN persona per ON s.id_persona = per.id_persona
                WHERE r.id_recibo = ?";
    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $this->con->CloseConnection();
            return null;
        }
    
        $stmt->bind_param('i', $id_recibo);
        $stmt->execute();
        $result = $stmt->get_result();
        $recibo = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    
        $stmt->close();
        $this->con->CloseConnection();
        return $recibo;
    }
    
    
    
    
    
    

    public function actualizarRecibo($id_recibo, $id_propiedad, $fecha_lectura, $lectura_anterior, $lectura_actual, $consumo_m3, $monto_pagar, $observaciones, $numero_serie) {
        if (!$this->con->CreateConnection()) return false;
    
        $conn = $this->con->getConnection();
        $sql = "UPDATE recibos 
                SET id_propiedad = ?, fecha_lectura = ?, lectura_anterior = ?, lectura_actual = ?, consumo_m3 = ?, importe_bs = ?, observaciones = ?, numero_serie = ?
                WHERE id_recibo = ?";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            $this->con->CloseConnection();
            return false;
        }
    
        $stmt->bind_param('issdddssi', $id_propiedad, $fecha_lectura, $lectura_anterior, $lectura_actual, $consumo_m3, $monto_pagar, $observaciones, $numero_serie, $id_recibo);
        $resultado = $stmt->execute();
    
        $stmt->close();
        $this->con->CloseConnection();
        return $resultado;
    }
    

    public function eliminarRecibo($id_recibo) {
        if (!$this->con->CreateConnection()) return false;

        $conn = $this->con->getConnection();
        $sql = "DELETE FROM recibos WHERE id_recibo = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $this->con->CloseConnection();
            return false;
        }

        $stmt->bind_param('i', $id_recibo);
        $resultado = $stmt->execute();

        $stmt->close();
        $this->con->CloseConnection();
        return $resultado;
    }

    public function obtenerRecibosPorSocio($id_socio) {
        if (!$this->con->CreateConnection()) return [];
    
        $sql = "SELECT r.*, p.numero AS numero_casa
                FROM recibos r
                INNER JOIN propiedades p ON r.id_propiedad = p.id_propiedades
                INNER JOIN socios s ON p.id_propiedades = s.id_propiedad
                WHERE s.id_socio = ?";
    
        $stmt = $this->con->getConnection()->prepare($sql);
        if (!$stmt) return [];
    
        $stmt->bind_param("i", $id_socio);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $recibos = [];
        while ($row = $result->fetch_assoc()) {
            $recibos[] = $row;
        }
    
        $stmt->close();
        $this->con->CloseConnection();
        return $recibos;
    }
    

    public function obtenerRecibosPorPropiedad($id_propiedad) {
        if (!$this->con->CreateConnection()) return [];

        $sql = "SELECT r.*, p.numero AS numero_casa
                FROM recibos r
                INNER JOIN propiedades p ON r.id_propiedad = p.id_propiedades
                WHERE r.id_propiedad = ?";

        $stmt = $this->con->getConnection()->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $id_propiedad);
        $stmt->execute();
        $result = $stmt->get_result();

        $recibos = [];
        while ($row = $result->fetch_assoc()) {
            $recibos[] = $row;
        }

        $stmt->close();
        $this->con->CloseConnection();
        return $recibos;
    }

    public function obtenerSiguienteNumeroSerie() {
        if (!$this->con->CreateConnection()) {
            return null;
        }
    
        $conn = $this->con->getConnection();
    
        $sql = "SELECT numero_serie FROM recibos ORDER BY numero_serie ASC";
        $result = $conn->query($sql);
        $existentes = [];
    
        while ($row = $result->fetch_assoc()) {
            $existentes[] = $row['numero_serie'];
        }
    
        for ($i = 1; $i <= 365; $i++) {
            $num = str_pad($i, 4, '0', STR_PAD_LEFT);
            if (!in_array($num, $existentes)) {
                $this->con->CloseConnection();
                return $num;
            }
        }
    
        $this->con->CloseConnection();
        return null; // todos usados
    }

    public function obtenerUltimaLecturaActual($id_propiedad) {
        if (!$this->con->CreateConnection()) return null;
    
        $conn = $this->con->getConnection();
        $sql = "SELECT lectura_actual FROM recibos WHERE id_propiedad = ? ORDER BY id_recibo DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            $this->con->CloseConnection();
            return null;
        }
    
        $stmt->bind_param('i', $id_propiedad);
        $stmt->execute();
        $result = $stmt->get_result();
        $lectura = $result->num_rows > 0 ? $result->fetch_assoc()['lectura_actual'] : null;
    
        $stmt->close();
        $this->con->CloseConnection();
        return $lectura;
    }
    
    
}
?>
