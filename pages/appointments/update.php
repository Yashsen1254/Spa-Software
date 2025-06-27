<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$clients = select("SELECT * FROM Clients");
$employees = select("SELECT * FROM Employee");

$Id = $_POST["Id"];
$appointment = selectOne("SELECT * FROM Appointments WHERE Id = $Id");

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
                                <h4 class="mb-sm-0 font-size-18">Update Appointment</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active"></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label>Client Name</label>
                                                    <select class="form-select" id="ClientId" name="ClientId">
                                                        <?php foreach ($clients as $client) : ?>
                                                            <option value="<?= $client['Id'] ?>" <?= $client['Id'] == $appointment['ClientId'] ? 'selected' : '' ?>><?= $client['Name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label>Employee Name</label>
                                                    <select class="form-select" id="EmployeeId" name="EmployeeId">
                                                        <?php foreach ($employees as $employee) : ?>
                                                            <option value="<?= $employee['Id'] ?>" <?= $employee['Id'] == $appointment['EmployeeId'] ? 'selected' : '' ?>><?= $employee['Name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-inputCity" class="form-label">Room Number</label>
                                                    <input type="number" class="form-control" id="RoomNo" name="RoomNo" placeholder="Enter Room Number"  value="<?= $appointment['RoomNo'] ?>">
                                                    <input type="hidden" id="Id" name="Id" value="<?= $appointment['Id'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Appointment Date</label>
                                                    <input type="date" class="form-control" id="AppointmentDate" name="AppointmentDate" value="<?= date('Y-m-d', strtotime($appointment['AppointmentDate'])) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-inputCity" class="form-label">Appointment Time</label>
                                                    <input type="time" class="form-control" id="AppointmentTime" name="AppointmentTime" placeholder="Enter Appointment Time"  value="<?= $appointment['AppointmentTime'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select class="form-select" id="Status" name="Status">
                                                        <option selected><?= $appointment['Status'] ?></option>
                                                        <option value="Scheduled">Scheduled</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary w-md" onclick="insertData()">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        
        <?php
        include pathOf("includes/scripts.php");
        ?>

        <script>
            function insertData() {
                var Id = $('#Id').val();
                console.log(Id);
                
                var ClientId = $('#ClientId').val();
                var EmployeeId = $('#EmployeeId').val();
                var RoomNo = $('#RoomNo').val();
                var AppointmentDate = $('#AppointmentDate').val();
                var AppointmentTime = $('#AppointmentTime').val();
                var Status = $('#Status').val();
                var IsDelete = 1;

                $.ajax({
                    url: '../../api/appointment/update.php',
                    type: 'POST',
                    data: {
                        Id: Id,
                        ClientId: ClientId,
                        EmployeeId: EmployeeId,
                        RoomNo: RoomNo,
                        AppointmentDate: AppointmentDate,
                        AppointmentTime: AppointmentTime,
                        Status: Status,
                        IsDelete: IsDelete
                    },
                    success: function(response) {
                        alert("Appointment Added Successfully");
                        window.location.href = 'index.php';
                    },
                    error: function(response) {
                        alert("Error: ");
                    }
                })
            }
        </script>

        <?php
        include pathOf("includes/pageend.php");
        ?>