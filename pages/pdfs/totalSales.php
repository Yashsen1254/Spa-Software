<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php'; // ✅ Correct path now

// Fetch sales data
$dailySales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Clients WHERE StartDate = CURDATE() AND IsDelete = 1")['Total'] ?? 0;
$monthlySales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Clients WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['Total'] ?? 0;
$yearlySales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Clients WHERE YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['Total'] ?? 0;

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Total Sales Report', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Period', 1, 0, 'C');
$pdf->Cell(60, 10, 'Sales Amount', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 10, 'Daily Sales', 1);
$pdf->Cell(60, 10, 'Rs ' . number_format($dailySales, 2), 1, 1);

$pdf->Cell(60, 10, 'Monthly Sales', 1);
$pdf->Cell(60, 10, 'Rs ' . number_format($monthlySales, 2), 1, 1);

$pdf->Cell(60, 10, 'Yearly Sales', 1);
$pdf->Cell(60, 10, 'Rs ' . number_format($yearlySales, 2), 1, 1);

$pdf->Output('I', 'total_sales_report.pdf');
