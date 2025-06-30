<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];
    $Duration = $_POST['Duration'];
    $NoOfAppointments = $_POST['NoOfAppointments'];

    $query = "INSERT INTO Services (Name,Description,Price,Duration,NoOfAppointments) VALUES (?,?,?,?,?)";
    $param = [$Name, $Description, $Price, $Duration, $NoOfAppointments];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Service Added Successfully"]);
?>