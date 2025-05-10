<?php
require_once 'conexion/conexionBase.php';

class ModeloPropiedad {
    private $manzano;
    private $numero;
    private $id_socio;
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    public function asignar($nombre, $valor) {
        $this->$nombre = $valor;
    }

    public function validar() {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();

        // 1. Verificar que el socio exista
        $sql_socio = "SELECT id_persona FROM socios WHERE id_persona = ?";
        $stmt_socio = $conn->prepare($sql_socio);
        $stmt_socio->bind_param('i', $this->id_socio);
        $stmt_socio->execute();
        $res_socio = $stmt_socio->get_result();

        if ($res_socio->num_rows == 0) {
            $stmt_socio->close();
            $this->con->CloseConnection();
            return "Error: Esta persona no está registrada como socio.";
        }
        $stmt_socio->close();

        // 2. Verificar que el socio no tenga ya una propiedad
        $sql_prop_socio = "SELECT COUNT(*) AS total FROM propiedades WHERE id_socio = ?";
        $stmt_prop = $conn->prepare($sql_prop_socio);
        $stmt_prop->bind_param('i', $this->id_socio);
        $stmt_prop->execute();
        $res = $stmt_prop->get_result()->fetch_assoc();

        if ($res['total'] > 0) {
            $stmt_prop->close();
            $this->con->CloseConnection();
            return "Este socio ya tiene una propiedad registrada.";
        }
        $stmt_prop->close();

        // 3. Validar que el número no esté usado globalmente (sin importar manzano)
        $sql_check_num = "SELECT COUNT(*) AS total FROM propiedades WHERE numero = ?";
        $stmt_num = $conn->prepare($sql_check_num);
        $stmt_num->bind_param('i', $this->numero);
        $stmt_num->execute();
        $res = $stmt_num->get_result()->fetch_assoc();

        if ($res['total'] > 0) {
            $stmt_num->close();
            $this->con->CloseConnection();
            return "El número {$this->numero} ya está registrado en una propiedad del barrio.";
        }
        $stmt_num->close();

        // 4. Registrar propiedad
        return $this->registrarPropiedad($this->manzano, $this->numero, $this->id_socio);
    }

    public function registrarPropiedad($manzano, $numero, $id_socio) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "INSERT INTO propiedades (manzano, numero, id_socio) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('ssi', $manzano, $numero, $id_socio);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al registrar la propiedad: " . $error;
        }
    }

    public function actualizarPropiedad($id_propiedad, $manzano, $numero, $id_socio) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();

        // Omitimos validación repetida aquí para no romper la edición.
        $sql = "UPDATE propiedades SET manzano = ?, numero = ?, id_socio = ? WHERE id_propiedades = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('ssii', $manzano, $numero, $id_socio, $id_propiedad);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al actualizar la propiedad: " . $error;
        }
    }

    public function eliminar($id_propiedad) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "DELETE FROM propiedades WHERE id_propiedades = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('i', $id_propiedad);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al eliminar la propiedad: " . $error;
        }
    }

    public function obtenerPropiedades() {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $sql = "SELECT p.id_propiedades, p.manzano, p.numero, p.id_socio,
                CONCAT(pe.nombre, ' ', pe.primer_apellido, ' ', pe.segundo_apellido) AS propietario
                FROM propiedades p
                INNER JOIN persona pe ON p.id_socio = pe.id_persona";

        $result = $this->con->ExecuteQuery($sql);

        if ($result === false) {
            return "Error al ejecutar la consulta: " . $this->con->getConnection()->error;
        }

        $propiedades = array();
        while ($row = $this->con->GetArrayResults($result)) {
            $propiedades[] = $row;
        }

        $this->con->CloseConnection();
        return $propiedades;
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
    
}
