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
                                <li class="breadcrumb-item"><a href="#!">Executives - Users</a></li>
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

                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-block">

                                            <form action="<?php echo base_url('executive_list/users/submit'); ?>"
                                                method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select name="executive_id" id="executive_id"
                                                            class="form-control" style="width:100%;">
                                                            <option value="">Select Executive</option>
                                                            <?php foreach ($executives as $key => $executive): ?>
                                                                <?php

                                                                $selected = ((isset($executive_id) && $executive['id'] == $executive_id) || ($executive['id'] == set_value('executive_id'))) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?= $executive['id'] ?>" <?= $selected ?>>
                                                                    <?= $executive['first_name'] . ' ' . $executive['last_name'] . ' (' . $executive['phone'] . ')' ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <br><br>
                                                        <?= form_error('executive_id', '<div class="text-danger">', '</div>'); ?>

                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-block">Submit</button>
                                                    </div>
                                                </div>
                                            </form>



                                        </div>
                                    </div>
                                </div>



                                <div class="col-xl-12 col-md-12">

                                    <h4>User List</h4>

                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#home3"
                                                role="tab">Ordered(<?= $ordered_user_count ?>)</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#profile3" role="tab">Not
                                                Ordered(<?= $not_ordered_user_count ?>)</a>
                                            <div class="slide"></div>
                                        </li>

                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content card-block mt-4">
                                        <div class="tab-pane active" id="home3" role="tabpanel">


                                            <div class="card">
                                                <div class="card-block">

                                                    <div class="dt-responsive table-responsive">
                                                        <table id="ordered_user_table"
                                                            class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Executive Details</th>
                                                                    <th>Name</th>
                                                                    <th>Ph No</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($ordered_user_list as $key => $ordered_user): ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td>Executive Name:
                                                                            <?php echo $ordered_user->executive_name; ?><br>
                                                                            Executive Type:
                                                                            <?php echo $ordered_user->executive_type; ?>
                                                                        </td>
                                                                        <td><?php echo $ordered_user->first_name; ?>
                                                                        </td>

                                                                        <td><?php echo $ordered_user->phone; ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tab-pane" id="profile3" role="tabpanel">

                                            <div class="card">

                                                <div class="card-block">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="unordered_user_table"
                                                            class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Executive Details</th>

                                                                    <th>Name</th>
                                                                    <th>Ph No</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($not_ordered_user_list as $key => $not_ordered_user): ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td>Executive Name:
                                                                            <?php echo $not_ordered_user->executive_name; ?><br>
                                                                            Executive Type:
                                                                            <?php echo $not_ordered_user->executive_type; ?>
                                                                        </td>
                                                                        <td><?php echo $not_ordered_user->first_name; ?>
                                                                        </td>

                                                                        <td><?php echo $not_ordered_user->phone; ?></td>

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



                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('#executive_id').select2();
    });
</script>

<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>