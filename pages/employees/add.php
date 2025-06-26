<?php
require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");
?>

<body data-sidebar="dark">
<div id="layout-wrapper">
    <header id="page-topbar">
        <!-- You can keep your topbar content here -->
    </header>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h4 class="mb-4">Add Employee</h4>

                <form id="employeeForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile No.</label>
                            <input type="number" class="form-control" name="Mobile" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="Address">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" name="Age">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation</label>
                            <input type="text" class="form-control" name="Relation">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Name</label>
                            <input type="text" class="form-control" name="RelationName">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Mobile</label>
                            <input type="number" class="form-control" name="RelationMobile">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Address</label>
                            <input type="text" class="form-control" name="RelationAddress">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Card Number</label>
                            <input type="number" class="form-control" name="AddharCardNumber">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary Paid</label>
                            <input type="number" class="form-control" name="SalaryPaid">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary Due</label>
                            <input type="number" class="form-control" name="SalaryDue">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee Image</label>
                            <input type="file" class="form-control" name="ImageFileName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Card Image</label>
                            <input type="file" class="form-control" name="AddharCardImageFileName" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include pathOf("includes/scripts.php"); ?>

<script>
document.getElementById("employeeForm").addEventListener("submit", function (event) {
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: '../../api/employees/insert.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            console.log(res);
            if (res.success) {
                alert("Employee added successfully!");
                window.location.href = 'index.php';
            } else {
                alert("Insert failed: " + (res.message || "Unknown error"));
            }
        },
        error: function (xhr) {
            alert("AJAX error: " + xhr.responseText);
        }
    });
});
</script>

<?php include pathOf("includes/pageend.php"); ?>
