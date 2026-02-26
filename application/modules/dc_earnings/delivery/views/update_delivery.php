 
 <div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('vehicle/r/0');?>">Delivery<i class="fa fa-angle-double-left"></i> 
Vehicle</a> 
   
    </div>
    </div>
<div class="row">
	<div class="col-12">
		<h4 class="ven">Update vehicle</h4>

		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('vehicle/u/o');?>" method="post">
			<div class="card-header">
<input type = "hidden" name = "id" value = "<?php echo $updelivery['id']; ?>">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Vehicle Name<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="vehiclename" required="" value="<?php echo $updelivery['name']; ?>">
						<div class="invalid-feedback">Give vehicle name</div>
						<?php echo form_error('vehiclename','<div style="color:red">','</div>')?>
					</div>
					 

                    <div class="form-group col-md-4">
						<label>Min Capacity<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="mincapecity" required="" value="<?php echo $updelivery['min_capacity']; ?>">
							<div class="invalid-feedback">Give Min Capacity</div>
						<?php echo form_error('mincapecity','<div style="color:red">','</div>')?>
					</div>

                    <div class="form-group col-md-4">
						<label>Max Capacity<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="maxcapecity" required="" value="<?php echo $updelivery['max_capacity_end']; ?>">
						 <p style="color:red">Note: Max total order weight in(gms) <?php echo $max_order_weight; ?></p>
						 <div class="invalid-feedback">Give Max Capacity</div>
						<?php echo form_error('maxcapecity','<div style="color:red">','</div>')?>
					</div>
               </div>

               <div class="form-row">

               <div class="form-group col-md-4">
						<label>Description </label> 
                        <textarea class="form-control" id="description" rows="6" name = "description"><?php echo $updelivery['desc']; ?></textarea>
					</div>
					 
               </div>
   
 


					<div class="form-group col-md-12 mt-4 pt-2">

						<button class="btn btn-primary mt-27 " type = "submit" name = "submit">Submit</button>
					</div>


				</div>


			</div>
		</form>

 