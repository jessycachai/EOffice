<?php include('../includes/header.php') ?>

<?php
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
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

                    <?php $page_name = "calendar"; ?>
                    <?php include('../includes/sidebar.php') ?>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">

                            <div class="main-body">
                                <div class="page-wrapper">

                                    <div class="page-header">
                                        <div class="page-block">
                                            <div class="row align-items-center">
                                                <div class="col-md-12">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10">Calendar</h5>
                                                    </div>

                                                    <ul class="breadcrumb">
                                                        <li class="breadcrumb-item">
                                                            <a href="index.php">
                                                                <i class="feather icon-home"></i>
                                                            </a>
                                                        </li>
                                                        <li class="breadcrumb-item">
                                                            <a href="calendar.php">Calendar</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Kalender E-Office</h5>
                                                        <span>Menampilkan event, cuti, dan hari libur</span>
                                                    </div>

                                                    <div class="card-block">
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="styleSelector"></div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include('../includes/scripts.php') ?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <style>
        #calendar {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
        }

        .fc-toolbar-title {
            font-size: 22px !important;
            font-weight: 600;
            color: #263544;
        }

        .fc-button {
            background-color: #4680ff !important;
            border-color: #4680ff !important;
            border-radius: 4px !important;
        }

        .fc-button:hover {
            background-color: #2f6fff !important;
            border-color: #2f6fff !important;
        }

        .fc-col-header-cell {
            background-color: #01a9ac;
            color: #ffffff;
            padding: 10px 0;
        }

        .fc-daygrid-event {
            border-radius: 4px;
            padding: 3px 5px;
            font-size: 13px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                height: 700,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },

                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    list: 'List'
                },

                events: 'calendar_events.php',

                eventClick: function (info) {
                    alert(info.event.title);
                }
            });

            calendar.render();
        });
    </script>

</body>
</html>