<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Daily Profit & Loss Report',0,1,'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Get today's date
$today = date('Y-m-d');

// --- Get Expenses ---
$expenseData = select("SELECT Name, TotalAmount FROM Expenses WHERE Date = ?", [$today]);
$totalExpense = 0;

// --- Get Sales ---
$salesData = select("SELECT Name, AmountPaid FROM Clients WHERE StartDate = ? AND IsDelete = 1", [$today]);
$totalSales = 0;

// Column widths
$leftX = 10;
$rightX = 110;
$colWidth = 90;

// Table Headers
$pdf->SetFont('Arial','B',12);
$pdf->SetXY($leftX, $pdf->GetY());
$pdf->Cell($colWidth,10,'Expenses',1,1,'C');

$pdf->SetXY($rightX, $pdf->GetY() - 10); // same Y
$pdf->Cell($colWidth,10,'Sales',1,1,'C');

// Table Body
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

// Total Row
$pdf->SetXY($leftX, $y);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,8,'Total',1);
$pdf->Cell(30,8,number_format($totalExpense, 2),1,0,'R');

$pdf->SetXY($rightX, $y);
$pdf->Cell(60,8,'Total',1);
$pdf->Cell(30,8,number_format($totalSales, 2),1,0,'R');

// Profit/Loss Section
$y += 15;
$pdf->SetY($y);
$profit = $totalSales - $totalExpense;
$color = ($profit >= 0) ? [0, 153, 0] : [255, 0, 0];
$pdf->SetTextColor($color[0], $color[1], $color[2]);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, ($profit >= 0 ? 'Profit: Rs ' : 'Loss: Rs ') . number_format(abs($profit), 2), 0, 1, 'C');

// Reset text color
$pdf->SetTextColor(0,0,0);

$pdf->Output();
?>
