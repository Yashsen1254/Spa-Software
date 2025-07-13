<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get date parameter (default to current date)
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$pdf = new FPDF();
$pdf->AddPage();

// ===== Header =====
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, 'Daily Sales Report', 0, 1, 'C');
$pdf->Cell(0, 8, 'Date: ' . date('d M Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(5);

// ===== Membership Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
// Table Headers
$pdf->Cell(50, 10, 'Name', 1, 0, 'C');
$pdf->Cell(30, 10, 'Amount', 1, 0, 'C');
$pdf->Cell(40, 10, 'Payment Mode', 1, 0, 'C');
$pdf->Cell(30, 10, 'Start Date', 1, 0, 'C');
$pdf->Cell(30, 10, 'End Date', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$membershipRows = select("
    SELECT 
        m.Name,
        COALESCE(SUM(a.Amount), 0) AS Amount,
        m.PaymentMode,
        m.StartDate,
        m.EndDate
    FROM Membership m
    LEFT JOIN Appointments a 
        ON m.Id = a.MemberId 
        AND DATE(a.AppointmentDate) = ? 
        AND a.IsDelete = 1
    WHERE m.IsDelete = 1
    GROUP BY m.Id
", [$date]);

$totalMembership = 0;
foreach ($membershipRows as $row) {
    $pdf->Cell(50, 8, $row['Name'], 1);
    $pdf->Cell(30, 8, number_format($row['Amount'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, $row['PaymentMode'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['StartDate'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['EndDate'], 1, 1, 'C');
    $totalMembership += $row['Amount'];
}

// Membership Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 8, 'Total Membership', 1);
$pdf->Cell(30, 8, 'Rs ' . number_format($totalMembership, 2), 1, 0, 'R');
$pdf->Cell(100, 8, '', 1, 1);
$pdf->Ln(10);

// ===== Client Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Client Sales', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
// Table Headers
$pdf->Cell(50, 10, 'Name', 1, 0, 'C');
$pdf->Cell(30, 10, 'Price', 1, 0, 'C');
$pdf->Cell(40, 10, 'Payment Mode', 1, 0, 'C');
$pdf->Cell(30, 10, 'Therapist', 1, 0, 'C');
$pdf->Cell(30, 10, 'Massage', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$clientRows = select("
    SELECT 
        c.Name, 
        c.Price, 
        c.PaymentMode, 
        e.Name AS TherapistName, 
        c.Massage
    FROM Clients c
    LEFT JOIN Employee e ON c.EmployeeId = e.Id
    WHERE c.Date = ?
", [$date]);

$totalClientSales = 0;
foreach ($clientRows as $row) {
    $pdf->Cell(50, 8, $row['Name'], 1);
    $pdf->Cell(30, 8, number_format($row['Price'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, $row['PaymentMode'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['TherapistName'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['Massage'], 1, 1, 'C');
    $totalClientSales += $row['Price'];
}

// Client Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 8, 'Total Clients', 1);
$pdf->Cell(30, 8, 'Rs ' . number_format($totalClientSales, 2), 1, 0, 'R');
$pdf->Cell(100, 8, '', 1, 1);
$pdf->Ln(10);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1, 0, 'R');
$pdf->Cell(100, 10, '', 1, 1);

$pdf->Output();
?>
