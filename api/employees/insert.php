<?php
require '../../includes/init.php';
header("Content-Type: application/json");

// Collect values safely
$Name = $_POST['Name'] ?? '';
$Mobile = $_POST['Mobile'] ?? '';
$Address = $_POST['Address'] ?? '';
$Age = $_POST['Age'] ?? '';
$Email = $_POST['Email'] ?? '';
$Relation = $_POST['Relation'] ?? '';
$RelationName = $_POST['RelationName'] ?? '';
$RelationMobile = $_POST['RelationMobile'] ?? '';
$RelationAddress = $_POST['RelationAddress'] ?? '';
$AddharCardNumber = $_POST['AddharCardNumber'] ?? '';
$SalaryPaid = $_POST['SalaryPaid'] ?? '';
$SalaryDue = $_POST['SalaryDue'] ?? '';

// Handle image uploads
$imageFileName = '';
$aadhaarFileName = '';

$uploadPath = pathOf("assets/uploads/");

if (isset($_FILES['ImageFileName']) && $_FILES['ImageFileName']['error'] === 0) {
    $time = time();
    $imageFileName = $time . '-' . basename($_FILES['ImageFileName']['name']);
    move_uploaded_file($_FILES['ImageFileName']['tmp_name'], $uploadPath . $imageFileName);
}

if (isset($_FILES['AddharCardImageFileName']) && $_FILES['AddharCardImageFileName']['error'] === 0) {
    $time = time();
    $aadhaarFileName = $time . '-' . basename($_FILES['AddharCardImageFileName']['name']);
    move_uploaded_file($_FILES['AddharCardImageFileName']['tmp_name'], $uploadPath . $aadhaarFileName);
}

// Insert into DB
$query = "INSERT INTO Employee
(Name, Mobile, Address, Age, Email, Relation, RelationName, RelationMobile, RelationAddress, ImageFileName, AddharCardImageFileName, AddharCardNumber, SalaryPaid, SalaryDue)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $Name, $Mobile, $Address, $Age, $Email, $Relation,
    $RelationName, $RelationMobile, $RelationAddress,
    $imageFileName, $aadhaarFileName, $AddharCardNumber,
    $SalaryPaid, $SalaryDue
];

$result = execute($query, $params);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database insert failed."]);
}
