<?php include('../includes/header.php') ?>

<?php
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}

$page_name = "chat";
$currentUser = $_SESSION['sstaff_id'];
$selectedUser = $_GET['user'] ?? null;

$users = mysqli_query($conn, "
    SELECT staff_id, first_name, last_name, role, image_path
    FROM tblemployees
    WHERE staff_id != '$currentUser'
    ORDER BY first_name ASC
");

$userInfo = null;
if ($selectedUser) {
    $userStmt = $conn->prepare("
        SELECT first_name, last_name, role, image_path
        FROM tblemployees
        WHERE staff_id = ?
        LIMIT 1
    ");
    $userStmt->bind_param("s", $selectedUser);
    $userStmt->execute();
    $userInfo = $userStmt->get_result()->fetch_assoc();
}

$messages = null;
if ($selectedUser) {
    $stmt = $conn->prepare("
        SELECT 
            m.*,
            s.first_name AS sender_first_name,
            s.last_name AS sender_last_name
        FROM tblchat_messages m
        LEFT JOIN tblemployees s ON m.sender_id = s.staff_id
        WHERE 
            (m.sender_id = ? AND m.receiver_id = ?)
            OR
            (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC
    ");

    $stmt->bind_param("ssss", $currentUser, $selectedUser, $selectedUser, $currentUser);
    $stmt->execute();
    $messages = $stmt->get_result();
}
?>

<style>
.chat-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.chat-user:hover {
    background: #f5f7fb;
    text-decoration: none;
}

.chat-user.active {
    background: #e9f8f8;
    border-left: 4px solid #01a9ac;
}

.chat-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-box {
    height: 480px;
    overflow-y: auto;
    background: #f5f7fb;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.message-row {
    width: 100%;
    margin-bottom: 12px;
    overflow: hidden;
}

.message-bubble {
    display: inline-block;
    max-width: 70%;
    padding: 12px 14px;
    border-radius: 15px;
    word-wrap: break-word;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.message-right {
    text-align: right;
}

.message-left {
    text-align: left;
}

.bubble-me {
    background: #01a9ac;
    color: #fff;
    border-radius: 15px 15px 5px 15px;
}

.bubble-other {
    background: #fff;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 15px 15px 15px 5px;
}

.sender-name {
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 5px;
}

.message-text {
    font-size: 14px;
    line-height: 1.5;
}

.message-time {
    font-size: 11px;
    opacity: .75;
    margin-top: 6px;
    text-align: right;
}

.chat-input-wrap {
    display: flex;
    gap: 10px;
}

.chat-input-wrap textarea {
    resize: none;
}
</style>

<body>
<?php include('../includes/loader.php') ?>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php') ?>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                <?php include('../includes/sidebar.php') ?>

                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
                        <div class="main-body">
                            <div class="page-wrapper">

                                <div class="page-header">
                                    <div class="page-header-title">
                                        <h4>Internal Chat</h4>
                                        <span>Chat antar user E-Office</span>
                                    </div>
                                </div>

                                <div class="page-body">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Daftar User</h5>
                                                </div>
                                                <div class="card-block" style="padding:0;">
                                                    <?php while ($row = mysqli_fetch_assoc($users)) { 
                                                        $avatar = !empty($row['image_path']) ? $row['image_path'] : '../files/assets/images/avatar-4.jpg';
                                                        $active = ($selectedUser == $row['staff_id']) ? 'active' : '';
                                                    ?>
                                                        <a class="chat-user <?= $active; ?>" href="chat.php?user=<?= urlencode($row['staff_id']); ?>">
                                                            <img src="<?= htmlspecialchars($avatar); ?>" class="chat-avatar" alt="avatar">
                                                            <div>
                                                                <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong><br>
                                                                <small><?= htmlspecialchars($row['role']); ?></small>
                                                            </div>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>
                                                        <?php if ($userInfo) { ?>
                                                            Chat dengan <?= htmlspecialchars($userInfo['first_name'] . ' ' . $userInfo['last_name']); ?>
                                                        <?php } else { ?>
                                                            Pesan
                                                        <?php } ?>
                                                    </h5>
                                                </div>

                                                <div class="card-block">
                                                    <?php if ($selectedUser) { ?>

                                                        <div id="chat-box" class="chat-box">
                                                            <?php if ($messages && mysqli_num_rows($messages) > 0) { ?>
                                                                <?php while ($msg = mysqli_fetch_assoc($messages)) { 
                                                                    $isMe = ($msg['sender_id'] == $currentUser);
                                                                    $senderName = trim($msg['sender_first_name'] . ' ' . $msg['sender_last_name']);
                                                                ?>
                                                                    <div class="message-row <?= $isMe ? 'message-right' : 'message-left'; ?>">
                                                                        <div class="message-bubble <?= $isMe ? 'bubble-me' : 'bubble-other'; ?>">
                                                                            <div class="sender-name">
                                                                                <?= htmlspecialchars($senderName); ?>
                                                                            </div>

                                                                            <div class="message-text">
                                                                                <?= nl2br(htmlspecialchars($msg['message'])); ?>
                                                                            </div>

                                                                            <div class="message-time">
                                                                                <?= date('H:i', strtotime($msg['created_at'])); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <div class="text-center text-muted p-5">
                                                                    Belum ada pesan.
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                        <form method="POST" action="../chat_send.php">
                                                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($selectedUser); ?>">
                                                            <input type="hidden" name="redirect_path" value="admin/chat.php">

                                                            <div class="chat-input-wrap">
                                                                <textarea name="message" class="form-control" rows="2" placeholder="Tulis pesan..." required></textarea>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="feather icon-send"></i>
                                                                </button>
                                                            </div>
                                                        </form>

                                                    <?php } else { ?>
                                                        <div class="text-center text-muted p-5">
                                                            <h5>Pilih user untuk mulai chat</h5>
                                                        </div>
                                                    <?php } ?>
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
</div>

<?php include('../includes/scripts.php') ?>

<script>
window.onload = function() {
    var chatBox = document.getElementById('chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
};
</script>

</body>
</html>