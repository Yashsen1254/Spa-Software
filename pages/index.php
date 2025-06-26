<?php

require '../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$dailySales = selectOne("SELECT SUM(AmountPaid) AS DailySales FROM Clients WHERE StartDate = CURDATE() AND IsDelete = 1")['DailySales'] ?? 0;
$monthlySales = selectOne("SELECT SUM(AmountPaid) AS MonthlySales FROM Clients WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['MonthlySales'] ?? 0;
$yearlySales = selectOne("SELECT SUM(AmountPaid) AS YearlySales FROM Clients WHERE YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['YearlySales'] ?? 0;


?>
    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Orders</p>
                                                        <h4 class="mb-0">1,235</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                            <span class="avatar-title">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Revenue</p>
                                                        <h4 class="mb-0">$35, 723</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-archive-in font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Average Price</p>
                                                        <h4 class="mb-0">$16.2</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                        </div>

                        <div class="row">
                        <!-- Card 1 -->
                        <div class="col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="bg-primary-subtle">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="text-primary p-3">
                                                <h5 class="text-primary">DAILY !</h5>
                                                <p>Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="avatar-md profile-user-wid mb-1">
                                            </div>
                                            <h5 class="font-size-15 text-truncate">Sales <?= $dailySales ?> </h5>
                                            <a href="./sales/index.php" class="btn btn-primary waves-effect waves-light btn-sm">View PDF <i class="mdi mdi-arrow-right ms-1"></i></a>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="pt-4">
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="bg-primary-subtle">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="text-primary p-3">
                                                <h5 class="text-primary">MONTHLY !</h5>
                                                <p>Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="avatar-md profile-user-wid mb-1">
                                            </div>
                                            <h5 class="font-size-15 text-truncate">Sales <?= $monthlySales ?></h5>
                                            <a href="./sales/index.php" class="btn btn-primary waves-effect waves-light btn-sm">View PDF <i class="mdi mdi-arrow-right ms-1"></i></a>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="pt-4">
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="bg-primary-subtle">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="text-primary p-3">
                                                <h5 class="text-primary">YEARLY !</h5>
                                                <p>Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="avatar-md profile-user-wid mb-1">
                                            </div>
                                            <h5 class="font-size-15 text-truncate">Sales <?= $yearlySales ?></h5>
                                            <a href="./sales/index.php" class="btn btn-primary waves-effect waves-light btn-sm">View PDF <i class="mdi mdi-arrow-right ms-1"></i></a>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="pt-4">
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

<?php

include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");

?>