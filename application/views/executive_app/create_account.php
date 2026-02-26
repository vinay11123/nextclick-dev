<!DOCTYPE html>
<html lang="en">

<head>
    <title>NEXT CLICK Executive</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>executive_app/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/ionicons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/simple-line-icons.css" rel="stylesheet"
        type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>executive_app/assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>executive_app/assets/css/responsive.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/jquery.min.js"></script>

    <style>
        #exec_loader {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }
    </style>
</head>

<body>
    <div id="exec_loader">
        <i class="fa fa-spinner fa-spin fa-3x"></i>
    </div>

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="logo">
                    <a href="#">
                        <strong class="logo_icon">
                            <img src="<?php echo base_url() ?>executive_app/assets/images/small-logo.png?v=1" alt="">
                        </strong>
                        <span class="logo-default">
                            <img src="<?php echo base_url() ?>executive_app/assets/images/logo.png?v=1" alt="">
                        </span>
                    </a>
                </div>
                <?php
                $phone_data = $this->session->flashdata('phone_number');
                ?>
                <div class="login-form">
                    <h3 class="text-center mb-4">Create Account</h3>
                    <form action="<?php echo base_url('create_account/submit'); ?>" method="POST"
                        enctype="multipart/form-data">

                        <input type="hidden" class="form-control" id="phone_number" name="phone_number"
                            value="<?php echo $phone_data ?? $phone_number ?? ''; ?>">

                        <div class="form-group">
                            <label for="profilePicture">Profile Picture <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="profilePicture" name="profile_image"
                                accept="image/*" capture="camera">
                            <?php echo form_error('profile_image', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="firstName">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control alphabet_space" id="firstName" name="first_name"
                                value="<?php echo set_value('first_name'); ?>">
                            <?php echo form_error('first_name', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control alphabet_space" id="lastName" name="last_name"
                                value="<?php echo set_value('last_name'); ?>">
                            <?php echo form_error('last_name', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo set_value('email'); ?>">
                            <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                            <?php echo form_error('confirm_password', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <?php if ($error_message = $this->session->flashdata('error_message')): ?>
                                <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                        </div>


                        <button type="submit" id="submitLoaderButton"
                            class="btn btn-success btn-flat m-b-30 m-t-30">Next</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url() ?>executive_app/assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url() ?>executive_app/assets/js/custom.js" type="text/javascript"></script>
    <script>
        $(document).on("input", ".alphabet_space", function () {
            this.value = this.value.replace(/[0-9~`!@#$%^&*()_+-=;',.?><:}{"]/g, "");
        });

        $(document).ready(function () {
            $("#submitLoaderButton").click(function () {
                $("#exec_loader").show();

                $(window).on('load', function () {
                    $("#exec_loader").hide();
                });
            });
        });
    </script>
</body>

</html>