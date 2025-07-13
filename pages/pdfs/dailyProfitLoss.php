<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Get date parameter (default to current date)
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Custom PDF class for responsive tables
class ResponsivePDF extends FPDF {

    function CheckPageBreak($height) {
        if ($this->GetY() + $height > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    function ResponsiveRow($data, $widths, $height = 8, $align = 'L') {
        $this->CheckPageBreak($height);

        foreach ($data as $i => $text) {
            // Truncate text if it’s too long
            if (strlen($text) > 30) {
                $text = substr($text, 0, 27) . '...';
            }
            $textWidth = $this->GetStringWidth($text);
            if ($textWidth > $widths[$i] - 2) {
                $this->SetFont('Arial', '', 9); // Reduce font size for long text
                break;
            }
        }

        $x = $this->GetX();
        $y = $this->GetY();

        foreach ($data as $i => $text) {
            $this->SetXY($x, $y);
            $this->MultiCell($widths[$i], $height, $text, 1, is_array($align) ? $align[$i] : $align);
            $x += $widths[$i];
            $this->SetXY($x, $y);
        }
        $this->Ln($height);
    }

    function TableHeader($headers, $widths, $height = 10) {
        $this->SetFont('Arial', 'B', 11);
        $this->ResponsiveRow($headers, $widths, $height, 'C');
        $this->SetFont('Arial', '', 10);
    }
}

$pdf = new ResponsivePDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Blissful Diivine Spa', 0, 1, 'C');
$pdf->Cell(0, 10, 'Daily Profit & Loss Report - ' . date('d M Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(8);

// ===== Employee Salaries =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Employee Salaries', 0, 1);

$salaryWidths = [50, 30, 30, 30, 40];
$salaryHeaders = ['Employee Name', 'Salary Paid', 'Date', 'Mode', 'Mobile'];
$pdf->TableHeader($salaryHeaders, $salaryWidths);

$totalSalaries = 0;
$salaryRows = select("SELECT Name, GivenSalary, SalaryPaidDate, PaymentMode, Mobile 
                     FROM Employee 
                     WHERE SalaryPaidDate = ?", [$date]);

foreach ($salaryRows as $row) {
    $data = [
        substr($row['Name'], 0, 20),
        'Rs ' . number_format($row['GivenSalary'], 2),
        $row['SalaryPaidDate'],
        $row['PaymentMode'],
        $row['Mobile']
    ];
    $pdf->ResponsiveRow($data, $salaryWidths, 7, ['L', 'R', 'C', 'C', 'C']);
    $totalSalaries += $row['GivenSalary'];
}

// Total Salaries
$pdf->SetFont('Arial', 'B', 11);
$totalRow = ['Total Salaries', 'Rs ' . number_format($totalSalaries, 2), '', '', ''];
$pdf->ResponsiveRow($totalRow, $salaryWidths, 8, ['L', 'R', 'C', 'C', 'C']);
$pdf->Ln(5);

// ===== Expenses =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Expenses', 0, 1);

$expenseWidths = [40, 35, 30, 20, 30, 30];
$expenseHeaders = ['Name', 'Description', 'Date', 'Qty', 'Amount', 'Mode'];
$pdf->TableHeader($expenseHeaders, $expenseWidths);

$totalExpenses = 0;
$expenseRows = select("SELECT Name, Description, Date, Quantity, TotalAmount, PaymentMode 
                      FROM Expenses 
                      WHERE Date = ?", [$date]);

foreach ($expenseRows as $row) {
    $data = [
        substr($row['Name'], 0, 15),
        substr($row['Description'], 0, 20) . (strlen($row['Description']) > 20 ? '...' : ''),
        $row['Date'],
        $row['Quantity'],
        'Rs ' . number_format($row['TotalAmount'], 2),
        $row['PaymentMode']
    ];
    $pdf->ResponsiveRow($data, $expenseWidths, 7, ['L', 'L', 'C', 'C', 'R', 'C']);
    $totalExpenses += $row['TotalAmount'];
}

// Total Expenses
$pdf->SetFont('Arial', 'B', 11);
$totalRow = ['Total Expenses', '', '', '', 'Rs ' . number_format($totalExpenses, 2), ''];
$pdf->ResponsiveRow($totalRow, $expenseWidths, 8, ['L', 'L', 'C', 'C', 'R', 'C']);
$pdf->Ln(5);

// ===== Sales =====
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Sales (Membership + Clients)', 0, 1);

$salesWidths = [50, 30, 30, 30, 30];
$salesHeaders = ['Customer Name', 'Amount', 'Date', 'Type', 'Mode'];
$pdf->TableHeader($salesHeaders, $salesWidths);

$totalSales = 0;

// Membership Sales
$membershipSales = select("
    SELECT m.Name, COALESCE(SUM(a.Amount), 0) AS TotalAmount, m.StartDate, m.PaymentMode
    FROM Membership m
    LEFT JOIN Appointments a ON m.Id = a.MemberId
        AND DATE(a.AppointmentDate) = ?
        AND a.IsDelete = 1
    WHERE m.IsDelete = 1
    GROUP BY m.Id, m.Name, m.StartDate, m.PaymentMode
", [$date]);

foreach ($membershipSales as $row) {
    if ($row['TotalAmount'] > 0) {
        $data = [
            substr($row['Name'], 0, 20),
            'Rs ' . number_format($row['TotalAmount'], 2),
            $row['StartDate'],
            'Membership',
            $row['PaymentMode']
        ];
        $pdf->ResponsiveRow($data, $salesWidths, 7, ['L', 'R', 'C', 'C', 'C']);
        $totalSales += $row['TotalAmount'];
    }
}

// Client Sales
$clientSales = select("SELECT Name, Price, Date, PaymentMode
                      FROM Clients 
                      WHERE Date = ?", [$date]);

foreach ($clientSales as $row) {
    $data = [
        substr($row['Name'], 0, 20),
        'Rs ' . number_format($row['Price'], 2),
        $row['Date'],
        'Client',
        $row['PaymentMode']
    ];
    $pdf->ResponsiveRow($data, $salesWidths, 7, ['L', 'R', 'C', 'C', 'C']);
    $totalSales += $row['Price'];
}

// Total Sales
$pdf->SetFont('Arial', 'B', 11);
$totalRow = ['Total Sales', 'Rs ' . number_format($totalSales, 2), '', '', ''];
$pdf->ResponsiveRow($totalRow, $salesWidths, 8, ['L', 'R', 'C', 'C', 'C']);
$pdf->Ln(5);

// ===== Profit / Loss =====
$totalExpenseOverall = $totalSalaries + $totalExpenses;
$profit = $totalSales - $totalExpenseOverall;

$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(($profit >= 0) ? 0 : 255, ($profit >= 0) ? 153 : 0, 0);
$pdf->Cell(60, 10, ($profit >= 0 ? 'Net Profit:' : 'Net Loss:'), 1);
$pdf->Cell(40, 10, 'Rs ' . number_format(abs($profit), 2), 1, 0, 'R');
$pdf->Cell(90, 10, '', 1);
$pdf->SetTextColor(0, 0, 0);

$pdf->Output();
?>
