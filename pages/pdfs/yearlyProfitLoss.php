<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, "Yearly Profit & Loss Report - $year", 0, 1, 'C');
$pdf->Ln(8);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Employee Salaries', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'Employee Name', 1, 0, 'C');
$pdf->Cell(30, 8, 'Given Salary', 1, 0, 'C');
$pdf->Cell(30, 8, 'Payment Date', 1, 0, 'C');
$pdf->Cell(40, 8, 'Mobile', 1, 0, 'C');
$pdf->Cell(40, 8, 'Payment Mode', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$salaryRows = select("
    SELECT Name, GivenSalary, SalaryPaidDate, Mobile, PaymentMode
    FROM Employee
    WHERE YEAR(SalaryPaidDate) = ?", [$year]);

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(50, 7, $row['Name'], 1);
        $pdf->Cell(30, 7, 'Rs ' . number_format($row['GivenSalary'], 2), 1, 0, 'R');
        $pdf->Cell(30, 7, $row['SalaryPaidDate'] ?? '-', 1);
        $pdf->Cell(40, 7, $row['Mobile'] ?? '-', 1);
        $pdf->Cell(40, 7, $row['PaymentMode'] ?? '-', 1, 1);
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(190, 7, 'No salary payments for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(150, 8, 'Total Salaries', 1, 0, 'R');
$pdf->Cell(40, 8, 'Rs ' . number_format($totalSalaries, 2), 1, 1, 'R');
$pdf->Ln(8);

// ===== Other Expenses =====
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Other Expenses', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, 'Name', 1, 0, 'C');
$pdf->Cell(45, 8, 'Description', 1, 0, 'C');
$pdf->Cell(25, 8, 'Date', 1, 0, 'C');
$pdf->Cell(15, 8, 'Qty', 1, 0, 'C');
$pdf->Cell(35, 8, 'Total Amount', 1, 0, 'C');
$pdf->Cell(35, 8, 'Payment Mode', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$expenseRows = select("
    SELECT Name, Description, Date, Quantity, TotalAmount, PaymentMode
    FROM Expenses
    WHERE YEAR(Date) = ?", [$year]);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(35, 7, $row['Name'], 1);
        $pdf->Cell(45, 7, $row['Description'], 1);
        $pdf->Cell(25, 7, $row['Date'] ?? '-', 1);
        $pdf->Cell(15, 7, $row['Quantity'] ?? '-', 1, 0, 'C');
        $pdf->Cell(35, 7, 'Rs ' . number_format($row['TotalAmount'], 2), 1, 0, 'R');
        $pdf->Cell(35, 7, $row['PaymentMode'] ?? '-', 1, 1);
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(190, 7, 'No other expenses for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(155, 8, 'Total Other Expenses', 1, 0, 'R');
$pdf->Cell(35, 8, 'Rs ' . number_format($totalExpenses, 2), 1, 1, 'R');
$pdf->Ln(8);

// ===== Sales (Membership + Clients) =====
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, 'Sales (Membership + Clients)', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'Customer Name', 1, 0, 'C');
$pdf->Cell(30, 8, 'Amount Paid', 1, 0, 'C');
$pdf->Cell(35, 8, 'Date', 1, 0, 'C');
$pdf->Cell(35, 8, 'Type', 1, 0, 'C');
$pdf->Cell(40, 8, 'Payment Mode', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$totalSales = 0;

// Membership Sales (no PaymentMode here)
$membershipSales = select("
    SELECT m.Name, COALESCE(SUM(a.Amount), 0) AS TotalAmount, MIN(a.AppointmentDate) AS FirstDate
    FROM Membership m
    LEFT JOIN Appointments a ON m.Id = a.MemberId
        AND YEAR(a.AppointmentDate) = ?
        AND a.IsDelete = 1
    WHERE m.IsDelete = 1
    GROUP BY m.Id
", [$year]);

if (!empty($membershipSales)) {
    foreach ($membershipSales as $row) {
        if ($row['TotalAmount'] > 0) {
            $pdf->Cell(50, 7, $row['Name'], 1);
            $pdf->Cell(30, 7, 'Rs ' . number_format($row['TotalAmount'], 2), 1, 0, 'R');
            $pdf->Cell(35, 7, $row['FirstDate'] ?? '-', 1);
            $pdf->Cell(35, 7, 'Membership', 1);
            $pdf->Cell(40, 7, '-', 1, 1); // No PaymentMode here
            $totalSales += $row['TotalAmount'];
        }
    }
}

// Client Sales (PaymentMode exists)
$clientSales = select("
    SELECT Name, Price, Date, PaymentMode
    FROM Clients
    WHERE YEAR(Date) = ?", [$year]);

if (!empty($clientSales)) {
    foreach ($clientSales as $row) {
        $pdf->Cell(50, 7, $row['Name'], 1);
        $pdf->Cell(30, 7, 'Rs ' . number_format($row['Price'], 2), 1, 0, 'R');
        $pdf->Cell(35, 7, $row['Date'] ?? '-', 1);
        $pdf->Cell(35, 7, 'Client', 1);
        $pdf->Cell(40, 7, $row['PaymentMode'] ?? '-', 1, 1);
        $totalSales += $row['Price'];
    }
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(150, 8, 'Total Sales', 1, 0, 'R');
$pdf->Cell(40, 8, 'Rs ' . number_format($totalSales, 2), 1, 1, 'R');
$pdf->Ln(8);

// ===== Profit / Loss =====
$totalExpenseOverall = $totalSalaries + $totalExpenses;
$profit = $totalSales - $totalExpenseOverall;

$pdf->SetFont('Arial', 'B', 13);
$color = ($profit >= 0) ? [0, 153, 0] : [255, 0, 0];
$pdf->SetTextColor($color[0], $color[1], $color[2]);
$pdf->Cell(150, 10, ($profit >= 0 ? 'Net Profit:' : 'Net Loss:'), 1, 0, 'R');
$pdf->Cell(40, 10, 'Rs ' . number_format(abs($profit), 2), 1, 1, 'R');
$pdf->SetTextColor(0, 0, 0);

$pdf->Output();
?>
