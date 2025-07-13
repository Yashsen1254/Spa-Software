<?php
require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// ✅ Get employee ID from POST or GET
$Id = $_POST["Id"] ?? $_GET["id"] ?? 0;
$Id = (int)$Id;

// Redirect if no valid employee ID
if ($Id <= 0) {
    echo "<script>alert('Invalid Employee ID'); window.location.href='all-employees.php';</script>";
    exit;
}

// ✅ Fetch specific employee details
$employee = selectOne("SELECT * FROM Employee WHERE Id = ?", [$Id]);
if (!$employee) {
    echo "<script>alert('Employee not found'); window.location.href='all-employees.php';</script>";
    exit;
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveSalary'])) {
    $salaryData = $_POST['salary'] ?? [];

    if (!empty($salaryData)) {
        try {
            foreach ($salaryData as $row) {
                $SalaryPaidDate = $row['date'];
                $GivenSalary = (int)$row['amount'];
                $PaymentMode = $row['mode'];

                if ($GivenSalary > 0 && !empty($SalaryPaidDate) && !empty($PaymentMode)) {
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
            }

            $successMessage = "Salaries saved successfully!";
            // Refresh employee data after update
            $employee = selectOne("SELECT * FROM Employee WHERE Id = ?", [$Id]);

        } catch (Exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    } else {
        $errorMessage = "No salary data provided!";
    }
}
?>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18"><?= htmlspecialchars($employee["Name"]) ?>'s Salary</h4>
                                <h5>Total Salary: ₹<?= $employee["TotalSalary"] ?></h5>
                                <h5>Given Salary: ₹<?= $employee["GivenSalary"] ?></h5>
                                <h5 class="pending-salary">
                                    Pending Salary: ₹<?= max(($employee["TotalSalary"] - $employee["GivenSalary"]), 0) ?>
                                </h5>
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
                                        <input type="hidden" name="Id" value="<?= $employee['Id'] ?>">
                                        <input type="hidden" name="saveSalary" value="1">

                                        <table id="salaryTable" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>Date</th>
                                                    <th>Amount Paid</th>
                                                    <th>Payment Mode</th>
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

                    <!-- Salary Summary for Specific Employee -->
                    <div class="row">
                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Salary Summary (<?= htmlspecialchars($employee['Name']) ?>)</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Given Salary</th>
                                                <th>Total Salary</th>
                                                <th>Pending Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= htmlspecialchars($employee['Name']) ?></td>
                                                <td>₹<?= $employee['GivenSalary'] ?></td>
                                                <td>₹<?= $employee['TotalSalary'] ?></td>
                                                <td>₹<?= max(($employee['TotalSalary'] - $employee['GivenSalary']), 0) ?></td>
                                            </tr>
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
        let totalSalary = <?= (int)$employee['TotalSalary'] ?>;
        let alreadyGiven = <?= (int)$employee['GivenSalary'] ?>;

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
