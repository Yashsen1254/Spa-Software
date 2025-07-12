<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

$employeeId = $_POST['Id'] ?? null;

if (!$employeeId || !is_numeric($employeeId)) {
    die("Invalid employee ID.");
}

$employeeId = (int)$employeeId;

// ✅ Fetch employee details
$employee = selectOne("SELECT * FROM Employee WHERE Id = ?", [$employeeId]);

if (!$employee) {
    die("Employee not found.");
}

// ✅ Fetch Appointments with Member Name
$appointments = select("
    SELECT 
        'Appointment' AS WorkType,
        AppointmentDate AS WorkDate,
        InTime,
        OutTime,
        RoomNo,
        a.Massage AS WorkDescription,
        m.Name AS ClientName
    FROM Appointments a
    LEFT JOIN Membership m ON a.MemberId = m.Id
    WHERE a.EmployeeId = ? AND a.IsDelete = 1
", [$employeeId]);

// ✅ Fetch Clients with Name
$clients = select("
    SELECT 
        'Client' AS WorkType,
        Date AS WorkDate,
        InTime,
        OutTime,
        c.Massage AS WorkDescription,
        c.Name AS ClientName
    FROM Clients c
    WHERE c.EmployeeId = ?
", [$employeeId]);

// ✅ Merge both and sort by date
$allWork = array_merge($appointments, $clients);

// Sort by WorkDate descending
usort($allWork, function ($a, $b) {
    return strtotime($b['WorkDate']) - strtotime($a['WorkDate']);
});

// ✅ Start PDF
$pdf = new FPDF();
$pdf->AddPage();

// ✅ Employee Info
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Employee Work Report', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, 'Name:');
$pdf->Cell(0, 8, $employee['Name'], 0, 1);
$pdf->Cell(40, 8, 'Mobile:');
$pdf->Cell(0, 8, $employee['Mobile'], 0, 1);
$pdf->Cell(40, 8, 'Email:');
$pdf->Cell(0, 8, $employee['Email'], 0, 1);
$pdf->Ln(10);

// ✅ Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 8, 'S.No', 1);
$pdf->Cell(30, 8, 'Date', 1);
$pdf->Cell(40, 8, 'Client/Member', 1);
$pdf->Cell(20, 8, 'In Time', 1);
$pdf->Cell(20, 8, 'Out Time', 1);
$pdf->Cell(20, 8, 'Room No', 1);
$pdf->Cell(50, 8, 'Massage', 1);
$pdf->Ln();

// ✅ Table Rows
$pdf->SetFont('Arial', '', 10);
$index = 1;
foreach ($allWork as $work) {
    $pdf->Cell(10, 8, $index++, 1);
    $pdf->Cell(30, 8, date('d-m-Y', strtotime($work['WorkDate'])), 1);
    $pdf->Cell(40, 8, $work['ClientName'] ?? '-', 1);
    $pdf->Cell(20, 8, $work['InTime'], 1);
    $pdf->Cell(20, 8, $work['OutTime'], 1);
    $pdf->Cell(20, 8, $work['RoomNo'] ?? '-', 1);
    $pdf->Cell(50, 8, $work['WorkDescription'] ?? '-', 1);
    $pdf->Ln();
}

// ✅ Output PDF
$pdfFileName = 'Employee_Work_' . $employee['Name'] . '.pdf';
$pdf->Output('I', $pdfFileName); // Inline view
// $pdf->Output('D', $pdfFileName); // Uncomment to force download
?>
