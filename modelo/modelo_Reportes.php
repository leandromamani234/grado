<?php
require_once 'conexion/conexionBase.php';

class ModeloReportes {
    private $con;

    public function __construct() {
        $this->con = new ConexionBase();
    }

    // Reporte de socios
    public function obtenerReporteSocios($estado = null) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();

        $sql = "SELECT 
                    p.id_persona, 
                    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo, 
                    s.estado,
                    o.nombre AS nombre_otb
                FROM persona p
                INNER JOIN socios s ON p.id_persona = s.id_persona
                INNER JOIN otb o ON s.id_otb = o.id_otb";

        if ($estado) {
            $sql .= " WHERE s.estado = ?";
        }

        $stmt = $conn->prepare($sql);
        if ($estado) {
            $stmt->bind_param('s', $estado);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $socios = [];
        while ($row = $result->fetch_assoc()) {
            $socios[] = [
                'nombre_completo' => $row['nombre_completo'],
                'estado' => $row['estado'],
                'nombre_otb' => $row['nombre_otb']
            ];
        }

        $stmt->close();
        $this->con->CloseConnection();
        return $socios;
    }

    public function obtenerReporteDeudas($estado = null) {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
    
        $sql = "SELECT d.id_deuda, 
                       CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_completo, 
                       d.monto, 
                       d.fecha_deuda, 
                       d.estado, 
                       d.tipo_deuda, 
                       d.observaciones 
                FROM deudas d
                INNER JOIN socios s ON d.id_socio = s.id_persona
                INNER JOIN persona p ON s.id_persona = p.id_persona";
    
        if ($estado) {
            $sql .= " WHERE d.estado = ?";
        }
    
        $stmt = $conn->prepare($sql);
        if ($estado) {
            $stmt->bind_param('s', $estado);
        }
    
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
    

    // Reporte de recibos con toda la información
    public function obtenerReporteRecibos() {
        $this->con->CreateConnection();
        $conn = $this->con->getConnection();
    
        $sql = "SELECT 
                    r.id_recibo, 
                    r.numero_serie,
                    r.lectura_anterior,
                    r.lectura_actual,
                    r.consumo_m3,
                    r.importe_bs,
                    r.fecha_lectura,
                    r.observaciones,
                    CONCAT(p.nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre,
                    pr.numero AS numero_casa
                FROM recibos r
                INNER JOIN propiedades pr ON r.id_propiedad = pr.id_propiedades
                INNER JOIN socios s ON pr.id_socio = s.id_persona
                INNER JOIN persona p ON s.id_persona = p.id_persona";
    
        $result = $conn->query($sql);
    
        $recibos = [];
        while ($row = $result->fetch_assoc()) {
            $recibos[] = $row;
        }
    
        $this->con->CloseConnection();
        return $recibos;
    }
    
    
}
?>
