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

// Fetch membership details
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

// Massage types
$allMassages = ["Swedish", "Deep Tissue", "Aromatherapy", "Hot Stone", "Thai"];

// Fetch massages already booked for this member
$bookedMassagesResult = select(
    "SELECT DISTINCT Massage FROM Appointments WHERE MemberId = ? AND IsDelete = 1",
    [$Id]
);
$bookedMassages = array_column($bookedMassagesResult, 'Massage');
$availableMassages = array_values(array_diff($allMassages, $bookedMassages)); // Remaining massages

// Handle appointments submission
if (isset($_POST['save_appointments'])) {
    $employeeIds = $_POST['employee_id'] ?? [];
    $roomNos = $_POST['room_no'] ?? [];
    $appointmentDates = $_POST['appointment_date'] ?? [];
    $massages = $_POST['massage'] ?? [];
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
            !empty($massages[$i])
        ) {
            // Check for duplicate massage (backend validation)
            if (in_array($massages[$i], $bookedMassages)) {
                echo "<script>alert('Massage \"{$massages[$i]}\" has already been booked.'); window.history.back();</script>";
                exit;
            }

            $datetime = $appointmentDates[$i] . ' ' . $inTimes[$i];

            // Insert appointment
            execute(
                "INSERT INTO Appointments (MemberId, EmployeeId, RoomNo, Massage, AppointmentDate, InTime, OutTime, Amount, IsDelete) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)", 
                [$Id, $employeeIds[$i], $roomNos[$i], $massages[$i], $datetime, $inTimes[$i], $outTimes[$i], $amounts[$i]]
            );

            $totalDeducted += (float)$amounts[$i]; // Add to deduction total
            $bookedMassages[] = $massages[$i]; // Update booked massages in memory
        }
    }

    // Deduct total amount from membership TotalAmount
    $newTotalAmount = (float)$membership['TotalAmount'] - $totalDeducted;
    if ($newTotalAmount < 0) {
        $newTotalAmount = 0; // Prevent negative TotalAmount
    }

    execute(
        "UPDATE Membership SET TotalAmount = ? WHERE Id = ?",
        [$newTotalAmount, $Id]
    );

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
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm"><img src="assets/images/logo.svg" alt="" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="" height="17"></span>
                    </a>
                    <a href="index.php" class="logo logo-light">
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
                                                <th>Amount</th>
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
                                                    <td>₹<?= number_format($appointment['Amount'], 2) ?></td>
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
    let availableMassages = <?= json_encode($availableMassages) ?>;

    function addRow() {
        const addedRows = document.querySelectorAll('#appointmentsTable tbody tr.new-row').length;
        const totalAppointments = existingAppointments + addedRows;

        if (totalAppointments >= maxAppointments) {
            alert("You cannot add more appointments. Maximum allowed is " + maxAppointments + ".");
            return;
        }

        if (availableMassages.length === 0) {
            alert("All massage types have already been booked.");
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
    <select name="massage[]" class="form-control massage-select" required onchange="removeSelectedMassage(this)">
        <option value="">Select Massage</option>
        <?= implode("", array_map(fn($m) => "<option value=\"{$m['Name']}\">{$m['Name']}</option>", $massages)) ?>
    </select>
</td>

            <td><input type="date" name="appointment_date[]" class="form-control" required></td>
            <td><input type="time" name="appointment_time[]" class="form-control" required></td>
            <td><input type="time" name="out_time[]" class="form-control" required></td>
            <td><input type="number" name="amount[]" class="form-control" placeholder="Enter Amount" step="0.01" required></td>
        `;
        tbody.appendChild(row);
    }

    function removeSelectedMassage(selectElement) {
        const selectedMassage = selectElement.value;

        if (selectedMassage) {
            // Remove selected massage from availableMassages
            availableMassages = availableMassages.filter(m => m !== selectedMassage);

            // Disable selected option in all other dropdowns
            document.querySelectorAll('.massage-select').forEach(sel => {
                if (sel !== selectElement) {
                    const option = sel.querySelector(`option[value="${selectedMassage}"]`);
                    if (option) option.remove();
                }
            });
        }
    }
</script>


<?php
include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");
?>
