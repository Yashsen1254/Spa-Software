<?php

    require '../../includes/init.php';
    header('Content-Type: application/json');

    $ClientId = $_POST['ClientId'];
    $EmployeeId = $_POST['EmployeeId'];
    $RoomNo = $_POST['RoomNo'];
    $AppointmentDate = $_POST['AppointmentDate'];
    $AppointmentTime = $_POST['AppointmentTime'];
    $Status = $_POST['Status'];
    $IsDelete = $_POST['IsDelete'];

    $query = "INSERT INTO Appointments (ClientId,EmployeeId,RoomNo,AppointmentDate,AppointmentTime,Status,IsDelete) VALUES (?,?,?,?,?,?,?)";
    $param = [$ClientId, $EmployeeId, $RoomNo, $AppointmentDate, $AppointmentTime, $Status, $IsDelete];

    execute($query, $param);

    echo json_encode(["status" => "success", "message" => "Appointment Added Successfully"]);
?>