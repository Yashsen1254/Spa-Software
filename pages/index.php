<?php

require '../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

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
                                <h4 class="mb-sm-0 font-size-18">DASH BOARD</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">DASH BOARD</li>
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
                                                <h5 class="text-primary">Welcome Back !</h5>
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
                                            <h5 class="font-size-15 text-truncate">Sales</h5>
                                            <a href="./sales/index.php" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
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
                                                <h5 class="text-primary">Welcome Back !</h5>
                                                <p>Skote Dashboard</p>
                                            </div>
                                        </div>
                                        <div class="col-5 align-self-end">
                                            <img src="<?= urlOf("assets/images/profile-img.png") ?>" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="avatar-md profile-user-wid mb-4">
                                                <img src="<?= urlOf("assets/images/users/avatar-1.jpg") ?>" alt="" class="img-thumbnail rounded-circle">
                                            </div>
                                            <h5 class="font-size-15 text-truncate">Henry Price</h5>
                                            <p class="text-muted mb-0 text-truncate">UI/UX Designer</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="pt-4">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h5 class="font-size-15">125</h5>
                                                        <p class="text-muted mb-0">Projects</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <h5 class="font-size-15">$1245</h5>
                                                        <p class="text-muted mb-0">Revenue</p>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                                </div>
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
                                                <p>EXPENSES</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="avatar-md profile-user-wid mb-1">
                                            </div>
                                            <h5 class="font-size-15 text-truncate">EXPENSES</h5>
                                            <a href="./expanses/index.php" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
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