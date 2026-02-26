<!--Add User And its list-->
 

 <?php
 $pro = 'profile'.$partner['unique_id'].'.jpg';
 $aadharcard = 'aadhar_card_'.$partner['unique_id'].'.jpg';
 $cancelcheque = 'cancel_cheque'.$partner['unique_id'].'.jpg';
  $pancard = 'pan_card'.$partner['unique_id'].'.jpg';

 $dirvinglicence = 'dirving_licence'.$partner['unique_id'].'.jpg';
 $passbook = 'pass_book'.$partner['unique_id'].'.jpg';
  $rcdoc = 'rc_doc'.$partner['unique_id'].'.jpg';
   
 ?>
 <div class="row">
 <div class="col-12">
		<h4 class="ven subcategory">Add Delivery Partner</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('delivery_partner/c/0'); ?>" method="post" enctype="multipart/form-data">
			<input type = "hidden" name = "id" value = "<?php echo $partner['id']; ?>">
			 
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>First Name</label> <input type="text" name="first_name" placeholder="First Name" class="form-control" required="" value = "<?php echo $partner['first_name']; ?>">
						<div class="invalid-feedback">Enter First Name?</div>
						<?php echo form_error('first_name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Last Name</label> <input type="text" name="last_name" placeholder="Last Name" class="form-control" required="" value = "<?php echo $partner['last_name']; ?>">
						<div class="invalid-feedback">Enter Last Name?</div>
						<?php echo form_error('last_name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Mobile No.</label> <input type="tel"  id="phone" name="phone"  maxlength="10" placeholder="Mobile No" class="form-control" required="" value = "<?php echo $partner['phone']; ?>">
						<div class="invalid-feedback">Enter Mobile number?</div>
						<?php echo form_error('phone','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Email ID</label> <input type="email" name="email" placeholder="Email ID" class="form-control" required="" value = "<?php echo $partner['email']; ?>">
						 
					</div>
					<!--<div class="form-group col-md-6">
						<label>Password</label> <input type="password" class="form-control" placeholder="Password" name="password" id="Password" required="" value = "<?php //echo $partner['password']; ?>">
						<?php //echo form_error('password','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Confirm Password</label> <input type="password"
							class="form-control" name="confirm_password" id="ConfirmPassword" placeholder="Confirm Password"
							required="" value = "<?php //echo $partner['password']; ?>">
						<?php //echo form_error('confirm_password','<div style="color:red">','</div>')?>
					</div>-->

					<div class="form-group col-md-6">
						<label>location</label>  
						 <input type="text" name="location" placeholder="location" class="form-control" required="" value = "<?php echo $partner['location_id']; ?>">
					</div>

                    

					<div class="form-group col-md-6">
						<label>Aadhaar card number</label>  
						 <input type="text" name="adhar_card_number" placeholder="Aadhaar card number" class="form-control" required="" value = "<?php echo $partner['aadhar_number']; ?>">
					</div>


					<div class="form-group col-md-6">
						<label>pan card number</label>  
            <img src = "<?php echo base_url(); ?>assets/pan_card_image/<?php echo $pancard ?>"   style = "width: 60px;">
						 <input type="text" name="pan_card_number" placeholder="pan card number"class="form-control" required="" value = "<?php echo $partner['pan_card_number']; ?>">
					</div>

                 <div class="form-group col-md-6">
						<label>driving license number</label>  
						 <input type="text" name="driving_license_number" placeholder="Driving licence number"class="form-control" required="" value = "<?php echo $partner['driving_license_number']; ?>">
					</div>

					                 <div class="form-group col-md-6">
						<label>vehicle number</label>  
						 <input type="text" name="vehicle_number" placeholder="vehicle number"class="form-control" required="" value = "<?php echo $partner['vehicle_number']; ?>">
					</div>

					<div class="form-group col-md-6">
						<label>Upload aadhaar card image</label> 
						<img src = "<?php echo base_url(); ?>assets/aadhar_card_image/<?php echo $aadharcard ?>" style = "width: 60px;"> 
						 <input type="file" name="adhar_image" placeholder="Aadhaar image"class="form-control" >
					</div>

                    <div class="form-group col-md-6">
						<label>Upload pan card image</label> 

						<img src = "<?php echo base_url(); ?>assets/pan_card_image/<?php echo $pancard ?>" style = "width: 60px;">

						 <input type="file" name="pan_image" placeholder="pan image"class="form-control" >
					</div>

					<div class="form-group col-md-6">
						<label>Upload bank passbook image</label>  
						<img src = "<?php echo base_url(); ?>assets/passbook_image/<?php echo $passbook ?>"  style = "width: 60px;">
						 <input type="file" name="bank_passbook" placeholder="bank passbook"class="form-control" >
					</div>


<div class="form-group col-md-6">
						<label>cancel cheque image</label>  
						<img src = "<?php echo base_url(); ?>assets/cancel_cheque_image/<?php echo $cancelcheque ?>"  style = "width: 60px;">
						 <input type="file" name="cancelcheck_image" placeholder="cancel check"class="form-control" >
					</div>



<div class="form-group col-md-6">
						<label>Upload RC document</label>  
						<img src = "<?php echo base_url(); ?>assets/rc_image/<?php echo $rcdoc ?>"       style = "width: 60px;"> 
						 <input type="file" name="rc_doc" placeholder="pan rc document"class="form-control">
					</div>



                   <div class="form-group col-md-6">
						<label>Upload Driving licence</label>  
						<img src = "<?php echo base_url(); ?>assets/dirving_licence_image/<?php echo $dirvinglicence ?>"       style = "width: 60px;"> 
						 <input type="file" name="dirving_licence_image" placeholder="Driving Licence"class="form-control">
					</div>


                   <div class="form-group col-md-6">
						<label>Upload Profile Image</label>  
						<img src = "<?php echo base_url(); ?>assets/profile_image/<?php echo $pro ?>"  style = "width: 60px;">
						 <input type="file" name="profile_image" placeholder="profile image"class="form-control">
					</div>


					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
					</div>
				</div>


			</div>
		</form>