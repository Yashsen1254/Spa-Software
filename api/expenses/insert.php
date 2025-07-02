<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Date = $_POST['Date'];
    $Volume = $_POST['Volume'];
    $Price = $_POST['Price'];
    $Quantity = $_POST['Quantity'];
    $TotalAmount = $_POST['TotalAmount'];

    $query = "INSERT INTO Expenses (Name,Description,Date,Volume,Price,Quantity,TotalAmount) VALUES (?,?,?,?,?,?,?)";
    $param = [$Name, $Description, $Date, $Volume, $Price, $Quantity, $TotalAmount];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Expense Added Successfully"]);
?>