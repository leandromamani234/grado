<?php
include_once "../includes/seguridad.php"; 
verificarRol([1, 4]);

require_once '../vista/librerias/fpdf.php';
require_once '../modelo/modelo_Reportes.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, utf8_decode('Reporte General'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function tablaDatos($header, $data, $widths) {
        $this->SetFont('Arial', 'B', 10);
        // Encabezados
        foreach ($header as $i => $columna) {
            $this->Cell($widths[$i], 8, iconv('UTF-8', 'windows-1252', $columna), 1, 0, 'C');
        }
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            $nb = 1; // número máximo de líneas
            foreach ($row as $i => $text) {
                $nb = max($nb, $this->NbLines($widths[$i], iconv('UTF-8', 'windows-1252', $text ?? '')));
            }
            $h = 5 * $nb; // altura total de la fila

            // Guardar posición inicial
            $x = $this->GetX();
            $y = $this->GetY();

            // Dibujar todas las celdas
            foreach ($row as $i => $text) {
                $w = $widths[$i];
                $this->Rect($x, $y, $w, $h);
                $this->MultiCell($w, 5, iconv('UTF-8', 'windows-1252', $text ?? 'N/A'), 0, 'C');
                $x += $w;
                $this->SetXY($x, $y);
            }

            $this->Ln($h);
        }
    }

    // Calcular cuántas líneas ocupará una celda
    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c] ?? 0;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}


ob_start();
$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetMargins(5, 10, 5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$modeloReportes = new ModeloReportes();
$tipoReporte = $_POST['tipoReporte'] ?? '';
$estadoFiltro = $_POST['estado'] ?? 'todos';

$data = [];
$header = [];
$widths = [];

switch ($tipoReporte) {
    case 'socios':
        $header = ['Nombre Completo', 'Estado', 'OTB'];
        $widths = [80, 40, 60];
        $socios = $modeloReportes->obtenerReporteSocios();

        if ($estadoFiltro === 'Activo') {
            $socios = array_filter($socios, fn($s) => $s['estado'] === 'Activo');
        } elseif ($estadoFiltro === 'Inactivo') {
            $socios = array_filter($socios, fn($s) => $s['estado'] === 'Inactivo');
        } elseif ($estadoFiltro === 'Suspendido') {
            $socios = array_filter($socios, fn($s) => $s['estado'] === 'Suspendido');
        }

        foreach ($socios as $socio) {
            $data[] = [
                $socio['nombre_completo'] ?? 'N/A',
                $socio['estado'] ?? 'N/A',
                $socio['nombre_otb'] ?? 'N/A'
            ];
        }
        break;

    case 'deudas':
        $header = ['ID', 'Nombre', 'Monto', 'Fecha', 'Estado', 'Tipo', 'Observaciones'];
        $widths = [10, 45, 20, 25, 25, 30, 50];
        $deudas = $modeloReportes->obtenerReporteDeudas();

        if ($estadoFiltro !== 'todos') {
            $deudas = array_filter($deudas, fn($d) => strtolower($d['estado']) === strtolower($estadoFiltro));
        }

        foreach ($deudas as $d) {
            $data[] = [
                $d['id_deuda'] ?? 'N/A',
                $d['nombre_completo'] ?? 'N/A',
                number_format($d['monto'] ?? 0, 2),
                $d['fecha_deuda'] ?? 'N/A',
                $d['estado'] ?? 'N/A',
                $d['tipo_deuda'] ?? 'N/A',
                $d['observaciones'] ?? ''
            ];
        }
        break;

    case 'recibos':
        $header = ['ID', 'Nombre', 'N° Casa', 'N° Serie', 'Lectura Ant.', 'Lectura Act.', 'Consumo', 'Importe', 'Fecha', 'Obs.'];
        $widths = [5, 45, 15, 20, 20, 20, 20, 20, 20, 15];
        $recibos = $modeloReportes->obtenerReporteRecibos();

        foreach ($recibos as $r) {
            $data[] = [
                $r['id_recibo'] ?? 'N/A',
                $r['nombre'] ?? 'N/A',
                $r['numero_casa'] ?? 'N/A',
                $r['numero_serie'] ?? '-',
                $r['lectura_anterior'] ?? '-',
                $r['lectura_actual'] ?? '-',
                $r['consumo_m3'] ?? '-',
                number_format($r['importe_bs'] ?? 0, 2),
                $r['fecha_lectura'] ?? 'N/A',
                $r['observaciones'] ?? '-'
            ];
        }
        break;

    default:
        die("Tipo de reporte no válido.");
}

$pdf->tablaDatos($header, $data, $widths);
ob_end_clean();
$pdf->Output('I', 'reporte_' . $tipoReporte . '.pdf');
