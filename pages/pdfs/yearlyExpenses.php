<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get year parameter (default to current year)
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Yearly Expenses Report - ' . $year, 0, 1, 'C');
$pdf->Ln(8);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Employee Salaries', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'Employee Name', 1, 0, 'C');
$pdf->Cell(35, 8, 'Salary', 1, 0, 'C');
$pdf->Cell(40, 8, 'Payment Date', 1, 0, 'C');
$pdf->Cell(50, 8, 'Mobile', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$salaryRows = select("
    SELECT Name, GivenSalary, SalaryPaidDate, Mobile
    FROM Employee
    WHERE YEAR(SalaryPaidDate) = ?
", [$year]);

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(50, 7, $row['Name'], 1);
        $pdf->Cell(35, 7, 'Rs ' . number_format($row['GivenSalary'], 2), 1, 0, 'R');
        $pdf->Cell(40, 7, $row['SalaryPaidDate'] ?? '-', 1);
        $pdf->Cell(50, 7, $row['Mobile'] ?? '-', 1, 1);
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(175, 7, 'No salary payments for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(125, 8, 'Total Salaries', 1, 0, 'R');
$pdf->Cell(50, 8, 'Rs ' . number_format($totalSalaries, 2), 1, 1, 'R');
$pdf->Ln(10);

// ===== Other Expenses =====
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Other Expenses', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 8, 'Name', 1, 0, 'C');
$pdf->Cell(55, 8, 'Description', 1, 0, 'C');
$pdf->Cell(30, 8, 'Date', 1, 0, 'C');
$pdf->Cell(20, 8, 'Qty', 1, 0, 'C');
$pdf->Cell(30, 8, 'Amount', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$expenseRows = select("
    SELECT Name, Description, Date, Quantity, TotalAmount
    FROM Expenses
    WHERE YEAR(Date) = ?
", [$year]);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(40, 7, $row['Name'], 1);
        $pdf->Cell(55, 7, $row['Description'], 1);
        $pdf->Cell(30, 7, $row['Date'] ?? '-', 1);
        $pdf->Cell(20, 7, $row['Quantity'] ?? '-', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Rs ' . number_format($row['TotalAmount'], 2), 1, 1, 'R');
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(175, 7, 'No expense records for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(145, 8, 'Total Other Expenses', 1, 0, 'R');
$pdf->Cell(30, 8, 'Rs ' . number_format($totalExpenses, 2), 1, 1, 'R');
$pdf->Ln(10);

// ===== Grand Total =====
$grandTotal = $totalSalaries + $totalExpenses;
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(145, 10, 'Grand Total Expenses', 1, 0, 'R');
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1, 1, 'R');

$pdf->Output();
?>
