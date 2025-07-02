<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Address = $_POST['Address'];
    $Age = $_POST['Age'];
    $Email = $_POST['Email'];
    $AmountPaid = $_POST['AmountPaid'];
    $AmountDue = $_POST['AmountDue'];
    $TotalAmount = $_POST['TotalAmount'];
    $ServiceId = $_POST['ServiceId'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $IsDelete = $_POST['IsDelete'];


    $query = "INSERT INTO Membership (Name,Mobile,Address,Age,Email,AmountPaid,AmountDue,TotalAmount,ServiceId,StartDate,EndDate,IsDelete) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $param = [$Name, $Mobile, $Address, $Age, $Email, $AmountPaid, $AmountDue, $TotalAmount, $ServiceId, $StartDate, $EndDate, $IsDelete];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Membership Added Successfully"]);
?>