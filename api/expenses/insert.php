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
    $PaymentMode = $_POST['PaymentMode'] ?? ''; // Optional field, default to empty string

    $query = "INSERT INTO Expenses (Name,Description,Date,Volume,Price,Quantity,TotalAmount,PaymentMode) VALUES (?,?,?,?,?,?,?,?)";
    $param = [$Name, $Description, $Date, $Volume, $Price, $Quantity, $TotalAmount, $PaymentMode];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Expense Added Successfully"]);
?>