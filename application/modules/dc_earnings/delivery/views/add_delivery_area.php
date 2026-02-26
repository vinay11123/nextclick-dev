 <!--Add User And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Delivery Area Rate</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('delivery_area/c/0'); ?>" method="post">
			<div class="card-header">
				<div class="form-row">
					 
             <div class="form-group col-md-6">
						<label>State Name<span class="text-danger">*</span></label>
						<select class="form-control " id="state_id" name="state_id" required="" >
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($state as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">Select State Name</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
						 
					 
					<div class="form-group col-md-6">
						 <label>District Name<span class="text-danger">*</span></label>
						<select class="form-control " id="district_id" name="district_id" required="">
							<option value="">Select District</option>
    						 
    							 
    							 
						</select>
						<div class="invalid-feedback">Select District Name</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						 
						  <label>Constituancy Name<span class="text-danger">*</span></label>
						<select class="form-control " id="constituancy_id" name="constituency_id" required="" >
							<option value="conall">--select--</option>
    							 
						</select>
						<div class="invalid-feedback">Select Constituancy Name</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Vechile Type<span class="text-danger">*</span></label>  
				   <select class="form-control " id="vechile" name="vehicle_type_id" required="" >
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($vechile as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">Select Vechile Type</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
						 
					</div>
					<!-- <div class="form-group col-md-6">
						<label>Constituancy Distance<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="constituency_km" id="Perkm" placeholder="Constituancy Distance"
							required="">
						<div class="invalid-feedback">Give Constituancy Distance</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div> -->
					<div class="form-group col-md-6">
						<label>Venor to User Max Distance (in Km)<span class="text-danger">*</span></label> <input type="number" class="form-control" placeholder="Vendor to User Max Distance in KM" name="vendor_to_user_max_distance" id="vendor_to_user_max_distance" required="">
						<div class="invalid-feedback">Give Venor to User Max Distance (in Km)</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Venor to Delivery Boy Max Distance (in Km)<span class="text-danger">*</span></label> <input type="number" class="form-control" placeholder="Vendor to Delivery Boy Max Distance in KM" name="vendor_to_delivery_captain_max_distance" id="vendor_to_delivery_captain_max_distance" required="">
						<div class="invalid-feedback">Venor to Delivery Boy Max Distance (in Km)</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Flat Distance (in Km)<span class="text-danger">*</span></label> <input type="number" class="form-control" placeholder="Flat Distance in KM" name="flatdistance" id="flatdistance" required="">
						<div class="invalid-feedback">Give Flat Distance</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Delivery Boy Flat Rate<span class="text-danger">*</span></label> <input type="text" class="form-control" placeholder="Delivery Boy Flat Rate" name="rlatrate" id="rlatrate" required="">
						<div class="invalid-feedback">Give Flat Rate</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>NC Flat Rate<span class="text-danger">*</span></label> <input type="text" class="form-control" placeholder="NC Flat Rate" name="nc_flat_rate" id="nc_flat_rate" required="">
						<div class="invalid-feedback">Give NC Flat Rate</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>Delivery Boy Rate Per Km After Flat Distance<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="per_km" id="Perkm" placeholder="Enter Delivery Boy Rate Per Km"
							required="">
						<div class="invalid-feedback">Give Rate after Flat Distance</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-6">
						<label>NC Rate Per Km After Flat Distance<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="nc_per_km" id="nc_per_km" placeholder="Enter NC Rate Per Km"
							required="">
						<div class="invalid-feedback">Give NC Rate after Flat Distance</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>

					

					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
					</div>
				</div>


			</div>
		</form>