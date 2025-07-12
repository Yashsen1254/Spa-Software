<?php
require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// Get employee details
$Id = $_POST["Id"] ?? 0;
$employees = selectOne("SELECT * FROM Employee WHERE Id = ?", [$Id]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveSalary'])) {
    $salaryData = $_POST['salary'] ?? [];

    if (!empty($salaryData)) {
        try {
            foreach ($salaryData as $row) {
                $SalaryPaidDate = $row['date'];
                $GivenSalary = (int)$row['amount'];
                $PaymentMode = $row['mode'];

                // ✅ Increment GivenSalary safely
                execute("
                    UPDATE Employee 
                    SET 
                        GivenSalary = COALESCE(GivenSalary, 0) + ?, 
                        SalaryPaidDate = ?, 
                        PaymentMode = ?
                    WHERE Id = ?
                ", [$GivenSalary, $SalaryPaidDate, $PaymentMode, $Id]);
            }

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

// Fetch all employees for summary
$allEmployees = select("SELECT Id, Name, GivenSalary FROM Employee");

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

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18"><?= htmlspecialchars($employees["Name"]) ?></h4>
                                <h4 class="mb-sm-0 font-size-18">Total Salary: ₹<?= $employees["TotalSalary"] ?></h4>                                
                                <h4 class="mb-sm-0 font-size-18">Given Salary: ₹<?= $employees["GivenSalary"] ?></h4>
                                <h4 class="mb-sm-0 font-size-18 pending-salary">
                                    Pending Salary: ₹<?= max(($employees["TotalSalary"] - $employees["GivenSalary"]), 0) ?>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if (!empty($successMessage)) : ?>
                        <div class="alert alert-success"><?= $successMessage ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMessage)) : ?>
                        <div class="alert alert-danger"><?= $errorMessage ?></div>
                    <?php endif; ?>

                    <!-- Salary Form -->
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
                                                <!-- Rows will be added dynamically -->
                                            </tbody>
                                        </table>

                                        <button type="button" class="btn btn-primary mb-2" onclick="addRow()">Add Row</button>
                                        <button type="submit" class="btn btn-success">Save Salary</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Summary Table -->
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
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="rightbar-overlay"></div>

    <script>
        let rowIndex = 0;
        let totalSalary = <?= (int)$employees['TotalSalary'] ?>;
        let alreadyGiven = <?= (int)$employees['GivenSalary'] ?>;

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
            updatePendingSalary();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
            updatePendingSalary();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#salaryTableBody tr');
            rowIndex = 0;
            rows.forEach((row) => {
                row.cells[0].innerText = ++rowIndex;
            });
        }

        function updatePendingSalary() {
            let additionalGiven = 0;
            const amounts = document.querySelectorAll('input[name^="salary"][name$="[amount]"]');

            amounts.forEach(input => {
                const value = parseInt(input.value) || 0;
                additionalGiven += value;
            });

            const totalGiven = alreadyGiven + additionalGiven;
            const pendingSalary = Math.max(totalSalary - totalGiven, 0);
            document.querySelector('.pending-salary').innerText = `Pending Salary: ₹${pendingSalary}`;
        }
    </script>
</body>
