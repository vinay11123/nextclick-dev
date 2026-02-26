<!DOCTYPE html>
<html lang="en">

<head>
    <title>NEXT CLICK Executive</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>executive_app/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/ionicons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>executive_app/assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>executive_app/assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>executive_app/assets/css/responsive.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/jquery.min.js"></script>

    <style>
        #exec_loader {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }
    </style>
</head>

<body>
    <!-- <div id="loader_wrpper">
        <div class="loader_style"></div>
    </div> -->

    <div id="exec_loader">
        <i class="fa fa-spinner fa-spin fa-3x"></i>
    </div>


    <div class="wrapper">
        <!-- header -->
        <header class="main-header">
            <div class="container_header">
                <div class="logo d-flex align-items-center">
                    <a href="#"> <strong class="logo_icon"> <img src="<?php echo base_url() ?>executive_app/assets/images/small-logo.png?v=1" alt="">
                        </strong>
                        <span class="logo-default">
                            <img src="<?php echo base_url() ?>executive_app/assets/images/logo2.png?v=1" alt=""> </span>
                    </a>
                </div>

            </div>

        </header>
        <!-- header_End -->
        <!-- Content_right -->
        <div class="container_full">
            <!--main contents start-->
            <main class="content_wrapper">

                <div class="container-fluid">
                    <!-- state start-->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-shadow mb-4">

                                <div class="card-body">
                                    <?php if (!empty($this->session->flashdata('error_message'))) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $this->session->flashdata('error_message') ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?php echo base_url('executive_app/authorize/kyc_submit'); ?>" method="POST" enctype="multipart/form-data">

                                        <!--<div class="form-group">-->
                                        <!--    <label for="exampleInputPassword1">Executive Type</label>-->
                                        <!--    <select name="executive_type_id" id="executive_type_id" class="form-control" onchange="checkall()">-->
                                        <!--        <option value="" selected disabled>Select Type</option>-->
                                        <!--        <?php// foreach ($executive_type as $extype) : ?>-->
                                        <!--            <option value="<?php //echo $extype['id']; ?>" <?php //echo ($extype['id'] == $_POST['executive_type_id']) ? 'selected' : ''; ?>>-->
                                        <!--                <?php //echo $extype['executive_type'] ?>-->
                                        <!--            </option>-->
                                        <!--        <?php// endforeach; ?>-->
                                        <!--    </select>-->
                                        <!--    <?php //echo form_error('executive_type_id', '<div class="text-danger">', '</div>'); ?>-->
                                        <!--</div>-->
                            <!-- ================= EXECUTIVE DETAILS ================= -->
                                                <!-- Executive Category -->
                                                <div class="form-group col-md-3">
                                                    <label>Executive Type</label>
                                                    <select name="vendor_type" id="vendor_type" class="form-control" required>
                                                        <option value="">--select--</option>
                                                        <option value="freelancer">NXC Freelancer</option>
                                                        <option value="employer">NXC Employer</option>
                                                        <option value="intern">NXC Intern</option>
                                                    </select>
                                                </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">State<span class="text-danger">*</span></label>
                                            <select name="state_id" id="state_id" class="form-control" onchange="district_fun(this.value);checkall()">
                                                <option value="" selected disabled>Select State</option>
                                                <?php foreach ($states as $state) : ?>
                                                    <option value="<?php echo $state['id']; ?>" <?php echo ($state['id'] == $_POST['state_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $state['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php echo form_error('state_id', '<div class="text-danger">', '</div>'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">District<span class="text-danger">*</span></label>
                                            <select name="district_id" id="district_id" class="form-control" onchange="constituency_fun(this.value);checkall()">
                                                <option value="" selected disabled>Select District</option>
                                                <?php
                                                if ($_POST['state_id'] != '') {
                                                    foreach ($districts as $district) : ?>
                                                        <option value="<?php echo $district['id']; ?>" <?php echo ($district['id'] == $_POST['district_id']) ? 'selected' : ''; ?>>
                                                            <?php echo $district['name'] ?>
                                                        </option>
                                                <?php endforeach;
                                                } ?>
                                            </select>
                                            <?php echo form_error('district_id', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Constituency<span class="text-danger">*</span></label>
                                            <select name="constituency_id" id="constituency_id" class="form-control" onchange="checkall()">
                                                <option value="" selected disabled>Select Constituency</option>
                                                <?php
                                                if ($_POST['district_id'] != '') {
                                                    foreach ($constituencies as $constituencie) : ?>
                                                        <option value="<?php echo $constituencie['id']; ?>" <?php echo ($constituencie['id'] == $_POST['constituency_id']) ? 'selected' : ''; ?>><?php echo $constituencie['name'] ?></option>
                                                <?php endforeach;
                                                } ?>
                                            </select>
                                            <?php echo form_error('constituency_id', '<div class="text-danger">', '</div>'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Aadhaar Number <span class="text-danger"></span></label>
                                            <input type="text" name="aadhaar_number" id="aadhaar_number" class="form-control aadhaar_number1" value="<?php if (isset($_POST['aadhaar_number']))
                                                                                                                                                        echo $_POST['aadhaar_number']; ?>">
                                            <?php echo form_error('aadhaar_number', '<div class="text-danger">', '</div>'); ?>
                                            <p style="display: none;color:red" id="adhar_error"> Aadhar Number is incorrect</p>
                                            <p style="display: none;color:red" id="adhar_duplicate"> Aadhar Number is already exist</p>
                                        </div>

                                        <a class="btn btn-primary" name="send_otp" id="send_otp" style="color: white; display:none" onclick="send_otp()">Send OTP</a>

                                        <div class="form-group" style="display: none;" id="otp_div">
                                            <label for="exampleInputEmail1">OTP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control otp" name="otp" id="otp" oninput="otp_verify_but()">
                                            <input type="hidden" class="form-control" name="ref_id" id="ref_id">
                                            <input type="hidden" class="form-control" name="success_aadhar" id="success_aadhar" oninput="checkall()">
                                            <p style="display: none;color:red" id="otp_error"> OTP is incorrect</p>
                                            <p style="display: none;color:green" id="otp_success"> Aadhar card Verified Successfully</p>
                                        </div>

                                        <a class="btn btn-primary" name="otp_verify_but" id="otp_verify_but" style="color: white; display:none" onclick="otp_verify()">Verify OTP</a>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Aadhaar Card Front <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="aadhaar_front" id="aadhaar_front" accept="image/*" capture="camera" onchange="checkall()">
                                            <?php echo form_error('aadhaar_front', '<div class="text-danger">', '</div>'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Aadhaar Card Back <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="aadhaar_back" id="aadhaar_back" accept="image/*" capture="camera" onchange="checkall()">
                                            <?php echo form_error('aadhaar_back', '<div class="text-danger">', '</div>'); ?>
                                        </div>


    <!-- Team Lead (ONLY FOR EMPLOYER) -->
    <div class="form-group col-md-3" id="teamLeadWrapper" style="display:none;">
        <label>Team Lead Name</label>
        <input type="text" name="team_lead" class="form-control">
    </div>

    <div class="form-group col-md-3">
        <label> Name</label>
        <input type="text" name="executive_name" class="form-control" required>
    </div>

    <!--<div class="form-group col-md-3">-->
    <!--    <label>Executive ID</label>-->
    <!--    <input type="text" name="executive_id" class="form-control" required>-->
    <!--</div>-->

    <!--<div class="form-group col-md-3">-->
    <!--    <label>Amount</label>-->
    <!--    <input type="number" name="amount" class="form-control" required>-->
    <!--</div>-->

<div class="form-group col-md-3">
    <label>Area Type</label>
    <select name="area_type" class="form-control" required>
        <option value="">--select--</option>
        <?php 
        $area_types = ['urban', 'tier1', 'tier2'];
        foreach ($area_types as $type): ?>
            <option value="<?= $type ?>" <?= ($edit && $edit->area_type == $type) ? 'selected' : '' ?>>
                <?= ucfirst($type) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group col-md-3">
    <label>City</label>
    <select name="city_name" class="form-control" required>
        <option value="">--select--</option>
        <?php if(!empty($exc_cities)): 
            $cities_added = [];
            foreach($exc_cities as $c): 
                if(!in_array($c->city_name, $cities_added)): 
                    $cities_added[] = $c->city_name;
        ?>
            <option value="<?= $c->city_name ?>" <?= ($edit && $edit->city_name == $c->city_name) ? 'selected' : '' ?>>
                <?= $c->city_name ?>
            </option>
        <?php 
                endif; 
            endforeach; 
        endif; ?>
    </select>
</div>

<div class="form-group col-md-3">
    <label>Circle</label>
    <select name="circle" class="form-control" required>
        <option value="">--select--</option>
        <?php if(!empty($exc_cities)): 
            $circles_added = [];
            foreach($exc_cities as $c): 
                if(!in_array($c->circle, $circles_added)): 
                    $circles_added[] = $c->circle;
        ?>
            <option value="<?= $c->circle ?>" <?= ($edit && $edit->circle == $c->circle) ? 'selected' : '' ?>>
                <?= $c->circle ?>
            </option>
        <?php 
                endif; 
            endforeach; 
        endif; ?>
    </select>
</div>

<div class="form-group col-md-3">
    <label>Ward</label>
    <select name="ward" class="form-control" required>
        <option value="">--select--</option>
        <?php if(!empty($exc_cities)): 
            $wards_added = [];
            foreach($exc_cities as $c): 
                if(!in_array($c->ward, $wards_added)): 
                    $wards_added[] = $c->ward;
        ?>
            <option value="<?= $c->ward ?>" <?= ($edit && $edit->ward == $c->ward) ? 'selected' : '' ?>>
                <?= $c->ward ?>
            </option>
        <?php 
                endif; 
            endforeach; 
        endif; ?>
    </select>
</div>


    <!--<div class="form-group col-md-3">-->
    <!--    <label>Target Freelancer</label>-->
    <!--    <input type="number" name="target_freelancer" class="form-control" required>-->
    <!--</div>-->

    <!--<div class="form-group col-md-3">-->
    <!--    <label>Target Executive</label>-->
    <!--    <input type="number" name="executive_target" class="form-control" required>-->
    <!--</div>-->

    <!--<div class="form-group col-md-3">-->
    <!--    <label>Monthly Target</label>-->
    <!--    <input type="number" name="monthly_target" class="form-control" required>-->
    <!--</div>-->

    <!-- Team Members -->
    <!--<div class="form-group col-md-6">-->
    <!--    <label>Team Members</label>-->

    <!--    <div id="team_wrapper">-->
    <!--        <div class="team_row mb-2">-->
    <!--            <input type="text" name="team[]" class="form-control d-inline-block" style="width:85%">-->
    <!--            <button type="button" class="btn btn-danger btn-sm remove_team">X</button>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <button type="button" id="add_team_btn" class="btn btn-success btn-sm mt-2">-->
    <!--        + Add Team-->
    <!--    </button>-->
    <!--</div>-->

    <!-- Roles -->
    <!--<div class="form-group col-md-6">-->
    <!--    <label>Roles Type</label>-->
    <!--    <select name="onboard_roles[]" class="form-control" multiple required>-->
    <!--        <option value="user_onboard">User Onboard</option>-->
    <!--        <option value="vendor_onboard">Vendor Onboard</option>-->
    <!--        <option value="delivery_onboard">Delivery Boy Onboard</option>-->
    <!--    </select>-->
    <!--</div>-->

</div>
<!-- ================= END EXECUTIVE DETAILS ================= -->

                                        <input type="hidden" class="form-control" name="check_box_true" id="check_box_true" oninput="checkall()">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="termsconditions" class="form-check-input" id="terms-checkbox" onchange="checkall()">
                                                I accept Terms & Conditions
                                            </label>
                                            <?php echo form_error('termsconditions', '<div class="text-danger">', '</div>'); ?>

                                        </div>

                                        <button type="submit" class="btn btn-primary" name="submit"  id="submitLoaderButton">Submit</button>
                                    </form>


                                </div>
                            </div>




                        </div>

                    </div>
                    <!-- state end-->
                </div>

            </main>
            <!--main contents end-->

        </div>
        <!-- Content_right_End -->
        <!-- Footer -->

        <!-- Footer_End -->
    </div>

    <div class="modal fade" id="large-Modal2" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="m-b-0"><?= $termandconditions[0]['desc']; ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" id="accept-terms-button" class="btn btn-primary waves-effect waves-light" onclick="check_box_true()">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // $(document).on("input", ".aadhaar_number", function() {
        //     this.value = this.value.replace(/\D/g, '').slice(0, 12);
        // });
        // $(document).on("input", ".otp", function() {
        //     this.value = this.value.replace(/\D/g, '').slice(0, 6);
        // });

        function checkall() {
            var executive_type_id = $("#executive_type_id").val();
            var state_id = $.trim($("#state_id").val());
            var district_id = $.trim($("#district_id").val());
            var constituency_id = $.trim($("#constituency_id").val());
            var success_aadhar = $.trim($("#success_aadhar").val());
            var aadhaar_front = $('#aadhaar_front').get(0).files.length
            var aadhaar_back = $('#aadhaar_back').get(0).files.length

            if (executive_type_id != '' && state_id != '' && district_id != '' && constituency_id != '' && success_aadhar != '' && aadhaar_front > 0 && aadhaar_back > 0) {
                $("#submitLoaderButton").attr("disabled", false);
            }
        }

        function check_box_true() {
            $("#check_box_true").val(true);
        }

        // function aadhar_verify() {
        //     var aadhaar_number = $("#aadhaar_number").val();
        //     $("#otp_div").hide();
        //     $("#otp_verify_but").hide();
        //     $("#adhar_duplicate").hide();
        //     $('#ref_id').val('');
        //     $('#otp').val('');
        //     if (aadhaar_number.length >= 12) {

        //         $.ajax({
        //             url: '<?php echo base_url('executive_app/authorize/check_duplicate_aadharjs') ?>',
        //             type: 'post',
        //             data: {
        //                 aadhaar_number: aadhaar_number
        //             },
        //             beforeSend: function() {
        //                 $("#exec_loader").show();
        //             },
        //             success: function(response) {
        //                 console.log(response);
        //                 if (response == true) {
        //                     $("#exec_loader").hide();
        //                     $("#send_otp").show();
        //                     $("#adhar_duplicate").hide();
        //                 } else {
        //                     $("#exec_loader").hide();
        //                     $("#adhar_duplicate").show();
        //                     $("#send_otp").hide();

        //                 }
        //             },
        //         })
        //     } else {
        //         $("#send_otp").hide();
        //     }
        // }

        // function send_otp() {
        //     var aadhaar_number = $("#aadhaar_number").val();
        //     $("#adhar_error").hide();
        //     $("#otp_div").hide();
        //     $("#otp_verify_but").hide();
        //     $.ajax({
        //         url: '<?php echo base_url('auth/api/cashfree/aadhar_otp_genrate') ?>',
        //         type: 'post',
        //         data: {
        //             aadhaar_number: aadhaar_number
        //         },
        //         dataType: 'json',
        //         beforeSend: function() {
        //             $("#exec_loader").show();
        //         },
        //         success: function(data) {
        //             if (data['data']['ref_id'] != '' && data['data']['status'] == 'SUCCESS') {
        //                 $("#exec_loader").hide();
        //                 $("#otp_div").show();
        //                 $('#ref_id').val(data['data']['ref_id']);
        //             } else {
        //                 $("#adhar_error").show();
        //                 $("#exec_loader").hide();
        //             }

        //         }
        //     })

        // }

        // function otp_verify_but() {
        //     var otp = $("#otp").val();
        //     var ref_id = $("#ref_id").val();
        //     $("#otp_error").hide();
        //     $("#otp_success").hide();
        //     if (otp.length >= 6) {
        //         $("#otp_verify_but").show();
        //     } else {
        //         $("#otp_verify_but").hide();
        //     }
        // }

        // function otp_verify() {
        //     var otp = $("#otp").val();
        //     var ref_id = $("#ref_id").val();
        //     $("#otp_error").hide();
        //     $("#otp_success").hide();
        //     if (otp != '' && ref_id != '') {
        //         $.ajax({
        //             url: '<?php echo base_url('auth/api/cashfree/aadhar_otp_verify') ?>',
        //             type: 'post',
        //             data: {
        //                 otp: otp,
        //                 ref_id: ref_id
        //             },
        //             dataType: 'json',
        //             beforeSend: function() {
        //                 $("#exec_loader").show();
        //             },
        //             success: function(data) {
        //                 if (data['data']['status'] == 'VALID') {
        //                     $("#otp_success").show();
        //                     $("#exec_loader").hide();
        //                     $("#otp_error").hide();
        //                     $("#success_aadhar").val(true);
        //                     checkall();
        //                 } else {
        //                     $("#exec_loader").hide();
        //                     $("#otp_error").show();
        //                 }
        //             }
        //         })
        //     }
        // }

        // Open the modal when the checkbox is clicked
        $('#terms-checkbox').on('click', function(e) {
            e.preventDefault();
            $('#large-Modal2').modal('show');
        });

        // When the modal is shown
        $('#large-Modal2').on('shown.bs.modal', function() {
            // Uncheck the checkbox
            $('#terms-checkbox').prop('checked', false);
        });

        // When the "Accept" button is clicked
        $('#accept-terms-button').on('click', function() {
            $('#terms-checkbox').prop('checked', true);
            $('#large-Modal2').modal('hide');
        });

        // Prevent the modal from closing when clicking outside of it or pressing ESC
        $('#large-Modal2').modal({
            backdrop: 'static',
            keyboard: false
        });


        function district_fun(state_id) {
            $.ajax({
                url: '<?php echo base_url('executive_app/authorize/district') ?>',
                type: 'post',
                data: {
                    state_id: state_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data == 1) {
                        data = null;
                    }
                    var options = '<option value="" selected disabled>Select District</option>';
                    if (data) {
                        for (var i = 0; i < data[0].length; i++) {
                            options += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>'
                        }
                    }
                    document.getElementById("district_id").innerHTML = options;
                }
            })
        }

        function constituency_fun(district_id) {
            $.ajax({
                url: '<?php echo base_url('executive_app/authorize/constituency') ?>',
                type: 'post',
                data: {
                    district_id: district_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data == 1) {
                        data = null;
                    }
                    var options = '<option value="" selected disabled>Select Constituency</option>';
                    if (data) {
                        for (var i = 0; i < data[0].length; i++) {
                            options += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>'
                        }
                    }
                    document.getElementById("constituency_id").innerHTML = options;
                }
            })
        }

        $(document).ready(function() {
            $("#submitLoaderButton").click(function() {
                $("#exec_loader").show();

                $(window).on('load', function() {
                    $("#exec_loader").hide();
                });
            });
        });
    </script>
    <script>
    // Show Team Lead only for Employer
    $('#vendor_type').on('change', function () {
        if ($(this).val() === 'employer') {
            $('#teamLeadWrapper').show();
            $('input[name="team_lead"]').prop('required', true);
        } else {
            $('#teamLeadWrapper').hide();
            $('input[name="team_lead"]').prop('required', false).val('');
        }
    });

    // Add / Remove Team Members
    $('#add_team_btn').on('click', function () {
        $('#team_wrapper').append(`
            <div class="team_row mb-2">
                <input type="text" name="team[]" class="form-control d-inline-block" style="width:85%">
                <button type="button" class="btn btn-danger btn-sm remove_team">X</button>
            </div>
        `);
    });

    $(document).on('click', '.remove_team', function () {
        $(this).closest('.team_row').remove();
    });
</script>



    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>executive_app/assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url() ?>executive_app/assets/js/custom.js" type="text/javascript"></script>
</body>

</html>