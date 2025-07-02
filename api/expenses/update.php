<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Date = $_POST['Date'];
    $Volume = $_POST['Volume'];
    $Price = $_POST['Price'];
    $Quantity = $_POST['Quantity'];
    $TotalAmount = $_POST['TotalAmount'];

    $query = "UPDATE Expenses SET Name = ?, Description = ?, Date = ?, Volume = ?, Price = ?, Quantity = ?, TotalAmount = ? WHERE Id = ?";
    $param = [$Name, $Description, $Date, $Volume, $Price, $Quantity, $TotalAmount, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Expenses Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>