<?php
require_once 'conexion/conexionBase.php';

class ModeloUsuario {
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
        $this->con->CreateConnection(); // Asegura que se establece la conexión al crear la instancia
    }
    

    public function verificarCredenciales($usuario, $password) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        $sql = "SELECT u.id_usuario, u.nick, u.pass, ur.id_rol, IFNULL(r.nombre_rol, 'Sin rol') as rol
        FROM usuarios u
        LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN roles r ON ur.id_rol = r.id_rol
        WHERE u.nick = ?";

        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $usuarioData = $result->fetch_assoc();
    
            echo "<pre>";
            echo "Usuario encontrado:<br>";
            echo "Nick: " . $usuarioData['nick'] . "<br>";
            echo "Contraseña ingresada: " . $password . "<br>";
            echo "Contraseña almacenada en la BD (hash): " . $usuarioData['pass'] . "<br>";
            echo "Rol: " . $usuarioData['rol'] . "<br>";
    
            if (password_verify($password, $usuarioData['pass'])) {
                echo "✅ Contraseña correcta.<br>";
                return [
                    'id_usuario' => $usuarioData['id_usuario'],
                    'nick' => $usuarioData['nick'],
                    'id_rol' => $usuarioData['id_rol'], // ✅ ahora se guarda bien
                    'rol' => $usuarioData['rol']
                ];
                
            } else {
                echo "❌ Contraseña incorrecta.<br>";
                return false;
            }
            echo "</pre>";
        } else {
            echo "❌ Usuario no encontrado en la base de datos.<br>";
            return false;
        }
    }
    
    
    // Registrar un nuevo usuario
    public function registrarUsuario($nick, $hashed_pass, $persona_id_persona, $email) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        // Insertar el email junto con otros datos
        $sql = "INSERT INTO usuarios (nick, pass, persona_id_persona, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $nick, $hashed_pass, $persona_id_persona, $email);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            $id_usuario = $stmt->insert_id;
        } else {
            $id_usuario = false;
        }
    
        $stmt->close();
        return $id_usuario;
    }
    
    

    public function registrarUsuarioConRol($nick, $hashed_pass, $persona_id_persona, $email, $id_rol) {
        // Primero, registramos al usuario pasando todos los parámetros necesarios
        $id_usuario = $this->registrarUsuario($nick, $hashed_pass, $persona_id_persona, $email);
    
        if ($id_usuario) {
            // Luego, asignamos el rol al usuario
            $resultado = $this->asignarRol($id_usuario, $id_rol);
    
            if ($resultado) {
                return "Usuario registrado y rol asignado con éxito.";
            } else {
                return "Error al asignar el rol al usuario.";
            }
        } else {
            return "Error al registrar el usuario.";
        }
    }

    public function asignarRol($id_usuario, $id_rol) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        // Verificar si el rol existe
        $sql_check_rol = "SELECT id_rol FROM roles WHERE id_rol = ?";
        $stmt_check_rol = $conn->prepare($sql_check_rol);
        $stmt_check_rol->bind_param("i", $id_rol);
        $stmt_check_rol->execute();
        $result_rol = $stmt_check_rol->get_result();
    
        if ($result_rol->num_rows === 0) {
            // Si el rol no existe, devolver un mensaje de error
            return "El rol con id_rol $id_rol no existe.";
        }
    
        // Verificar si ya existe una relación entre el usuario y el rol
        $sql_check = "SELECT * FROM usuario_rol WHERE id_usuario = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id_usuario);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
    
        if ($result->num_rows > 0) {
            // Si ya existe la relación, actualizar el rol
            $sql_update = "UPDATE usuario_rol SET id_rol = ? WHERE id_usuario = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $id_rol, $id_usuario);
            $resultado = $stmt_update->execute();
            $stmt_update->close();
        } else {
            // Si no existe la relación, insertarla
            $sql_insert = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ii", $id_usuario, $id_rol);
            $resultado = $stmt_insert->execute();
            $stmt_insert->close();
        }
    
        $stmt_check->close();
        return $resultado;
    }
    

    public function eliminarUsuario($id_usuario) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        // Iniciar una transacción para asegurar que todas las eliminaciones sean exitosas
        $conn->begin_transaction();
    
        try {
            // 1. Eliminar la relación usuario-rol en `usuario_rol`
            $sql_rol = "DELETE FROM usuario_rol WHERE id_usuario = ?";
            $stmt_rol = $conn->prepare($sql_rol);
            $stmt_rol->bind_param("i", $id_usuario);
            $stmt_rol->execute();
            $stmt_rol->close();
    
            // 2. Eliminar el usuario de la tabla `usuarios`
            $sql_usuario = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bind_param("i", $id_usuario);
            $stmt_usuario->execute();
    
            if ($stmt_usuario->affected_rows > 0) {
                $conn->commit(); // Confirmar la eliminación
                $stmt_usuario->close();
                return true;
            } else {
                throw new Exception("No se pudo eliminar el usuario.");
            }
        } catch (Exception $e) {
            $conn->rollback(); // Revertir si hay algún error
            return "Error: " . $e->getMessage();
        } finally {
            $this->con->CloseConnection(); // Cerrar la conexión
        }
    }
    
    // Obtener todos los usuarios con sus roles
    public function obtenerUsuariosConRoles() {
        $conn = $this->con->getConnection();
    
        $sql = "SELECT u.id_usuario, u.nick, u.email, 
                       CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo, 
                       r.nombre_rol AS rol
                FROM usuarios u
                INNER JOIN persona p ON u.persona_id_persona = p.id_persona
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol";
    
        $result = $conn->query($sql);
        $usuarios = array();
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
    
        $this->con->CloseConnection();
        return $usuarios;
    }

    public function actualizarContrasena($id_usuario, $nuevaContrasena) {
        // Verificar si la conexión sigue activa
        if (!$this->con) {
            throw new Exception("Error: Conexión a la base de datos no inicializada.");
        }
    
        $conn = $this->con->getConnection(); // Obtener la conexión activa
    
        if (!$conn) {
            throw new Exception("Error: Conexión cerrada antes de ejecutar la consulta.");
        }
    
        $hashed_pass = $pass; // Se almacena en texto plano

    
        $sql = "UPDATE usuarios SET pass = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
    
        $stmt->bind_param("si", $hashedPassword, $id_usuario);
        $resultado = $stmt->execute();
        $stmt->close();
    
        return $resultado;
    }
    
    // Verificar existencia del rol en la base de datos
    public function verificarRol($id_rol) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        $sql = "SELECT id_rol FROM roles WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Error preparando la consulta para verificar el rol: " . $conn->error);
        }
    
        $stmt->bind_param("i", $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        return $result->num_rows > 0; // Retorna true si existe, false si no existe
    }
    
    // Verificar si el usuario existe
    public function verificarUsuario($usuario) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        $sql = "SELECT id_usuario, email FROM usuarios WHERE nick = ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $conn->error);
        }
    
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $usuarioExiste = $result->num_rows > 0;
        $stmt->close();
    
        return $usuarioExiste;
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        $sql = "SELECT u.id_usuario, u.nick, u.email, r.id_rol, r.nombre_rol 
                FROM usuarios u
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol
                WHERE u.id_usuario = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return false;
        }
    }

    public function actualizarUsuario($id_usuario, $nick, $email, $nuevaContrasena = null, $id_rol) {
        $conn = $this->con->getConnection();
    
        if (!$conn) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    
        // Actualizar nick, email y opcionalmente contraseña
        if (!empty($nuevaContrasena)) {
            $hashed_pass = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nick = ?, email = ?, pass = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nick, $email, $hashed_pass, $id_usuario);
        } else {
            $sql = "UPDATE usuarios SET nick = ?, email = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nick, $email, $id_usuario);
        }
    
        $stmt->execute();
        $stmt->close();
    
        // Actualizar rol
        $sqlRol = "UPDATE usuario_rol SET id_rol = ? WHERE id_usuario = ?";
        $stmtRol = $conn->prepare($sqlRol);
        $stmtRol->bind_param("ii", $id_rol, $id_usuario);
        $stmtRol->execute();
        $stmtRol->close();
    
        return true;
    }
    
    public function correoExiste($correo) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
    
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $existe = $result->num_rows > 0;
    
        $stmt->close();
        $this->con->CloseConnection();
    
        return $existe;
    }
    

    //funcion p ra  buscar por el email en el la table de usuarios 
    public function buscarPorEmail($email) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $stmt = $conn->prepare("SELECT id_usuario, email FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $this->con->CloseConnection();

        return $resultado;
    }

    public function actualizarContrasenas($id_usuario, $nueva) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $hashed = password_hash($nueva, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE usuarios SET pass = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $hashed, $id_usuario);
        $resultado = $stmt->execute();

        $stmt->close();
        $this->con->CloseConnection();

        return $resultado;
    }
    

}
?>
