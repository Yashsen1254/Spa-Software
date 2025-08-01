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

// ===== Membership Sales (Amount from Appointments) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Membership Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Customer Name', 1);
$pdf->Cell(30, 10, 'Amount', 1);
$pdf->Cell(25, 10, 'Start Date', 1);
$pdf->Cell(25, 10, 'End Date', 1);
$pdf->Cell(40, 10, 'Service', 1);
$pdf->Cell(30, 10, 'Payment', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$membershipRows = select("
    SELECT 
        m.Name,
        COALESCE(SUM(a.Amount), 0) AS TotalAmount,
        m.StartDate,
        m.EndDate,
        s.Name AS ServiceName,
        m.PaymentMode
    FROM Membership m
    LEFT JOIN Appointments a 
        ON m.Id = a.MemberId 
        AND YEAR(a.AppointmentDate) = ? 
        AND a.IsDelete = 1
    LEFT JOIN Services s ON m.ServiceId = s.Id
    WHERE m.IsDelete = 1
    GROUP BY m.Id
", [$year]);

$totalMembership = 0;
if (!empty($membershipRows)) {
    foreach ($membershipRows as $row) {
        $pdf->Cell(40, 10, substr($row['Name'], 0, 20) . (strlen($row['Name']) > 20 ? '...' : ''), 1);
        $pdf->Cell(30, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Cell(25, 10, $row['StartDate'], 1);
        $pdf->Cell(25, 10, $row['EndDate'], 1);
        $pdf->Cell(40, 10, substr($row['ServiceName'] ?? '-', 0, 15), 1);
        $pdf->Cell(30, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 10), 1);
        $pdf->Ln();
        $totalMembership += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(190, 10, 'No membership sales data for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Total Membership', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalMembership, 2), 1);
$pdf->Cell(120, 10, '', 1);
$pdf->Ln(12);

// ===== Client Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Client Sales', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Customer Name', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(25, 10, 'Date', 1);
$pdf->Cell(30, 10, 'Therapist', 1);
$pdf->Cell(30, 10, 'Massage', 1);
$pdf->Cell(35, 10, 'Payment', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$clientRows = select("
    SELECT c.Name, c.Price, c.Date, e.Name AS TherapistName, c.Massage, c.PaymentMode
    FROM Clients c
    LEFT JOIN Employee e ON c.EmployeeId = e.Id
    WHERE YEAR(c.Date) = ?
", [$year]);

$totalClientSales = 0;
if (!empty($clientRows)) {
    foreach ($clientRows as $row) {
        $pdf->Cell(40, 10, substr($row['Name'], 0, 20) . (strlen($row['Name']) > 20 ? '...' : ''), 1);
        $pdf->Cell(30, 10, 'Rs ' . number_format($row['Price'], 2), 1);
        $pdf->Cell(25, 10, $row['Date'], 1);
        $pdf->Cell(30, 10, substr($row['TherapistName'] ?? '-', 0, 12), 1);
        $pdf->Cell(30, 10, substr($row['Massage'] ?? '-', 0, 12), 1);
        $pdf->Cell(35, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 10), 1);
        $pdf->Ln();
        $totalClientSales += $row['Price'];
    }
} else {
    $pdf->Cell(190, 10, 'No client sales data for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Total Client Sales', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalClientSales, 2), 1);
$pdf->Cell(120, 10, '', 1);
$pdf->Ln(12);

// ===== Grand Total =====
$grandTotal = $totalMembership + $totalClientSales;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(40, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(120, 10, '', 1);

$pdf->Output();
?>
