<?php
require '../../includes/init.php';
header("Content-Type: application/json");

// Validate ID
$Id = $_POST['Id'] ?? null;
if (!$Id) {
    echo json_encode(["success" => false, "message" => "Missing employee ID"]);
    exit;
}

// Collect form data
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

$uploadPath = pathOf("assets/uploads/");
$imageFileName = null;
$aadhaarFileName = null;

// Handle new image upload
if (isset($_FILES['ImageFileName']) && $_FILES['ImageFileName']['error'] === 0) {
    $imageFileName = time() . '-' . $_FILES['ImageFileName']['name'];
    move_uploaded_file($_FILES['ImageFileName']['tmp_name'], $uploadPath . $imageFileName);
}

// Handle new Aadhaar image upload
if (isset($_FILES['AddharCardImageFileName']) && $_FILES['AddharCardImageFileName']['error'] === 0) {
    $aadhaarFileName = time() . '-' . $_FILES['AddharCardImageFileName']['name'];
    move_uploaded_file($_FILES['AddharCardImageFileName']['tmp_name'], $uploadPath . $aadhaarFileName);
}

// Start building SQL
$query = "UPDATE Employee SET 
    Name = ?, 
    Mobile = ?, 
    Address = ?, 
    Age = ?, 
    Email = ?, 
    Relation = ?, 
    RelationName = ?, 
    RelationMobile = ?, 
    RelationAddress = ?, 
    AddharCardNumber = ?, 
    SalaryPaid = ?, 
    SalaryDue = ?";

$params = [
    $Name, $Mobile, $Address, $Age, $Email, $Relation, $RelationName,
    $RelationMobile, $RelationAddress, $AddharCardNumber, $SalaryPaid, $SalaryDue
];

// Add uploaded filenames if present
if ($imageFileName) {
    $query .= ", ImageFileName = ?";
    $params[] = $imageFileName;
}
if ($aadhaarFileName) {
    $query .= ", AddharCardImageFileName = ?";
    $params[] = $aadhaarFileName;
}

$query .= " WHERE Id = ?";
$params[] = $Id;

// Execute update
$result = execute($query, $params);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update employee"]);
}
