<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// Get massage details from POST
$massageName = $_POST['MassageName'] ?? 'Unknown Massage';

// Fetch massage history: Appointments + Clients
$massageHistory = select("
    SELECT 
        e.Name AS EmployeeName,
        m.Name AS ClientOrMemberName,
        a.AppointmentDate AS WorkDate,
        a.InTime,
        a.OutTime
    FROM Appointments a
    INNER JOIN Employee e ON a.EmployeeId = e.Id
    LEFT JOIN Membership m ON a.MemberId = m.Id
    WHERE a.IsDelete = 1 AND a.Massage = ?
    
    UNION ALL
    
    SELECT 
        e.Name AS EmployeeName,
        c.Name AS ClientOrMemberName,
        c.Date AS WorkDate,
        c.InTime,
        c.OutTime
    FROM Clients c
    INNER JOIN Employee e ON c.EmployeeId = e.Id
    WHERE c.Massage = ?
    ORDER BY WorkDate DESC
", [$massageName, $massageName]);

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

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Massage Detail: <?= htmlspecialchars($massageName) ?></h4>
                            </div>
                        </div>
                    </div>

                    <!-- Massage History Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php if (count($massageHistory) > 0): ?>
                                    <table class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Employee Name</th>
                                                <th>Client/Member Name</th>
                                                <th>Date</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; foreach ($massageHistory as $history): ?>
                                            <tr>
                                                <td><?= $sr++ ?></td>
                                                <td><?= htmlspecialchars($history['EmployeeName']) ?></td>
                                                <td><?= htmlspecialchars($history['ClientOrMemberName']) ?></td>
                                                <td><?= htmlspecialchars($history['WorkDate']) ?></td>
                                                <td><?= htmlspecialchars($history['InTime']) ?></td>
                                                <td><?= htmlspecialchars($history['OutTime']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <div class="alert alert-warning">No records found for this massage.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- End Page-content -->
        </div> <!-- end main content-->
    </div> <!-- end layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

<?php
include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");
?>
