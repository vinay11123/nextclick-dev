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

                <!-- <p><a href="<?php echo base_url('executive/login'); ?>">Back</a></p> -->
                <h1 class="text-center mb-4" style="font-size: 50px;">CREATE ACCOUNT</h1>
                <h2 class="text-center text-danger mb-45">WELCOME TO NEXTCLICK</h2>
                <p class="text-center">To become an executive, please fill the KYC Details...</p>
                <div>

                    <a href="<?php echo base_url('kyc_details'); ?>" type="submit" id="submitLoaderButton"
                        class="btn btn-success btn-block btn-flat mb-30 m-t-30">Next</a>

                    <p class="text-center"><a href="<?php echo base_url('executive_app/authorize/kyc_logout'); ?>">Login</a></p>
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
</body>

</html>