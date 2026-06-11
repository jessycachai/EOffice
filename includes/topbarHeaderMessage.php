<?php
$topbar_query = mysqli_query($conn, "
    SELECT emp_id, first_name, middle_name, last_name, image_path
    FROM tblemployees
    WHERE emp_id = '$session_id'
    LIMIT 1
");

$topbar_user = mysqli_fetch_assoc($topbar_query);

$topbar_name = trim(
    $topbar_user['first_name'] . ' ' .
    $topbar_user['middle_name'] . ' ' .
    $topbar_user['last_name']
);

$topbar_image = !empty($topbar_user['image_path'])
    ? $topbar_user['image_path']
    : '../files/assets/images/avatar-4.jpg';
?>
<?php
$notif_count = 0;
$notifications = [];

if (isset($_SESSION['srole']) && ($_SESSION['srole'] == 'Admin' || $_SESSION['srole'] == 'Manager')) {
    $notif_count = 0;
}
?>
<ul class="nav-right">

    <li class="header-notification">
        <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
                <i class="feather icon-bell"></i>

                <?php if ($notif_count >= 0) { ?>
                    <span class="badge bg-c-pink"><?= $notif_count ?></span>
                <?php } ?>
            </div>

            <ul class="show-notification notification-view dropdown-menu"
                data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">

                <li>
                    <h6>Notifications</h6>
                    <?php if ($notif_count > 0) { ?>
                        <label class="label label-danger">New</label>
                    <?php } ?>
                </li>

                <?php if (!empty($notifications)) { ?>
                    <?php foreach ($notifications as $notif) { ?>
                        <li>
                            <a href="<?= $notif['link'] ?>">
                                <div class="media">
                                    <img class="d-flex align-self-center img-radius"
                                        src="../files/assets/images/avatar-4.jpg"
                                        alt="Notification Image">

                                    <div class="media-body">
                                        <h5 class="notification-user"><?= $notif['name'] ?></h5>
                                        <p class="notification-msg"><?= $notif['message'] ?></p>
                                        <span class="notification-time"><?= $notif['time'] ?></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <li>
                        <div class="media">
                            <div class="media-body">
                                <p class="notification-msg">Tidak ada notifikasi baru</p>
                            </div>
                        </div>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </li>

    <li class="user-profile header-notification">
        <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= $topbar_image ?>" class="img-radius" alt="User-Profile-Image">
                <span><?= $topbar_name ?></span>
                <i class="feather icon-chevron-down"></i>
            </div>

            <ul class="show-notification profile-notification dropdown-menu"
                data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <li>
                    <a href="staff_detailed.php?id=<?= $session_id ?>&view=2">
                        <i class="feather icon-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <i class="feather icon-log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </li>

</ul>