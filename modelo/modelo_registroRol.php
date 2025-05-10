<?php
require_once 'conexion/conexionBase.php';

class ModeloRegistroRol {
    private $nombre_rol;
    private $con;

    public function __construct() {
        $this->nombre_rol = "";
        $this->con = new ConexionBase();
    }

    public function asignar($nombre, $valor) {
        $this->$nombre = $valor;
    }

    public function registrar($id_permisos = []) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        if (empty($this->nombre_rol)) {
            return "Error: El nombre del rol no puede estar vacío.";
        }

        $sql = "INSERT INTO roles (nombre_rol) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('s', $this->nombre_rol);

        if ($stmt->execute()) {
            $id_rol = $stmt->insert_id;
            $stmt->close();

            // Asignar permisos
            foreach ($id_permisos as $id_permiso) {
                $this->asignarPermiso($id_rol, $id_permiso);
            }

            $this->con->CloseConnection();
            return "Rol registrado con éxito.";
        } else {
            $mensaje = "Error al registrar el rol: " . $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return $mensaje;
        }
    }

    private function asignarPermiso($id_rol, $id_permiso) {
        $conn = $this->con->getConnection();

        $sql = "INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error en la consulta de asignación de permiso: " . $conn->error;
        }

        $stmt->bind_param('ii', $id_rol, $id_permiso);

        if (!$stmt->execute()) {
            $mensaje = "Error al asignar permiso: " . $stmt->error;
            $stmt->close();
            return $mensaje;
        }

        $stmt->close();
    }

    public function eliminarPermisos($id_rol) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $sql = "DELETE FROM rol_permiso WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error al preparar la consulta de eliminación: " . $conn->error;
        }

        $stmt->bind_param('i', $id_rol);
        $stmt->execute();
        $stmt->close();
    }

    public function eliminarRol($id_rol) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        // Primero elimina los permisos del rol
        $this->eliminarPermisos($id_rol);

        $sql = "DELETE FROM roles WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('i', $id_rol);

        if ($stmt->execute()) {
            $stmt->close();
            $this->con->CloseConnection();
            return "Rol eliminado con éxito.";
        } else {
            $mensaje = "Error al eliminar el rol: " . $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return $mensaje;
        }
    }

    public function obtenerRolPorId($id_rol) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $sql = "SELECT id_rol, nombre_rol FROM roles WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param('i', $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        $rol = $result->fetch_assoc();

        $stmt->close();
        $this->con->CloseConnection();

        return $rol;
    }

    public function obtenerPermisosPorRol($id_rol) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $sql = "SELECT p.nombre_permiso 
                FROM permisos p 
                INNER JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso 
                WHERE rp.id_rol = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return [];
        }

        $stmt->bind_param('i', $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        $permisos = [];
        while ($row = $result->fetch_assoc()) {
            $permisos[] = $row['nombre_permiso'];
        }

        $stmt->close();
        $this->con->CloseConnection();

        return $permisos;
    }

    public function obtenerRoles() {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
    
        $sql = "SELECT id_rol, nombre_rol FROM roles ORDER BY nombre_rol";
        $result = $conn->query($sql);
    
        if (!$result || $result->num_rows === 0) {
            return [];
        }
    
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
    
        $this->con->CloseConnection();
        return $roles;
    }
    

    public function actualizarRol($id_rol, $id_permisos = []) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $sql = "UPDATE roles SET nombre_rol = ? WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }

        $stmt->bind_param('si', $this->nombre_rol, $id_rol);

        if ($stmt->execute()) {
            $stmt->close();

            $this->eliminarPermisos($id_rol);

            foreach ($id_permisos as $id_permiso) {
                $this->asignarPermiso($id_rol, $id_permiso);
            }

            $this->con->CloseConnection();
            return "Rol actualizado con éxito.";
        } else {
            $mensaje = "Error al actualizar el rol: " . $stmt->error;
            $stmt->close();
            $this->con->CloseConnection();
            return $mensaje;
        }
    }
}
?>
