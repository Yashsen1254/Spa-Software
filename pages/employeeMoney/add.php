<?php

require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// Get employee details
$index = 0;
$Id = $_POST["Id"] ?? 0;
$employees = selectOne("SELECT * FROM Employee WHERE Id = ?", [$Id]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveSalary'])) {
    $salaryData = $_POST['salary'] ?? [];

    if (!empty($salaryData)) {
        try {
            $totalGiven = 0; // Track total given amount in this submission

            foreach ($salaryData as $row) {
                $SalaryPaidDate = $row['date'];
                $GivenSalary = (int)$row['amount'];
                $PaymentMode = $row['mode'];

                // Reduce TotalSalary by GivenSalary
                $totalGiven += $GivenSalary;

                // Update Employee Table (only store latest entry)
                execute("
                    UPDATE Employee 
                    SET SalaryPaidDate = ?, GivenSalary = ?, PaymentMode = ?
                    WHERE Id = ?
                ", [$SalaryPaidDate, $GivenSalary, $PaymentMode, $Id]);
            }

            // Deduct total given amount from TotalSalary
            $newTotalSalary = (int)$employees['TotalSalary'] - $totalGiven;
            if ($newTotalSalary < 0) {
                $newTotalSalary = 0; // prevent negative salary
            }

            execute("
                UPDATE Employee 
                SET TotalSalary = ?
                WHERE Id = ?
            ", [$newTotalSalary, $Id]);

            $successMessage = "Salaries saved successfully!";
            // Refresh employee data after update
            $employees = selectOne("SELECT * FROM Employee WHERE Id = ?", [$Id]);

        } catch (Exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    } else {
        $errorMessage = "No salary data provided!";
    }
}

// Fetch all employees to show in summary table
$allEmployees = select("SELECT Id, Name, GivenSalary FROM Employee WHERE GivenSalary IS NOT NULL");

?>


<body data-sidebar="dark">

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
                                <h4 class="mb-sm-0 font-size-18"><?= htmlspecialchars($employees["Name"]) ?></h4>
                                <h4 class="mb-sm-0 font-size-18">Total Salary: ₹<?= $employees["TotalSalary"] ?></h4>                                
                                <h4 class="mb-sm-0 font-size-18">Given Salary: ₹<?= $employees["GivenSalary"] ?></h4>
                                <h4 class="mb-sm-0 font-size-18">Pending Salary: ₹<?= ($employees["TotalSalary"] - $employees["GivenSalary"]) ?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php if (!empty($successMessage)) : ?>
                        <div class="alert alert-success"><?= $successMessage ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMessage)) : ?>
                        <div class="alert alert-danger"><?= $errorMessage ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="Id" value="<?= $employees['Id'] ?>">
                                        <input type="hidden" name="saveSalary" value="1">

                                        <table id="salaryTable" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>Date</th>
                                                    <th>Amount Taken</th>
                                                    <th>Mode</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="salaryTableBody">
                                                <!-- Rows will be added here dynamically -->
                                            </tbody>
                                        </table>

                                        <button type="button" class="btn btn-primary mb-2" onclick="addRow()">Add Row</button>
                                        <button type="submit" class="btn btn-success">Save Salary</button>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <!-- ✅ Summary Table -->
                    <div class="row">
                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Salary Summary</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Name</th>
                                                <th>Given Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; foreach ($allEmployees as $emp) : ?>
                                                <tr>
                                                    <td><?= $sr++ ?></td>
                                                    <td><?= htmlspecialchars($emp['Name']) ?></td>
                                                    <td>₹<?= $emp['GivenSalary'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end summary row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->

    </div>
    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>


    <script>
let rowIndex = 0;
let totalSalary = <?= (int)$employees['TotalSalary'] ?>; // PHP TotalSalary

function addRow() {
    rowIndex++;
    const tableBody = document.getElementById('salaryTableBody');

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${rowIndex}</td>
        <td>
            <input type="date" name="salary[${rowIndex}][date]" class="form-control" required>
        </td>
        <td>
            <input type="number" name="salary[${rowIndex}][amount]" class="form-control" placeholder="Enter Amount" required oninput="updatePendingSalary()">
        </td>
        <td>
            <select name="salary[${rowIndex}][mode]" class="form-control" required>
                <option value="">Select Mode</option>
                <option value="Cash">Cash</option>
                <option value="Online">Online</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button>
        </td>
    `;

    tableBody.appendChild(newRow);
    updatePendingSalary(); // recalculate after adding row
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateRowNumbers();
    updatePendingSalary(); // recalculate after removing row
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#salaryTableBody tr');
    rowIndex = 0;
    rows.forEach((row) => {
        row.cells[0].innerText = ++rowIndex;
    });
}

function updatePendingSalary() {
    let givenTotal = 0;
    const amounts = document.querySelectorAll('input[name^="salary"][name$="[amount]"]');

    amounts.forEach(input => {
        const value = parseInt(input.value) || 0;
        givenTotal += value;
    });

    const pendingSalary = Math.max(totalSalary - givenTotal, 0);
    document.querySelector('.pending-salary').innerText = `Pending Salary: ₹${pendingSalary}`;
}
</script>

