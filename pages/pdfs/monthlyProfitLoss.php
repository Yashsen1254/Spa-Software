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
$pdf->Cell(90, 10, 'Employee Name', 1);
$pdf->Cell(50, 10, 'Given Salary', 1);
$pdf->Cell(40, 10, 'Payment Mode', 1);
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
        $pdf->Cell(90, 10, substr($row['Name'], 0, 30) . (strlen($row['Name']) > 30 ? '...' : ''), 1);
        $pdf->Cell(50, 10, 'Rs ' . number_format($row['GivenSalary'], 2), 1);
        $pdf->Cell(40, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 15), 1);
        $pdf->Ln();
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(180, 10, 'No salary data for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Total Salaries', 1);
$pdf->Cell(50, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Cell(40, 10, '', 1);
$pdf->Ln(15);

// ===== Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Other Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Amount', 1);
$pdf->Cell(40, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$expenseRows = select("
    SELECT Name, Description, TotalAmount, PaymentMode
    FROM Expenses
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?", 
    [$month, $year]
);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(60, 10, substr($row['Name'], 0, 20) . (strlen($row['Name']) > 20 ? '...' : ''), 1);
        $pdf->Cell(50, 10, substr($row['Description'], 0, 25) . (strlen($row['Description']) > 25 ? '...' : ''), 1);
        $pdf->Cell(30, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Cell(40, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 15), 1);
        $pdf->Ln();
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(180, 10, 'No expense data for this month.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total Expenses', 1);
$pdf->Cell(30, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Cell(40, 10, '', 1);
$pdf->Ln(15);

// ===== Sales (Membership + Clients) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sales (Membership + Clients)', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 10, 'Customer Name', 1);
$pdf->Cell(30, 10, 'Amount Paid', 1);
$pdf->Cell(40, 10, 'Type', 1);
$pdf->Cell(40, 10, 'Payment Mode', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$totalSales = 0;

// Membership sales
$membershipSales = select("
    SELECT m.Name, COALESCE(SUM(a.Amount), 0) AS TotalAmount, m.PaymentMode
    FROM Membership m
    LEFT JOIN Appointments a ON m.Id = a.MemberId
        AND MONTH(a.AppointmentDate) = ? 
        AND YEAR(a.AppointmentDate) = ?
        AND a.IsDelete = 1
    WHERE m.IsDelete = 1
    GROUP BY m.Id
", [$month, $year]);

foreach ($membershipSales as $row) {
    if ($row['TotalAmount'] > 0) {
        $pdf->Cell(70, 10, substr($row['Name'], 0, 25) . (strlen($row['Name']) > 25 ? '...' : ''), 1);
        $pdf->Cell(30, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Cell(40, 10, 'Membership', 1);
        $pdf->Cell(40, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 15), 1);
        $pdf->Ln();
        $totalSales += $row['TotalAmount'];
    }
}

// Client sales
$clientSales = select("
    SELECT Name, Price, PaymentMode
    FROM Clients
    WHERE MONTH(Date) = ? AND YEAR(Date) = ?",
    [$month, $year]
);

foreach ($clientSales as $row) {
    $pdf->Cell(70, 10, substr($row['Name'], 0, 25) . (strlen($row['Name']) > 25 ? '...' : ''), 1);
    $pdf->Cell(30, 10, 'Rs ' . number_format($row['Price'], 2), 1);
    $pdf->Cell(40, 10, 'Client', 1);
    $pdf->Cell(40, 10, substr($row['PaymentMode'] ?? 'N/A', 0, 15), 1);
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
