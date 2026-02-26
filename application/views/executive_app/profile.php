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
                <div>
                    <span class="pull-right">
                        <a href="<?php echo base_url('executive/edit_profile/r'); ?>" class="btn btn-primary">
                            Edit Profile
                        </a>
                    </span>
                    <div class="rounded-image">

                        <img src="<?php echo base_url('uploads/profile_image/profile_') . $id . '.jpg?t=' . date('d-m-Y H:i:s') ?>" alt="Profile Image">
                    </div>
                    <div>
                        <h3 class="h3 text-center"><?php echo $first_name . ' ' . $last_name; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="panel">

                    <div class="panel-content panel-about">
                        <?php if ($this->session->flashdata('success_message') != '') { ?>
                            <div class="text-center text-success"><?php echo $this->session->flashdata('success_message'); ?></div>
                        <?php };
                        // $success_message = '';
                        ?>
                        <table>
                           <tbody>

    <tr>
        <th>
            <i class="fa fa-briefcase"></i>
            <?= ($primary_intent == 'vendor') ? 'Vendor ID' : 'Executive ID'; ?>
        </th>
        <td><?= $id ?></td>
    </tr>

    <tr>
        <th>
            <i class="fa fa-user"></i>
            <?= ($primary_intent == 'vendor') ? 'Vendor Name' : 'Executive Name'; ?>
        </th>
        <td><?= $display_name ?></td>
    </tr>

    <tr>
        <th>
            <i class="fa fa-birthday-cake"></i>Email
        </th>
        <td><?= $email ?></td>
    </tr>

    <tr>
        <th>
            <i class="fa fa-map-marker"></i>Mobile
        </th>
        <td><?= $phone ?></td>
    </tr>

    <?php if($primary_intent != 'vendor'){ ?>
    <tr>
        <td colspan="2">
            <img src="<?= $company_signature ?>" width="150" height="80" alt="" />
        </td>
    </tr>
    <tr>
        <td colspan="2">Authorized Signature</td>
    </tr>
    <?php } ?>

</tbody>

                        </table>
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