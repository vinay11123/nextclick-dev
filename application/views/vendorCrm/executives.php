<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
        /* Rounded slider */
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        /* Rounded slider */
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(16px);
    }
</style>

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
                                <li class="breadcrumb-item">Executives</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- Main-body start -->
            <div class="main-body">
                <div class="page-wrapper">

                    <!-- Page-body start -->
                    <div class="page-body">

                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="ven">List of Executives</h4>

                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Sno</th>
                                                    <th>Executive Details</th>
                                                    <th>Created On</th>
                                                    <th>No of Vendors</th>
                                                    <th>No of Delivery Partners</th>
                                                    <th>No of Users</th>
                                                    <?php if ($this->ion_auth_acl->has_permission('executive_approval')) : ?>
                                                        <th>Approve</th>
                                                    <?php endif; ?>
                                                    <?php if ($this->ion_auth_acl->has_permission('executive_details')) : ?>
                                                        <th class="not-export-column">Actions</th>
                                                    <?php endif; ?>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($this->ion_auth_acl->has_permission('executive_view')) : ?>
                                                    <?php if (!empty($executives)) : ?>
                                                        <?php $sno = 1;
                                                        foreach ($executives as $executive) : ?>

                                                            <tr>
                                                                <td><?php echo $sno++; ?></td>
                                                                <td>Emp Id: <?php echo $executive['id']; ?><br>
                                                                    Name:
                                                                    <?php echo $executive['first_name'] . ' ' . $executive['last_name']; ?><br>
                                                                    Type: <?php if ($executive['executive_type'] != '') echo $executive['executive_type'];
                                                                            else echo "<span style='color:red;'>KYC Incomplete</span>" ?><br>
                                                                    Email: <?php echo $executive['email']; ?><br>
                                                                    Mobile Number: <?php echo $executive['phone']; ?><br>
                                                                    State: <?php echo $executive['state_name']; ?><br>
                                                                    District: <?php echo $executive['district_name']; ?><br>
                                                                    Constituency: <?php echo $executive['constitution_name']; ?></td>
                                                                <td><?php echo date('d-m-Y h:i A', strtotime($executive['created_at'])); ?>
                                                                </td>
                                                                <td><?php
                                                                    if (!empty($executive['vendor_count'])) {
                                                                        echo $executive['vendor_count'];
                                                                    } else {
                                                                        echo "0";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (!empty($executive['delivery_captain_count'])) {
                                                                        echo $executive['delivery_captain_count'];
                                                                    } else {
                                                                        echo "0";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (!empty($executive['user_count'])) {
                                                                        echo $executive['user_count'];
                                                                    } else {
                                                                        echo "0";
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <?php if ($this->ion_auth_acl->has_permission('executive_approval')) : ?>
                                                                    <td>



                                                                        <label class="switch">
                                                                            <input type="checkbox" class="statusToggle" id="<?php echo $executive['id']; ?>" <?php echo ($executive['status'] == 1) ? 'checked' : ''; ?>>
                                                                            <span class="slider round"></span>
                                                                        </label>


                                                                        <!-- <input type="checkbox" class="approve_executive"
                                                                                id="<?php echo $executive['id']; ?>" <?php echo ($executive['status'] == 1) ? 'checked' : ''; ?>
                                                                                data-toggle="toggle" data-style="ios" data-on="Approved"
                                                                                data-off="Dispprove" data-onstyle="success"
                                                                                data-offstyle="danger"> -->
                                                                    </td>
                                                                <?php endif; ?>
                                                                <td>
                                                                    <?php if ($this->ion_auth_acl->has_permission('executive_details')) : ?>
                                                                        <a href="<?php echo base_url() ?>executive_list/executive?exe_id=<?php echo $executive['id'] ?>" class="mr-2" type="executive"> <i class="feather icon-user" style="color: red;"></i></a>


                                                                    <?php endif; ?>
                                                                    <?php if ($this->ion_auth_acl->has_permission('executive_details')) : ?>
                                                                        <a href="<?php echo base_url() ?>executive_list/executive?eye_id=<?php echo $executive['id'] ?>" class="mr-2" type="executive"> <i class="feather icon-eye"></i></a>
                                                                    <?php endif; ?>

                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <th colspan='6'>
                                                                <h3>
                                                                    <center>No Executives</center>
                                                                </h3>
                                                            </th>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <th colspan='10'>
                                                            <h3>
                                                                <center>No Access!</center>
                                                            </h3>
                                                        </th>
                                                    </tr>
                                                <?php endif; ?>
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
</div>
</div>
</div>


<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>