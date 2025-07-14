<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// ✅ Validate Membership ID from POST or GET
if (isset($_POST["Id"])) {
    $Id = (int) $_POST["Id"];
} elseif (isset($_GET['Id'])) {
    $Id = (int) $_GET['Id'];
} else {
    echo "<script>alert('Membership ID not found.'); window.location.href='index.php';</script>";
    exit;
}

$index = 0;

// ✅ Fetch membership details
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
$massages = select("SELECT * FROM Massage");

// ✅ Handle Update Appointment
if (isset($_POST['update_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $employeeId = $_POST['employee_id'];
    $roomNo = $_POST['room_no'];
    $massage = $_POST['massage'];
    $appointmentDate = $_POST['appointment_date'];
    $inTime = $_POST['appointment_time'];
    $outTime = $_POST['out_time'];
    $amount = $_POST['amount'];

    execute(
        "UPDATE Appointments 
         SET EmployeeId=?, RoomNo=?, Massage=?, AppointmentDate=?, InTime=?, OutTime=?, Amount=? 
         WHERE Id=?",
        [$employeeId, $roomNo, $massage, $appointmentDate . ' ' . $inTime, $inTime, $outTime, $amount, $appointmentId]
    );

    echo "<script>alert('Appointment updated successfully!'); window.location.href='?Id=$Id';</script>";
    exit;
}

// ✅ Handle Delete Appointment
if (isset($_POST['delete_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    execute("DELETE FROM Appointments WHERE Id=?", [$appointmentId]);

    echo "<script>alert('Appointment deleted successfully!'); window.location.href='?Id=$Id';</script>";
    exit;
}

// ✅ Handle New Appointment Insertion
if (isset($_POST['save_appointments'])) {
    $employeeIds = $_POST['employee_id'] ?? [];
    $roomNos = $_POST['room_no'] ?? [];
    $appointmentDates = $_POST['appointment_date'] ?? [];
    $massagesInput = $_POST['massage'] ?? [];
    $inTimes = $_POST['appointment_time'] ?? [];
    $outTimes = $_POST['out_time'] ?? [];
    $amounts = $_POST['amount'] ?? [];

    $totalDeducted = 0;

    for ($i = 0; $i < count($employeeIds); $i++) {
        if (
            !empty($employeeIds[$i]) &&
            !empty($roomNos[$i]) &&
            !empty($appointmentDates[$i]) &&
            !empty($inTimes[$i]) &&
            !empty($outTimes[$i]) &&
            !empty($amounts[$i]) &&
            !empty($massagesInput[$i])
        ) {
            $datetime = $appointmentDates[$i] . ' ' . $inTimes[$i];

            // Insert appointment
            execute(
                "INSERT INTO Appointments (MemberId, EmployeeId, RoomNo, Massage, AppointmentDate, InTime, OutTime, Amount, IsDelete) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)",
                [$Id, $employeeIds[$i], $roomNos[$i], $massagesInput[$i], $datetime, $inTimes[$i], $outTimes[$i], $amounts[$i]]
            );

            $totalDeducted += (float)$amounts[$i];
        }
    }

    // Deduct total amount
    $newTotalAmount = (float)$membership['TotalAmount'] - $totalDeducted;
    if ($newTotalAmount < 0) $newTotalAmount = 0;

    execute("UPDATE Membership SET TotalAmount=? WHERE Id=?", [$newTotalAmount, $Id]);

    echo "<script>alert('Appointments saved successfully!'); window.location.href='?Id=$Id';</script>";
    exit;
}

// ✅ Load current appointments
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
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm"><img src="assets/images/logo.svg" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-dark.png" height="17"></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- ✅ Membership Details -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Age</th>
                                            <th>Email</th>
                                            <th>Total Amount</th>
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
                                            <td>₹<?= number_format($membership['TotalAmount'], 2) ?></td>
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

                <!-- ✅ Appointments Management -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Appointments</h4>
                                <div class="table-responsive">
                                    <table id="appointmentsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Room No</th>
                                                <th>Massage</th>
                                                <th>Date</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($appointments as $appointment): ?>
                                            <tr>
                                                <form method="POST">
                                                    <input type="hidden" name="Id" value="<?= $Id ?>">
                                                    <input type="hidden" name="appointment_id" value="<?= $appointment['Id'] ?>">
                                                    <td>
                                                        <select name="employee_id" class="form-control" required>
                                                            <?php foreach ($employees as $emp): ?>
                                                                <option value="<?= $emp['Id'] ?>" <?= ($emp['Id'] == $appointment['EmployeeId']) ? 'selected' : '' ?>>
                                                                    <?= $emp['Name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="room_no" class="form-control" value="<?= $appointment['RoomNo'] ?>" required></td>
                                                    <td>
                                                        <select name="massage" class="form-control" required>
                                                            <?php foreach ($massages as $m): ?>
                                                                <option value="<?= $m['Name'] ?>" <?= ($m['Name'] == $appointment['Massage']) ? 'selected' : '' ?>>
                                                                    <?= $m['Name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="date" name="appointment_date" class="form-control" value="<?= date('Y-m-d', strtotime($appointment['AppointmentDate'])) ?>" required></td>
                                                    <td><input type="time" name="appointment_time" class="form-control" value="<?= $appointment['InTime'] ?>" required></td>
                                                    <td><input type="time" name="out_time" class="form-control" value="<?= $appointment['OutTime'] ?>" required></td>
                                                    <td><input type="number" name="amount" class="form-control" value="<?= $appointment['Amount'] ?>" step="0.01" required></td>
                                                    <td>
                                                        <button type="submit" name="update_appointment" class="btn btn-sm btn-primary">Update</button>
                                                        <button type="submit" name="delete_appointment" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</button>
                                                    </td>
                                                </form>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Add Row</button>
                                <form method="POST">
                                    <input type="hidden" name="Id" value="<?= $Id ?>">
                                    <div id="newAppointments"></div>
                                    <button type="submit" name="save_appointments" class="btn btn-success">Save New Appointments</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function addRow() {
    const employees = <?= json_encode($employees) ?>;
    const massages = <?= json_encode(array_column($massages, 'Name')) ?>;

    const rowHTML = `
        <div class="row mb-2">
            <div class="col-md-2"><select name="employee_id[]" class="form-control" required>
                <option value="">Select Employee</option>
                ${employees.map(emp => `<option value="${emp.Id}">${emp.Name}</option>`).join('')}
            </select></div>
            <div class="col-md-1"><input type="number" name="room_no[]" class="form-control" required></div>
            <div class="col-md-2"><select name="massage[]" class="form-control" required>
                <option value="">Select Massage</option>
                ${massages.map(m => `<option value="${m}">${m}</option>`).join('')}
            </select></div>
            <div class="col-md-2"><input type="date" name="appointment_date[]" class="form-control" required></div>
            <div class="col-md-1"><input type="time" name="appointment_time[]" class="form-control" required></div>
            <div class="col-md-1"><input type="time" name="out_time[]" class="form-control" required></div>
            <div class="col-md-1"><input type="number" name="amount[]" class="form-control" placeholder="Amount" step="0.01" required></div>
        </div>
    `;
    document.getElementById('newAppointments').insertAdjacentHTML('beforeend', rowHTML);
}
</script>

<?php include pathOf("includes/scripts.php"); ?>
<?php include pathOf("includes/pageend.php"); ?>
