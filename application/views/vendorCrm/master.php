<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('vendor_crm/dashboard'); ?>">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pcoded-inner-content">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">
                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <div class="card">
                                        <div class="card-block">
                                            <?php if ($vendor_data[0]['business_name'] != '') { ?>
                                                <h3>Welcome to <?php echo $vendor_data[0]['business_name'] ?>!!!</h3>
                                            <?php } else { ?>
                                                <h3>Welcome to NextClick!!!</h3>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php $this->load->view('vendorCrm/footer'); ?>