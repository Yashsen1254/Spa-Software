<?php
require '../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// SALES (Membership + Clients)
$dailyMembershipSales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Membership WHERE StartDate = CURDATE() AND IsDelete = 1")['Total'] ?? 0;
$dailyClientSales = selectOne("SELECT SUM(Price) AS Total FROM Clients WHERE Date = CURDATE()")['Total'] ?? 0;
$dailySales = $dailyMembershipSales + $dailyClientSales;

$monthlyMembershipSales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Membership WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['Total'] ?? 0;
$monthlyClientSales = selectOne("SELECT SUM(Price) AS Total FROM Clients WHERE MONTH(Date) = MONTH(CURDATE()) AND YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$monthlySales = $monthlyMembershipSales + $monthlyClientSales;

$yearlyMembershipSales = selectOne("SELECT SUM(AmountPaid) AS Total FROM Membership WHERE YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['Total'] ?? 0;
$yearlyClientSales = selectOne("SELECT SUM(Price) AS Total FROM Clients WHERE YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$yearlySales = $yearlyMembershipSales + $yearlyClientSales;

// EXPENSES + SALARIES
$dailyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE Date = CURDATE()")['Total'] ?? 0;
$dailySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE SalaryPaidDate = CURDATE()")['Total'] ?? 0;
$dailyTotalExpenses = $dailyExpenses + $dailySalaries;

$monthlyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE MONTH(Date) = MONTH(CURDATE()) AND YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$monthlySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE MONTH(SalaryPaidDate) = MONTH(CURDATE()) AND YEAR(SalaryPaidDate) = YEAR(CURDATE())")['Total'] ?? 0;
$monthlyTotalExpenses = $monthlyExpenses + $monthlySalaries;

$yearlyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$yearlySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE YEAR(SalaryPaidDate) = YEAR(CURDATE())")['Total'] ?? 0;
$yearlyTotalExpenses = $yearlyExpenses + $yearlySalaries;

function formatCurrency($amount) {
    return '₹' . number_format($amount, 2);
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
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        </div>
                    </div>
                </div>

                <!-- Sales Cards -->
                <div class="row">
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Daily Sales</p>
                        <h4 class="mb-0"><?= formatCurrency($dailySales) ?></h4>
                    </div></div></div>
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Monthly Sales</p>
                        <h4 class="mb-0"><?= formatCurrency($monthlySales) ?></h4>
                    </div></div></div>
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Yearly Sales</p>
                        <h4 class="mb-0"><?= formatCurrency($yearlySales) ?></h4>
                    </div></div></div>
                </div>

                <!-- Expenses Cards -->
                <div class="row">
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Daily Expenses</p>
                        <h4 class="mb-0"><?= formatCurrency($dailyTotalExpenses) ?></h4>
                    </div></div></div>
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Monthly Expenses</p>
                        <h4 class="mb-0"><?= formatCurrency($monthlyTotalExpenses) ?></h4>
                    </div></div></div>
                    <div class="col-md-4"><div class="card mini-stats-wid"><div class="card-body">
                        <p class="text-muted fw-medium">Yearly Expenses</p>
                        <h4 class="mb-0"><?= formatCurrency($yearlyTotalExpenses) ?></h4>
                    </div></div></div>
                </div>

                <!-- PDF Downloads -->
                <div class="row">
                    <?php
                    $pdfs = [
                        "Daily Sales" => "dailySales",
                        "Monthly Sales" => "monthlySales",
                        "Yearly Sales" => "yearlySales",
                        "Daily Expenses" => "dailyExpenses",
                        "Monthly Expenses" => "monthlyExpenses",
                        "Yearly Expenses" => "yearlyExpenses",
                        "Daily Profit & Loss" => "dailyProfitLoss",
                        "Monthly Profit & Loss" => "monthlyProfitLoss",
                        "Yearly Profit & Loss" => "yearlyProfitLoss"
                    ];
                    foreach ($pdfs as $label => $file):
                    ?>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium"><?= $label ?></p>
                                <a href="./pdfs/<?= $file ?>.php" class="btn btn-danger btn-sm mt-2">Download <?= $label ?> PDF</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");
?>
