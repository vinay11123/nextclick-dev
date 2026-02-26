<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<style>
    .copied-message {
        color: green;
        float: right;
        margin-right: 10px;
    }
</style>

<div class="content_wrapper">
    <div class="container-fluid">
        <!-- breadcrumb -->

        <!-- breadcrumb_End -->

        <!-- Section -->
        <section class="chart_section">

            <div class="row">
                <div class="col-12 mt-1 mb-2">
                    <a class="btn-primary btn-sm" href="<?php echo base_url('executive/dashboard'); ?>">Back</a>
                </div>
                <div class="col-12">
                    <div class="card-group mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-right">
                                            <i class="f24 opacity-3 icon-share fas fa-share-alt" data-message="Register as a vendor on our app using referral code: *<?= $referral_code; ?> - NEXT CLICK*" data-type='vendor'>
                                            </i>
                                        </div>
                                        <h3 class="text-success mb-2">Vendors</h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress mt-3 mb-3" style="height: 3px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="text-muted f12 mt-2" onclick="copyReferralCode(this)">
                                            <span class="referral-code"><?= $referral_code; ?></span>
                                            <span class="float-right copy-icon"><i class="f24 fa fa-copy"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-right">
                                            <i class="f24 opacity-3 icon-share fas fa-share-alt" data-message="Register as a delivery captain on our app using referral code: *<?= $referral_code; ?> - NEXT CLICK*" data-type='deliveryboynew'>
                                            </i>
                                        </div>
                                        <h3 class="text-success mb-2">Delivery Boy's</h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress mt-3 mb-3" style="height: 3px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="text-muted f12 mt-2" onclick="copyReferralCode(this)">
                                            <span class="referral-code"><?= $referral_code; ?></span>
                                            <span class="float-right copy-icon"><i class="f24 fa fa-copy"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-right">
                                            <i class="f24 opacity-3 icon-share fas fa-share-alt" data-message="Register as a user on our app using referral code: *<?= $referral_code; ?> - NEXT CLICK*" data-type='user'>
                                            </i>
                                        </div>
                                        <h3 class="text-success mb-2">Users</h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress mt-3 mb-3" style="height: 3px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="text-muted f12 mt-2" onclick="copyReferralCode(this)">
                                            <span class="referral-code"><?= $referral_code; ?></span>
                                            <span class="float-right copy-icon"><i class="f24 fa fa-copy"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section_End -->
    </div>
</div>
</div>
<!-- Content_right_End -->

</div>


<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>