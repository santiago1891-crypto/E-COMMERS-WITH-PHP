<?php

require('./fpdf.php');

class PDF extends FPDF {
    // Encabezado
        function Header() {
        $this->Image('../imagenes/logo_redondeado.png', 170, 10, 30);

        // Fuente y título
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(95, 28, 28);
        $this->Cell(0,10,'FACTURA',0,1,'L');
        $this->Ln(5);
    }

}



// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(248, 246, 242);

// Datos empresa
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,6,'Fecha: '. date("Y/m/d"),0,1);
$pdf->Ln(5);

// Datos facturación y envío
$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(178, 34, 34);
$pdf->Cell(50,6,'Titular de la cuenta:',0,0);

$pdf->SetFont('Arial','',10);
$pdf->Ln(5);

// Fechas
$pdf->SetTextColor(0,0,0);
$pdf->Ln(5);

// Encabezado de tabla (con borde inferior)
$pdf->SetTextColor(178, 34, 34);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,'CANT.', 'B', 0, 'C');
$pdf->Cell(100,10,'DESCRIPCION', 'B', 0, 'C');
$pdf->Cell(35,10,'PRECIO UNIT.', 'B', 0, 'C');
$pdf->Cell(35,10,'IMPORTE', 'B', 1, 'C');

// Filas (solo borde inferior)
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',12);
$items = [
    ["1", "Talla pequeña traje de luces en rojo", "100.00", "100.00"],
    ["2", "Muy grande churrolito", "25.00", "50.00"],
    ["3", "Equipaje de Fútbol", "5.00", "15.00"],
];

foreach($items as $row){
    $pdf->Cell(20,10,$row[0],'B',0,'C');
    $pdf->Cell(100,10,utf8_decode($row[1]),'B',0,'L');
    $pdf->Cell(35,10,$row[2],'B',0,'R');
    $pdf->Cell(35,10,$row[3],'B',1,'R');
}

// Subtotales
$pdf->Cell(120,8,'',0);
$pdf->Cell(35,8,'Subtotal','B');
$pdf->Cell(35,8,'165.00','B',1,'R');

$pdf->Cell(120,8,'',0);
$pdf->Cell(35,8,'IVA 21%','B');
$pdf->Cell(35,8,'34.65','B',1,'R');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(120,8,'',0);
$pdf->Cell(35,8,'TOTAL','B');
$pdf->Cell(35,8,'$199.65 ','B',1,'R');

$pdf->Output();
?>

