<?php
require '../../includes/init.php';
$index = 0;
$employeeId = $_POST['Id'] ?? 0;

// Get employee details
$employee = selectOne("SELECT * FROM Employee WHERE Id = ?", [$employeeId]);

if (!$employee) {
    die("Employee not found.");
}

// Fetch Appointments with Member Name
$appointments = select("
    SELECT 
        'Appointment' AS WorkType,
        AppointmentDate AS WorkDate,
        InTime,
        OutTime,
        RoomNo,
        a.Massage AS WorkDescription,
        m.Name AS ClientName
    FROM Appointments a
    LEFT JOIN Membership m ON a.MemberId = m.Id
    WHERE a.EmployeeId = ? AND a.IsDelete = 1
", [$employeeId]);

// Fetch Clients with Name
$clients = select("
    SELECT 
        'Client' AS WorkType,
        Date AS WorkDate,
        InTime,
        OutTime,
        c.Massage AS WorkDescription,
        c.Name AS ClientName
    FROM Clients c
    WHERE c.EmployeeId = ?
", [$employeeId]);

// Merge both and sort by date
$allWork = array_merge($appointments, $clients);

// Sort by WorkDate descending
usort($allWork, function($a, $b) {
    return strtotime($b['WorkDate']) - strtotime($a['WorkDate']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Work Report</title>
    <?php include pathOf("includes/header.php"); ?>
</head>
<body>
    <div class="container mt-4">
        <h2>Work Report for <?= htmlspecialchars($employee['Name']) ?></h2>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($employee['Mobile']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($employee['Email']) ?></p>
        <hr>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Date</th>
                    <th>Client/Member Name</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Room No</th>
                    <th>Massage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($allWork) > 0): ?>
                    <?php foreach ($allWork as $work): ?>
                        <tr>
                            <td><?= $index+= 1 ?></td>
                            <td><?= date('d-m-Y', strtotime($work['WorkDate'])) ?></td>
                            <td><?= htmlspecialchars($work['ClientName'] ?? '-') ?></td>
                            <td><?= $work['InTime'] ?></td>
                            <td><?= $work['OutTime'] ?></td>
                            <td><?= htmlspecialchars($work['RoomNo'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($work['WorkDescription'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No work found for this employee.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="empDetail.php" method="POST">
            <input type="hidden" name="Id" value="<?= $employeeId ?>">
            <button type="submit" class="btn btn-success">Download PDF</button>
        </form>
    </div>

    <?php include pathOf("includes/scripts.php"); ?>
</body>
</html>
