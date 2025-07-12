<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Address = $_POST['Address'];
    $Age = $_POST['Age'];
    $Email = $_POST['Email'];
    $TotalAmount = $_POST['TotalAmount'];
    $ServiceId = $_POST['ServiceId'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $PaymentMode = $_POST['PaymentMode'];

    $query = "UPDATE Membership SET Name = ?, Mobile = ?, Address = ?, Age = ?, Email = ?, TotalAmount = ?, ServiceId = ?, StartDate = ?, EndDate = ?, PaymentMode = ? WHERE Id = ?";
    $param = [$Name, $Mobile, $Address, $Age, $Email, $TotalAmount, $ServiceId, $StartDate, $EndDate, $PaymentMode, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Membership Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>