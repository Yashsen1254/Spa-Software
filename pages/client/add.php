<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$employees = select("SELECT * FROM Employee");

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
                                    <h4 class="mb-sm-0 font-size-18">Add Client</h4>

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
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-email-input" class="form-label">Mobile</label>
                                                        <input type="number" class="form-control" id="Mobile" name="Mobile" placeholder="Enter Mobile Of The Client">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-email-input" class="form-label">Therapy</label>
                                                        <input type="text" class="form-control" id="Therapy" name="Therapy" placeholder="Enter Therapy Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-password-input" class="form-label">Therapist Name</label>
                                                        <select name="EmployeeId" id="EmployeeId" class="form-select">
                                                            <option value="">Select Therapist</option>
                                                            <?php foreach ($employees as $employee): ?>
                                                                <option value="<?= $employee['Id'] ?>"><?= $employee['Name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Date</label>
                                                        <input type="date" class="form-control" id="Date" name="Date" placeholder="Enter Date">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">In Time</label>
                                                        <input type="time" class="form-control" id="InTime" name="InTime" placeholder="Enter In Time">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Out Time</label>
                                                        <input type="time" class="form-control" id="OutTime" name="OutTime" placeholder="Enter Out Time">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Massage</label>
                                                        <select name="Massage" id="Massage" class="form-select">
                                                            <option value="">Select Massage</option>
                                                            <option value="Swedish">Swedish</option>
                                                            <option value="Deep Tissue">Deep Tissue</option>
                                                            <option value="Aromatherapy">Aromatherapy</option>
                                                            <option value="Hot Stone">Hot Stone</option>
                                                            <option value="Thai">Thai</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="Price" name="Price" placeholder="Enter Price">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">Payment Mode</label>
                                                        <select name="PaymentMode" id="PaymentMode" class="form-select">
                                                            <option value="">Select Payment Mode</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Credit Card">Credit Card</option>
                                                            <option value="Debit Card">Debit Card</option>
                                                            <option value="UPI">UPI</option>
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
        var Therapy = $('#Therapy').val();
        var EmployeeId = $('#EmployeeId').val();
        var Date = $('#Date').val();
        var InTime = $('#InTime').val();
        var OutTime = $('#OutTime').val();
        var Price = $('#Price').val();
        var Massage = $('#Massage').val();
        var Payment = $('#PaymentMode').val();

        $.ajax({
            url: '../../api/client/insert.php',
            type: 'POST',
            data: {
                Name: Name,
                Mobile: Mobile,
                Therapy: Therapy,
                EmployeeId: EmployeeId,
                Date: Date,
                InTime: InTime,
                OutTime: OutTime,
                Price: Price,
                Payment: Payment,
                Massage: Massage
            },
            success: function(response) {
                alert("Client Added Successfully");
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