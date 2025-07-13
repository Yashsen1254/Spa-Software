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
$pdf->Cell(80, 10, 'Employee Name', 1);
$pdf->Cell(40, 10, 'Given Salary', 1);
$pdf->Cell(50, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$salaryRows = select("
    SELECT Name, GivenSalary, PaymentMode
    FROM Employee
    WHERE GivenSalary IS NOT NULL AND GivenSalary > 0
");

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(80, 10, substr($row['Name'], 0, 25) . (strlen($row['Name']) > 25 ? '...' : ''), 1);
        $pdf->Cell(40, 10, 'Rs ' . number_format($row['GivenSalary'], 2), 1);
        $pdf->Cell(50, 10, substr($row['PaymentMode'], 0, 20) . (strlen($row['PaymentMode']) > 20 ? '...' : ''), 1);
        $pdf->Ln();
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(170, 10, 'No salary data available for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Total Salaries', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Cell(50, 10, '', 1);
$pdf->Ln(15);

// ===== Other Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Other Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(25, 10, 'Date', 1);
$pdf->Cell(30, 10, 'Amount', 1);
$pdf->Cell(45, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$expenseRows = select("
    SELECT Name, Description, Date, TotalAmount, PaymentMode
    FROM Expenses
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?", [$month, $year]
);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $description = substr($row['Description'], 0, 30);
        if (strlen($row['Description']) > 30) {
            $description .= '...';
        }
        $pdf->Cell(40, 10, substr($row['Name'], 0, 15) . (strlen($row['Name']) > 15 ? '...' : ''), 1);
        $pdf->Cell(50, 10, $description, 1);
        $pdf->Cell(25, 10, $row['Date'], 1);
        $pdf->Cell(30, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Cell(45, 10, substr($row['PaymentMode'], 0, 20) . (strlen($row['PaymentMode']) > 20 ? '...' : ''), 1);
        $pdf->Ln();
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(190, 10, 'No expense data available for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(115, 10, 'Total Other Expenses', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Cell(45, 10, '', 1);
$pdf->Ln(15);

// ===== Grand Total =====
$grandTotal = $totalSalaries + $totalExpenses;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(115, 10, 'Grand Total Expenses', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($grandTotal, 2), 1);
$pdf->Cell(45, 10, '', 1);

$pdf->Output();
?>
