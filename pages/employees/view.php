<?php
require '../../includes/init.php';
require '../../includes/lib/fpdf.php';

// Fetch employee data by ID
$employeeId = $_POST['Id'] ?? 0;
$employee = selectOne("SELECT * FROM Employee WHERE Id = ?", [$employeeId]);

if (!$employee) {
    die("Employee not found.");
}

// Add TotalSalary to employee array manually (if it's not already in DB)
$employee['Salary'] = ($employee['SalaryPaid'] ?? 0) + ($employee['SalaryDue'] ?? 0);

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Employee Profile', 0, 1, 'C');
        $this->Ln(3);
    }

    function EmployeeDetailsTable($employee) {
        $this->SetFont('Arial', '', 10);
        $this->SetXY(10, 30);

        $labelWidth = 50;
        $valueWidth = 65;
        $lineHeight = 7;

        foreach ($employee as $key => $value) {
            // Skip image fields and salary fields
            if (in_array($key, ['ImageFileName', 'AddharCardImageFileName', 'SalaryPaidDate', 'SalaryDue', 'SalaryPaid'])) continue;

            $label = ucwords(str_replace(['Id', 'FileName'], ['ID', ''], preg_replace('/(?<!^)[A-Z]/', ' $0', $key)));

            $this->Cell($labelWidth, $lineHeight, "$label", 1);
            $this->Cell($valueWidth, $lineHeight, iconv('UTF-8', 'windows-1252', $value), 1);
            $this->Ln();
        }
    }

    function EmployeeImages($employee) {
        $imageX = 140;
        $employeeImgY = 30;
        $aadharImgY = 85;

        $employeeImgPath = "../../assets/uploads/" . $employee['ImageFileName'];
        $aadharImgPath = "../../assets/uploads/" . $employee['AddharCardImageFileName'];

        $this->SetFont('Arial', 'B', 10);

        // Label for Employee Image
        $this->SetXY($imageX, $employeeImgY);
        $this->Cell(50, 6, 'Employee Image', 0, 1, 'C');

        // Display Employee Image
        if (file_exists($employeeImgPath)) {
            $this->Image($employeeImgPath, $imageX, $employeeImgY + 7, 50, 40);
        } else {
            $this->SetXY($imageX, $employeeImgY + 7);
            $this->Cell(50, 10, "No Photo", 1, 0, 'C');
        }

        // Label for Aadhaar Image
        $this->SetXY($imageX, $aadharImgY);
        $this->Cell(50, 6, 'Aadhaar Card', 0, 1, 'C');

        // Display Aadhaar Card Image
        if (file_exists($aadharImgPath)) {
            $this->Image($aadharImgPath, $imageX, $aadharImgY + 7, 50, 30);
        } else {
            $this->SetXY($imageX, $aadharImgY + 7);
            $this->Cell(50, 10, "No Aadhaar", 1, 0, 'C');
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->EmployeeDetailsTable($employee);
$pdf->EmployeeImages($employee);
$pdf->Output("I", "employee_profile_{$employeeId}.pdf");
