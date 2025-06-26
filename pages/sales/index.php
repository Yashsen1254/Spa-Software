<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/salesnavbar.php");

$dailySales = selectOne("SELECT SUM(AmountPaid) AS DailySales FROM Clients WHERE StartDate = CURDATE() AND IsDelete = 1")['DailySales'] ?? 0;
$monthlySales = selectOne("SELECT SUM(AmountPaid) AS MonthlySales FROM Clients WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['MonthlySales'] ?? 0;
$yearlySales = selectOne("SELECT SUM(AmountPaid) AS YearlySales FROM Clients WHERE YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['YearlySales'] ?? 0;


?>


<body data-topbar="dark" data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">SALES</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">SALES</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
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
                </div> <!-- container-fluid -->
            </div>
        </div>
    </div>
    <!-- END layout-wrapper -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>