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
                    <h3 class="text-center mb-4">SIGN UP WITH MOBILE</h3>
                    <p class="text-center"><img src="<?php echo base_url() ?>executive_app/assets/images/mobile.png"
                            alt="" style="width: 75%; margin: 0 auto;" /></p>
                    <form action="<?php echo base_url('register/submit'); ?>" method="POST">
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" name='phone_number' class="form-control numeric"
                                placeholder="Enter Phone Number" value="<?php echo $_POST['phone_number'] ?>">
                            <?php echo form_error('phone_number', '<div class="text-danger">', '</div>'); ?>
                            <?php if ($error_message = $this->session->flashdata('error_message')): ?>
                                <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" id="submitLoaderButton"
                            class="btn btn-success btn-flat m-b-30 m-t-30">Continue</button>
                    </form>
                    <p class="text-right mt-2"><a href="<?php echo base_url('executive/login'); ?>">Login</a></p>
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
        $(document).on("input", ".numeric", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
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