<style>
    html,
    * {
        font-family: 'Inter';
    }

    body {
        background-color: #fafafa;
        line-height: 1.6;
    }

    .lead {
        font-size: 1.5rem;
        font-weight: 300;
    }

    /*
.container {
	margin: 60px auto;
	max-width: 960px;
}
*/

    #imgDiv {
        cursor: pointer;
    }

    .table td,
    .table th {
        padding: 2px !important;

    }

    /*
body {
	background: -webkit-linear-gradient(# #f6f6f6, #6c757d);
}
*/

    .main-content {
        padding-top: 0px;
    }

    /* .bordercfd,.bordercf,.bordercfw {
    border: 1px solid gray;
} */
    .emp-profile {
        padding: 3%;
        margin-bottom: 3%;
        border-radius: 0.5rem;
        background: #fff;
    }

    .profile-img {
        text-align: center;
    }

    .profile-img img {
        width: 70%;
        height: 100%;
    }

    .profile-img .file {
        position: relative;
        overflow: hidden;
        margin-top: 20%;
        width: 50%;
        border: none;
        border-radius: 0;
        font-size: 15px;
        background: #212529b8;
    }

    .profile-img .file input {
        position: absolute;
        opacity: 0;
        right: 0;
        top: 0;
    }

    .profile-head h5 {
        color: #333;
    }

    .profile-head h6 {
        color: #0062cc;
    }

    .profile-edit-btn {
        border: none;
        border-radius: 1.5rem;
        width: 70%;
        padding: 2%;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
    }

    .proile-rating {
        font-size: 12px;
        color: #818182;
        margin-top: 5%;
    }

    .proile-rating span {
        color: #495057;
        font-size: 15px;
        font-weight: 600;
    }

    .profile-head .nav-tabs {
        margin-bottom: 5%;
    }

    .profile-head .nav-tabs .nav-link {
        font-weight: 600;
        border: none;
    }

    .profile-head .nav-tabs .nav-link.active {
        border: none;
        border-bottom: 2px solid #0062cc;
    }

    .profile-work {
        padding: 14%;
        margin-top: -15%;
    }

    .profile-work p {
        font-size: 12px;
        color: #818182;
        font-weight: 600;
        margin-top: 10%;
    }

    .profile-work a {
        text-decoration: none;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
    }

    .profile-work ul {
        list-style: none;
    }

    .profile-tab label {
        font-weight: 600;
    }

    .profile-tab p {
        font-weight: 600;
        color: #0062cc;
    }

    .toggle.btn.btn-danger.off.ios {
        margin-top: 26px;
    }

    button {

        margin-bottom: 9px;
    }

    /* div#zoomModal {
    position: fixed !important;
    z-index: 1000000 !important;
    padding: 0px !important;
    left: 0px !important;
    top: 0px !important;
    width: 100% !important;
    height: 100% !important;
    overflow: auto !important;
    background-color: rgb(0 0 0 / 98%) !important;
    transition: all 0.3s ease 0s !important;
    display: block !important;

}

img#zoomModalImg {
	border-radius: 5px;
    cursor: move;
    margin: auto;
    display: block;
    max-width: 29% !important;
    max-height: 91% !important;
    transform: scale(1) translateX(0px) translateY(0px) rotate(
0deg);
    transform-origin: center center;
	zoom: 1.00 !important;
  min-zoom: 0.75 !important;
  max-zoom: 1.5 !important;
} */
</style>



<?php
$pro = 'profile' . $partner['id'] . '.jpg';
$aadharcard = 'aadhar_card_' . $partner['id'] . '.jpg';
$cancelcheque = 'cancel_cheque' . $partner['id'] . '.jpg';
$pancard = 'pan_card' . $partner['id'] . '.jpg';
$dirvinglicence = 'dirving_licence' . $partner['id'] . '.jpg';
$passbook = 'pass_book' . $partner['id'] . '.jpg';
?>

<div class="container deliverypartner">
    <div class="emp-profile">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <h4 class="ven subcategory" style="float:left;padding-bottom: 12px;">Delivery Partner Profile</h4>
            </div>
        </div>
        <!--<div class="row bordercfwd">
		<div class="col-md-2">
			<div class="profile-img">
				<h5>Profile Photo</h5>
				<img 
					src="<?php echo base_url(); ?>uploads/profile_image/profile_<?php echo $partner['unique_id']; ?>.jpg?<?php echo time(); ?>">
			</div>
		</div>
		<div class="profile-head">
			<h5 style="margin-left: 100px;">
            <?php echo $partner['first_name'] . '  ' . $partner['last_name']; ?><span class="badge badge-secondary desprove"><?php echo ($partner['delivery_partner_approval_status'] == 1) ? 'APPROVED' : 'DISAPPROVED' ?></span></h5>
            
            <h6 style="margin-left: 100px;">Delivery Partner Details</h6>
		</div>
	</div>-->
        <div class="row bordercfw">
            <div class="col-md-3">
                <div class="profile-work"></div>
            </div>
            <div class="col-md-8">
                <div class="row bordercfw">

                    <div class="col-md-5">
                        <div class="profile-img ">
                            <h5 style="float:left">Profiled Photo</h5><br />
                            <img class="zoom1 modal-target" id="mainimage5" style="width:33%;float: left;position: relative;left: -117px;" src="<?php echo base_url(); ?>uploads/profile_image/profile_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>">
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="profile-head">
                            <h5 style="">
                                <?php echo $partner['first_name'] . '  ' . $partner['last_name']; ?><span class="badge badge-secondary desprove"><?php echo ($partner['delivery_partner_approval_status'] == 1) ? 'APPROVED' : 'DISAPPROVED' ?></span></h5>

                            <h6 style="">Delivery Partner Status</h6>
                        </div>

                    </div>
                </div>

                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row bordercfd">
                            <div class="col-md-5">
                                <label>User Id</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $partner['id']; ?></p>
                            </div>
                        </div>
                        <!-- starting edit by ramakrishna  -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Full Name</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $partner['first_name'] . " " . $partner['last_name']; ?></p>
                            </div>
                        </div>
                        <!-- end edit by ramakrishna  -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $partner['email']; ?></p>
                            </div>
                        </div>

                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Mobile Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $partner['phone']; ?></p>
                            </div>
                        </div>

                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Aadhaar Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($partner['delivery_boy_biometrics']['aadhar'])) ? $partner['delivery_boy_biometrics']['aadhar'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <!-- starting edit by ramakrishna  -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Location</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($location['location'])) ? $location['location'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <!-- ramakrishna add 11/11/2021 -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Constituency</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($constituency['name'])) ? $constituency['name'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <!-- ramakrishna ends 11/11/2021 -->

                        <!-- <div class="row bordercf">
					<div class="col-md-5">
							<label>Permanent Address<span style="font-size: 8px;"> (as per aadhaar ).</span></label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner['permanent_address'])) ? $partner['permanent_address'] : 'NA'; ?></p>
						</div>
					</div> -->

                        <!-- <div class="row bordercf">
						<div class="col-md-5">
							<label>State</label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner['State'])) ? $partner['State'] : 'NA'; ?></p>
						</div>
					</div>

					<div class="row bordercf">
						<div class="col-md-5">
							<label>District</label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner['District'])) ? $partner['District'] : 'NA'; ?></p>
						</div>
					</div>

					<div class="row bordercf">
					     <div class="col-md-5">
							<label>Consituency</label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner['Consituency'])) ? $partner['Consituency'] : 'NA'; ?></p>
						</div>
					</div>
					
					<div class="row bordercf">
					    <div class="col-md-5">
							<label> Pincode</label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner[' Pincode'])) ? $partner[' Pincode'] : 'NA'; ?></p>
						</div>
					</div> -->

                        <!-- end edit by ramakrishna  -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>PAN Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($partner['delivery_boy_biometrics']['pan'])) ? $partner['delivery_boy_biometrics']['pan'] : 'NA'; ?></p>
                            </div>
                        </div>

                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Vehicle Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($partner['delivery_boy_biometrics']['vehicle_number'])) ? $partner['delivery_boy_biometrics']['vehicle_number'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Vechical Insurance Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($partner['delivery_boy_biometrics']['vehicle_insurance'])) ? $partner['delivery_boy_biometrics']['vehicle_insurance'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <!-- starting edit by ramakrishna  -->
                        <!-- <div class="row bordercf">
						<div class="col-md-5">
							<label>Where you want to be a deliever partner</label>
						</div>
						<div class="col-md-6">
							<p><?php echo (!empty($partner['deliever_partner'])) ? $partner['deliever_partner'] : 'NA'; ?></p>
						</div>
					</div> -->

                        <!-- end edit by ramakrishna  -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Driving License Number</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo (!empty($partner['delivery_boy_biometrics']['driving_license'])) ? $partner['delivery_boy_biometrics']['driving_license'] : 'NA'; ?></p>
                            </div>
                        </div>
                        <!--ramakrishna start 11/11/2021 -->
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Security Deposit Amount</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $security_deposite; ?></p>
                            </div>
                        </div>
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Security Deposit Txn Id</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $security_deposite_payment['txn_id']; ?></p>
                            </div>
                        </div>
                        <div class="row bordercf">
                            <div class="col-md-5">
                                <label>Security Deposit Txn Date</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo date($security_deposite_payment['created_at']); ?></p>
                            </div>
                        </div>


                        <!-- ramakrishna end 11/11/2021 -->

                        <div class="row">
                            <div class="col-md-8">
                                <ul class="nav nav-tabs" id="myTab" role="tablist"></ul>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!--Start  by ramakrishna -->
            <?php if ($this->ion_auth_acl->has_permission('delivery_partner_verfication')) : ?>
                <div class="table-responsive">

                    <table class="table table-striped table-hover dataTable no-footer" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="text-align:center">Aadhaar <br /> Card</th>
                                <th style="text-align:center">Pan <br /> Card</th>
                                <th style="text-align:center">Vehicle <br /> Images</th>
                                <th style="text-align:center">Driving <br /> Licence</th>
                                <th style="text-align:center">RC Card <br />Images</th>
                                <th style="text-align:center"> vehicle <br />Insurance Image</th>
                                <th style="text-align:center">Bank <br />information</th>
                                <th style="text-align:center">Canceled <br /> Check</th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <div class="container1">
                                        <label><b style="text-align:center">Front</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/aadhar_card_image/aadhar_card_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage1">
                                    </div>
                                    <div class="container2">
                                        <label><b style="text-align:center">Back</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/aadhar_card_image/aadhar_card_back_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage7">
                                    </div>
                                </td>

                                <td>
                                    <div class="container1">
                                        <label><b style="text-align:center">Front</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/pan_card_image/pan_card_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage2">
                                    </div>
                                    <div class="container2">
                                        <label><b style="text-align:center">Back</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/pan_card_image/pan_card_back_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage8">
                                    </div>
                                </td>

                                <td>
                                    <div class="container1">
                                        <label><b style="text-align:center">Front</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/vehicle_image/vehicle_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage3">
                                    </div>
                                    <div class="container2">
                                        <label><b style="text-align:center">Back</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/vehicle_image/vehicle_back_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage9">
                                    </div>
                                </td>

                                <td>
                                    <div class="container1">
                                        <label><b style="text-align:center">Front</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/driving_license_image/driving_license_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage4">
                                    </div>
                                    <div class="container2">
                                        <label><b style="text-align:center">Back</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/driving_license_image/driving_license_back_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage10">
                                    </div>
                                </td>

                                <td>
                                    <div class="container1">
                                        <label><b style="text-align:center">Front</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/rc_image/rc_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage6">
                                    </div>
                                    <div class="container2">
                                        <label><b style="text-align:center">Back</b></label><br />
                                        <img src="<?php echo base_url(); ?>uploads/rc_image/rc_back_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage6">
                                    </div>
                                </td>

                                <td>
                                    <div class="container1"><img src="<?php echo base_url(); ?>uploads/vehicle_insurance_image/vehicle_insurance_front_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage6">
                                    </div>

                                </td>
                                <td>
                                    <div class="container1"><img src="<?php echo base_url(); ?>uploads/bank_passbook_image/bank_passbook_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage11">
                                    </div>

                                </td>
                                <td>
                                    <div class="container1"><img src="<?php echo base_url(); ?>uploads/cancellation_cheque_image/cancellation_cheque_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>" width="5" id="mainimage12">
                                    </div>

                                </td>
                                <!-- <td>
							<div class="container1">
							
								<img
									src="<?php echo base_url(); ?>uploads/rc_image/rc_<?php echo $partner['id']; ?>.jpg?<?php echo time(); ?>"
									width="5" id="mainimage6">
							</div>
							
						</td> -->
                                <!--end by ramakrishna -->
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" id="" class="adhar_card_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['adhar_card_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['adhar_card_message'] ?></label>
                                    <select id="aadhar_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="addhar_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>

                                <td>
                                    <input type="checkbox" id="" class="pan_card_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['pan_card_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['pan_card_message'] ?></label>
                                    <select id="pan_card_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="pan_card_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>

                                <td>
                                    <input type="checkbox" id="" class="cancel_cheque_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['cancel_cheque_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['cancel_cheque_message'] ?></label>
                                    <select id="cancel_cheque_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="cancel_cheque_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>

                                <td>
                                    <input type="checkbox" id=" " class="driving_licence_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['driving_licence_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['driving_licence_message'] ?></label>
                                    <select id="driving_licence_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="driving_licence_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>

                                <td>
                                    <input type="checkbox" id=" " class="pass_book_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['pass_book_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['pass_book_message'] ?></label>
                                    <select id="pass_book_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="pass_book_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>

                                <td>
                                    <input type="checkbox" id=" " class="rc_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['rc_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['rc_message'] ?></label>
                                    <select id="rc_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="rc_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>
                                <!-- ramakrishna start 11/11/2021-->
                                <td>

                                    <input type="checkbox" id=" " class="rc_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['rc_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['rc_message'] ?></label>
                                    <select id="rc_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="rc_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>


                                </td>
                                <!-- ramakrishna end 11/11/2021 -->
                                <td>
                                    <input type="checkbox" id=" " class="rc_toggle checkw" user_id="<?php echo $doc['created_user_id']; ?>" <?php echo ($doc['rc_status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Approved" data-off="Dispprove" data-onstyle="success" data-offstyle="danger">
                                    <label style="color: red"><?php echo $doc['rc_message'] ?></label>
                                    <select id="rc_reason" class="form-control srnew" style="margin-top: 12px;margin-bottom: 12px;">
                                        <option value="0" selected disabled>Select..</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">pending</option>
                                        <option value="Image is not clear! Can you please upload it again?">Image is not clear! Can you please upload it again?</option>
                                    </select>
                                    <button class='btn btn-primary mtnct' id="rc_button" style="margin-top: 7px;padding: 3px;
    width: 64px;">submit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <form id="form-smtp" action="<?php echo base_url('admin/delivery_partner/bank_details'); ?>" class="needs-validation form" novalidate="" method="post">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                    </div>

                    <h2 class="card-title ven ">Bank Details</h2>
                </header>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 ">A/C Holder Name<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_holder_name" class="form-control" placeholder="A/C Holder Name" required="" value="<?php echo $bank_details['ac_holder_name'] ?>">
                        </div>
                        <?php echo form_error('ac_holder_name', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">Bank Name<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required="" value="<?php echo $bank_details['bank_name'] ?>">
                        </div>
                        <?php echo form_error('bank_name', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">Bank Branch<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="bank_branch" class="form-control" placeholder="Bank Branch" required="" value="<?php echo $bank_details['bank_branch'] ?>">
                        </div>
                        <?php echo form_error('bank_branch', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">A/C Number<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_number" class="form-control" placeholder="A/C Number" required="" value="<?php echo $bank_details['ac_number'] ?>">
                        </div>
                        <?php echo form_error('ac_number', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">IFSC Code<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ifsc" class="form-control" placeholder="IFSC Code" required="" value="<?php echo $bank_details['ifsc'] ?>">
                        </div>
                        <?php echo form_error('ifsc', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <input type="hidden" name="user_id" value="<?php echo $_GET['id'] ?>" />
                            <button class="btn btn-primary">Submit</button>
                            <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                        </div>
                    </div>
                </div>

            </section>
        </form>
    </div>
</div>

<div div class="row">
    <div class="col-12">

        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                </div>

                <h2 class="card-title ven ">Current Location</h2>
            </header>
            <div class="card-body">

                <div class="row">
                    <label class="col-sm-3 "><b>Longitude</b></label>
                    <div class="col-sm-9">
                        <p><?php echo $current_location['longitude']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 "><b>Latitude</b></label>
                    <div class="col-sm-9">
                        <p><?php echo $current_location['latitude']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 "><b>Address</b></label>
                    <div class="col-sm-9">
                        <p><?php echo $current_location['address']; ?></p>
                    </div>
                </div>

            </div>

        </section>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/zoom/ezoom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        ezoom.onInit($('#mainimage1'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });

        ezoom.onInit($('#mainimage2'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });

        ezoom.onInit($('#mainimage3'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });


        ezoom.onInit($('#mainimage4'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });


        ezoom.onInit($('#mainimage5'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });
        ezoom.onInit($('#mainimage6,#mainimage7,#mainimage8,#mainimage9,#mainimage10,#mainimage11,#mainimage12'), {
            hideControlBtn: false,
            onClose: function(result) {
                console.log(result);
            },
            onRotate: function(result) {
                console.log(result);
            },
        });


    });
</script>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>