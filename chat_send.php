<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['slogin'])) {
    header("Location: index.php");
    exit();
}

$sender_id = $_SESSION['sstaff_id'] ?? '';
$receiver_id = $_POST['receiver_id'] ?? '';
$message = trim($_POST['message'] ?? '');

if ($sender_id == '' || $receiver_id == '' || $message == '') {
    die("Chat Error: sender_id / receiver_id / message kosong");
}

$stmt = $conn->prepare("
    INSERT INTO tblchat_messages (sender_id, receiver_id, message)
    VALUES (?, ?, ?)
");

$stmt->bind_param("sss", $sender_id, $receiver_id, $message);

if (!$stmt->execute()) {
    die("Insert Error: " . $stmt->error);
}

$redirect = $_POST['redirect_path'] ?? 'admin/chat.php';

header("Location: " . $redirect . "?user=" . urlencode($receiver_id));
exit();