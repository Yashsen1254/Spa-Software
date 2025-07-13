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
$pdf->Cell(0, 10, 'Daily Expenses Report - ' . date('d M Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(10);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Employee Salaries', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Employee Name', 1, 0, 'C');
$pdf->Cell(30, 10, 'Salary Paid', 1, 0, 'C');
$pdf->Cell(35, 10, 'Payment Date', 1, 0, 'C');
$pdf->Cell(35, 10, 'Payment Mode', 1, 0, 'C');
$pdf->Cell(40, 10, 'Mobile', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$salaryRows = select("
    SELECT Name, GivenSalary, SalaryPaidDate, PaymentMode, Mobile 
    FROM Employee 
    WHERE SalaryPaidDate = ?", 
    [$date]
);

$totalSalaries = 0;
foreach ($salaryRows as $row) {
    $pdf->Cell(50, 10, $row['Name'], 1);
    $pdf->Cell(30, 10, number_format($row['GivenSalary'], 2), 1, 0, 'R');
    $pdf->Cell(35, 10, $row['SalaryPaidDate'], 1, 0, 'C');
    $pdf->Cell(35, 10, $row['PaymentMode'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['Mobile'], 1, 1, 'C');
    $totalSalaries += $row['GivenSalary'];
}

// ===== Total Salaries =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Total Salaries', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalSalaries, 2), 1, 0, 'R');
$pdf->Cell(110, 10, '', 1, 1);
$pdf->Ln(8);

// ===== Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Expenses', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(35, 10, 'Name', 1, 0, 'C');
$pdf->Cell(55, 10, 'Description', 1, 0, 'C');
$pdf->Cell(25, 10, 'Date', 1, 0, 'C');
$pdf->Cell(15, 10, 'Qty', 1, 0, 'C');
$pdf->Cell(30, 10, 'Amount', 1, 0, 'C');
$pdf->Cell(30, 10, 'Payment Mode', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$expenseRows = select("
    SELECT Name, Description, Date, Quantity, TotalAmount, PaymentMode 
    FROM Expenses 
    WHERE Date = ?", 
    [$date]
);

$totalExpenses = 0;
foreach ($expenseRows as $row) {
    $pdf->Cell(35, 10, $row['Name'], 1);
    $pdf->Cell(55, 10, $row['Description'], 1);
    $pdf->Cell(25, 10, $row['Date'], 1, 0, 'C');
    $pdf->Cell(15, 10, $row['Quantity'], 1, 0, 'C');
    $pdf->Cell(30, 10, number_format($row['TotalAmount'], 2), 1, 0, 'R');
    $pdf->Cell(30, 10, $row['PaymentMode'], 1, 1, 'C');
    $totalExpenses += $row['TotalAmount'];
}

// ===== Total Expenses =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total Expenses', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalExpenses, 2), 1, 0, 'R');
$pdf->Cell(30, 10, '', 1, 1);
$pdf->Ln(8);

// ===== Grand Total =====
$grandTotal = $totalSalaries + $totalExpenses;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(130, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1, 0, 'R');
$pdf->Cell(30, 10, '', 1, 1);

$pdf->Output();
?>
