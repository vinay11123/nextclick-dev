<!DOCTYPE html>
<html lang="en">

<head>
  <title>Verify OTP</title>

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
           <a href="<?php echo base_url('auth/logout'); ?>"><img src="<?php echo base_url() ?>vendor_crm/files/assets/images/logo.png" alt="NextClick"></a>
          </div>
          <div class="auth-box card">
            <div class="card-block">
              <div class="row m-b-20">
                <div class="col-md-12">
                  <h3 class="text-center">Enter OTP</h3>
                  <P align="center">We will help you to grow your business using NextClick</P>
                  <?php if (!empty($this->session->flashdata('message'))) : ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $this->session->flashdata('message') ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
				<form method="post" action="<?= site_url('auth/verify_otp_auth') ?>">
					<label for="otp">Enter OTP</label>
					<input type="text" name="otp" id="otp" required>
					<button type="submit">Verify OTP</button>
				</form>


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