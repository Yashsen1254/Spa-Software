<?php
require '../../includes/init.php';
header('Content-Type: application/json');

// Get data
$Id = $_POST['Id'] ?? null;
$Name = $_POST['Name'] ?? '';
$Mobile = $_POST['Mobile'] ?? '';
$RoomNo = $_POST['RoomNo'] ?? '';
$EmployeeId = $_POST['EmployeeId'] ?? '';
$Date = $_POST['Date'] ?? '';
$InTime = $_POST['InTime'] ?? '';
$OutTime = $_POST['OutTime'] ?? '';
$Massage = $_POST['Massage'] ?? '';
$Price = $_POST['Price'] ?? '';
$PaymentMode = $_POST['PaymentMode'] ?? '';

// Validate ID
if (!$Id) {
    echo json_encode(["status" => "error", "message" => "Client ID is missing."]);
    exit;
}

// Update query
$query = "UPDATE Clients SET Name = ?, Mobile = ?, RoomNo = ?, EmployeeId = ?, Date = ?, InTime = ?, OutTime = ?, Massage = ?, Price = ?, PaymentMode = ? WHERE Id = ?";
$params = [$Name, $Mobile, $RoomNo, $EmployeeId, $Date, $InTime, $OutTime, $Massage, $Price, $PaymentMode, $Id];

$result = execute($query, $params);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Client updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed."]);
}
?>