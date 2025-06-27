<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Address = $_POST['Address'];
    $Age = $_POST['Age'];
    $Email = $_POST['Email'];
    $AmountPaid = $_POST['AmountPaid'];
    $AmountDue = $_POST['AmountDue'];
    $ServiceId = $_POST['ServiceId'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $IsDelete = $_POST['IsDelete'];

    $query = "UPDATE Clients SET Name = ?, Mobile = ?, Address = ?, Age = ?, Email = ?, AmountPaid = ?, AmountDue = ?, ServiceId = ?, StartDate = ?, EndDate = ?, IsDelete = ? WHERE Id = ?";
    $param = [$Name, $Mobile, $Address, $Age, $Email, $AmountPaid, $AmountDue, $ServiceId, $StartDate, $EndDate, $IsDelete, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Client Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>