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

                <div class="col-12 mb-4">

                    <div class="card card-shadow mb-4">
                        <div class="card-header">
                            <div class="card-title text-success">
                                Approved Vendors(<?= $vendor_approved ?>)
                            </div>
                            <span class="pull-right" style="margin-top:-27px;"><a class="btn-primary btn-sm"
                                    href="<?php echo base_url('executive/vendors'); ?>">Back</a></span>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-info mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active f12" data-toggle="tab" href="#tab-p-info_1">
                                        Subscribed(<?= $subscribed_vendor_count ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link f12" data-toggle="tab" href="#tab-p-info_2">
                                        Unsubscribed(<?= $unsubscribed_vendor_count ?>)</a>
                                </li>

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-p-info_1" role="tabpanel">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subscribed_vendor_list as $key => $subscribed_list): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Shop Name: <span
                                                            class="text-primary"><?php echo $subscribed_list->business_name; ?></span><br>
                                                        Vendor Name: <span
                                                            class="text-danger"><?php echo $subscribed_list->owner_name; ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $subscribed_list->whats_app_no; ?></span><br>
                                                        Location: <span
                                                            class="text-success"><?php echo $subscribed_list->location; ?></span><br>
                                                        Plan:
                                                        <span
                                                            class="text-primarys"><?php echo $subscribed_list->package_plan; ?></span>
                                                        <br>
                                                        Sub. Date:
                                                        <span
                                                            class="text-success text-dark"><?php echo date('d-m-Y h:i A', strtotime($subscribed_list->first_paid_subscription_at)); ?></span>
                                                        <br>

                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab-p-info_2" role="tabpanel">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($unsubscribed_vendor_list as $key => $unsubscribed_list): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Shop Name: <span
                                                            class="text-primary"><?php echo $unsubscribed_list->business_name; ?></span><br>
                                                        Vendor Name: <span
                                                            class="text-danger"><?php echo $unsubscribed_list->owner_name; ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $unsubscribed_list->whats_app_no; ?></span><br>
                                                        Location: <span
                                                            class="text-success"><?php echo $unsubscribed_list->location; ?></span><br>

                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>