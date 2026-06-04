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
                                                    <?php while($row = mysqli_fetch_assoc($users)) { ?>
                                                        <a class="d-block p-2 border-bottom"
                                                           href="chat.php?user=<?php echo urlencode($row['staff_id']); ?>">
                                                            <strong>
                                                                <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                                                            </strong>
                                                            <br>
                                                            <small><?php echo $row['role']; ?></small>
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
                                                        <div style="height:350px; overflow-y:auto; border:1px solid #ddd; padding:15px; margin-bottom:15px;">
                                                            <?php if ($messages && mysqli_num_rows($messages) > 0) { ?>
                                                                <?php while($msg = mysqli_fetch_assoc($messages)) { ?>
                                                                    <div style="margin-bottom:12px; text-align:<?php echo ($msg['sender_id'] == $currentUser) ? 'right' : 'left'; ?>;">
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

                                                        <form method="POST" action="../chat_send.php">
                                                            <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selectedUser); ?>">
                                                            <input type="hidden" name="redirect_path" value="staff/chat.php">

                                                            <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan..." required></textarea>
                                                            <br>
                                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <p>Pilih user untuk mulai chat.</p>
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