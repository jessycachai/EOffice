<?php
date_default_timezone_set('Asia/Jakarta');

include('../includes/header.php');

$userRole = $_SESSION['srole'] ?? '';
$sessionStaffId = $_SESSION['sstaff_id'] ?? '';
$sessionDepartment = $_SESSION['department'] ?? '';
$isSupervisor = $_SESSION['is_supervisor'] ?? 0;

if (!isset($_SESSION['slogin'])) {
    header('Location: ../index.php');
    exit();
}

if ($userRole !== 'Admin' && $userRole !== 'Manager' && $isSupervisor != 1) {
    header('Location: ../index.php');
    exit();
}

$fromDate = $_GET['from_date'] ?? date('Y-m-01');
$toDate = $_GET['to_date'] ?? date('Y-m-d');
$staffName = trim($_GET['staff_name'] ?? '');

$sql = "SELECT 
            a.*,
            e.first_name,
            e.middle_name,
            e.last_name,
            e.designation,
            d.department_name
        FROM tblattendance a
        LEFT JOIN tblemployees e ON a.staff_id = e.staff_id
        LEFT JOIN tbldepartments d ON e.department = d.id
        WHERE DATE(a.date) BETWEEN '$fromDate' AND '$toDate'";

if ($userRole === 'Manager') {
    $sql .= " AND e.department = '$sessionDepartment'";
} elseif ($isSupervisor == 1 && $userRole !== 'Admin') {
    $sql .= " AND e.supervisor_id = (
        SELECT emp_id FROM tblemployees 
        WHERE staff_id = '$sessionStaffId' 
        LIMIT 1
    )";
}
if (!empty($staffName)) {
    $staffName = mysqli_real_escape_string($conn, $staffName);

    $sql .= " AND (
                e.first_name LIKE '%$staffName%'
                OR e.middle_name LIKE '%$staffName%'
                OR e.last_name LIKE '%$staffName%'
              )";
}
$sql .= " ORDER BY a.date DESC, a.time_in DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<style>
.print-title{
    display:none;
}

@media print {

            @page {
                size: A4;
                margin: 5mm;
            }

            body{
                background:#fff !important;
                color:#000 !important;
                font-size:12px;
            }

            .pcoded-navbar,
            .pcoded-header,
            .pcoded-main-container .page-header,
            form,
            .btn,
            .card-header{
                display:none !important;
            }

            .pcoded-content,
            .pcoded-inner-content,
            .main-body,
            .page-wrapper,
            .page-body{
                margin:0 !important;
                padding:0 !important;
                width:100% !important;
            }

            .print-container {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }

    .card{
        border:none !important;
        box-shadow:none !important;
    }

    .print-title{
        display:block !important;
        text-align:center;
        margin-bottom:20px;
    }

    .print-title h2{
        margin:0;
        font-size:24px;
    }

    .print-title p{
        margin:3px 0;
    }

    table{
        width:100% !important;
        border-collapse:collapse !important;
    }

    table th,
    table td{
        border:1px solid #000 !important;
        padding:6px !important;
        font-size:11px;
    }

    table th{
        background:#f2f2f2 !important;
        font-weight:bold;
    }

    .badge{
        background:none !important;
        color:#000 !important;
        border:none !important;
    }
}
</style>

<body>

<?php include('../includes/loader.php')?>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>

    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php')?>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                <?php include('../includes/sidebar.php')?>

                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
                        <div class="main-body">
                            <div class="page-wrapper">

                                <div class="page-header">
                                    <div class="page-header-title">
                                        <h4>Attendance Report</h4>
                                    </div>
                                </div>

                                <div class="page-body">

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Filter Attendance</h5>
                                        </div>

                                        <div class="card-block">
                                            <form method="GET" class="row">
                                                <div class="col-md-4">
                                                    <label>From Date</label>
                                                    <input type="date" name="from_date" class="form-control" value="<?= $fromDate; ?>">
                                                </div>

                                                <div class="col-md-4">
                                                    <label>To Date</label>
                                                    <input type="date" name="to_date" class="form-control" value="<?= $toDate; ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Staff Name</label>
                                                    <input type="text"
                                                        name="staff_name"
                                                        class="form-control"
                                                        placeholder="Search staff..."
                                                        value="<?= htmlspecialchars($_GET['staff_name'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4" style="margin-top: 28px;">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <button type="button" onclick="window.print()" class="btn btn-success">
                                                        <i class="feather icon-printer"></i> Print Report
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="print-container">
                                        </div> <!-- print-container -->
                                    </div>
                                        

                                        <div class="print-title">
                                            <h2>ATTENDANCE REPORT</h2>

                                            <p>
                                                Period :
                                                <?= date('d M Y', strtotime($fromDate)); ?>
                                                -
                                                <?= date('d M Y', strtotime($toDate)); ?>
                                            </p>

                                            <p>
                                                Generated :
                                                <?= date('d M Y H:i'); ?>
                                            </p>

                                            <hr>
                                        </div>

                                        <div class="card-header">
                                            <h5>Attendance List</h5>
                                        </div>

                                        <div class="card-block table-border-style">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Staff ID</th>
                                                            <th>Name</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Date</th>
                                                            <th>Time In</th>
                                                            <th>Time Out</th>
                                                            <th>Total Hours</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $no = 1;
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $name = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);

                                                                if (empty($row['time_out'])) {
                                                                    $status = 'Not Clocked Out';
                                                                    $totalHours = '-';
                                                                } else {
                                                                    $timeIn = strtotime($row['date'] . ' ' . $row['time_in']);
                                                                    $timeOut = strtotime($row['date'] . ' ' . $row['time_out']);

                                                                    $diffSeconds = $timeOut - $timeIn;
                                                                    $totalHoursNumber = $diffSeconds / 3600;
                                                                    $totalHours = number_format($totalHoursNumber, 2) . ' Hours';

                                                                    if ($totalHoursNumber < 8) {
                                                                        $status = 'Incomplete';
                                                                    } else {
                                                                        $status = 'Completed';
                                                                    }
                                                                }
                                                        ?>
                                                                <tr>
                                                                    <td><?= $no++; ?></td>
                                                                    <td><?= $row['staff_id']; ?></td>
                                                                    <td><?= $name; ?></td>
                                                                    <td><?= $row['department_name']; ?></td>
                                                                    <td><?= $row['designation']; ?></td>
                                                                    <td><?= $row['date']; ?></td>
                                                                    <td><?= $row['time_in']; ?></td>
                                                                    <td><?= $row['time_out'] ?: '-'; ?></td>
                                                                    <td><?= $totalHours; ?></td>
                                                                    <td>
                                                                        <?php if ($status == 'Completed') { ?>
                                                                            <span class="badge badge-success">Completed</span>

                                                                        <?php } elseif ($status == 'Incomplete') { ?>
                                                                            <span class="badge badge-danger">Incomplete</span>

                                                                        <?php } else { ?>
                                                                            <span class="badge badge-warning">Not Clocked Out</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="9" class="text-center">No attendance data found</td></tr>';
                                                        }
                                                        ?>
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

            </div>
        </div>

    </div>
</div>

<?php include('../includes/scripts.php'); ?>

</body>
</html>