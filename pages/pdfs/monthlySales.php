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

// ===== Membership Sales (Amount from Appointments) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Customer Name', 1);
$pdf->Cell(30, 10, 'Amount', 1);
$pdf->Cell(30, 10, 'Start Date', 1);
$pdf->Cell(30, 10, 'End Date', 1);
$pdf->Cell(50, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$membershipRows = select("
    SELECT 
        m.Name,
        COALESCE(SUM(a.Amount), 0) AS TotalAmount, -- Sum of Appointment.Amount for the month
        m.StartDate,
        m.EndDate,
        m.PaymentMode
    FROM Membership m
    LEFT JOIN Appointments a 
        ON m.Id = a.MemberId 
        AND MONTH(a.AppointmentDate) = ? 
        AND YEAR(a.AppointmentDate) = ? 
        AND a.IsDelete = 1
    WHERE m.IsDelete = 1
    GROUP BY m.Id
", [$month, $year]);

$totalMembership = 0;
foreach ($membershipRows as $row) {
    $pdf->Cell(50, 10, substr($row['Name'], 0, 20) . (strlen($row['Name']) > 20 ? '...' : ''), 1);
    $pdf->Cell(30, 10, number_format($row['TotalAmount'], 2), 1);
    $pdf->Cell(30, 10, $row['StartDate'], 1);
    $pdf->Cell(30, 10, $row['EndDate'], 1);
    $pdf->Cell(50, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 20), 1);
    $pdf->Ln();
    $totalMembership += $row['TotalAmount'];
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
$pdf->Cell(50, 10, 'Customer Name', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(40, 10, 'Date', 1);
$pdf->Cell(50, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$clientRows = select("
    SELECT Name, Price, Date, PaymentMode
    FROM Clients
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?",
    [$month, $year]);

$totalClientSales = 0;
foreach ($clientRows as $row) {
    $pdf->Cell(50, 10, substr($row['Name'], 0, 20) . (strlen($row['Name']) > 20 ? '...' : ''), 1);
    $pdf->Cell(30, 10, number_format($row['Price'], 2), 1);
    $pdf->Cell(40, 10, $row['Date'], 1);
    $pdf->Cell(50, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 20), 1);
    $pdf->Ln();
    $totalClientSales += $row['Price'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Total Client Sales', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalClientSales, 2), 1);
$pdf->Cell(90, 10, '', 1);
$pdf->Ln(15);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(90, 10, '', 1);

$pdf->Output();
?>
