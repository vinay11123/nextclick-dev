<!--Add User And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Delivery Partner</h4>
</div>
</div>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('delivery_partner/c/0'); ?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>First Name</label> <input type="text" name="first_name" placeholder="First Name"
							class="form-control" required="" >
						<div class="invalid-feedback">Enter First Name?</div>
						<?php echo form_error('first_name','<div style="color:red">','</div>')?>
					</div>

					<div class="form-group col-md-6">
						<label>Last Name</label> <input type="text" name="last_name" placeholder="Last Name"
							class="form-control" required="">
						<div class="invalid-feedback">Enter Last Name?</div>
						<?php echo form_error('last_name','<div style="color:red">','</div>')?>
					</div>

					<div class="form-group col-md-6">
						<label>Mobile No.</label> <input type="tel"  id="phone" name="phone"  maxlength="10" placeholder="Mobile No"
							class="form-control" required="" >
						<div class="invalid-feedback">Enter Mobile number?</div>
						<?php echo form_error('phone','<div style="color:red">','</div>')?>
					</div>

					<div class="form-group col-md-6">
						<label>Email ID</label> <input type="email" name="email" placeholder="Email ID" class="form-control" required="">
						 
					</div>

					<div class="form-group col-md-6">
						<label>Password</label> <input type="password" class="form-control" placeholder="Password" name="password" id="Password" required="">
						<?php echo form_error('password','<div style="color:red">','</div>')?>
					</div>
					
					<div class="form-group col-md-6">
						<label>Confirm Password</label> <input type="password"
							class="form-control" name="confirm_password" id="ConfirmPassword" placeholder="Confirm Password"
							required="">
						<?php echo form_error('confirm_password','<div style="color:red">','</div>')?>
					</div>

					<div class="form-group col-md-6">
						<label>location</label>  
						 <input type="text" name="location" placeholder="location"
							class="form-control" required="">
					</div>

                    

					<div class="form-group col-md-6">
						<label>Adhar card number</label>  
						 <input type="text" name="adhar_card_number" placeholder="adhar card number" class="form-control" required="">
					</div>


					<div class="form-group col-md-6">
						<label>pan card number</label>  
						 <input type="text" name="pan_card_number" placeholder="pan card number"class="form-control" required="">
					</div>

                 <div class="form-group col-md-6">
						<label>driving license number</label>  
						 <input type="text" name="driving_license_number" placeholder="Driving licence number"class="form-control" required="">
					</div>

					                 <div class="form-group col-md-6">
						<label>vehicle number</label>  
						 <input type="text" name="vehicle_number" placeholder="vehicle number"class="form-control" required="">
					</div>

					<div class="form-group col-md-6">
						<label>Upload ahdar card image</label>  
						 <input type="file" name="adhar_image" placeholder="adhar image"class="form-control" required="">
					</div>

                    <div class="form-group col-md-6">
						<label>Upload pan card image</label>  
						 <input type="file" name="pan_image" placeholder="pan image"class="form-control" required="">
					</div>

					<div class="form-group col-md-6">
						<label>Upload bank passbook image</label>  
						 <input type="file" name="bank_passbook" placeholder="bank passbook"class="form-control" required="">
					</div>


<div class="form-group col-md-6">
						<label>cancel cheque image</label>  
						 <input type="file" name="cancelcheck_image" placeholder="cancel check"class="form-control" required="">
					</div>



<div class="form-group col-md-6">
						<label>Upload RC document</label>  
						 <input type="file" name="rc_doc" placeholder="pan rc document"class="form-control" required="">
					</div>



                   <div class="form-group col-md-6">
						<label>Upload Driving licence</label>  
						 <input type="file" name="dirving_licence_image" placeholder="Driving Licence"class="form-control" required="">
					</div>


                   <div class="form-group col-md-6">
						<label>Upload Profile Image</label>  
						 <input type="file" name="profile_image" placeholder="profile image"class="form-control" required="">
					</div>


					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
					</div>
				</div>


			</div>
		</form>