<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get date parameter (default to current date)
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Daily Sales Report - ' . date('d M Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(10);

// ===== Membership Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Amount Paid', 1);
$pdf->Cell(40, 10, 'Start Date', 1);
$pdf->Cell(40, 10, 'End Date', 1);
$pdf->Cell(30, 10, 'Service', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$membershipRows = select("
    SELECT m.Name, s.Amount, m.StartDate, m.EndDate, srv.Name AS ServiceName
    FROM Sales s
    JOIN Membership m ON s.MemberId = m.Id
    JOIN Services srv ON m.ServiceId = srv.Id
    WHERE DATE(m.StartDate) = ? AND m.IsDelete = 1
", [$date]);

$totalMembership = 0;
foreach ($membershipRows as $row) {
    $pdf->Cell(50, 10, $row['Name'], 1);
    $pdf->Cell(30, 10, number_format($row['Amount'], 2), 1);
    $pdf->Cell(40, 10, $row['StartDate'], 1);
    $pdf->Cell(40, 10, $row['EndDate'], 1);
    $pdf->Cell(30, 10, $row['ServiceName'], 1);
    $pdf->Ln();
    $totalMembership += $row['Amount'];
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Total Membership', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalMembership, 2), 1);
$pdf->Cell(110, 10, '', 1);
$pdf->Ln(15);

// ===== Client Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Client Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(40, 10, 'Date', 1);
$pdf->Cell(40, 10, 'Therapist', 1);
$pdf->Cell(30, 10, 'Massage', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$clientRows = select("
    SELECT c.Name, c.Price, c.Date, e.Name AS TherapistName, c.Massage
    FROM Clients c
    LEFT JOIN Employee e ON c.EmployeeId = e.Id
    WHERE c.Date = ?
", [$date]);

$totalClientSales = 0;
foreach ($clientRows as $row) {
    $pdf->Cell(50, 10, $row['Name'], 1);
    $pdf->Cell(30, 10, number_format($row['Price'], 2), 1);
    $pdf->Cell(40, 10, $row['Date'], 1);
    $pdf->Cell(40, 10, $row['TherapistName'], 1);
    $pdf->Cell(30, 10, $row['Massage'], 1);
    $pdf->Ln();
    $totalClientSales += $row['Price'];
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Total Clients', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalClientSales, 2), 1);
$pdf->Cell(110, 10, '', 1);
$pdf->Ln(15);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(110, 10, '', 1);

$pdf->Output();
?>
