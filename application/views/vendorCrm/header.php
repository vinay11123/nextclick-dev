<!DOCTYPE html>
<html lang="en">

<head>
    <title>NEXT CLICK CRM</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <link rel="icon" href="<?php echo base_url() ?>vendor_crm/files/assets/images/favicon.png" type="image/x-icon">
    <script type="text/javascript"
        src="<?php echo base_url() ?>vendor_crm/files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/assets/js/pace.min.js"></script>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- waves.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>vendor_crm/files/assets/pages/waves/css/waves.min.css"
        type="text/css" media="all">
    <!-- feather icon -->
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/assets/icon/feather/css/feather.css">
    <!-- font-awesome-n -->
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/assets/css/font-awesome-n.min.css">
    <!-- Redial css -->
    <!-- <link rel="stylesheet" href="files/assets/pages/chart/radial/css/radial.css" type="text/css" media="all">-->

    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/assets/pages/data-table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/css/widget.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url() ?>vendor_crm/files/bower_components/sweetalert/css/sweetalert.css">
    <link href="<?php echo base_url() ?>assets/bundles/select2/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <!-- [ Header ] start -->
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a href="<?php echo base_url() ?>vendor_crm/dashboard">
                            <img class="img-fluid"
                                src="<?php echo base_url() ?>vendor_crm/files/assets/images/logo1.png" alt="Logo" />
                        </a>
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu"></i>
                        </a>
                        <a class="mobile-options waves-effect waves-light">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>
                    <?php
                    if ($this->ion_auth->get_user_id() != 1) {
                        $notification_sql = "SELECT * FROM notifications where notified_user_id=" . $this->ion_auth->get_user_id() . " and notification_type_id=9 order by id desc";
                        $query = $this->db->query($notification_sql);
                        $notifications = $query->result_array();
                        $notification_pending_sql = "SELECT count(id) pending_notification_count FROM notifications where notified_user_id=" . $this->ion_auth->get_user_id() . " and notification_type_id=9 and status=1 order by id desc";
                        $query_pending = $this->db->query($notification_pending_sql);
                        $notifications_pending = $query_pending->result_array();
                    }
                    ?>
                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                    <i class="full-screen feather icon-maximize"></i>
                                </a>
                            </li>

                        </ul>
                        <ul class="nav-right">
                            <?php if ($this->ion_auth->get_user_id() != 1) { ?>
                                <li class="header-notification">
                                    <div class="dropdown-primary dropdown">
                                        <div class="dropdown-toggle" aria-expanded="false">
                                            <a href="<?php echo base_url('vendor_crm/dashboard/notification') ?>">
                                                <i class="feather icon-bell"></i>
                                            </a>
                                            <span
                                                class="badge bg-c-red"><?php if ($notifications_pending[0]['pending_notification_count'] > 0)
                                                    echo $notifications_pending[0]['pending_notification_count'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                            <?php } ?>

                            <li class="user-profile header-notification">
                                <div class="dropdown-primary dropdown">
                                    <div class="dropdown-toggle" data-toggle="dropdown">
                                        <?php
                                        echo $user->first_name . ' ' . $user->last_name;

                                        ?>

                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu"
                                        data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        <li class="drp-u-details">
                                            <img src="<?php echo base_url() ?>vendor_crm/files/assets/images/avatar-4.jpg"
                                                class="img-radius" alt="User-Profile-Image">
                                            <span>
                                                <?php echo $user->first_name; ?>
                                            </span>
                                            <!-- <a href="logout.php" class="dud-logout" title="Logout">
                                                <i class="feather icon-log-out"></i> -->
                                            </a>
                                        </li>

                                        <!-- <li>
                                            <a href="profile.php">
                                                <i class="feather icon-user"></i> Profile
                                            </a>
                                        </li> -->

                                        <li>
                                            <a href="<?php echo base_url(); ?>auth/logout">
                                                <i class="feather icon-lock"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- [ chat user list ] start -->