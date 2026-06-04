<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['slogin'])) {
    header("Location: index.php");
    exit();
}

$sender_id = $_SESSION['sstaff_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$stmt = $conn->prepare("
    INSERT INTO tblchat_messages (sender_id, receiver_id, message)
    VALUES (?, ?, ?)
");

$stmt->bind_param("sss", $sender_id, $receiver_id, $message);
$stmt->execute();

$redirect = isset($_POST['redirect_path']) ? $_POST['redirect_path'] : 'chat.php';

header("Location: " . $redirect . "?user=" . urlencode($receiver_id));
exit();