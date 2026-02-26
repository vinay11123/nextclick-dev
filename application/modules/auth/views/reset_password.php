<!-- General CSS Files -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/app.min.css">
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/bundles/prism/prism.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/components.css"> 
<!-- Custom style CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/custom.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
<style>
  .iee,.ptxt{
    position: relative;
   bottom: 30px;
    left: 354px;
    /* bottom: 14px;
    left: 188px; */
}
form i {
    /* margin-left: -30px;
    cursor: pointer; */
    margin-left: 271px;
    cursor: pointer;
    position: relative;
    top: -27px;
  }
  </style>
<div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Reset Password</h4>
              </div>
              <?php if(!empty($this->session->flashdata('message'))):?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo $this->session->flashdata('message')?>
                  </div>
              <?php endif;?>
              <div class="card-body">
                <form method="POST" action="<?php echo base_url('auth/reset_password/' . $code);?>" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">New Password</label>
                    <input id="email" type="password" class="form-control" value="<?php echo set_value('new')?>" name="new" tabindex="1" placeholder="New Password" required autofocus>
                    <div class="invalid-feedback">Please fill new password</div>
                    <?php echo form_error('new', '<div style="color:red">', '</div>')?>
                  </div>
                  <input type="hidden" name="id" value="<?php echo (isset($_GET['id']))? $_GET['id']:0?>" />
                  <div class="form-group pass_show">
                    <label for="email">Confirm Password</label>
                    <input id="email" type="password" class="form-control confirmpwd" value="<?php echo set_value('new_confirm')?>" name="new_confirm" tabindex="1" placeholder="Confirm Password" required autofocus>
                    <i class="bi bi-eye-slash togglePassword" id=""></i>
                    <!-- <div class="input-group-addon">
                    <a href="">
                    <i class="fa fa-eye-slash iee" aria-hidden="true"></i>
                    </a>
                   </div>-->
                    <div class="invalid-feedback">Please fill confirm password</div>
                    <?php echo form_error('new_confirm', '<div style="color:red">', '</div>')?>
                  </div>
                    <?php echo form_input($user_id);?>
            <?php echo form_hidden($csrf); ?>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Submit
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
  <!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
 <script>
$(document).ready(function(){
  $('.pass_show').append('<i class="fa fa-eye-slash iee" aria-hidden="true"></i>');
});
$(document).on('click','.pass_show .iee', function(){ 
  $(this).prev().prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; });
});
</script>-->
<script >
      
const togglePassword = document.querySelector('.togglePassword');
const password = document.querySelector('.confirmpwd');
      togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye eye slash icon
    this.classList.toggle('bi-eye');
});
      </script>