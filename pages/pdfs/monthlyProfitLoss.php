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
$pdf->Cell(0, 10, "Monthly Profit & Loss Report - " . date('F Y', mktime(0, 0, 0, $month, 1, $year)), 0, 1, 'C');
$pdf->Ln(10);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Employee Salaries', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(120, 10, 'Employee Name', 1);
$pdf->Cell(60, 10, 'Given Salary', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$salaryRows = select("
    SELECT Name, GivenSalary
    FROM Employee
    WHERE GivenSalary IS NOT NULL AND GivenSalary > 0
");

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(120, 10, $row['Name'], 1);
        $pdf->Cell(60, 10, 'Rs ' . number_format($row['GivenSalary'], 2), 1);
        $pdf->Ln();
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(180, 10, 'No salary data for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(120, 10, 'Total Salaries', 1);
$pdf->Cell(60, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Ln(15);

// ===== Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Other Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Name', 1);
$pdf->Cell(60, 10, 'Description', 1);
$pdf->Cell(40, 10, 'Amount', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$expenseRows = select("
    SELECT Name, Description, TotalAmount
    FROM Expenses
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?", 
    [$month, $year]
);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(80, 10, $row['Name'], 1);
        $pdf->Cell(60, 10, $row['Description'], 1);
        $pdf->Cell(40, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Ln();
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(180, 10, 'No expense data for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, 'Total Expenses', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Ln(15);

// ===== Sales (Membership + Clients) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sales (Membership + Clients)', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Customer Name', 1);
$pdf->Cell(40, 10, 'Amount Paid', 1);
$pdf->Cell(40, 10, 'Type', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$totalSales = 0;

// Membership sales
$membershipSales = select("
    SELECT Name, TotalAmount
    FROM Membership
    WHERE MONTH(StartDate) = ? AND YEAR(StartDate) = ? AND IsDelete = 1",
    [$month, $year]
);
foreach ($membershipSales as $row) {
    $pdf->Cell(100, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
    $pdf->Cell(40, 10, 'Membership', 1);
    $pdf->Ln();
    $totalSales += $row['TotalAmount'];
}

// Client sales
$clientSales = select("
    SELECT Name, Price
    FROM Clients
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?",
    [$month, $year]
);
foreach ($clientSales as $row) {
    $pdf->Cell(100, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, 'Rs ' . number_format($row['Price'], 2), 1);
    $pdf->Cell(40, 10, 'Client', 1);
    $pdf->Ln();
    $totalSales += $row['Price'];
}

if ($totalSales === 0) {
    $pdf->Cell(180, 10, 'No sales data for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Total Sales', 1);
$pdf->Cell(80, 10, 'Rs ' . number_format($totalSales, 2), 1);
$pdf->Ln(15);

// ===== Profit / Loss =====
$totalExpenseOverall = $totalSalaries + $totalExpenses;
$profit = $totalSales - $totalExpenseOverall;

$pdf->SetFont('Arial', 'B', 14);
$color = ($profit >= 0) ? [0, 153, 0] : [255, 0, 0];
$pdf->SetTextColor($color[0], $color[1], $color[2]);
$pdf->Cell(100, 10, ($profit >= 0 ? 'Net Profit:' : 'Net Loss:'), 1);
$pdf->Cell(80, 10, 'Rs ' . number_format(abs($profit), 2), 1);

// Reset text color
$pdf->SetTextColor(0, 0, 0);
$pdf->Output();
?>
