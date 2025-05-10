<?php
require_once '../modelo/modelo_estadisticas.php';

class ControladorHome {
    
    private $modeloEstadisticas;

    public function __construct() {
        $this->modeloEstadisticas = new ModeloEstadisticas(); // Instancia del modelo
    }

    public function obtenerEstadisticas() {
        // Inicializamos un arreglo para almacenar las estadísticas
        $estadisticas = [];

        // Obtenemos los datos del modelo y verificamos que se obtienen correctamente
        $total_socios = $this->modeloEstadisticas->obtenerTotalSocios();
        $total_recibos = $this->modeloEstadisticas->obtenerTotalRecibos();
        $total_deudas = $this->modeloEstadisticas->obtenerTotalDeudas();

        // Validar que no hubo errores en las consultas y que los datos sean los correctos
        if (is_numeric($total_socios)) {
            $estadisticas['total_socios'] = $total_socios;
        } else {
            $estadisticas['total_socios'] = 0; // Valor por defecto si hay un error
        }

        if (is_array($total_recibos)) {
            $estadisticas['total_recibos'] = $total_recibos['total_recibos'];
            $estadisticas['total_monto_recibos'] = $total_recibos['total_monto_recibos'];
        } else {
            $estadisticas['total_recibos'] = 0;
            $estadisticas['total_monto_recibos'] = 0;
        }

        if (is_array($total_deudas)) {
            $estadisticas['total_deudas'] = $total_deudas['total_deudas'];
            $estadisticas['total_monto_deudas'] = $total_deudas['total_monto_deudas'];
        } else {
            $estadisticas['total_deudas'] = 0;
            $estadisticas['total_monto_deudas'] = 0;
        }

        return $estadisticas;
    }
}
?>
