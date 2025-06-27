<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

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
                                    <h4 class="mb-sm-0 font-size-18">Add Service</h4>

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
                                                <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Product Name">
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-email-input" class="form-label">Description</label>
                                                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description Of The Product">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-password-input" class="form-label">Date</label>
                                                        <input type="date" class="form-control" id="Date" name="Date" placeholder="Enter Product Date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="Price" name="Price" placeholder="Enter Price Of The Product">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" id="Quantity" name="Quantity" placeholder="Enter Quantity Of The Product">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Total Amount</label>
                                                        <input type="number" class="form-control" id="TotalAmount" name="TotalAmount" placeholder="Enter Total Amount Of The Product">
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
        var Description = $('#Description').val();
        var Date = $('#Date').val();
        var Price = $('#Price').val();
        var Quantity = $('#Quantity').val();
        var TotalAmount = $('#TotalAmount').val();

        $.ajax({
            url: '../../api/products/insert.php',
            type: 'POST',
            data: {
                Name: Name,
                Description: Description,
                Date: Date,
                Price: Price,
                Quantity: Quantity,
                TotalAmount: TotalAmount
            },
            success: function(response) {
                alert("Products Added Successfully");
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