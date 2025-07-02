<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $Therapy = $_POST['Therapy'];
    $TherapistName = $_POST['TherapistName'];
    $Date = $_POST['Date'];
    $InTime = $_POST['InTime'];
    $OutTime = $_POST['OutTime'];
    $Price = $_POST['Price'];
    $Payment = $_POST['Payment'];

    $query = "UPDATE Clients SET Name = ?, Mobile = ?, Therapy = ?, TherapistName = ?, Date = ?, InTime = ?, OutTime = ?, Price = ?, Payment = ? WHERE Id = ?";
    $param = [$Name, $Mobile, $Therapy, $TherapistName, $Date, $InTime, $OutTime, $Price, $Payment, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Client Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>