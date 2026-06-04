<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['slogin'])) {
    header("Location: index.php");
    exit();
}

$page_name = "chat";
$currentUser = $_SESSION['sstaff_id'];
$selectedUser = isset($_GET['user']) ? $_GET['user'] : null;

// Ambil daftar user
$users = mysqli_query($conn, "
    SELECT staff_id, first_name, last_name, role
    FROM tblemployees
    WHERE staff_id != '$currentUser'
    ORDER BY first_name ASC
");

// Ambil pesan jika user dipilih
$messages = null;
if ($selectedUser) {
    $stmt = $conn->prepare("
        SELECT *
        FROM tblchat_messages
        WHERE 
            (sender_id = ? AND receiver_id = ?)
            OR
            (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param("ssss", $currentUser, $selectedUser, $selectedUser, $currentUser);
    $stmt->execute();
    $messages = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internal Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .chat-container {
            display: flex;
            gap: 20px;
        }

        .user-list {
            width: 250px;
            border: 1px solid #ccc;
            padding: 15px;
        }

        .user-list a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .user-list a:hover {
            background: #f1f1f1;
        }

        .chat-area {
            width: 600px;
            border: 1px solid #ccc;
            padding: 15px;
        }

        .messages {
            height: 350px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .message {
            margin-bottom: 12px;
        }

        .me {
            text-align: right;
            color: #0a7;
        }

        .other {
            text-align: left;
            color: #333;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        button {
            padding: 10px 20px;
            background: #00a8a8;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Internal Chat</h2>
<p>Halo, <?php echo $_SESSION['sfirstname']; ?></p>

<div class="chat-container">

    <div class="user-list">
        <h3>Daftar User</h3>

        <?php while($row = mysqli_fetch_assoc($users)) { ?>
            <a href="chat.php?user=<?php echo urlencode($row['staff_id']); ?>">
                <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                <br>
                <small><?php echo $row['role']; ?></small>
            </a>
        <?php } ?>
    </div>

    <div class="chat-area">
        <?php if ($selectedUser) { ?>
            <h3>Chat</h3>

            <div class="messages">
                <?php if ($messages && mysqli_num_rows($messages) > 0) { ?>
                    <?php while($msg = mysqli_fetch_assoc($messages)) { ?>
                        <div class="message <?php echo ($msg['sender_id'] == $currentUser) ? 'me' : 'other'; ?>">
                            <strong>
                                <?php echo ($msg['sender_id'] == $currentUser) ? 'Saya' : 'Dia'; ?>
                            </strong>
                            <br>
                            <?php echo htmlspecialchars($msg['message']); ?>
                            <br>
                            <small><?php echo $msg['created_at']; ?></small>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>Belum ada pesan.</p>
                <?php } ?>
            </div>

            <form method="POST" action="chat_send.php">
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selectedUser); ?>">
                <textarea name="message" placeholder="Tulis pesan..." required></textarea>
                <br><br>
                <button type="submit">Kirim</button>
            </form>

        <?php } else { ?>
            <h3>Pilih user untuk mulai chat</h3>
        <?php } ?>
    </div>

</div>

</body>
</html>