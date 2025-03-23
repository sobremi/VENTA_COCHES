<?php
require_once('../fpdf/fpdf.php');

class VehiculosPDF extends FPDF {
    function Header() {
        // Encabezado con logo
        $this->SetFillColor(52, 152, 219);
        $this->SetTextColor(255);
        $this->Cell(0, 20, '', 0, 1, 'C', true);
        $this->SetY(10);
        
        // Logo (asegúrate de tener este archivo)
        $this->Image('../assets/img/logo.png', 10, 5, 30);
        
        // Título
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, 'Reporte de Vehículos', 0, 1, 'C');
        
        // Fecha
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Fecha: ' . date('d/m/Y H:i'), 0, 1, 'R');
        
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function TablaVehiculos($header, $data) {
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(241, 196, 15);
        $this->SetTextColor(0);

        // Ancho de columnas
        $w = [20, 30, 30, 15, 25, 30, 25, 25, 30];

        // Cabecera
        $this->SetFont('Arial', 'B', 9);
        foreach($header as $i => $h) {
            $this->Cell($w[$i], 7, $h, 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(245, 245, 245);
        $fill = false;
        
        foreach($data as $row) {
            foreach($row as $i => $field) {
                $align = ($i == 6 || $i == 8) ? 'R' : 'L'; // Alineación derecha para precios y kilometraje
                $this->Cell($w[$i], 6, $field, 'LR', 0, $align, $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        
        // Línea de cierre
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function EstadisticasSeccion($stats) {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Estadísticas Generales', 0, 1, 'C');
        $this->Ln(5);

        // Datos principales
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, sprintf('Total de vehículos: %d', $stats['total']), 0, 1);
        $this->Cell(0, 8, sprintf('Vehículos disponibles: %d', $stats['disponibles']), 0, 1);
        $this->Cell(0, 8, sprintf('Precio promedio: %.2f €', $stats['precioPromedio']), 0, 1);
        
        $this->Ln(10);

        // Gráfico de disponibilidad (simulado con celdas)
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Disponibilidad', 0, 1);
        
        $disponibles = ($stats['disponibles'] / $stats['total']) * 100;
        $this->SetFillColor(46, 204, 113);
        $this->Cell(($disponibles * 1.5), 10, sprintf('%.1f%%', $disponibles), 0, 0, 'C', true);
        $this->SetFillColor(231, 76, 60);
        $this->Cell((100 - $disponibles) * 1.5, 10, sprintf('%.1f%%', 100 - $disponibles), 0, 1, 'C', true);
    }
}