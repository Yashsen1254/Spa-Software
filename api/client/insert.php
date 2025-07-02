<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Therapy = $_POST['Therapy'];
    $TherapistName = $_POST['TherapistName'];
    $Date = $_POST['Date'];
    $InTime = $_POST['InTime'];
    $OutTime = $_POST['OutTime'];
    $Price = $_POST['Price'];
    $Payment = $_POST['Payment'];

    $query = "INSERT INTO Clients (Name,Mobile,Therapy,TherapistName,Date,InTime,OutTime,Price,Payment) VALUES (?,?,?,?,?,?,?,?,?)";
    $param = [$Name, $Mobile, $Therapy, $TherapistName, $Date, $InTime, $OutTime, $Price, $Payment];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Client Added Successfully"]);
?>