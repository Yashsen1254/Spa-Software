<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

class PDF extends FPDF
{
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Monthly Profit & Loss Report',0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$month = date('m');
$year = date('Y');

// Expenses
$expenseData = select("SELECT Name, TotalAmount FROM Expenses WHERE MONTH(Date) = ? AND YEAR(Date) = ?", [$month, $year]);
$totalExpense = 0;

// Sales
$salesData = select("SELECT Name, AmountPaid FROM Clients WHERE MONTH(StartDate) = ? AND YEAR(StartDate) = ? AND IsDelete = 1", [$month, $year]);
$totalSales = 0;

// Table Setup
$leftX = 10;
$rightX = 110;
$colWidth = 90;

$pdf->SetFont('Arial','B',12);
$pdf->SetXY($leftX, $pdf->GetY());
$pdf->Cell($colWidth,10,'Expenses',1,1,'C');

$pdf->SetXY($rightX, $pdf->GetY() - 10);
$pdf->Cell($colWidth,10,'Sales',1,1,'C');

$pdf->SetFont('Arial','',12);
$maxRows = max(count($expenseData), count($salesData));
$y = $pdf->GetY();

for ($i = 0; $i < $maxRows; $i++) {
    $pdf->SetXY($leftX, $y);
    if (isset($expenseData[$i])) {
        $pdf->Cell(60,8,$expenseData[$i]['Name'],1);
        $pdf->Cell(30,8,number_format($expenseData[$i]['TotalAmount'], 2),1,0,'R');
        $totalExpense += $expenseData[$i]['TotalAmount'];
    } else {
        $pdf->Cell(60,8,'',1);
        $pdf->Cell(30,8,'',1);
    }

    $pdf->SetXY($rightX, $y);
    if (isset($salesData[$i])) {
        $pdf->Cell(60,8,$salesData[$i]['Name'],1);
        $pdf->Cell(30,8,number_format($salesData[$i]['AmountPaid'], 2),1,0,'R');
        $totalSales += $salesData[$i]['AmountPaid'];
    } else {
        $pdf->Cell(60,8,'',1);
        $pdf->Cell(30,8,'',1);
    }

    $y += 8;
}

$pdf->SetXY($leftX, $y);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,8,'Total',1);
$pdf->Cell(30,8,number_format($totalExpense, 2),1,0,'R');

$pdf->SetXY($rightX, $y);
$pdf->Cell(60,8,'Total',1);
$pdf->Cell(30,8,number_format($totalSales, 2),1,0,'R');

// Profit or Loss
$y += 15;
$pdf->SetY($y);
$profit = $totalSales - $totalExpense;
$color = ($profit >= 0) ? [0, 153, 0] : [255, 0, 0];
$pdf->SetTextColor(...$color);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, ($profit >= 0 ? 'Profit: Rs ' : 'Loss: Rs ') . number_format(abs($profit), 2), 0, 1, 'C');
$pdf->SetTextColor(0,0,0);

$pdf->Output();
