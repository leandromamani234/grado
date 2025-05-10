<?php
require_once 'conexion/conexionBase.php'; // Asegúrate de que la ruta es correcta

class ModeloEstadisticas {

    private $con;

    public function __construct() {
        $this->con = new ConexionBase(); // Instancia de la conexión a la base de datos
    }

    // Método para obtener el total de socios
    public function obtenerTotalSocios() {
        $this->con->CreateConnection();
        $sql = "SELECT COUNT(*) as total_socios FROM socios";
        $result = $this->con->ExecuteQuery($sql);

        if ($result === false) {
            return "Error en la consulta de socios"; // Manejo de error
        }

        $data = $this->con->GetArrayResults($result);
        $this->con->CloseConnection();
        return $data['total_socios'] ?? 0; // Retorna 0 si no encuentra resultado
    }

    // Método para obtener el total de recibos y el monto acumulado de los recibos
    public function obtenerTotalRecibos() {
        $this->con->CreateConnection();
        $sql = "SELECT COUNT(*) as total_recibos, SUM(importe_bs) as total_monto_recibos FROM recibos"; 
        $result = $this->con->ExecuteQuery($sql);

        if ($result === false) {
            return "Error en la consulta de recibos"; // Manejo de error
        }

        $data = $this->con->GetArrayResults($result);
        $this->con->CloseConnection();
        return [
            'total_recibos' => $data['total_recibos'] ?? 0,
            'total_monto_recibos' => $data['total_monto_recibos'] ?? 0
        ];
    }

    // Método para obtener el total de deudas y el monto acumulado de las deudas
    public function obtenerTotalDeudas() {
        $this->con->CreateConnection();
        $sql = "SELECT COUNT(*) as total_deudas, SUM(monto) as total_monto_deudas FROM deudas"; 
        $result = $this->con->ExecuteQuery($sql);

        if ($result === false) {
            return "Error en la consulta de deudas"; // Manejo de error
        }

        $data = $this->con->GetArrayResults($result);
        $this->con->CloseConnection();
        return [
            'total_deudas' => $data['total_deudas'] ?? 0,
            'total_monto_deudas' => $data['total_monto_deudas'] ?? 0
        ];
    }

    // Eliminar el método obtenerTotalLecturas() ya que la tabla 'lecturas' no existe.
    /*
    public function obtenerTotalLecturas() {
        $this->con->CreateConnection();
        $sql = "SELECT COUNT(*) as total_lecturas FROM lecturas";
        $result = $this->con->ExecuteQuery($sql);

        if ($result === false) {
            return "Error en la consulta de lecturas"; // Manejo de error
        }

        $data = $this->con->GetArrayResults($result);
        $this->con->CloseConnection();
        return $data['total_lecturas'] ?? 0; // Retorna 0 si no encuentra resultado
    }
    */
}
?>
