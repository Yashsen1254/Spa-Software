<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Daily Sales Report', 0, 1, 'C');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Client Name', 1);
$pdf->Cell(40, 10, 'Amount Paid', 1);
$pdf->Cell(50, 10, 'Start Date', 1);
$pdf->Cell(40, 10, 'End Date', 1);
$pdf->Ln();

// Table Body
$pdf->SetFont('Arial', '', 12);
$rows = select("SELECT Name, AmountPaid, StartDate, EndDate FROM Clients WHERE StartDate = CURDATE() AND IsDelete = 1");
foreach ($rows as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, $row['AmountPaid'], 1);
    $pdf->Cell(50, 10, $row['StartDate'], 1);
    $pdf->Cell(40, 10, $row['EndDate'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>
