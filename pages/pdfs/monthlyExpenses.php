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
$pdf->Cell(0, 10, 'Monthly Expenses Report - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)), 0, 1, 'C');
$pdf->Ln(10);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Employee Salaries', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Employee Name', 1);
$pdf->Cell(30, 10, 'Salary Paid', 1);
$pdf->Cell(40, 10, 'Payment Date', 1);
$pdf->Cell(60, 10, 'Mobile', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$salaryRows = select("SELECT Name, SalaryPaid, SalaryPaidDate, Mobile 
                     FROM Employee 
                     WHERE MONTH(SalaryPaidDate) = ? AND YEAR(SalaryPaidDate) = ?", 
                     [$month, $year]);

$totalSalaries = 0;
foreach ($salaryRows as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(30, 10, $row['SalaryPaid'], 1);
    $pdf->Cell(40, 10, $row['SalaryPaidDate'], 1);
    $pdf->Cell(60, 10, $row['Mobile'], 1);
    $pdf->Ln();
    $totalSalaries += $row['SalaryPaid'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Total Salaries', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Cell(100, 10, '', 1);
$pdf->Ln(15);

// ===== Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Date', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Total Amount', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$expenseRows = select("SELECT Name, Description, Date, Quantity, TotalAmount 
                      FROM Expenses 
                      WHERE MONTH(Date) = ? AND YEAR(Date) = ?", 
                      [$month, $year]);

$totalExpenses = 0;
foreach ($expenseRows as $row) {
    $pdf->Cell(40, 10, $row['Name'], 1);
    $pdf->Cell(50, 10, $row['Description'], 1);
    $pdf->Cell(30, 10, $row['Date'], 1);
    $pdf->Cell(30, 10, $row['Quantity'], 1);
    $pdf->Cell(40, 10, $row['TotalAmount'], 1);
    $pdf->Ln();
    $totalExpenses += $row['TotalAmount'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Total Expenses', 1);
$pdf->Cell(80, 10, '', 1);
$pdf->Cell(30, 10, '', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Ln(15);

// ===== Grand Total =====
$grandTotal = $totalSalaries + $totalExpenses;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Grand Total', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(100, 10, '', 1);

$pdf->Output();
?>