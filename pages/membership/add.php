<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$services = select("SELECT * FROM Services")

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
                                <h4 class="mb-sm-0 font-size-18">Add Membership</h4>

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
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Client Name">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-email-input" class="form-label">Mobile</label>
                                                    <input type="number" class="form-control" id="Mobile" name="Mobile" placeholder="Enter Mobile Number Of The Client">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="Address" name="Address" placeholder="Enter Client Address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-inputCity" class="form-label">Age</label>
                                                    <input type="number" class="form-control" id="Age" name="Age" placeholder="Enter Age Of The Client">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Client's Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Total Amount</label>
                                                    <input type="number" class="form-control" id="TotalAmount" name="TotalAmount" placeholder="Enter Total Amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label>Service Type</label>
                                                    <select class="form-select" id="ServiceId" name="ServiceId">
                                                        <option selected>Select Service Type</option>
                                                        <?php foreach ($services as $service) : ?>
                                                            <option value="<?= $service['Id'] ?>"><?= $service['Name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Service Start Date</label>
                                                    <input type="date" class="form-control" id="StartDate" name="StartDate" placeholder="Enter Service Start Date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Service End Date</label>
                                                    <input type="date" class="form-control" id="EndDate" name="EndDate" placeholder="Enter Service End Date">
                                                    <input type="hidden" id="IsDelete" name="IsDelete" value="1">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="formrow-password-input" class="form-label">Payment Mode</label>
                                                    <select name="PaymentMode" id="PaymentMode" class="form-select">
                                                        <option value="Cash">Cash</option>
                                                        <option value="Online">Online</option>
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
                var Name = $('#Name').val();
                var Mobile = $('#Mobile').val();
                var Address = $('#Address').val();
                var Age = $('#Age').val();
                var Email = $('#Email').val();
                var TotalAmount = $('#TotalAmount').val();
                var ServiceId = $('#ServiceId').val();
                var StartDate = $('#StartDate').val();
                var EndDate = $('#EndDate').val();
                var IsDelete = $('#IsDelete').val();
                var PaymentMode = $('#PaymentMode').val();

                $.ajax({
                    url: '../../api/membership/insert.php',
                    type: 'POST',
                    data: {
                        Name: Name,
                        Mobile: Mobile,
                        Address: Address,
                        Age: Age,
                        Email: Email,
                        TotalAmount: TotalAmount,
                        ServiceId: ServiceId,
                        StartDate: StartDate,
                        EndDate: EndDate,
                        IsDelete: IsDelete,
                        PaymentMode: PaymentMode
                    },
                    success: function(response) {
                        alert("Membership Added Successfully");
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