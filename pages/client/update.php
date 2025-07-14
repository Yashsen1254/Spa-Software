<?php
require '../../includes/init.php';
include pathOf("includes/header.php");
include pathOf("includes/navbar.php");

// ✅ Validate ID
if (!isset($_POST["Id"])) {
    echo "<script>alert('Client ID is missing!'); window.location.href='index.php';</script>";
    exit;
}

$Id = (int)$_POST["Id"];

// ✅ Fetch client data
$client = selectOne("SELECT * FROM Clients WHERE Id = ?", [$Id]);
if (!$client) {
    echo "<script>alert('Client not found!'); window.location.href='index.php';</script>";
    exit;
}

// ✅ Fetch dropdown data
$employees = select("SELECT * FROM Employee");
$massages = select("SELECT * FROM Massage");
?>
<body data-sidebar="dark">
<div id="layout-wrapper">
    <header id="page-topbar">
        <!-- Your header content -->
    </header>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Update Client</h4>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="updateForm" onsubmit="submitUpdate(event)">
                            <input type="hidden" id="Id" name="Id" value="<?= $client['Id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($client['Name']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Mobile</label>
                                    <input type="number" class="form-control" id="Mobile" name="Mobile" value="<?= htmlspecialchars($client['Mobile']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Room No</label>
                                    <input type="text" class="form-control" id="RoomNo" name="RoomNo" value="<?= htmlspecialchars($client['RoomNo']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Therapist</label>
                                    <select name="EmployeeId" id="EmployeeId" class="form-select" required>
                                        <option value="">Select Therapist</option>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= $employee['Id'] ?>" <?= $client['EmployeeId'] == $employee['Id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($employee['Name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" id="Date" name="Date" value="<?= $client['Date'] ?>" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">In Time</label>
                                    <input type="time" class="form-control" id="InTime" name="InTime" value="<?= $client['InTime'] ?>" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Out Time</label>
                                    <input type="time" class="form-control" id="OutTime" name="OutTime" value="<?= $client['OutTime'] ?>" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <label class="form-label">Massage</label>
                                    <select name="Massage" id="Massage" class="form-select" required>
                                        <?php foreach ($massages as $massage): ?>
                                            <option value="<?= htmlspecialchars($massage['Name']) ?>" <?= $client['Massage'] == $massage['Name'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($massage['Name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Price</label>
                                    <input type="number" class="form-control" id="Price" name="Price" value="<?= $client['Price'] ?>" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Payment Mode</label>
                                    <select name="PaymentMode" id="PaymentMode" class="form-select" required>
                                        <?php foreach (["Cash", "Credit Card", "Debit Card", "UPI"] as $mode): ?>
                                            <option value="<?= $mode ?>" <?= $client['PaymentMode'] == $mode ? 'selected' : '' ?>>
                                                <?= $mode ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-md">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script>
function submitUpdate(event) {
    event.preventDefault(); // ✅ Stop page reload
    $.ajax({
        url: '../../api/client/update.php',
        type: 'POST',
        data: $('#updateForm').serialize(),
        success: function(response) {
            if (response.status === "success") {
                alert(response.message);
                window.location.href = 'index.php';
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function() {
            alert("An error occurred during update.");
        }
    });
}
</script>
<?php include pathOf("includes/scripts.php"); ?>
<?php include pathOf("includes/pageend.php"); ?>
