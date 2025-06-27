<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$Id = $_POST["Id"];
$index = 0;
$clients = select("SELECT Clients.*, Services.Name AS ServiceName FROM Clients INNER JOIN Services ON Clients.ServiceId = Services.Id WHERE Clients.IsDelete = 1 AND Clients.Id = $Id");

?>
    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
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
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">
                                        </h4>
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
                                            <?php foreach($clients as $client) : ?>
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
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            </div>
            <!-- end main content-->

        </div>
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

  
        
<?php

include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");

?>