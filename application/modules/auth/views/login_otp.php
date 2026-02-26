<!DOCTYPE html>
<html lang="en">

<head>
    <title>NextClick</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="<?php echo base_url() ?>vendor_crm/files/assets/images/favicon.png" type="image/x-icon">
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/assets/js/pace.min.js"></script>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- waves.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>vendor_crm/files/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <!-- feather icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/icon/feather/css/feather.css">
    <!-- font-awesome-n -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/css/font-awesome-n.min.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>vendor_crm/files/assets/css/pages.css">

</head>

<body themebg-pattern="theme2">
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->

                    <div class="text-center">
                        <img src="<?php echo base_url() ?>vendor_crm/files/assets/images/logo.png" alt="NextClick">
                    </div>
                    <div class="auth-box card" style="display:<?php if ($phone_no['value'] != '') echo 'none';else echo "block"; ?>">
                        <div class="card-block">
                            <div class="row m-b-20">
                                <div class="col-md-12">
                                    <h3 class="text-center">GROW YOUR BUSINESS </h3>
                                    <P align="center">We will help you to grow your business using NextClick</P>
                                    <?php if (!empty($this->session->flashdata('message'))) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $this->session->flashdata('message') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <form class="md-float-material form-material needs-validation" method="POST" action="<?php echo base_url("auth/login_otp"); ?>" novalidate="">
                                <div class="form-group form-primary">
                                    <input id="email" type="text" class="form-control" value="<?php echo set_value('phone_no') ?>" name="phone_no" required autofocus>
                                    <span class="form-bar"></span>
                                    <label class="float-label">Phone number</label>
                                    <div class="invalid-feedback">Please fill phone number</div>
                                    <?php echo form_error('phone_no', '<div style="color:red">', '</div>') ?>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12">
                                        <div class="forgot-phone text-right float-right">
                                            <a href="<?php echo base_url('auth/forgot_password') ?>" class="text-right f-w-600"> Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <!-- <a href="Dashboard.php" class="btn btn-warning btn-md btn-block waves-effect waves-light text-center m-b-20">Login</a> -->
                                        <button type="submit" class="btn btn-warning btn-md btn-block waves-effect waves-light text-center m-b-20">Get OTP</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="forgot-phone text-right float-right">
                                        <a href="<?php echo base_url('auth/login') ?>" class="text-right f-w-600 text-danger"> Login with password</a>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row">
                                <div class="col-md-9">
                                    <p class="text-inverse text-left m-b-0">Don't have account?</p>
                                    <p class="text-inverse text-left"><a href="register.php"><b>Signup Here</b></a></p>
                                </div>
                                <div class="col-md-3 fa-pull-right">
                                    <a class="btn btn-default btn-md  text-center" href="dashboard.php">Signup</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-box card" style="display:<?php if ($phone_no['value'] == '') echo 'none';else echo "block"; ?>">
                        <div class="card-block">
                            <div class="row m-b-20">
                                <div class="col-md-12">
                                    <h3 class="text-center">GROW YOUR BUSINESS</h3>
                                    <P align="center">We will help you to grow your business using NextClick</P>
                                    <?php if (!empty($this->session->flashdata('message'))) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $this->session->flashdata('message') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <form class="md-float-material form-material needs-validation" method="POST" action="<?php echo base_url("auth/verify_otp"); ?>" novalidate="">
                                <div class="form-group form-primary">
                                    <input id="email" type="text" class="form-control" value="<?php echo set_value('otp') ?>" name="otp" required autofocus>
                                    <span class="form-bar"></span>
                                    <label class="float-label">OTP</label>
                                    <div class="invalid-feedback">Please fill otp</div>
                                    <?php echo form_error('otp', '<div style="color:red">', '</div>') ?>
                                </div>
                                <input type="hidden" value="<?php if ($phone_no['value'] != '') echo $phone_no['value'] ?>" name="phone_number">
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <!-- <a href="Dashboard.php" class="btn btn-warning btn-md btn-block waves-effect waves-light text-center m-b-20">Login</a> -->
                                        <button type="submit" class="btn btn-warning btn-md btn-block waves-effect waves-light text-center m-b-20">Verify OTP</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="forgot-phone text-right float-right">
                                        <a href="<?php echo base_url('auth/login_otp') ?>" class="text-right f-w-600 text-danger"> Reenter Phone Number</a>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row">
                                <div class="col-md-9">
                                    <p class="text-inverse text-left m-b-0">Don't have account?</p>
                                    <p class="text-inverse text-left"><a href="register.php"><b>Signup Here</b></a></p>
                                </div>
                                <div class="col-md-3 fa-pull-right">
                                    <a class="btn btn-default btn-md  text-center" href="dashboard.php">Signup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>


    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- waves js -->
    <script src="<?php echo base_url() ?>vendor_crm/files/assets/pages/waves/js/waves.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>vendor_crm/files/assets/js/common-pages.js"></script>
</body>


</html>