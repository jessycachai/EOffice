<?php
include_once('../includes/config.php');

$events = [];

$query = mysqli_query($conn, "SELECT * FROM tblcalendar_events ORDER BY start_date ASC");

while ($row = mysqli_fetch_assoc($query)) {

    $color = '#1b00ff';

    if ($row['type'] == 'cuti') {
        $color = '#f39c12';
    } elseif ($row['type'] == 'libur') {
        $color = '#e74c3c';
    }

    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start_date'],
        'end' => $row['end_date'],
        'backgroundColor' => $color,
        'borderColor' => $color
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
?>