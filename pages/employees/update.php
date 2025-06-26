<?php
require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$Id = $_POST["Id"];
$employee = selectOne("SELECT * FROM Employee WHERE Id = $Id");

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
                            <input type="text" class="form-control" name="Name" value="<?= $employee['Name'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile No.</label>
                            <input type="number" class="form-control" name="Mobile" value="<?= $employee['Mobile'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="Address" value="<?= $employee['Address'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" name="Age" value="<?= $employee['Age'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="Email" value="<?= $employee['Email'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation</label>
                            <input type="text" class="form-control" name="Relation" value="<?= $employee['Relation'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Name</label>
                            <input type="text" class="form-control" name="RelationName" value="<?= $employee['RelationName'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Mobile</label>
                            <input type="number" class="form-control" name="RelationMobile" value="<?= $employee['RelationMobile'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relation Address</label>
                            <input type="text" class="form-control" name="RelationAddress" value="<?= $employee['RelationAddress'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Card Number</label>
                            <input type="number" class="form-control" name="AddharCardNumber" value="<?= $employee['AddharCardNumber'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary Paid</label>
                            <input type="number" class="form-control" name="SalaryPaid" value="<?= $employee['SalaryPaid'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary Due</label>
                            <input type="number" class="form-control" name="SalaryDue"  value="<?= $employee['SalaryDue'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee Image</label>
                            <input type="file" class="form-control" name="ImageFileName" required>
                            <img src="<?= urlOf("assets/uploads/" . $employee['ImageFileName']) ?>" alt="" height="200" width="250">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Card Image</label>
                            <input type="file" class="form-control" name="AddharCardImageFileName" required>
                            <img src="<?= urlOf("assets/uploads/" . $employee['AddharCardImageFileName']) ?>" alt="" height="200" width="250">
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
                alert("Employee updated successfully!");
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
