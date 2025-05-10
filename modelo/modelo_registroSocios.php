<?php
require_once 'conexion/conexionBase.php';

class ModeloSocios {
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    // Registrar socio
    public function registrarSocio($id_persona, $estado, $id_otb) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();

        // Verificar si el socio ya está registrado
        $checkSql = "SELECT * FROM socios WHERE id_persona = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $id_persona);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            $this->con->CloseConnection();
            return "Error: Este socio ya está registrado.";
        }
        $checkStmt->close();

        // Insertar nuevo socio
        $sql = "INSERT INTO socios (id_persona, estado, id_otb) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $this->con->CloseConnection();
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('isi', $id_persona, $estado, $id_otb);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al registrar el socio: " . $error;
        }
    }

    // Obtener socio por ID
    public function obtenerSocioPorId($id_persona) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "SELECT s.id_persona, s.estado, s.id_otb, p.nombre, p.primer_apellido, p.segundo_apellido 
                FROM socios s
                JOIN persona p ON s.id_persona = p.id_persona
                WHERE s.id_persona = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $this->con->CloseConnection();
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('i', $id_persona);
        $stmt->execute();
        $result = $stmt->get_result();
        $socio = $result->fetch_assoc();
        $stmt->close();
        $this->con->CloseConnection();

        return $socio;
    }

    // Actualizar socio (solo id_persona y id_otb, NO estado)
    public function actualizarSocio($id_persona_actual, $id_persona_nueva, $id_otb) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();

        if ($id_persona_actual != $id_persona_nueva) {
            // Verificar que no esté ya registrado como socio
            $sql_check = "SELECT COUNT(*) FROM socios WHERE id_persona = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param('i', $id_persona_nueva);
            $stmt_check->execute();
            $stmt_check->bind_result($existe);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($existe > 0) {
                $this->con->CloseConnection();
                return "Error: La persona seleccionada ya está registrada como socio.";
            }

            // Verificar que exista en persona
            $sql_validar = "SELECT COUNT(*) FROM persona WHERE id_persona = ?";
            $stmt_validar = $conn->prepare($sql_validar);
            $stmt_validar->bind_param('i', $id_persona_nueva);
            $stmt_validar->execute();
            $stmt_validar->bind_result($existe_persona);
            $stmt_validar->fetch();
            $stmt_validar->close();

            if ($existe_persona == 0) {
                $this->con->CloseConnection();
                return "Error: La persona seleccionada no existe en la tabla persona.";
            }
        }

        // Actualizar id_persona e id_otb (estado NO se toca)
        $sql = "UPDATE socios SET id_persona = ?, id_otb = ? WHERE id_persona = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $this->con->CloseConnection();
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('iii', $id_persona_nueva, $id_otb, $id_persona_actual);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al actualizar el socio: " . $error;
        }
    }

    // Eliminar socio
    public function eliminarSocio($id_persona) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "DELETE FROM socios WHERE id_persona = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $this->con->CloseConnection();
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('i', $id_persona);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al eliminar el socio: " . $error;
        }
    }

    // Obtener todos los socios
    public function obtenerSocios() {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "SELECT s.id_persona, s.estado, o.nombre AS otb, 
                       p.nombre, p.primer_apellido, p.segundo_apellido 
                FROM socios s
                JOIN persona p ON s.id_persona = p.id_persona
                JOIN otb o ON s.id_otb = o.id_otb";
        $result = $conn->query($sql);

        $socios = array();
        while ($row = $result->fetch_assoc()) {
            $socios[] = $row;
        }

        $this->con->CloseConnection();
        return $socios;
    }

    // Obtener reporte de socios
    public function obtenerReporteSocios() {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "SELECT s.id_persona, CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo
                FROM socios s
                INNER JOIN persona p ON s.id_persona = p.id_persona";
        $result = $conn->query($sql);

        $socios = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $socios[] = $row;
            }
        }

        $this->con->CloseConnection();
        return $socios;
    }

    // Cambiar estado de socio
    public function cambiarEstado($id_persona, $nuevo_estado) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }

        $conn = $this->con->getConnection();
        $sql = "UPDATE socios SET estado = ? WHERE id_persona = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nuevo_estado, $id_persona);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return "Error al actualizar el estado: $error";
        }
    }

    public function actualizarBarrio($id_persona, $id_otb) {
    if (!$this->con->CreateConnection()) {
        return "Error al conectar a la base de datos.";
    }

    $conn = $this->con->getConnection();
    $sql = "UPDATE socios SET id_otb = ? WHERE id_persona = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $this->con->CloseConnection();
        return "Error en la preparación de la consulta: " . $conn->error;
    }

    $stmt->bind_param('ii', $id_otb, $id_persona);

    if ($stmt->execute()) {
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    } else {
        $error = $stmt->error;
        $stmt->close();
        $this->con->CloseConnection();
        return "Error al actualizar el socio: " . $error;
    }
}

}
?>
