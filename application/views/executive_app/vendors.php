<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
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
                <div class="col-12 mb-4">
                    <a href="<?php echo base_url('executive/approved_vendors_list'); ?>">
                        <div class="card card-shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="bg-warning text-center wb-icon-box">
                                            <i class="icon-people text-light f24"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <h6 class="mt-1 mb-0">Approved Vendors</h6>
                                        <p class="mb-0 f24 weight-600 text-success"><?= $vendor_approved ?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 mb-4">
                    <a href="<?php echo base_url('executive/pending_vendors_list'); ?>">
                        <div class="card card-shadow ">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="bg-info text-center wb-icon-box">
                                            <i class="icon-people text-light f24"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <h6 class="mt-1 mb-0">Pending Vendors</h6>
                                        <p class="mb-0 f24 weight-600 text-danger"><?= $vendor_pending ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


            </div>




        </section>
        <!-- Section_End -->

    </div>
</div>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>