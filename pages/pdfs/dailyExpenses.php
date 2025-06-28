<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Daily Expenses Report (Including Salaries)', 0, 1, 'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Date', 1);
$pdf->Cell(60, 10, 'Name', 1);
$pdf->Cell(40, 10, 'Amount (Rs.)', 1);
$pdf->Ln();

$today = date('Y-m-d');
$totalExpenses = 0;

// Expenses for today
$expenses = select("SELECT Date, Name, TotalAmount FROM Expenses WHERE Date = ?", [$today]);

$pdf->SetFont('Arial', '', 12);
foreach ($expenses as $row) {
    $pdf->Cell(50, 10, $row['Date'], 1);
    $pdf->Cell(60, 10, $row['Name'], 1);
    $pdf->Cell(40, 10, number_format($row['TotalAmount'], 2), 1);
    $pdf->Ln();
    $totalExpenses += $row['TotalAmount'];
}

// Salaries paid today
$salaries = select("SELECT SalaryPaidDate AS Date, Name, SalaryPaid FROM Employee WHERE SalaryPaidDate = ?", [$today]);

foreach ($salaries as $row) {
    $pdf->Cell(50, 10, $row['Date'], 1);
    $pdf->Cell(60, 10, 'Salary - ' . $row['Name'], 1);
    $pdf->Cell(40, 10, number_format($row['SalaryPaid'], 2), 1);
    $pdf->Ln();
    $totalExpenses += $row['SalaryPaid'];
}

// Total row
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total Daily Expenses (with Salaries)', 1);
$pdf->Cell(40, 10, 'Rs. ' . number_format($totalExpenses, 2), 1);

$pdf->Output('I', 'DailyExpenses.pdf');
?>
