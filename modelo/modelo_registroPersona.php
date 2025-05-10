<?php
require_once 'conexion/conexionBase.php'; // Asegúrate de que la ruta es correcta

class ModeloPersona {
    private $con; // Conexión a la base de datos
    private $nombre;
    private $primer_apellido;
    private $segundo_apellido;
    private $telefono;
    private $celular;
    private $direccion;
    private $email;
    private $CI;

    public function __construct() {
        // Inicializar las propiedades
        $this->nombre = "";
        $this->primer_apellido = "";
        $this->segundo_apellido = "";
        $this->telefono = "";
        $this->celular = "";
        $this->direccion = "";
        $this->email = "";
        $this->CI = "";
        $this->con = new ConexionBase(); // Crear una instancia de la conexión a la base de datos
    }

    // Asignar valores a las propiedades del modelo
    public function asignar($nombre, $valor) {
        $this->$nombre = $valor;
    }

    // Validar los datos antes de registrar
    public function validar() {
        return $this->registrar();
    }

    // Registrar una nueva persona en la base de datos
    public function registrar() {
        // Abre la conexión a la base de datos
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }
        $conn = $this->con->getConnection();
    
        // Buscar el primer ID faltante
        $sql = "SELECT MIN(t1.id_persona + 1) AS next_id
                FROM persona t1
                LEFT JOIN persona t2 ON t1.id_persona + 1 = t2.id_persona
                WHERE t2.id_persona IS NULL";
        
        $result = $conn->query($sql);
        $next_id = 1; // Si no encuentra un ID faltante, comenzará desde 1.
        
        if ($row = $result->fetch_assoc()) {
            if ($row['next_id']) {
                $next_id = $row['next_id'];
            }
        }
    
        // Preparar la consulta SQL para insertar usando el ID faltante
        $sql = "INSERT INTO persona (id_persona, nombre, primer_apellido, segundo_apellido, telefono, celular, direccion, email, CI) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issssssss', $next_id, $this->nombre, $this->primer_apellido, $this->segundo_apellido, 
                                          $this->telefono, $this->celular, $this->direccion, $this->email, $this->CI);
    
        // Ejecutar la consulta y verificar si tuvo éxito
        if ($stmt->execute()) {
            $stmt->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión
            return true; // Éxito
        } else {
            $error = $stmt->error; // Capturar el error si ocurre
            $stmt->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión
            return "Error al registrar la persona: " . $error;
        }
    }

    // Obtener todas las personas de la base de datos
    public function obtenerPersonas() {
        if (!$this->con->CreateConnection()) {
            return [];
        }
    
        // Consulta SQL que asegura traer todos los campos necesarios
        $sql = "SELECT id_persona, nombre, primer_apellido, segundo_apellido, telefono, celular, direccion, email, CI FROM persona";
        $result = $this->con->getConnection()->query($sql);
        $personas = $result->fetch_all(MYSQLI_ASSOC);
    
        $this->con->CloseConnection();
        return $personas;
    }
    

// Obtener una persona por su ID
public function obtenerPersonaPorId($id_persona) {
    // Intentar conectar a la base de datos
    if (!$this->con->CreateConnection()) {
        return "Error al conectar a la base de datos.";
    }

    // Preparar la consulta SQL
    $sql = "SELECT id_persona, nombre, primer_apellido, segundo_apellido, telefono, celular, direccion, email, CI 
            FROM persona WHERE id_persona = ?";
    $stmt = $this->con->getConnection()->prepare($sql);

    // Comprobar si la preparación de la consulta fue exitosa
    if (!$stmt) {
        return "Error al preparar la consulta SQL.";
    }

    // Bindear el parámetro y ejecutar
    $stmt->bind_param('i', $id_persona);
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();
    $persona = $result->fetch_assoc();

    // Cerrar la declaración y la conexión
    $stmt->close();
    $this->con->CloseConnection();

    // Retornar los datos de la persona o null si no se encontró
    return $persona ?: "Persona no encontrada.";
}


    // Actualizar una persona existente en la base de datos
    public function actualizarPersona($id_persona) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }
        $conn = $this->con->getConnection();

        // Preparar la declaración SQL para actualizar
        $sql = "UPDATE persona SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, telefono = ?, celular = ?, direccion = ?, email = ?, CI = ? 
                WHERE id_persona = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi', $this->nombre, $this->primer_apellido, $this->segundo_apellido, 
                                        $this->telefono, $this->celular, $this->direccion, $this->email, $this->CI, $id_persona);

        // Ejecutar la declaración preparada y verificar si tuvo éxito
        if ($stmt->execute()) {
            $stmt->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión a la base de datos
            return true; // Éxito
        } else {
            $error = $stmt->error; // Capturar el error
            $stmt->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión a la base de datos
            return "Error al actualizar la persona: " . $error;
        }
    }

    // Eliminar una persona de la base de datos
    public function eliminarPersona($id_persona) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }
        $conn = $this->con->getConnection();

        // Primero, eliminar las relaciones en la tabla socios (si corresponde)
        $sql = "DELETE FROM socios WHERE id_persona = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_persona);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            return "Error al eliminar relación en socios: " . $error;
        }

        // Preparar la declaración SQL para eliminar persona
        $sql_persona = "DELETE FROM persona WHERE id_persona = ?";
        $stmt_persona = $conn->prepare($sql_persona);
        $stmt_persona->bind_param('i', $id_persona);

        // Ejecutar la declaración preparada y verificar si tuvo éxito
        if ($stmt_persona->execute()) {
            $stmt_persona->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión a la base de datos
            return true; // Éxito
        } else {
            $error = $stmt_persona->error; // Capturar el error
            $stmt_persona->close(); // Cerrar la declaración preparada
            $this->con->CloseConnection(); // Cerrar la conexión a la base de datos
            return "Error al eliminar la persona: " . $error;
        }
    }

    public function validarDatosUnicos($telefono, $celular, $email, $CI) {
        if (!$this->con->CreateConnection()) {
            return "Error al conectar a la base de datos.";
        }
    
        $conn = $this->con->getConnection();
    
        $sql = "SELECT telefono, celular, email, CI FROM persona WHERE telefono = ? OR celular = ? OR email = ? OR CI = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $telefono, $celular, $email, $CI);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $errores = [];
    
            if ($row['telefono'] == $telefono) {
                $errores[] = "el número de teléfono";
            }
            if ($row['celular'] == $celular) {
                $errores[] = "el número de celular";
            }
            if ($row['email'] == $email) {
                $errores[] = "el correo electrónico";
            }
            if ($row['CI'] == $CI) {
                $errores[] = "el CI";
            }
    
            $this->con->CloseConnection();
    
            return "Error: " . implode(", ", $errores) . " ya está registrado(a) a nombre de otra persona.";
        }
    
        $stmt->close();
        $this->con->CloseConnection();
        return true;
    }
    
    public function buscarPersonasNoSocias($texto) {
        if (!$this->con->CreateConnection()) return [];
    
        $conn = $this->con->getConnection();
        $sql = "SELECT p.id_persona, CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo
                FROM persona p
                WHERE p.id_persona NOT IN (SELECT id_persona FROM socios)
                  AND (p.nombre LIKE ? OR p.primer_apellido LIKE ? OR p.segundo_apellido LIKE ?)
                ORDER BY p.nombre LIMIT 10";
    
        $stmt = $conn->prepare($sql);
        $like = "%$texto%";
        $stmt->bind_param("sss", $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $personas = [];
        while ($row = $result->fetch_assoc()) {
            $personas[] = $row;
        }
    
        $stmt->close();
        $this->con->CloseConnection();
        return $personas;
    }
    
    
    
    
}
?>
