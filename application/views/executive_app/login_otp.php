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

    <?php $phone_data = ''; ?>
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
                <div id="flashMessage"></div>
                <div class="login-form">
                    <h3 class="text-center mb-4"><a class="btn-primary btn-sm" href="<?php echo base_url('register'); ?>">Back</a> Verify
                        Phone</h3>
                    <p class="text-center small text-success mt-20">One Time Password (OTP) has been sent to your mobile
                        number</p>
                    <?php
                    $phone_data = $this->session->flashdata('phone_number');
                    ?>
                    <h2 class="text-center mb-30 mt-30">(+91)
                        <?php echo $phone_data ?? $phone_number ?? ''; ?>
                    </h2>
                    <form action="<?php echo base_url('login_otp/submit'); ?>" method="POST"
                        enctype="multipart/form-data">

                        <input type="hidden" name="phone_number" id="phone_number"
                            value="<?php echo $phone_data ?? $phone_number ?? ''; ?>">

                        <div class="form-group">
                            <label>Enter OTP</label>
                            <input type="text" id="otp" name="otp" class="form-control numeric" placeholder="Enter OTP"
                                value="<?php echo set_value('otp'); ?>">
                            <?php echo form_error('otp', '<div class="text-danger">', '</div>'); ?>
                            <?php if ($error_message = $this->session->flashdata('error_message')): ?>
                                <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                        </div>

                        <p class="text-danger text-center"><a href="#" id="sendOTP"
                                data-phone="<?php echo $phone_data ?? $phone_number ?? ''; ?>"
                                class="text-danger">Resend OTP</a></p>
                        <button type="submit" name="submit" id="submitLoaderButton"
                            class="btn btn-success btn-flat m-b-30 m-t-30">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#sendOTP').click(function (e) {
                e.preventDefault();
                var phone = $(this).data('phone');
                if (phone) {
                    $.ajax({
                        url: "<?php echo base_url('executive/login_resend_otp'); ?>",
                        type: "POST",
                        data: {
                            phone_number: phone
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                $('#flashMessage').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                            } else {
                                $('#flashMessage').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#flashMessage').html('<div class="alert alert-danger" role="alert">Error: Unable to send OTP.</div>');
                        }
                    });
                } else {
                    $('#flashMessage').html('<div class="alert alert-danger" role="alert">Error: Phone number is empty.</div>');
                }
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
            this.value = this.value.replace(/\D/g, '');
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