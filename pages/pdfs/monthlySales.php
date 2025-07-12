<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get month and year parameters (default to current month)
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Monthly Sales Report - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)), 0, 1, 'C');
$pdf->Ln(10);

// ===== Membership Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Customer Name', 1);
$pdf->Cell(40, 10, 'Total Amount', 1);
$pdf->Cell(45, 10, 'Start Date', 1);
$pdf->Cell(45, 10, 'End Date', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$membershipRows = select("
    SELECT m.Name, m.TotalAmount, m.StartDate, m.EndDate
    FROM Membership m
    WHERE MONTH(m.StartDate) = ? AND YEAR(m.StartDate) = ? AND m.IsDelete = 1",
    [$month, $year]);

$totalMembership = 0;
foreach ($membershipRows as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, $row['TotalAmount'], 1);
    $pdf->Cell(45, 10, $row['StartDate'], 1);
    $pdf->Cell(45, 10, $row['EndDate'], 1);
    $pdf->Ln();
    $totalMembership += $row['TotalAmount'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Total Membership', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalMembership, 2), 1);
$pdf->Cell(90, 10, '', 1);
$pdf->Ln(15);

// ===== Client Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Client Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 10, 'Customer Name', 1);
$pdf->Cell(40, 10, 'Price', 1);
$pdf->Cell(60, 10, 'Date', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$clientRows = select("
    SELECT Name, Price, Date
    FROM Clients
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?",
    [$month, $year]);

$totalClientSales = 0;
foreach ($clientRows as $row) {
    $pdf->Cell(70, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, $row['Price'], 1);
    $pdf->Cell(60, 10, $row['Date'], 1);
    $pdf->Ln();
    $totalClientSales += $row['Price'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 10, 'Total Client Sales', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalClientSales, 2), 1);
$pdf->Cell(60, 10, '', 1);
$pdf->Ln(15);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(70, 10, 'Grand Total', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(60, 10, '', 1);

$pdf->Output();
?>
