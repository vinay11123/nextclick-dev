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
                                Approved Delivery
                                Captain's(<?= isset($approved_captain_count) ? $approved_captain_count : 0; ?>)
                            </div>
                            <span class="pull-right" style="margin-top:-27px;"><a class="btn-primary btn-sm"
                                    href="<?php echo base_url('executive/delivery_boys'); ?>">Back</a></span>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-info mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active f12" data-toggle="tab" href="#tab-p-info_1">
                                        Target
                                        Achieved(<?= isset($target_achieved_captain_count) ? $target_achieved_captain_count : 0; ?>)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link f12" data-toggle="tab" href="#tab-p-info_2">
                                        Not
                                        Achieved(<?= isset($target_not_achieved_captain_count) ? $target_not_achieved_captain_count : 0; ?>)</a>
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
                                            <?php foreach ($target_achieved_captains_list as $key => $target_achieved_captain): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Name: <span
                                                            class="text-primary"><?php echo $target_achieved_captain->captain_name; ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $target_achieved_captain->captain_phone; ?></span><br>
                                                        Location: <span
                                                            class="text-success"><?php echo $target_achieved_captain->location; ?></span><br>
                                                        Target:
                                                        <span
                                                            class="text-danger"><?php echo $target_achieved_captain->target_achieved_count . '/' . $target_achieved_captain->target_given_count; ?>
                                                        </span>
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
                                            <?php foreach ($target_not_achieved_captains_list as $key => $target_not_achieved_captain): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key + 1; ?></th>
                                                    <td>
                                                        Name: <span
                                                            class="text-primary"><?php echo $target_not_achieved_captain->captain_name; ?></span><br>
                                                        Ph No: <span
                                                            class="text-warning"><?php echo $target_not_achieved_captain->captain_phone; ?></span><br>
                                                        Location: <span
                                                            class="text-success"><?php echo $target_not_achieved_captain->location; ?></span><br>
                                                        Target:
                                                        <span class="text-danger"><?php
                                                        $target_nachieved_count = isset($target_not_achieved_captain->target_achieved_count) && !empty($target_not_achieved_captain->target_achieved_count) ? $target_not_achieved_captain->target_achieved_count : 0;
                                                        echo $target_nachieved_count . '/' . $target_not_achieved_captain->target_given_count;
                                                        ?></span>
                                                        <br>
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