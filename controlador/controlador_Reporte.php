<?php
require_once '../modelo/modelo_Reportes.php';

class ControladorReportes {
    private $modeloReportes;

    public function __construct() {
        $this->modeloReportes = new ModeloReportes();
    }

    // Obtener los 3 reportes principales
    public function obtenerReportes() {
        return [
            'socios' => $this->modeloReportes->obtenerReporteSocios(),
            'deudas' => $this->modeloReportes->obtenerReporteDeudas(),
            'recibos' => $this->modeloReportes->obtenerReporteRecibos()
        ];
    }

    // Generar reportes PDF según tipo
    public function generarReporte($tipo_reporte, $estado = null) {
        if ($tipo_reporte === 'socios') {
            $this->generarReporteSocios($estado);
        } elseif ($tipo_reporte === 'deudas') {
            $this->generarReporteDeudas($estado);
        } elseif ($tipo_reporte === 'recibos') {
            $this->generarReporteRecibos();
        }
    }

    private function generarReporteSocios($estado) {
        $socios = $this->modeloReportes->obtenerReporteSocios($estado);

        if (empty($socios)) {
            echo "No hay socios que coincidan con el estado seleccionado.";
            return;
        }

        require_once '../vista/librerias/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Socios', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Nombre Completo');
        $pdf->Cell(40, 10, 'Estado');
        $pdf->Cell(60, 10, 'OTB');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($socios as $socio) {
            $pdf->Cell(80, 10, utf8_decode($socio['nombre_completo']));
            $pdf->Cell(40, 10, $socio['estado']);
            $pdf->Cell(60, 10, utf8_decode($socio['nombre_otb']));
            $pdf->Ln();
        }

        $pdf->Output('I', 'reporte_socios.pdf');
    }

    private function generarReporteDeudas($estado) {
        $deudas = $this->modeloReportes->obtenerReporteDeudas($estado);

        if (empty($deudas)) {
            echo "No hay deudas que coincidan con el estado seleccionado.";
            return;
        }

        require_once '../vista/librerias/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Deudas', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID');
        $pdf->Cell(50, 10, 'Socio');
        $pdf->Cell(30, 10, 'Monto');
        $pdf->Cell(30, 10, 'Estado');
        $pdf->Cell(60, 10, 'Observaciones');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($deudas as $deuda) {
            $pdf->Cell(20, 10, $deuda['id_deuda']);
            $pdf->Cell(50, 10, utf8_decode($deuda['nombre_completo']));
            $pdf->Cell(30, 10, $deuda['monto']);
            $pdf->Cell(30, 10, $deuda['estado']);
            $pdf->Cell(60, 10, utf8_decode($deuda['observaciones']));
            $pdf->Ln();
        }

        $pdf->Output('I', 'reporte_deudas.pdf');
    }

    private function generarReporteRecibos() {
        $recibos = $this->modeloReportes->obtenerReporteRecibos();

        if (empty($recibos)) {
            echo "No hay recibos registrados.";
            return;
        }

        require_once '../vista/librerias/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Recibos Emitidos', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID');
        $pdf->Cell(50, 10, 'Nombre');
        $pdf->Cell(30, 10, 'Casa');
        $pdf->Cell(30, 10, 'Importe');
        $pdf->Cell(50, 10, 'Fecha');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($recibos as $recibo) {
            $pdf->Cell(20, 10, $recibo['id_recibo']);
            $pdf->Cell(50, 10, utf8_decode($recibo['nombre']));
            $pdf->Cell(30, 10, $recibo['numero_casa']);
            $pdf->Cell(30, 10, $recibo['importe_bs']);
            $pdf->Cell(50, 10, $recibo['fecha_lectura']);
            $pdf->Ln();
        }

        $pdf->Output('I', 'reporte_recibos.pdf');
    }
}
