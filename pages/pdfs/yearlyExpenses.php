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
$pdf->Ln(10);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Employee Salaries', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Employee Name', 1);
$pdf->Cell(35, 10, 'Given Salary', 1);
$pdf->Cell(40, 10, 'Payment Date', 1);
$pdf->Cell(50, 10, 'Mobile', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$salaryRows = select("
    SELECT Name, GivenSalary, SalaryPaidDate, Mobile
    FROM Employee
    WHERE YEAR(SalaryPaidDate) = ?
", [$year]);

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(50, 10, $row['Name'], 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['GivenSalary'], 2), 1);
        $pdf->Cell(40, 10, $row['SalaryPaidDate'] ?? '-', 1);
        $pdf->Cell(50, 10, $row['Mobile'] ?? '-', 1);
        $pdf->Ln();
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(175, 10, 'No salary payments for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Total Salaries', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Cell(90, 10, '', 1);
$pdf->Ln(12);

// ===== Other Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Other Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Date', 1);
$pdf->Cell(25, 10, 'Qty', 1);
$pdf->Cell(35, 10, 'Total Amount', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$expenseRows = select("
    SELECT Name, Description, Date, Quantity, TotalAmount
    FROM Expenses
    WHERE YEAR(Date) = ?
", [$year]);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(40, 10, $row['Name'], 1);
        $pdf->Cell(50, 10, $row['Description'], 1);
        $pdf->Cell(30, 10, $row['Date'] ?? '-', 1);
        $pdf->Cell(25, 10, $row['Quantity'] ?? '-', 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Ln();
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(180, 10, 'No expense records for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(145, 10, 'Total Other Expenses', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Ln(12);

// ===== Grand Total =====
$grandTotal = $totalSalaries + $totalExpenses;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(70, 10, 'Grand Total Expenses', 1);
$pdf->Cell(110, 10, 'Rs ' . number_format($grandTotal, 2), 1);

$pdf->Output();
?>
