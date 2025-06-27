<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];
    $Duration = $_POST['Duration'];

    $query = "INSERT INTO Services (Name,Description,Price,Duration) VALUES (?,?,?,?)";
    $param = [$Name, $Description, $Price, $Duration];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Client Added Successfully"]);
?>