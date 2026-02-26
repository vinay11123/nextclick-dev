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


<?php if ($type == 'executive') { ?>
    <div class="pcoded-content">

        <!-- [ breadcrumb ] end -->

        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Page-body start -->
                <div class="page-body">

                    <div class="card">

                        <div class="card-block">
                            <div class="row invoive-info">
                                <div class="col-md-6 col-xs-12 invoice-client-info">
                                    <h5>Executive Details</h5>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">User ID:</strong></div>
                                        <div class="col-md-7 col-xs-6"><?php echo $users['id']; ?></div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">Name:</strong></div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['first_name'] . ' ' . $users['last_name']; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">Mobile Number:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6"><?php echo $users['phone']; ?></div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">Email:</strong></div>
                                        <div class="col-md-7 col-xs-6"><?php echo $users['email']; ?></div>
                                    </div>

                                    <!-- <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">Location:</strong></div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['executive_address']['location']; ?>
                                        </div>
                                    </div> -->

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6"><strong class="text-dark">Referral Code:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6"><?php echo $users['referral_code']; ?></div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6">
                                            <strong">Aadhar Number:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['eaadhar_number']; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6">
                                            <strong">Executive Type:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo !empty($users['executive_type']) ? $users['executive_type'] : '<span style="color:red;">KYC Incomplete</span>'; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6">
                                            <strong">State:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['state_name']; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6">
                                            <strong">District:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['district_name']; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-5 col-xs-6">
                                            <strong">Constituency:</strong>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php echo $users['constituency_name']; ?>
                                        </div>
                                    </div>


                                    <?php if (!empty($users['executive_type'])) { ?>
                                        <div class="row mt-2">
                                            <div class="col-md-5 col-xs-6">
                                                <strong>Status:</strong>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php if ($this->ion_auth_acl->has_permission('executive_approval')): ?>
                                                    <label class="switch">
                                                        <input type="checkbox" class="statusToggle" id="<?php echo $users['id']; ?>"
                                                            <?php echo ($users['status'] == 1) ? 'checked' : ''; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="col-md-2 col-sm-12">
                                    <br><br>
                                    <p>Profile Image:</p><br>
                                    <?php
                                    $image_path = 'uploads/profile_image/profile_' . $users['id'] . '.jpg';

                                    if (file_exists($image_path)) {
                                        echo '<img src="' . base_url() . $image_path . '" class="img-thumb modal-target" style="width: 100px; height: 100px; object-fit: cover;" alt="" data-toggle="modal" data-target="#large-Modal1" />';
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                </div>

                                <div class="col-md-2 col-sm-12">
                                    <br><br>
                                    <p>Aadhar Card Front Image:</p>
                                    <?php
                                    $aadhar_front_image_path = 'uploads/aadhar_card_image/aadhar_card_front_' . $users['id'] . '.jpg';

                                    if (file_exists($aadhar_front_image_path)) {
                                        echo '<img src="' . base_url() . $aadhar_front_image_path . '" class="img-thumb modal-target" style="width: 100px; height: 100px; object-fit: cover;" alt="" data-toggle="modal" data-target="#large-Modal1" />';
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                </div>

                                <div class="col-md-2 col-sm-12">
                                    <br><br>
                                    <p>Aadhar Card Back Image:</p>
                                    <?php
                                    $aadhar_back_image_path = 'uploads/aadhar_card_image/aadhar_card_back_' . $users['id'] . '.jpg';

                                    if (file_exists($aadhar_back_image_path)) {
                                        echo '<img src="' . base_url() . $aadhar_back_image_path . '" class="img-thumb modal-target" style="width: 100px; height: 100px; object-fit: cover;" alt="" data-toggle="modal" data-target="#large-Modal1" />';
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($users['executive_address']['executive_type_id'] == 1) { ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered invoice-detail-table"
                                                    id="bank_details_table">
                                                    <thead>
                                                        <tr class="bg-c-blue">
                                                            <th>ID</th>
                                                            <th width="150">Account Holder Name</th>
                                                            <th>Bank Name</th>
                                                            <th>Account Number</th>
                                                            <th>IFSC Code</th>
                                                            <th>Status</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;

                                                        if (!empty($executive_bank_details)):
                                                            foreach ($executive_bank_details as $bank_details): ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $bank_details->ac_holder_name; ?></td>
                                                                    <td><?php echo $bank_details->bank_name; ?></td>
                                                                    <td><?php echo $bank_details->ac_number; ?></td>
                                                                    <td><?php echo $bank_details->ifsc; ?></td>
                                                                    <td><?php if ($bank_details->is_primary) {
                                                                        echo '<div class="text-primary">primary</div>';
                                                                    } ?></td>
                                                                </tr>

                                                            <?php endforeach;
                                                        endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="large-Modal1" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <img id="modal-image" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
    </div>

<?php } ?>
<script>
    $(document).ready(function () {
        $('.modal-target').on('click', function () {
            var imgSrc = $(this).attr('src');
            $('#modal-image').attr('src', imgSrc);
        });
    });
</script>
<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>