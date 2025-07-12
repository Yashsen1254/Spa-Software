<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $RoomNo = $_POST['RoomNo'];
    $EmployeeId = $_POST['EmployeeId'];
    $Date = $_POST['Date'];
    $InTime = $_POST['InTime'];
    $OutTime = $_POST['OutTime'];
    $Price = $_POST['Price'];
    $Payment = $_POST['Payment'];
    $Massage = $_POST['Massage'] ?? ''; // Optional field for Massage

    $query = "INSERT INTO Clients (Name,Mobile,RoomNo,EmployeeId,Date,InTime,OutTime,Price,PaymentMode,Massage) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $param = [$Name, $Mobile, $RoomNo, $EmployeeId, $Date, $InTime, $OutTime, $Price, $Payment, $Massage];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Client Added Successfully"]);
?>