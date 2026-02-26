<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<style>
/* ===== SMALL DASHBOARD ONE LINE (FIXED) ===== */

.small-dashboard-row {
    display: flex !important;
    flex-wrap: nowrap !important;
    overflow-x: auto;
    gap: 1px;
    
}

/* Override Bootstrap columns */
.small-dashboard-row > .col-md-2,
.small-dashboard-row > .col-12,
.small-dashboard-row > .col-6 {
    flex: 0 0 auto !important;
    width: 120px !important;
    max-width: 120px !important;
}

/* Card compact */
.small-dashboard-row .card {
    padding: 8px !important;
    min-height: 70px;
    border-radius: 8px;
}

/* Icon size */
.small-dashboard-row i {
    font-size: 22px !important;
    
}

/* Text size */
.small-dashboard-row div,
.small-dashboard-row small {
    font-size: 9px !important;
}

/* Wide card (Helpdesk) */
.small-dashboard-row .wide-card {
    width: 260px !important;
    max-width: 260px !important;
}

/* Scrollbar */
.small-dashboard-row::-webkit-scrollbar {
    height: 4px;
}
.small-dashboard-row::-webkit-scrollbar-thumb {
    background: #bbb;
    border-radius: 10px;
}

/* Mobile fine-tune */
@media (max-width: 768px) {
    .small-dashboard-row > .col-md-2,
    .small-dashboard-row > .col-12,
    .small-dashboard-row > .col-6 {
        width: 110px !important;
        max-width: 110px !important;
    }
}
/* ===== MOBILE DASHBOARD FIX ===== */

/* Equal height + compact */
.info_items {
    height: 90px;
    padding: 10px 12px !important;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Icon circle smaller */
.info_items_icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
}

.info_items_icon i {
    font-size: 18px;
}

/* Text layout */
.info_item_content {
    flex: 1;
    min-width: 0;
}

.info_items_text {
    white-space: nowrap;        /* force one line */
    overflow: hidden;           /* hide extra text */
    text-overflow: ellipsis;    /* show ... */
        line-height: 1.2;
      margin-left: -64px;
        margin-top: -48px;
}



.info_items_number {
    font-size: 18px;
    font-weight: 600;
}

/* Remove extra spacing from anchor */
a > .info_items {
    margin: 0;
}

/* MOBILE ONLY */
@media (max-width: 576px) {

    .info_items {
        height: 80px;
        padding: 8px 10px !important;
    }

    .info_items_text {
        font-size: 11px;
    }

    .info_items_number {
        font-size: 16px;
    }
}
span.user_name {
    margin-left: 18px;
    font-size: 12px;
}
span.user_name_id {
    margin-left: 136px;
    font-size: 12px;
}

</style>
         <?php if(!empty($vendors)) { ?>

    <span class="user_name">
        Name : <?= $vendors['display_name'] ?: ($vendors['first_name'].' '.$vendors['last_name']); ?>
    </span>

    <span class="user_name_id">
        ID : <?= $vendors['id']; ?>
    </span>

<?php }  else { ?><span class="user_name">Name : <?= $exc_roles['executive_name']; ?> </span>
                      <span class="user_name_id"> ID: <?= $exc_roles['executive_id']; ?></span>
                    <?php  } ?>
                    
                    
    
<div class="content_wrapper">
    <div class="container-fluid">
        <!-- breadcrumb -->

        <!-- breadcrumb_End -->

        <!-- Section -->
        <section class="chart_section">

<!-- ===== SMALL DASHBOARD TOP CARDS ===== -->
<div class="row mb-3 small-dashboard-row">

    <div class="col-md-2 col-12">
        <a href="<?= base_url('executive/vendor_checklist'); ?>">
            <div class="card text-center shadow-sm">
                <i class="ion-log-in text-primary"></i>
                <div>VendorCheck</div>
            </div>
        </a>
    </div>

    <div class="col-md-2 col-12">
        <a href="<?= base_url('executive/reports'); ?>">
            <div class="card text-center shadow-sm">
                <i class="ion-ios-paper text-warning"></i>
                <div>Reports</div>
            </div>
        </a>
    </div>

    <div class="col-md-1 col-12">
        <a href="<?= base_url('executive/profile'); ?>">
            <div class="card text-center shadow-sm">
                <i class="ion-person text-success"></i>
                <div>Profile</div>
            </div>
        </a>
    </div>

</div>

<!-- VISITOR / VENDOR SMALL CARDS -->
<div class="row text-center mb-3 small-dashboard-row">

    <div class="col-6">
         <a href="<?php echo base_url('executive/myarchive'); ?>">
        <div class="card shadow-sm">
            <i class="ion-ios-people-outline text-info"></i>
            <div style="font-weight:600;"></div><br>
            <small>My archive</small>
        </div>
        </a>
    </div>
                <?php
            $roles = explode(',', $exc_roles['role_type']);

            if (in_array('vendor_onboard', $roles)) {

            ?>
    <div class="col-6">
         <a href="<?php echo base_url('executive/vendors'); ?>">
        <div class="card shadow-sm">
            <i class="ion-android-people text-danger"></i>
            <div style="font-weight:600;"><?= $vendor_count ?? 0; ?></div>
            <small>Vendor</small>
        </div>
        </a>
    </div>
    <?php } ?>

</div>

<!-- ===== END SMALL DASHBOARD ===== -->


            <!-- ===== YOUR EXISTING CODE (UNCHANGED) ===== -->

            <div class="row">
                <?php
            $roles = explode(',', $exc_roles['role_type']);

if (in_array('vendor_onboard', $roles)) {

?>
                <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/vendors'); ?>">
                        <div class="info_items bg_green d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-android-people"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">Vendors</span>
                                <span class="info_items_number"><?= isset($vendor_count) ? $vendor_count : 0; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
                <?php
                if (in_array('delivery_onboard', $roles)) { ?>

                <!-- /info-box-content -->
              <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/delivery_boys'); ?>">
                        <div class="info_items bg_yellow d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-ios-person"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">Delivery Captain's</span>
                                <span
                                    class="info_items_number"><?= isset($deliveryCaptain) ? $deliveryCaptain : 0; ?></span>
                                    
                            </div>
                            
                        </div>
                    </a>
                </div>
                <?php } ?>
                 <?php
                if (in_array('user_onboard', $roles)) { ?>
                <!-- /info-box-content -->
             <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/users'); ?>">
                        <div class="info_items bg_blue d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-ios-book"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">Users</span>
                                <span class="info_items_number"><?= isset($users_count) ? $users_count : 0; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
                <!-- /info-box-content -->
                <?php
                if ($executive_address['executive_type_id'] == 1) {
                    ?>
                     <div class="col-md-3 col-6 mb-3">
                        <a href="<?php echo base_url('executive/wallet'); ?>">
                            <div class="bg_pink info_items d-flex align-items-center">
                                <span class="info_items_icon">
                                    <i class="ion-ios-pricetags"></i>
                                </span>
                                <div class="info_item_content">
                                    <span class="info_items_text">Wallet</span>
                                    <span class="info_items_number"><?= isset($walletAmount) ? $walletAmount : 0; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                    </div>
                    <?php } ?>
               <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/referral_link'); ?>">
                        <div class=" bg-blue info_items d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-link"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">Referral Links</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                </div>
  

             <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/users'); ?>">
                        <div class="info_items bg_blue d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-ios-book"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">>Vendor → User Referra</span>
                                <span class="info_items_number">  ₹<?= number_format($users['vendor_touser_referral_amount'] ?? 0, 2); ?></span>
                            </div>
                        </div>
                    </a>
                </div>

<div class="container-fluid">

    <!-- TOP INFO BOXES -->
    <div class="row">

        <div class="col-md-4 col-12 mb-3">
            <div class="bg-blue info_items d-flex align-items-center p-3 rounded">
                <i class="ion-link mr-3"></i>
                <div>
                    <small>Referral Code</small>
                    <h5><?= $referral_code; ?></h5>
                </div>
            </div>
        </div>

        <!--<div class="col-md-4 col-12 mb-3">-->
        <!--    <div class="bg-green info_items d-flex align-items-center p-3 rounded">-->
        <!--        <i class="ion-person mr-3"></i>-->
        <!--        <div>-->
        <!--            <small>Executive Name</small>-->
        <!--            <h5><?// $exc_roles['executive_name']; ?></h5>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

        <!--<div class="col-md-4 col-12 mb-3">-->
        <!--    <div class="bg-purple info_items d-flex align-items-center p-3 rounded">-->
        <!--        <i class="ion-id-card mr-3"></i>-->
        <!--        <div>-->
        <!--            <small>Executive ID</small>-->
        <!--            <h5><?// $exc_roles['executive_id']; ?></h5>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

    </div>

    <!-- TARGETS -->
    <!--<div class="row text-center">-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3">-->
    <!--            <strong>Monthly Target</strong>-->
    <!--            <h4>//<?//$exc_roles['monthly_target']; ?></h4>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3">-->
    <!--            <strong>Executive Target</strong>-->
    <!--            <h4><?// $exc_roles['executive_target']; ?></h4>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3">-->
    <!--            <strong>Freelancer Target</strong>-->
    <!--            <h4><?// $exc_roles['target_freelancer']; ?></h4>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3">-->
    <!--            <strong>Amount</strong>-->
    <!--            <h4>₹<// number_format($exc_roles['amount'], 2); ?></h4>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--</div>-->

    <!-- LOCATION + STATUS -->
    <!--<div class="row">-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3 text-center">-->
    <!--            <strong>City</strong>-->
    <!--            <p><?// $exc_roles['city_name']; ?></p>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3 text-center">-->
    <!--            <strong>Circle</strong>-->
    <!--            <p><?// $exc_roles['circle']; ?></p>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-md-3 col-6 mb-3">-->
    <!--        <div class="card p-3 text-center">-->
    <!--            <strong>Ward</strong>-->
    <!--            <p><?/// $exc_roles['ward']; ?></p>-->
    <!--        </div>-->
    <!--    </div>-->


    <!--</div>-->

    <!-- ROLE TYPES -->
    <!--<div class="row">-->
    <!--    <div class="col-md-6 col-12">-->
    <!--        <div class="card">-->
    <!--            <div class="card-header"><strong>Role Types</strong></div>-->
    <!--            <div class="card-body">-->
    <!--                <ul>-->
    <!--                    <?php //foreach (explode(',', $exc_roles['role_type']) as $role): ?>-->
    <!--                        <li><?// ucfirst(str_replace('_', ' ', $role)); ?></li>-->
    <!--                    <?php //endforeach; ?>-->
    <!--                </ul>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

        <!-- TEAM MEMBERS -->
    <!--    <div class="col-md-6 col-12">-->
    <!--        <div class="card">-->
    <!--            <div class="card-header"><strong>Team Members</strong></div>-->
    <!--            <div class="card-body">-->
    <!--                <ul>-->
    <!--                //    ->
    <!--                    //$team = json_decode($exc_roles['team_members'], true);-->
    <!--                    //if (!empty($team)):-->
    <!--                        //foreach ($team as $member):-->
    <!--                   // ?>-->
    <!--                        <li><?// $member; ?></li>-->
    <!--                    <?php //endforeach; else: ?>-->
    <!--                        <li>No team members</li>-->
    <!--                    <?php// endif; ?>-->
    <!--                </ul>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

</div>

            </div>
            <!--graph widget end-->

        </section>
        <!-- Section_End -->

</div>
</div>
<!-- Content_right_End -->

</div>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>