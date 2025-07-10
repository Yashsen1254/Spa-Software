<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// Validate Membership ID via POST only
if (!isset($_POST["Id"])) {
    echo "<script>alert('Membership ID not found.'); window.location.href='index.php';</script>";
    exit;
}

$Id = (int) $_POST["Id"];
$index = 0;

$membership = selectOne(
    "SELECT Membership.*, Services.Name AS ServiceName, Services.NoOfAppointments 
     FROM Membership 
     JOIN Services ON Membership.ServiceId = Services.Id 
     WHERE Membership.IsDelete = 1 AND Membership.Id = ?", 
    [$Id]
);

if (!$membership) {
    echo "<script>alert('Membership not found.'); window.location.href='index.php';</script>";
    exit;
}

$noOfAppointments = (int)$membership['NoOfAppointments'];
$employees = select("SELECT * FROM Employee");

// Handle appointments submission
if (isset($_POST['save_appointments'])) {
    $employeeIds = $_POST['employee_id'] ?? [];
    $roomNos = $_POST['room_no'] ?? [];
    $appointmentDates = $_POST['appointment_date'] ?? [];
    $massage = $_POST['massage'] ?? [];
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
                "INSERT INTO Appointments (MemberId, EmployeeId, RoomNo, Massage, AppointmentDate, InTime, OutTime, IsDelete) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)", 
                [$Id, $employeeIds[$i], $roomNos[$i], $massage[$i], $datetime, $inTimes[$i], $outTimes[$i]]
            );
        }
    }

    echo "<script>alert('Appointments saved successfully!'); window.location.href='index.php';</script>";
    exit;
}

// Load current appointments
$appointments = select(
    "SELECT Appointments.*, Employee.Name AS EmployeeName 
     FROM Appointments 
     JOIN Employee ON Appointments.EmployeeId = Employee.Id 
     WHERE Appointments.MemberId = ? AND Appointments.IsDelete = 1", 
    [$Id]
);
?>

<body data-sidebar="dark">
<div id="layout-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm"><img src="assets/images/logo.svg" alt="" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="" height="17"></span>
                    </a>
                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm"><img src="assets/images/logo-light.svg" alt="" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-light.png" alt="" height="19"></span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Membership Details -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">MEMBERSHIP</h4>
                            <div class="page-title-right">
                                <a href="index.php" class="btn btn-primary w-md">GO BACK</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Membership Info Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered w-100">
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
                                        <tr>
                                            <td><?= ++$index ?></td>
                                            <td><?= $membership['Name'] ?></td>
                                            <td><?= $membership['Mobile'] ?></td>
                                            <td><?= $membership['Address'] ?></td>
                                            <td><?= $membership['Age'] ?></td>
                                            <td><?= $membership['Email'] ?></td>
                                            <td><?= $membership['AmountPaid'] ?></td>
                                            <td><?= $membership['AmountDue'] ?></td>
                                            <td><?= $membership['ServiceName'] ?></td>
                                            <td><?= $membership['StartDate'] ?></td>
                                            <td><?= $membership['EndDate'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Management -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Appointments</h4>
                                <form method="POST">
                                    <input type="hidden" name="Id" value="<?= $Id ?>">
                                    <table id="appointmentsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Room No</th>
                                                <th>Massage</th>
                                                <th>Date</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="appointmentsBody">
                                            <?php foreach ($appointments as $appointment): ?>
                                                <tr>
                                                    <td><?= $appointment['EmployeeName'] ?></td>
                                                    <td><?= $appointment['RoomNo'] ?></td>
                                                    <td><?= $appointment['Massage'] ?></td>
                                                    <td><?= date('Y-m-d', strtotime($appointment['AppointmentDate'])) ?></td>
                                                    <td><?= $appointment['InTime'] ?></td>
                                                    <td><?= $appointment['OutTime'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
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

<script>
    const maxAppointments = <?= $noOfAppointments ?>;
    const existingAppointments = <?= count($appointments) ?>;

    function addRow() {
        const addedRows = document.querySelectorAll('#appointmentsTable tbody tr.new-row').length;
        const totalAppointments = existingAppointments + addedRows;

        if (totalAppointments >= maxAppointments) {
            alert("You cannot add more appointments. Maximum allowed is " + maxAppointments + ".");
            return;
        }

        const employees = <?= json_encode($employees) ?>;
        const tbody = document.querySelector('#appointmentsTable tbody');
        const row = document.createElement('tr');
        row.classList.add('new-row');
        row.innerHTML = `
            <td>
                <select name="employee_id[]" class="form-control" required>
                    <option value="">Select Employee</option>
                    ${employees.map(emp => `<option value="${emp.Id}">${emp.Name}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="room_no[]" class="form-control" required></td>
            <td>
                <select name="massage[]" class="form-control" required>
                    <option value="">Select Massage</option>
                    <option value="Swedish">Swedish</option>
                    <option value="Deep Tissue">Deep Tissue</option>
                    <option value="Aromatherapy">Aromatherapy</option>
                    <option value="Hot Stone">Hot Stone</option>
                    <option value="Thai">Thai</option>
                </select>
            </td>
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
