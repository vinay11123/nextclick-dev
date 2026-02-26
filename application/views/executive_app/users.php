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
                                Users(<?= $users ?>)
                            </div>
                            <span class="pull-right" style="margin-top:-27px;"><a class="btn-primary btn-sm"
                                    href="<?php echo base_url('executive/dashboard'); ?>">Back</a></span>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-info mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active f12" data-toggle="tab" href="#tab-p-info_1">
                                        Ordered(<?= $ordered_user_count ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link f12" data-toggle="tab" href="#tab-p-info_2">
                                        Not Ordered(<?= $not_ordered_user_count ?>)</a>
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

                                            <?php foreach ($ordered_user_list as $key => $ordered_list): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Name: <span class="text-danger"><?php echo $ordered_list->first_name . ' ' . $unordered_list->last_name;
                                                        ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $ordered_list->phone; ?></span><br>
                                                        Registered At: <span
                                                            class="text-warning"><?php echo date('d-m-Y h:i A', strtotime($ordered_list->created_at)); ?></span><br>
                                                        Ordered At: <span
                                                            class="text-warning"><?php echo date('d-m-Y h:i A', strtotime($ordered_list->first_order_at)); ?></span><br>
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
                                            <?php foreach ($not_ordered_user_list as $key => $unordered_list): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Name: <span
                                                            class="text-danger"><?php echo $unordered_list->first_name . ' ' . $unordered_list->last_name; ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $unordered_list->phone; ?></span><br>
                                                        Registered At: <span
                                                            class="text-warning"><?php echo date('d-m-Y h:i A', strtotime($unordered_list->created_at)); ?></span><br>

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