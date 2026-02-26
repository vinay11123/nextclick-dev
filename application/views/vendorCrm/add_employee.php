<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <div class="main-body">
                <div class="page-wrapper">

                    <!-- Page-body start -->
                    <div class="page-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="text-center">Add Users</h4>
                                <form class="needs-validation" novalidate=""
                                    action="<?php echo base_url('add_vehicle/c/0'); ?>" method="post">
                                    <div class="card-header">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>First Name</label> <input type="text" name="first_name"
                                                    placeholder="First Name" class="form-control" required="">
                                                <?php //echo base_url('employee/c/0'); ?>
                                                <div class="invalid-feedback">Enter First Name?</div>
                                                <?php echo form_error('first_name', '<div style="color:red">', '</div>') ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Last Name</label> <input type="text" name="last_name"
                                                    placeholder="Last Name" class="form-control" required="">
                                                <div class="invalid-feedback">Enter Last Name?</div>
                                                <?php echo form_error('last_name', '<div style="color:red">', '</div>') ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Mobile No.</label> <input type="tel" id="mobile" name="phone"
                                                    maxlength="10" placeholder="Mobile No" class="form-control"
                                                    required="">
                                                <div class="invalid-feedback">Enter Mobile number?</div>
                                                <div style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;display:none"
                                                    id="invalid">Mobile no already exists in our database!!</div>
                                                <?php echo form_error('phone', '<div style="color:red">', '</div>') ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Email ID</label> <input type="email" name="email"
                                                    placeholder="Email ID" class="form-control" required="">
                                                <div class="invalid-feedback">Enter Email ID?</div>
                                                <?php echo form_error('email', '<div style="color:red">', '</div>') ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Password</label> <input type="password" class="form-control"
                                                    placeholder="Password" name="password" id="Password" required="">
                                                <?php echo form_error('password', '<div style="color:red">', '</div>') ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Confirm Password</label> <input type="password"
                                                    class="form-control" name="confirm_password" id="ConfirmPassword"
                                                    placeholder="Confirm Password" required="">
                                                <?php echo form_error('confirm_password', '<div style="color:red">', '</div>') ?>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Role(Group)</label> <br>
                                                <select id="example-getting-started" class="form-control" name="role[]">
                                                    <option value="" selected disabled>--select--</option>
                                                    <?php foreach ($groups as $group): ?>
                                                        <option value="<?php echo $group['id']; ?>">
                                                            <?php echo $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Select the role for User?</div>
                                            </div>

                                            <div class="form-group col-md-12">

                                                <button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
                                            </div>
                                        </div>


                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    $(document).ready(function () {
        $("#mobile").keyup(function () {
            var search = $(this).val();

            if (search != "") {

                $.ajax({
                    url: '<?= site_url() ?>admin/check_number',
                    type: 'post',
                    data: { search: search },
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response == null) {
                            document.getElementById("invalid").style.display = "none";
                            document.getElementById("btnSubmit").disabled = false;



                        }
                        else {
                            document.getElementById("invalid").style.display = "block";
                            document.getElementById("btnSubmit").disabled = true;
                        }

                    }
                });
            }
        });
    });
</script>

<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>