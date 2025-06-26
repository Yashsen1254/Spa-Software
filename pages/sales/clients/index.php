<?php

require '../../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/salessidebar.php");
$index = 0;
$clients = select("SELECT * FROM Clients WHERE IsDelete = 1");

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
                                            <a href="<?= urlOf("pages/sales/clients/add.php") ?>" class="btn btn-primary w-md">Add Clients</a>
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
                                                <th>Amount Paid</th>
                                                <th>Amount Due</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($clients as $client) : ?>
                                                <tr>
                                                    <td><?= $index += 1 ?></td>
                                                    <td><?= $client["Name"] ?></td>
                                                    <td><?= $client["Mobile"] ?></td>
                                                    <td><?= $client["AmountPaid"] ?></td>
                                                    <td><?= $client["AmountDue"] ?></td>
                                                    <form action="update.php" method="POST">
                                                        <td>
                                                            <input type="hidden" name="Id" id="Id" value="<?= $client['Id'] ?>">
                                                            <button type="submit" class="btn btn-primary w-md">UPDATE</button>
                                                        </td>
                                                    </form>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary w-md" onclick="deleteData(<?= $client['Id'] ?>)">DELETE</button>
                                                    </td>
                                                    <form action="view.php" method="POST">
                                                        <td>
                                                            <input type="hidden" name="Id" id="Id" value="<?= $client['Id'] ?>">
                                                            <button type="submit" class="btn btn-primary w-md">VIEW</button>
                                                        </td>
                                                    </form>
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function deleteData(Id) {
        $.ajax({
            url: '../../../api/sales/clients/delete.php',
            type: 'POST',
            data: {
                Id: Id
            },
            success: function(response) {
                alert("Client Deleted Successfully");
                location.reload();
            },
            error: function(response) {
                alert("Error")
                location.reload();
            }
        })
    }
</script>