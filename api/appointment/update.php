<?php
    require '../../includes/init.php';
    header('Content-Type: application/json');

    $Id = $_POST['Id'];
    $ClientId = $_POST['ClientId'];
    $EmployeeId = $_POST['EmployeeId'];
    $RoomNo = $_POST['RoomNo'];
    $AppointmentDate = $_POST['AppointmentDate'];
    $AppointmentTime = $_POST['AppointmentTime'];
    $Status = $_POST['Status'];
    $IsDelete = $_POST['IsDelete'];

    $query = "UPDATE Appointments SET ClientId = ?, EmployeeId = ?, RoomNo = ?, AppointmentDate = ?, AppointmentTime = ?, Status = ?, IsDelete = ? WHERE Id = ?";
    $param = [$ClientId, $EmployeeId, $RoomNo, $AppointmentDate, $AppointmentTime, $Status, $IsDelete, $Id];

    $result = execute($query, $param);

    if($result) {
        echo json_encode(["status" => "success", "message" => "Appointment Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something Went Wrong"]);
    }
?>