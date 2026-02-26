 <!--Add User And its list-->
 <div class="row pb-4">
 	<div class="col-md-12">
 		<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('delivery_area/r/0'); ?>">Delivery<i class="fa fa-angle-double-left"></i>
 			Delivery Area</a>

 	</div>
 </div>

 <div class="row">
 	<div class="col-12">
 		<h4 class="ven subcategory">Delivery Area Rate</h4>
 		<form class="needs-validation" novalidate="" action="<?php echo base_url('delivery_area/u/0'); ?>" method="post">
 			<div class="card-header">
 				<div class="form-row">
 					<input type="hidden" name="id" value="<?php echo $updatearea['id']; ?>">
 					<div class="form-group col-md-6">
 						<label>State Name</label>
 						<select class="form-control " id="state_id" name="state_id" required="">
 							<option value="" selected disabled>--select--</option>
 							<?php foreach ($state as $item) : ?>
 								<option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $updatearea['state_id']) {
																				echo "selected";
																			} ?>><?php echo $item['name'] ?></option>
 							<?php endforeach; ?>
 						</select>
 					</div>


 					<div class="form-group col-md-6">
 						<label>District Name</label>
 						<select class="form-control " id="district_id" name="district_id" required="">
 							<!--<option value="" >Select District</option>-->
 							<?php
								if ($item['district_id'] !== '') {
									$districtdata = $this->district_model->get($updatearea['district_id']);
								?>
 								<option value="<?php echo $districtdata['id'] ?>" <?php if ($districtdata['id'] == $updatearea['district_id']) {
																						echo "selected";
																					} ?>><?php echo $districtdata['name'] ?></option>
 							<?php }
								?>

 						</select>
 					</div>

 					<div class="form-group col-md-6">

 						<label>Constituency Name</label>
 						<select class="form-control " id="constituancy_id" name="constituancy_id" required="">
 							<option value="conall">--select--</option>
 							<?php
								if ($item['constituencies_id'] !== '') {
									$constidata = $this->constituency_model->get($updatearea['constituency_id']);

								?>
 								<option value="<?php echo $constidata['id'] ?>" <?php if ($constidata['id'] == $updatearea['constituency_id']) {
																						echo "selected";
																					} ?>><?php echo $constidata['name'] ?></option>
 							<?php }
								?>

 						</select>
 					</div>
 					<div class="form-group col-md-6">
 						<label>Vechile Type</label>

 						<select class="form-control " id="vechile" name="vechile" required="">
 							<option value="" selected disabled>--select--</option>
 							<?php foreach ($vechile as $item) : ?>
 								<option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $updatearea['vehicle_type_id']) {
																				echo "selected";
																			} ?>>
 									<?php echo $item['name'] ?></option>
 							<?php endforeach; ?>
 						</select>

 					</div>

 					<!-- <div class="form-group col-md-6">
 						<label>Constituancy Distance</label> <input type="text" class="form-control" name="constituency_km" id="Perkm" placeholder="Constituancy Distance" value="<?php echo $updatearea['constituency_km']; ?>">
 					</div> -->

 					<div class="form-group col-md-6">
 						<label>Venor to User Max Distance (in Km)</label> <input type="number" class="form-control" placeholder="Venor to User Max Distance (in Km)" name="vendor_to_user_max_distance" id="vendor_to_user_max_distance" value="<?php echo $updatearea['vendor_to_user_max_distance']; ?>" required="">
 					</div>
					 <div class="form-group col-md-6">
 						<label>Venor to Delivery Boy Max Distance (in Km)</label> <input type="number" class="form-control" placeholder="Venor to Delivery Boy Max Distance (in Km)" name="vendor_to_delivery_captain_max_distance" id="vendor_to_delivery_captain_max_distance" value="<?php echo $updatearea['vendor_to_delivery_captain_max_distance']; ?>" required="">
 					</div>
					 <div class="form-group col-md-6">
 						<label>Flat Distance (in Km)</label> <input type="number" class="form-control" placeholder="Max Distance in KM" name="flatdistance" id="flatdistance" value="<?php echo $updatearea['flat_distance']; ?>" required="">
 					</div>
 					<div class="form-group col-md-6">
 						<label>Delivery Boy Flat Rate</label> <input type="text" class="form-control" placeholder="Delivery Boy Flat Rate" name="rlatrate" id="rlatrate" value="<?php echo $updatearea['flat_rate']; ?>">
 					</div>
					 <div class="form-group col-md-6">
 						<label>NC Flat Rate</label> <input type="text" class="form-control" placeholder="NC Flat Rate" name="nc_flat_rate" id="nc_flat_rate" value="<?php echo $updatearea['nc_flat_rate']; ?>">
 					</div>
 					<div class="form-group col-md-6">
 						<label>Delivery Boy Per Km After Flat Distance</label> <input type="text" class="form-control" name="Perkm" id="Perkm" placeholder="Delivery Boy Enter Rate Per Km" value="<?php echo $updatearea['per_km']; ?>">
 					</div>
					 <div class="form-group col-md-6">
 						<label>NC Per Km After Flat Distance</label> <input type="text" class="form-control" name="nc_per_km" id="nc_per_km" placeholder="NC Enter Rate Per Km" value="<?php echo $updatearea['nc_per_km']; ?>">
 					</div>



 					<div class="form-group col-md-12">

 						<button class="btn btn-primary mt-27 " id="btnSubmit">Submit</button>
 					</div>
 				</div>


 			</div>
 		</form>