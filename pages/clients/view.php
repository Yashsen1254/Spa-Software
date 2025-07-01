<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$Id = $_POST["Id"];
$index = 0;
$clients = select("
    SELECT 
        Clients.*, 
        Services.Name AS ServiceName, 
        Services.NoOfAppointments 
    FROM Clients 
    JOIN Services ON Clients.ServiceId = Services.Id 
    WHERE Clients.IsDelete = 1
");
$noOfAppointments = (int)$clients[0]['NoOfAppointments'];

$employees = select("SELECT * FROM Employee");

// Handle appointments submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_appointments'])) {
    $clientId = $clients[0]['Id'];

    $employeeIds = $_POST['employee_id'] ?? [];
    $roomNos = $_POST['room_no'] ?? [];
    $appointmentDates = $_POST['appointment_date'] ?? [];
    $inTimes = $_POST['appointment_time'] ?? [];
    $outTimes = $_POST['out_time'] ?? [];

    for ($i = 0; $i < count($employeeIds); $i++) {
        if (
            !empty($employeeIds[$i]) &&
            !empty($roomNos[$i]) &&
            !empty($appointmentDates[$i]) &&
            !empty($inTimes[$i]) &&
            !empty($outTimes[$i])
        ) {
            $datetime = $appointmentDates[$i] . ' ' . $inTimes[$i];
            execute(
                "INSERT INTO Appointments (ClientId, EmployeeId, RoomNo, AppointmentDate, InTime, OutTime, IsDelete) 
                 VALUES (?, ?, ?, ?, ?, ?, 1)",
                [$clientId, $employeeIds[$i], $roomNos[$i], $datetime, $inTimes[$i], $outTimes[$i]]
            );
        }
    }

    echo "<script>alert('Appointments saved successfully!'); window.location.href='index.php';</script>";
    exit;
}

$appointments = select("
    SELECT 
        Appointments.*, 
        Employee.Name AS EmployeeName 
    FROM Appointments 
    JOIN Employee ON Appointments.EmployeeId = Employee.Id 
    WHERE Appointments.ClientId = $Id AND Appointments.IsDelete = 1");

?>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="assets/images/logo.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/logo-dark.png" alt="" height="17">
                            </span>
                        </a>
                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="assets/images/logo-light.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/logo-light.png" alt="" height="19">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">CLINTS</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <a href="index.php" class="btn btn-primary w-md">GO BACK</a>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"></h4>
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Email</th>
                                                <th>Amount Paid</th>
                                                <th>Amount Due</th>
                                                <th>Service</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($clients as $client) : ?>
                                                <tr>
                                                    <td><?= $index += 1 ?></td>
                                                    <td><?= $client['Name'] ?></td>
                                                    <td><?= $client['Mobile'] ?></td>
                                                    <td><?= $client['Address'] ?></td>
                                                    <td><?= $client['Age'] ?></td>
                                                    <td><?= $client['Email'] ?></td>
                                                    <td><?= $client['AmountDue'] ?></td>
                                                    <td><?= $client['AmountPaid'] ?></td>
                                                    <td><?= $client['ServiceName'] ?></td>
                                                    <td><?= $client['StartDate'] ?></td>
                                                    <td><?= $client['EndDate'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Appointments</h4>
                                    <form method="POST">
                                        <table id="appointmentsTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Room No</th>
                                                    <th>Date</th>
                                                    <th>In Time</th>
                                                    <th>Out Time</th>
                                                </tr>
                                            </thead>
                                            <tbody id="appointmentsBody">
                                                <?php if (!empty($appointments)) : ?>
                                                    <?php foreach ($appointments as $appointment) : ?>
                                                        <tr>
                                                            <td><?= $appointment['EmployeeName'] ?></td>
                                                            <td><?= $appointment['RoomNo'] ?></td>
                                                            <td><?= $appointment['AppointmentDate'] ?></td>
                                                            <td><?= $appointment['InTime'] ?></td>
                                                            <td><?= $appointment['OutTime'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Add Row</button>
                                        <button type="submit" name="save_appointments" class="btn btn-success mb-3">Save Appointments</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="rightbar-overlay"></div>

    <!-- <script>
        function addRow() {
            const employees = <?= json_encode($employees) ?>;
            const tbody = document.querySelector('#appointmentsTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="employee_id[]" class="form-control" required>
                        <option value="">Select Employee</option>
                        ${employees.map(emp => `<option value="${emp.Id}">${emp.Name}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" name="room_no[]" class="form-control" required></td>
                <td><input type="date" name="appointment_date[]" class="form-control" required></td>
                <td><input type="time" name="appointment_time[]" class="form-control" required></td>
                <td><input type="time" name="out_time[]" class="form-control" required></td>
            `;
            tbody.appendChild(row);
        }
    </script> -->

    <script>
    const maxAppointments = <?= $noOfAppointments ?>;
    const existingAppointments = <?= count($appointments) ?>;

    function addRow() {
        const currentRows = document.querySelectorAll('#appointmentsTable tbody tr').length;

        if (currentRows >= maxAppointments) {
            alert("You cannot add more appointments. Maximum allowed is " + maxAppointments + ".");
            return;
        }

        const employees = <?= json_encode($employees) ?>;
        const tbody = document.querySelector('#appointmentsTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="employee_id[]" class="form-control" required>
                    <option value="">Select Employee</option>
                    ${employees.map(emp => `<option value="${emp.Id}">${emp.Name}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="room_no[]" class="form-control" required></td>
            <td><input type="date" name="appointment_date[]" class="form-control" required></td>
            <td><input type="time" name="appointment_time[]" class="form-control" required></td>
            <td><input type="time" name="out_time[]" class="form-control" required></td>
        `;
        tbody.appendChild(row);
    }
</script>


    <?php
    include pathOf("includes/scripts.php");
    include pathOf("includes/pageend.php");
    ?>
