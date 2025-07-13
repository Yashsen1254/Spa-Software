<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, "Yearly Profit & Loss Report - $year", 0, 1, 'C');
$pdf->Ln(10);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Employee Salaries', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Employee Name', 1);
$pdf->Cell(35, 10, 'Given Salary', 1);
$pdf->Cell(40, 10, 'Payment Date', 1);
$pdf->Cell(55, 10, 'Mobile', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$salaryRows = select("
    SELECT Name, GivenSalary, SalaryPaidDate, Mobile
    FROM Employee
    WHERE YEAR(SalaryPaidDate) = ?", [$year]);

$totalSalaries = 0;
if (!empty($salaryRows)) {
    foreach ($salaryRows as $row) {
        $pdf->Cell(60, 10, $row['Name'], 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['GivenSalary'], 2), 1);
        $pdf->Cell(40, 10, $row['SalaryPaidDate'] ?? '-', 1);
        $pdf->Cell(55, 10, $row['Mobile'] ?? '-', 1);
        $pdf->Ln();
        $totalSalaries += $row['GivenSalary'];
    }
} else {
    $pdf->Cell(190, 10, 'No salary payments for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Total Salaries', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalSalaries, 2), 1);
$pdf->Cell(95, 10, '', 1);
$pdf->Ln(12);

// ===== Other Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Other Expenses', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Date', 1);
$pdf->Cell(25, 10, 'Qty', 1);
$pdf->Cell(40, 10, 'Total Amount', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$expenseRows = select("
    SELECT Name, Description, Date, Quantity, TotalAmount
    FROM Expenses
    WHERE YEAR(Date) = ?", [$year]);

$totalExpenses = 0;
if (!empty($expenseRows)) {
    foreach ($expenseRows as $row) {
        $pdf->Cell(40, 10, $row['Name'], 1);
        $pdf->Cell(50, 10, $row['Description'], 1);
        $pdf->Cell(30, 10, $row['Date'] ?? '-', 1);
        $pdf->Cell(25, 10, $row['Quantity'] ?? '-', 1);
        $pdf->Cell(40, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
        $pdf->Ln();
        $totalExpenses += $row['TotalAmount'];
    }
} else {
    $pdf->Cell(185, 10, 'No other expenses for this year.', 1, 1, 'C');
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(145, 10, 'Total Other Expenses', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalExpenses, 2), 1);
$pdf->Ln(12);

// ===== Sales (Membership + Clients) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sales (Membership + Clients)', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Customer Name', 1);
$pdf->Cell(35, 10, 'Amount Paid', 1);
$pdf->Cell(45, 10, 'Date', 1);
$pdf->Cell(50, 10, 'Type', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$totalSales = 0;

// Membership Sales (using Appointments.Amount)
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
        if ($row['TotalAmount'] > 0) { // Show only if sales exist
            $pdf->Cell(60, 10, $row['Name'], 1);
            $pdf->Cell(35, 10, 'Rs ' . number_format($row['TotalAmount'], 2), 1);
            $pdf->Cell(45, 10, $row['FirstDate'] ?? '-', 1);
            $pdf->Cell(50, 10, 'Membership', 1);
            $pdf->Ln();
            $totalSales += $row['TotalAmount'];
        }
    }
}

// Client Sales
$clientSales = select("
    SELECT Name, Price, Date
    FROM Clients
    WHERE YEAR(Date) = ?", [$year]);

if (!empty($clientSales)) {
    foreach ($clientSales as $row) {
        $pdf->Cell(60, 10, $row['Name'], 1);
        $pdf->Cell(35, 10, 'Rs ' . number_format($row['Price'], 2), 1);
        $pdf->Cell(45, 10, $row['Date'] ?? '-', 1);
        $pdf->Cell(50, 10, 'Client', 1);
        $pdf->Ln();
        $totalSales += $row['Price'];
    }
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Total Sales', 1);
$pdf->Cell(35, 10, 'Rs ' . number_format($totalSales, 2), 1);
$pdf->Cell(95, 10, '', 1);
$pdf->Ln(12);

// ===== Profit / Loss =====
$totalExpenseOverall = $totalSalaries + $totalExpenses;
$profit = $totalSales - $totalExpenseOverall;

$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(($profit >= 0) ? 0 : 255, ($profit >= 0) ? 153 : 0, ($profit >= 0) ? 0 : 0);
$pdf->Cell(60, 10, ($profit >= 0 ? 'Net Profit:' : 'Net Loss:'), 1);
$pdf->Cell(35, 10, 'Rs ' . number_format(abs($profit), 2), 1);
$pdf->Cell(95, 10, '', 1);
$pdf->SetTextColor(0, 0, 0); // Reset color

$pdf->Output();
?>
