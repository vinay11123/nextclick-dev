<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<!--Add User And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Users</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('employee/c/0'); ?>" method="post">
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>First Name</label> <input type="text" name="first_name" placeholder="First Name"
							class="form-control" required="" >
							<?php //echo base_url('employee/c/0');?>
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
						<label>Mobile No.</label> <input type="tel"  id="mobile" name="phone"  maxlength="10" placeholder="Mobile No"
							class="form-control" required="" >
						<div class="invalid-feedback">Enter Mobile number?</div>
						<div style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;display:none" id="invalid">Mobile no already exists in our database!!</div>
						<?php echo form_error('phone','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Email ID</label>
						<input type="email" 
							   name="email" 
							   id="email"
							   placeholder="Email ID"
							   class="form-control"
							   required>
						<div class="invalid-feedback">Only @nextclick.in email is allowed</div>
						<?php echo form_error('email','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Password</label> <input type="password" class="form-control" placeholder="Password"
							name="password" id="Password" required="">
						<?php echo form_error('password','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Confirm Password</label> <input type="password"
							class="form-control" name="confirm_password" id="ConfirmPassword" placeholder="Confirm Password"
							required="">
						<?php echo form_error('confirm_password','<div style="color:red">','</div>')?>
					</div>

					<div class="form-group col-md-6">
						<label>Role(Group)</label> <br>
						<select id="example-getting-started" class="form-control" name="role[]">
						<option value="" selected disabled>--select--</option>
                                                    	<?php foreach ($groups as $group):?>
                                                        	<option 
                        								value="<?php echo $group['id'];?>"><?php echo $group['name']?></option>
                                                        <?php endforeach;?>
                        </select>
						<div class="invalid-feedback">Select the role for User?</div>
					</div>

					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
					</div>
				</div>


			</div>
		</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

   $(document).ready(function(){
            $("#mobile").keyup(function(){
				var search = $(this).val();

                if(search != ""){
					
                    $.ajax({
                        url: '<?= site_url() ?>admin/check_number',
                        type: 'post',
                        data: {search:search},
                        dataType: 'json',
                        success:function(response){
                            console.log(response);
							if(response ==null)
							{
								document.getElementById("invalid").style.display = "none";
								document.getElementById("btnSubmit").disabled  = false;


								
							}
							else{
								document.getElementById("invalid").style.display = "block";
								document.getElementById("btnSubmit").disabled  = true;
							}
                                
                            }
                            });
                        }
                    });
                });
</script>
<script>
$(document).ready(function(){

    const excludedGroups = ["vendor", "executive","user","delivery_partner"];
    const regex = /^[a-zA-Z0-9._%+-]+@nextclick\.in$/;

    const emailField = $("#email");
    const roleSelect = $("#example-getting-started");

    function validateEmail() {
        const email = emailField.val();
        const selectedRole = roleSelect.find("option:selected").text().trim().toLowerCase();

        // If Vendor or Executive → remove validation
        if(excludedGroups.includes(selectedRole)) {
            emailField.prop("required", false);
            emailField[0].setCustomValidity("");
            emailField.removeClass("is-invalid is-valid");
            return;
        } else {
            // Other roles → enforce validation
            emailField.prop("required", true);
            if(email === "" || regex.test(email)) {
                emailField[0].setCustomValidity("");
                emailField.removeClass("is-invalid").addClass("is-valid");
            } else {
                emailField[0].setCustomValidity("Invalid email. Only @nextclick.in allowed");
                emailField.removeClass("is-valid").addClass("is-invalid");
            }
        }
    }

    // Validate while typing in email
    emailField.on("input", validateEmail);

    // Validate when role changes
    roleSelect.on("change", validateEmail);

});
</script>