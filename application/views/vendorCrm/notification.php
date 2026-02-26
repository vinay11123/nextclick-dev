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
                                <li class="breadcrumb-item"><a href="<?php echo base_url('vendor_crm/dashboard/notification'); ?>">Notification List</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="pcoded-inner-content">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">

                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">

                                <div class="col-xl-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header text-dark">
                                            <h5 class="text-dark"><i class="feather icon-home"></i> Notification List</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="dt-responsive table-responsive">
                                                <table id="base-style" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Message</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($notification_data as $key => $notification) { ?>
                                                            <tr>
                                                                <td><?php echo $notification['title'] ?></td>
                                                                <td>
                                                                    <?php if ($notification['status'] == 1) { ?>
                                                                        <a href="javascript:void(0);" class="text-underline"><?php echo $notification['message'] ?></a>
                                                                    <?php } else { ?>
                                                                        <?php echo $notification['message'] ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>

                                                        <?php } ?>
                                                </table>
                                            </div>
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