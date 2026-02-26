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
                <div class="login-form">
                    <h3 class="text-center mb-4">Executive Login</h3>
                    <form action="<?php echo base_url('login_otp_phone/submit'); ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" name='phone_number' value="<?php echo set_value('phone_number'); ?>"
                                class="form-control numeric" placeholder="Enter Phone Number">
                            <?php echo form_error('phone_number', '<div class="text-danger">', '</div>'); ?>
                            <?php if ($error_message = $this->session->flashdata('error_message')): ?>
                                <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-15 text-right">
                            <a href="<?php echo base_url('forgot_password/r'); ?>" class="text-sm text-right">Forgot
                                Password?</a>
                        </div>
                        <button type="submit" id="submitLoaderButton" class="btn btn-success btn-flat m-b-30 m-t-30">
                            Send OTP</button>
                        <div class="social-login-content">
                            <div class="social-button">
                                <a href="<?php echo base_url('executive/login'); ?>" type="button"
                                    class="btn social facebook btn-flat btn-addon mb-3">
                                    Login with Password</a>

                            </div>
                        </div>
                        <div class="register-link mt-15 text-center">
                            <p>Don't have an account?
                                <a href="<?php echo base_url('register/r'); ?>"> Sign Up</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#submitLoaderButton").click(function () {
                $("#exec_loader").show();

                $(window).on('load', function () {
                    $("#exec_loader").hide();
                });
            });
        });
    </script>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url() ?>executive_app/assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url() ?>executive_app/assets/js/custom.js" type="text/javascript"></script>
    <script>
        $(document).on("input", ".numeric", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    </script>

</body>

</html>