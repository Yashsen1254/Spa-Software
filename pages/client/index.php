<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$index = 0;
$clients = select("SELECT * FROM Clients");

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
                                    <h4 class="mb-sm-0 font-size-18">Clients</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <a href="<?= urlOf("pages/client/add.php") ?>" class="btn btn-primary w-md">Add Client</a>
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
                                                <th>Therapy</th>
                                                <th>Therapist Name</th>
                                                <th>Date</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                                <th>Price</th>
                                                <th>Payment</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($clients as $client) : ?>
                                                <tr>
                                                    <td><?= $index += 1 ?></td>
                                                    <td><?= $client["Name"] ?></td>
                                                    <td><?= $client["Mobile"] ?></td>
                                                    <td><?= $client["Therapy"] ?></td>
                                                    <td><?= $client["TherapistName"] ?></td>
                                                    <td><?= $client["Date"] ?></td>
                                                    <td><?= $client["InTime"] ?></td>
                                                    <td><?= $client["OutTime"] ?></td>
                                                    <td><?= $client["Price"] ?></td>
                                                    <td><?= $client["Payment"] ?></td>
                                                    <form action="update.php" method="POST">
                                                        <td>
                                                            <input type="hidden" name="Id" id="Id" value="<?= $client['Id'] ?>">
                                                            <button type="submit" class="btn btn-primary w-md">UPDATE</button>
                                                        </td>
                                                    </form>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary w-md" onclick="deleteData(<?= $client['Id'] ?>)">DELETE</button>
                                                    </td>
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

<script>
    function deleteData(Id) {
        $.ajax({
            url: '../../api/client/delete.php',
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

<?php

include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");

?>