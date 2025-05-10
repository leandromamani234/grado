<?php
require_once 'conexion/conexionBase.php';

class ModeloDeudas {
    private $id_deuda;
    private $id_socio;
    private $monto;
    private $fecha_deuda;
    private $estado;
    private $tipo_deuda;
    private $observaciones;
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    public function asignar($campo, $valor) {
        $this->$campo = $valor;
    }

    public function registrarDeuda() {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();

        // Validar que el id_socio exista en la tabla socios (clave primaria es id_persona)
        $check = $conn->prepare("SELECT id_persona FROM socios WHERE id_persona = ?");
        if (!$check) return "Error: " . $conn->error;
        $check->bind_param('i', $this->id_socio);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            $check->close();
            $this->con->CloseConnection();
            return "El socio no existe.";
        }
        $check->close();

        // Insertar deuda
        $stmt = $conn->prepare("INSERT INTO deudas (id_socio, monto, fecha_deuda, estado, tipo_deuda, observaciones)
                                VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) return "Error en prepare: " . $conn->error;

        $stmt->bind_param('idssss', $this->id_socio, $this->monto, $this->fecha_deuda, $this->estado, $this->tipo_deuda, $this->observaciones);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al registrar: " . $error;
        }

        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }

    public function obtenerDeudas() {
        if (!$this->con->CreateConnection()) return [];

        $sql = "SELECT d.id_deuda, d.monto, d.fecha_deuda, d.estado, d.tipo_deuda, d.observaciones,
                       CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo
                FROM deudas d
                INNER JOIN socios s ON d.id_socio = s.id_persona
                INNER JOIN persona p ON s.id_persona = p.id_persona";

        $result = $this->con->ExecuteQuery($sql);
        $deudas = [];

        while ($row = $this->con->GetArrayResults($result)) {
            $deudas[] = $row;
        }

        $this->con->CloseConnection();
        return $deudas;
    }

    public function eliminarDeuda($id_deuda) {
        if (!$this->con->CreateConnection()) return false;
        $conn = $this->con->getConnection();

        $stmt = $conn->prepare("DELETE FROM deudas WHERE id_deuda = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_deuda);
        $stmt->execute();
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }

    public function actualizarDeuda() {
        if (!$this->con->CreateConnection()) return false;
        $conn = $this->con->getConnection();

        $stmt = $conn->prepare("UPDATE deudas SET id_socio = ?, monto = ?, fecha_deuda = ?, estado = ?, tipo_deuda = ?, observaciones = ? WHERE id_deuda = ?");
        if (!$stmt) return false;

        $stmt->bind_param('idssssi', $this->id_socio, $this->monto, $this->fecha_deuda, $this->estado, $this->tipo_deuda, $this->observaciones, $this->id_deuda);
        $stmt->execute();
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }
    public function pagarDeuda($id_deuda) {
        if (!$this->con->CreateConnection()) return false;
        $conn = $this->con->getConnection();
    
        $stmt = $conn->prepare("UPDATE deudas SET estado = 'Pagado' WHERE id_deuda = ?");
        if (!$stmt) return false;
    
        $stmt->bind_param("i", $id_deuda);
        $stmt->execute();
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }
    
    public function anularDeuda($id_deuda) {
        if (!$this->con->CreateConnection()) return false;
        $conn = $this->con->getConnection();
    
        $stmt = $conn->prepare("UPDATE deudas SET estado = 'Anulado' WHERE id_deuda = ?");
        if (!$stmt) return false;
    
        $stmt->bind_param("i", $id_deuda);
        $stmt->execute();
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }
    
    public function obtenerDeudaPorId($id_deuda) {
        if (!$this->con->CreateConnection()) return false;

        $stmt = $this->con->getConnection()->prepare("SELECT * FROM deudas WHERE id_deuda = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_deuda);
        $stmt->execute();
        $result = $stmt->get_result();
        $deuda = $result->fetch_assoc();
        $stmt->close();
        $this->con->CloseConnection();
        return $deuda;
    }

    public function obtenerDeudasPorPersona($id_persona) {
        if (!$this->con->CreateConnection()) return [];
    
        $sql = "SELECT d.id_deuda, d.monto, d.fecha_deuda, d.estado, d.tipo_deuda, d.observaciones,
                       CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo
                FROM deudas d
                INNER JOIN socios s ON d.id_socio = s.id_persona
                INNER JOIN persona p ON s.id_persona = p.id_persona
                WHERE d.id_socio = ?";
    
        $conn = $this->con->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
    
        $stmt->bind_param("i", $id_persona);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $deudas = [];
        while ($row = $result->fetch_assoc()) {
            $deudas[] = $row;
        }
    
        $stmt->close();
        $this->con->CloseConnection();
        return $deudas;
    }
    
}
