 <!--Add User And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Banner Area Rate</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('banner_cost/c/0'); ?>" method="post">
			<div class="card-header">
				<div class="form-row">
					 
             <div class="form-group col-md-6">
						<label>State Name</label>
						<select class="form-control " id="state_id" name="state_id" required="" >
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($state as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
					</div>
						 
					 
					<div class="form-group col-md-6">
						 <label>District Name</label>
						<select class="form-control " id="district_id" name="district_id" required="">
							<option value="">Select District</option>
    						 
    							 
    							 
						</select>
					</div>
					<div class="form-group col-md-6">
						 
						  <label>Constituancy Name</label>
						<select class="form-control " id="constituancy_id" name="constituency_id" required="" >
							<option value="conall">--select--</option>
    							 
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Banner Type</label>  
				   <select class="form-control " id="banner_type" name="banner_type" required="" >
							<option value="" selected disabled>--select--</option>
    								<option value="1">Big Offer</option>
    								<option value="2">Vendor Promotion Offer</option>
						</select>
						 
					</div>
					<div class="form-group col-md-6">
						<label>Cost (per day)</label> <input type="text" class="form-control" placeholder="Cost" name="rlatrate" id="rlatrate" required="">
					</div>
					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
					</div>
				</div>


			</div>
		</form>