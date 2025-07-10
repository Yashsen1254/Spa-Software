<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$index = 0;

// Fetch employee data with total work counts
$employees = select("
    SELECT 
        e.*,
        COALESCE(a.TotalAppointments, 0) AS TotalAppointments,
        COALESCE(c.TotalClients, 0) AS TotalClients,
        (COALESCE(a.TotalAppointments, 0) + COALESCE(c.TotalClients, 0)) AS TotalWork
    FROM Employee e
    LEFT JOIN (
        SELECT EmployeeId, COUNT(*) AS TotalAppointments
        FROM Appointments
        WHERE IsDelete = 1
        GROUP BY EmployeeId
    ) a ON e.Id = a.EmployeeId
    LEFT JOIN (
        SELECT EmployeeId, COUNT(*) AS TotalClients
        FROM Clients
        GROUP BY EmployeeId
    ) c ON e.Id = c.EmployeeId
");

// Fetch massage history for all employees
$massageHistory = select("
    SELECT 
        e.Name AS EmployeeName,
        COALESCE(m.Name, c.Name) AS ClientName,
        COALESCE(a.AppointmentDate, c.Date) AS WorkDate,
        COALESCE(a.InTime, c.InTime) AS InTime,
        COALESCE(a.OutTime, c.OutTime) AS OutTime,
        COALESCE(a.Massage, c.Massage) AS MassageDescription
    FROM Employee e
    LEFT JOIN Appointments a ON e.Id = a.EmployeeId AND a.IsDelete = 1
    LEFT JOIN Membership m ON a.MemberId = m.Id
    LEFT JOIN Clients c ON e.Id = c.EmployeeId
    WHERE COALESCE(a.Massage, c.Massage) IS NOT NULL
    ORDER BY WorkDate DESC
");

?>
<body data-sidebar="dark">
    <div id="layout-wrapper">
        <!-- Header -->
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
                            </div>
                        </div>
                    </div>

                    <!-- Employee Summary Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a class="btn btn-primary w-md" href="./employee.php">Employee</a>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a class="btn btn-primary w-md" href="./massage.php">Massage</a>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    
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
