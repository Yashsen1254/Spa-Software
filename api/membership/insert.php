<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Address = $_POST['Address'];
    $Age = $_POST['Age'];
    $Email = $_POST['Email'];
    $TotalAmount = $_POST['TotalAmount'];
    $ServiceId = $_POST['ServiceId'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $IsDelete = $_POST['IsDelete'];
    $PaymentMode = $_POST['PaymentMode'] ?? ''; // Optional field, default to empty string


    $query = "INSERT INTO Membership (Name,Mobile,Address,Age,Email,TotalAmount,ServiceId,StartDate,EndDate,IsDelete,PaymentMode) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $param = [$Name, $Mobile, $Address, $Age, $Email, $TotalAmount, $ServiceId, $StartDate, $EndDate, $IsDelete, $PaymentMode];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Membership Added Successfully"]);
?>