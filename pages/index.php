<?php

require '../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

$dailySales = selectOne("SELECT SUM(AmountPaid) AS DailySales FROM Clients WHERE StartDate = CURDATE() AND IsDelete = 1")['DailySales'] ?? 0;
$monthlySales = selectOne("SELECT SUM(AmountPaid) AS MonthlySales FROM Clients WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['MonthlySales'] ?? 0;
$yearlySales = selectOne("SELECT SUM(AmountPaid) AS YearlySales FROM Clients WHERE YEAR(StartDate) = YEAR(CURDATE()) AND IsDelete = 1")['YearlySales'] ?? 0;

$dailyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE Date = CURDATE()")['Total'] ?? 0;
$dailySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE SalaryPaidDate = CURDATE()")['Total'] ?? 0;
$dailyTotalExpenses = $dailyExpenses + $dailySalaries;

$monthlyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE MONTH(Date) = MONTH(CURDATE()) AND YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$monthlySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE MONTH(SalaryPaidDate) = MONTH(CURDATE()) AND YEAR(SalaryPaidDate) = YEAR(CURDATE())")['Total'] ?? 0;
$monthlyTotalExpenses = $monthlyExpenses + $monthlySalaries;

$yearlyExpenses = selectOne("SELECT IFNULL(SUM(TotalAmount), 0) AS Total FROM Expenses WHERE YEAR(Date) = YEAR(CURDATE())")['Total'] ?? 0;
$yearlySalaries = selectOne("SELECT IFNULL(SUM(SalaryPaid), 0) AS Total FROM Employee WHERE YEAR(SalaryPaidDate) = YEAR(CURDATE())")['Total'] ?? 0;
$yearlyTotalExpenses = $yearlyExpenses + $yearlySalaries;

?>
<body data-sidebar="dark">

<!-- Begin page -->
<div id="layout-wrapper">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Daily Expenses</p>
                                <h4 class="mb-0">₹<?= $dailyTotalExpenses ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Monthly Sales</p>
                                <h4 class="mb-0">₹<?= $monthlyTotalExpenses ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Yearly Expenses</p>
                                <h4 class="mb-0">₹<?= $yearlyTotalExpenses ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Daily Sales</p>
                                <h4 class="mb-0">₹<?= $dailySales ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Monthly Sales</p>
                                <h4 class="mb-0">₹<?= $monthlySales ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Yearly Sales</p>
                                <h4 class="mb-0">₹<?= $yearlySales ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Daily Expenses</p>
                                <a href="./pdfs/dailyExpenses.php" class="btn btn-danger btn-sm mt-2">Download Daily Expenses PDF</a>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Monthly Expenses</p>
                                <a href="./pdfs/monthlyExpenses.php" class="btn btn-danger btn-sm mt-2">Download Monthly Expenses PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Yearly Expenses</p>
                                <a href="./pdfs/yearlyExpenses.php" class="btn btn-danger btn-sm mt-2">Download Yearly Expenses PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Daily Sales</p>
                                <a href="./pdfs/dailySales.php" class="btn btn-danger btn-sm mt-2">Download Daily Sales PDF</a>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Monthly Sales</p>
                                <a href="./pdfs/monthlySales.php" class="btn btn-danger btn-sm mt-2">Download Monthly Sales PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Yearly Sales</p>
                                <a href="./pdfs/yearlySales.php" class="btn btn-danger btn-sm mt-2">Download Yearly Sales PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Daily Profit & Loss</p>
                                <a href="./pdfs/dailyProfitLoss.php" class="btn btn-danger btn-sm mt-2">Download Daily Profit & Loss PDF</a>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Monthly Profit & Loss</p>
                                <a href="./pdfs/monthlyProfitLoss.php" class="btn btn-danger btn-sm mt-2">Download Monthly Profit & Loss PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <p class="text-muted fw-medium">Yearly Profit & Loss</p>
                                <a href="./pdfs/yearlyProfitLoss.php" class="btn btn-danger btn-sm mt-2">Download Yearly Profit & Loss PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include pathOf("includes/scripts.php");
include pathOf("includes/pageend.php");
?>