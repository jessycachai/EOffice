<?php include('../includes/header.php') ?>

<?php
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}

$page_name = "chat";
$currentUser = $_SESSION['sstaff_id'];
$selectedUser = isset($_GET['user']) ? $_GET['user'] : null;

$users = mysqli_query($conn, "
    SELECT staff_id, first_name, last_name, role
    FROM tblemployees
    WHERE staff_id != '$currentUser'
    ORDER BY first_name ASC
");

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
.chat-box {
    height: 450px;
    overflow-y: auto;
    border: 1px solid #e5e5e5;
    padding: 15px;
    background: #f3f6fb;
    margin-bottom: 15px;
    border-radius: 10px;
}

.chat-row {
    width: 100%;
    clear: both;
    margin-bottom: 15px;
}

.chat-left {
    justify-content: flex-start;
}

.chat-right {
    justify-content: flex-end;
}

.chat-bubble {
    max-width: 70%;
    padding: 12px 15px;
    border-radius: 16px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.chat-me {
    float: right;
    max-width: 70%;
    background: #01a9ac;
    color: #fff;
    padding: 12px;
    border-radius: 15px 15px 5px 15 px;
}

.chat-other {
    float: left;
    max-width: 70%;
    background: #fff;
    color: #333;
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 15px 15px 15px 5px;
}

.chat-name {
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 5px;
}

.chat-message {
    font-size: 15px;
    line-height: 1.4;
}

.chat-time {
    font-size: 11px;
    opacity: 0.75;
    text-align: right;
    margin-top: 6px;
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
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <div class="d-inline">
                                                    <h4>Internal Chat</h4>
                                                    <span>Chat antar user EOffice</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="page-body">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Daftar User</h5>
                                                </div>
                                                <div class="card-block">
                                                    <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                                                        <a class="d-block p-2 border-bottom"
                                                           href="chat.php?user=<?php echo urlencode($row['staff_id']); ?>">
                                                            <strong>
                                                                <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                                            </strong>
                                                            <br>
                                                            <small><?php echo htmlspecialchars($row['role']); ?></small>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Pesan</h5>
                                                </div>
                                                
                                                <div class="card-block">
                                                    <?php if ($selectedUser) { ?>
                                                        <div id="chat-box" class="chat-box">
                                                            <?php if ($messages && mysqli_num_rows($messages) > 0) { ?>
                                                                <?php while ($msg = mysqli_fetch_assoc($messages)) { ?>
                                                                    <?php 
                                                                        $isMe = $msg['sender_id'] == $currentUser; 
                                                                        $senderName = trim($msg['sender_first_name'] . ' ' . $msg['sender_last_name']);
                                                                    ?>

                                                                     <div class="chat-row">

                                                                        <div class="<?= $isMe ? 'chat-me' : 'chat-other' ?>">

                                                                            <div class="chat-name">
                                                                                <?= htmlspecialchars($senderName) ?>
                                                                            </div>

                                                                            <div class="chat-message">
                                                                                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                                                            </div>

                                                                            <div class="chat-time">
                                                                                <?= date('H:i', strtotime($msg['created_at'])) ?>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <p class="text-muted">Belum ada pesan.</p>
                                                            <?php } ?>
                                                        </div>

                                                        <form method="POST" action="../chat_send.php">
                                                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($selectedUser); ?>">
                                                            <input type="hidden" name="redirect_path" value="admin/chat.php">

                                                            <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan..." required></textarea>
                                                            <br>

                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="feather icon-send"></i> Kirim
                                                            </button>
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
</body>
</html>