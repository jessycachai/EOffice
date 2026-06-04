<?php
include 'includes/config.php';

// Check departments
echo "=== DEPARTMENTS ===\n";
$result = mysqli_query($conn, "SELECT id, department_name FROM tbldepartments");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['id'] . ", Name: " . $row['department_name'] . "\n";
}

echo "\n=== STAFF BY DEPARTMENT ===\n";
$result = mysqli_query($conn, "
    SELECT emp.emp_id, emp.first_name, emp.last_name, emp.department, dept.department_name
    FROM tblemployees emp
    LEFT JOIN tbldepartments dept ON emp.department = dept.id
    ORDER BY emp.department
");

while ($row = mysqli_fetch_assoc($result)) {
    echo "Staff: " . $row['first_name'] . " " . $row['last_name'] . 
         " | Dept ID: " . $row['department'] . 
         " | Dept Name: " . $row['department_name'] . "\n";
}

echo "\n=== TEST FILTER QUERY ===\n";
// Test with department 1
$selectedDepartmentId = 1;
$query = "SELECT emp_id, first_name, last_name FROM tblemployees WHERE department = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $selectedDepartmentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count = mysqli_num_rows($result);
echo "Records with department ID 1: " . $count . "\n";
?>
