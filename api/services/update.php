<?php
    require '../../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $Name = $_POST['Name'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];
    $Duration = $_POST['Duration'];

    $query = "UPDATE Services SET Name = ?, Description = ?, Price = ?, Duration = ? WHERE Id = ?";
    $param = [$Name, $Description, $Price, $Duration, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Services Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>