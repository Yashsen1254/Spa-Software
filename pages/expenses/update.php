<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$Id = $_POST['Id'];
$expense = selectOne("SELECT * FROM Expenses WHERE Id = $Id");

?>
<body data-sidebar="dark">
<div id="layout-wrapper">

    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm"><img src="assets/images/logo.svg" alt="" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="" height="17"></span>
                    </a>
                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm"><img src="assets/images/logo-light.svg" alt="" height="22"></span>
                        <span class="logo-lg"><img src="assets/images/logo-light.png" alt="" height="19"></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Update Expenses</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active"></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Product Name" value="<?= $expense['Name'] ?>">
                                        <input type="hidden" class="form-control" id="Id" name="Id" value="<?= $expense['Id'] ?>">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description" value="<?= $expense['Description'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date</label>
                                                <input type="date" class="form-control" id="Date" name="Date" value="<?= $expense['Date'] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Volume</label>
                                                <input type="text" class="form-control" id="Volume" name="Volume" placeholder="Enter Volume" value="<?= $expense['Volume'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Price</label>
                                                <input type="number" class="form-control" id="Price" name="Price" placeholder="Enter Price" oninput="calculateTotal()" value="<?= $expense['Price'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" id="Quantity" name="Quantity" placeholder="Enter Quantity" oninput="calculateTotal()"     value="<?= $expense['Quantity'] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <input type="number" class="form-control" id="TotalAmount" name="TotalAmount" placeholder="Total Amount" readonly value="<?= $expense['TotalAmount'] ?>">
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-primary w-md" onclick="insertData(); return false;">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div> <!-- main-content -->
</div>

<div class="rightbar-overlay"></div>

<?php include pathOf("includes/scripts.php"); ?>

<script>
    function calculateTotal() {
        const price = parseFloat(document.getElementById("Price").value) || 0;
        const quantity = parseFloat(document.getElementById("Quantity").value) || 0;
        document.getElementById("TotalAmount").value = (price * quantity).toFixed(2);
    }

    function insertData() {
        const Id = $('#Id').val();
        const Name = $('#Name').val();
        const Description = $('#Description').val();
        const Date = $('#Date').val();
        const Volume = $('#Volume').val();
        const Price = $('#Price').val();
        const Quantity = $('#Quantity').val();
        const TotalAmount = $('#TotalAmount').val();

        $.ajax({
            url: '../../api/expenses/update.php',
            type: 'POST',
            data: {
                Id: Id,
                Name: Name,
                Description: Description,
                Date: Date,
                Volume: Volume,
                Price: Price,
                Quantity: Quantity,
                TotalAmount: TotalAmount
            },
            success: function (response) {
                alert("Expenses Updated Successfully");
                window.location.href = 'index.php';
            },
            error: function (xhr, status, error) {
                alert("Error occurred: " + error);
            }
        });
    }
</script>

<?php include pathOf("includes/pageend.php"); ?>
