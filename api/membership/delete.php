<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];

    $query = "UPDATE Membership SET IsDelete = -1 WHERE Id = ?";
    $param = [$Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Membership Deleted Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>