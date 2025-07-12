<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get date parameter (default to current date)
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Daily Profit & Loss Report - ' . date('d M Y', strtotime($date)), 0, 1, 'C');
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
$salaryRows = select("SELECT Name, GivenSalary, SalaryPaidDate, Mobile 
                     FROM Employee 
                     WHERE SalaryPaidDate = ?", 
                     [$date]);
$totalSalaries = 0;
foreach ($salaryRows as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(30, 10, $row['GivenSalary'], 1);
    $pdf->Cell(40, 10, $row['SalaryPaidDate'], 1);
    $pdf->Cell(60, 10, $row['Mobile'], 1);
    $pdf->Ln();
    $totalSalaries += $row['GivenSalary'];
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
                      WHERE Date = ?", 
                      [$date]);
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

// ===== Sales (Membership + Clients) =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sales (Membership + Clients)', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Customer Name', 1);
$pdf->Cell(40, 10, 'Amount Paid', 1);
$pdf->Cell(50, 10, 'Start/Date', 1);
$pdf->Cell(40, 10, 'Type', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$totalSales = 0;

$membershipSales = select("SELECT Name, TotalAmount, StartDate 
                           FROM Membership 
                           WHERE StartDate = ? AND IsDelete = 1", 
                           [$date]);
foreach ($membershipSales as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, $row['TotalAmount'], 1);
    $pdf->Cell(50, 10, $row['StartDate'], 1);
    $pdf->Cell(40, 10, 'Membership', 1);
    $pdf->Ln();
    $totalSales += $row['TotalAmount'];
}

$clientSales = select("SELECT Name, Price, Date 
                      FROM Clients 
                      WHERE Date = ?", 
                      [$date]);
foreach ($clientSales as $row) {
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, $row['Price'], 1);
    $pdf->Cell(50, 10, $row['Date'], 1);
    $pdf->Cell(40, 10, 'Client', 1);
    $pdf->Ln();
    $totalSales += $row['Price'];
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Total Sales', 1);
$pdf->Cell(40, 10, 'Rs ' . number_format($totalSales, 2), 1);
$pdf->Cell(90, 10, '', 1);
$pdf->Ln(15);

// ===== Profit / Loss =====
$totalExpenseOverall = $totalSalaries + $totalExpenses;
$profit = $totalSales - $totalExpenseOverall;

$pdf->SetFont('Arial', 'B', 14);
$color = ($profit >= 0) ? [0, 153, 0] : [255, 0, 0];
$pdf->SetTextColor($color[0], $color[1], $color[2]);
$pdf->Cell(60, 10, ($profit >= 0 ? 'Net Profit:' : 'Net Loss:'), 1);
$pdf->Cell(40, 10, 'Rs ' . number_format(abs($profit), 2), 1);
$pdf->Cell(90, 10, '', 1);

// Reset text color
$pdf->SetTextColor(0, 0, 0);

$pdf->Output();
?>
