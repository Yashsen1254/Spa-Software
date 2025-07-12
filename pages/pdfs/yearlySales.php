<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get year parameter (default to current year)
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Yearly Sales Report - ' . $year, 0, 1, 'C');
$pdf->Ln(10);

// ===== Membership Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Customer Name', 1);
$pdf->Cell(35, 10, 'Total Amount', 1);
$pdf->Cell(30, 10, 'Start Date', 1);
$pdf->Cell(30, 10, 'End Date', 1);
$pdf->Cell(45, 10, 'Service', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$membershipRows = select("
    SELECT m.Name, m.TotalAmount, m.StartDate, m.EndDate, s.Name AS ServiceName
    FROM Membership m
    LEFT JOIN Services s ON m.ServiceId = s.Id
    WHERE YEAR(m.StartDate) = ? AND m.IsDelete = 1
", [$year]);

$totalMembership = 0;
if (!empty($membershipRows)) {
    foreach ($membershipRows as $row) {
        $pdf->Cell(50, 10, $row['Name'], 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Cell(30, 10, $row['StartDate'], 1);
        $pdf->Cell(30, 10, $row['EndDate'], 1);
        $pdf->Cell(45, 10, $row['ServiceName'] ?? '-', 1);
        $pdf->Ln();
        $totalMembership += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(190, 10, 'No membership sales data for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Total Membership', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalMembership, 2), 1);
$pdf->Cell(105, 10, '', 1);
$pdf->Ln(12);

// ===== Client Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Client Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Customer Name', 1);
$pdf->Cell(35, 10, 'Price', 1);
$pdf->Cell(30, 10, 'Date', 1);
$pdf->Cell(35, 10, 'Therapist', 1);
$pdf->Cell(40, 10, 'Massage', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$clientRows = select("
    SELECT c.Name, c.Price, c.Date, e.Name AS TherapistName, c.Massage
    FROM Clients c
    LEFT JOIN Employee e ON c.EmployeeId = e.Id
    WHERE YEAR(c.Date) = ?
", [$year]);

$totalClientSales = 0;
if (!empty($clientRows)) {
    foreach ($clientRows as $row) {
        $pdf->Cell(50, 10, $row['Name'], 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['Price'], 2), 1);
        $pdf->Cell(30, 10, $row['Date'], 1);
        $pdf->Cell(35, 10, $row['TherapistName'] ?? '-', 1);
        $pdf->Cell(40, 10, $row['Massage'] ?? '-', 1);
        $pdf->Ln();
        $totalClientSales += $row['Price'];
    }
} else {
    $pdf->Cell(190, 10, 'No client sales data for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Total Client Sales', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalClientSales, 2), 1);
$pdf->Cell(105, 10, '', 1);
$pdf->Ln(12);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Grand Total', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(105, 10, '', 1);

$pdf->Output();
?>
