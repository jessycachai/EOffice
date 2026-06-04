<?php
include 'includes/config.php';

// Check if table exists
$tables_result = mysqli_query($conn, "SHOW TABLES LIKE 'tblemployees'");
if (mysqli_num_rows($tables_result) == 0) {
    echo "ERROR: Table 'tblemployees' does not exist!";
    exit;
}

// Count records
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tblemployees");
$row = mysqli_fetch_assoc($result);
echo "Total staff in database: " . $row['total'] . "\n\n";

// Show first few records
$result = mysqli_query($conn, "SELECT emp_id, first_name, last_name, email_id FROM tblemployees LIMIT 5");
echo "First 5 records:\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['emp_id'] . ", Name: " . $row['first_name'] . " " . $row['last_name'] . ", Email: " . $row['email_id'] . "\n";
}
?>
