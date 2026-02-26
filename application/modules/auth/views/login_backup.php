<!-- General CSS Files -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/app.min.css">
<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bundles/prism/prism.css">

<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/components.css">
<!-- Custom style CSS -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom.css">
<style type="text/css">
  @media (min-width: 768px) {
    .col-12.col-sm-8.offset-sm-2.col-md-6.offset-md-3.col-lg-6.offset-lg-3.col-xl-4.offset-xl-4>. .card.card-primary {
      overflow-x: hidden;
      /* position: fixed; */
      position: relative;
      /* min-height: 519px; */
      min-height: 100%;
      background-size: cover;
      width: 544px;
    }
  }

  i.fa.fa-eye,
  i.fa.fa-eye-slash {
    position: relative;
    bottom: 31px;
    /* float: right; */
    left: 261px;
  }
</style>
<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="card card-primary">
            <div class="card-header">
              <h4>Login</h4>
            </div>
            <?php if (!empty($this->session->flashdata('message'))) : ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $this->session->flashdata('message') ?>
              </div>
            <?php endif; ?>
            <div class="card-body">
              <form method="POST" action="<?php echo base_url("auth/login"); ?>" class="needs-validation" novalidate="">
                <div class="form-group">
                  <label for="email">User id</label>
                  <input id="email" type="text" class="form-control" value="<?php echo set_value('identity') ?>" name="identity" tabindex="1" required autofocus>
                  <div class="invalid-feedback">Please fill in your email/userid</div>
                  <?php echo form_error('identity', '<div style="color:red">', '</div>') ?>
                </div>
                <div class="form-group" id="show_hide_password">
                  <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                    <div class="float-right">
                      <a href="<?php echo base_url('auth/forgot_password') ?>" class="text-small">
                        Forgot Password?
                      </a>
                    </div>
                  </div>
                  <input id="password" type="password" class="form-control pwd" name="password" value="<?php echo set_value('password') ?>" tabindex="2" required>
                  <div class="input-group-addon">
                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                  </div>

                  <div class="invalid-feedback">please fill in your password</div>
                  <?php echo form_error('password', '<div style="color:red">', '</div>') ?>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" value="1" id="remember-me">
                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Login
                  </button>
                </div>
              </form>

            </div>
          </div>
          <!--             <div class="mt-5 text-muted text-center"> -->
          <!--               Don't have an account? <a href="auth-register.html">Create One</a> -->
          <!--             </div> -->
        </div>
      </div>
    </div>
  </section>
</div>

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
  //$( document ).ready(function() {
  //   $(".reveal").on('click',function() {
  //     var $pwd = $(".pwd");
  //     if ($pwd.attr('type') === 'password') {
  //         $pwd.attr('type', 'text');
  //     } else {
  //         $pwd.attr('type', 'password');
  //     }
  // }); 
  //});
  $(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
      event.preventDefault();
      if ($('#show_hide_password input').attr("type") == "text") {
        $('#show_hide_password input').attr('type', 'password');
        $('#show_hide_password i').addClass("fa-eye-slash");
        $('#show_hide_password i').removeClass("fa-eye");
      } else if ($('#show_hide_password input').attr("type") == "password") {
        $('#show_hide_password input').attr('type', 'text');
        $('#show_hide_password i').removeClass("fa-eye-slash");
        $('#show_hide_password i').addClass("fa-eye");
      }
    });
  });
</script>