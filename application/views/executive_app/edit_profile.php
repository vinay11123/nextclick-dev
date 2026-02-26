<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<!--main contents start-->
<main class="content_wrapper">
    <!--page title start-->

    <!--page title end-->
    <div class="container-fluid">
        <!-- state start-->
        <div class="row">

            <div class="col-12">
                <div class="panel">

                    <div class="panel-content panel-about">


                        <form action="<?php echo base_url('executive/edit_profile/c'); ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="profilePicture">Profile Picture <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control" id="profilePicture" name="profile_image"
                                        accept="image/*" capture="camera">
                                </div>



                                <div class="col-md-4">
                                    <?php
                                    $upload_path = 'uploads/profile_image/';
                                    $user_id = $this->session->userdata('user_id');
                                    $image_path = $upload_path . 'profile_' . $user_id . '.jpg';

                                    if (file_exists($image_path)) {
                                        ?>
                                        <img src="<?php echo base_url($image_path) . '?t=' . date('d-m-Y H:i:s'); ?>"
                                            style="width: 70px; height: 70px;">
                                    <?php } ?><br>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <?php echo form_error('profile_image', '<div class="text-danger">', '</div>'); ?>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="firstName">First Name <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control alphabet_space" id="firstName"
                                        name="first_name"
                                        value="<?php echo set_value('first_name', isset($first_name) ? $first_name : ''); ?>">
                                    <?php echo form_error('first_name', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="lastName">Last Name <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control alphabet_space" id="lastName"
                                        name="last_name"
                                        value="<?php echo set_value('last_name', isset($last_name) ? $last_name : ''); ?>">
                                    <?php echo form_error('last_name', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-3">
                                    <!-- This div is empty to maintain the alignment -->
                                </div>
                                <div class="col-md-4">
                                    <?php if ($error_message = $this->session->flashdata('error_message')): ?>
                                        <div class="text-danger"><?php echo $error_message; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <!-- This div is empty to maintain the alignment -->
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" id="submitLoaderButton"
                                        class="btn btn-success btn-flat m-b-30 m-t-30">Next</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
        </div>
        <!-- state end-->
    </div>
</main>
<!--main contents end-->
</div>
<!-- Content_right_End -->
<!-- Footer -->

<!-- Footer_End -->
</div>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>