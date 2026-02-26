 
 
<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Vechile</h4>

		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('vehicle/c/0');?>" method="post">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Vehicle Name<span class="text-danger">*</span></label> <input type="text"
							class="form-control" name="vehiclename" required="" value="">
						 
							<div class="invalid-feedback">Give vehicle name</div>
						<?php echo form_error('vehiclename','<div style="color:red">','</div>')?>
					</div>
					 

                    <div class="form-group col-md-4">
						<label>Min Capacity (in gms)<span class="text-danger">*</span></label> <input type="number"
							class="form-control" name="mincapecity" required="" value="">
							<div class="invalid-feedback">Give Min Capacity</div>
						<?php echo form_error('mincapecity','<div style="color:red">','</div>')?>
					</div>

                    <div class="form-group col-md-4">
						<label>Max Capacity (in gms) <span class="text-danger">*</span> </label> <input type="number"
							class="form-control" name="maxcapecity" required="" value="">
						 <p style="color:red">Note: Max total order weight in(gms) <?php echo $max_order_weight; ?></p>
						 <div class="invalid-feedback">Give Max Capacity</div>
						<?php echo form_error('maxcapecity','<div style="color:red">','</div>')?>
					</div>
               </div>

               <div class="form-row">

               <div class="form-group col-md-4">
						<label>Description </label> 
                        <textarea class="form-control" id="description" rows="6" name = "description"></textarea>
					</div>
					 
               </div>
   
 


					<div class="form-group col-md-12 mt-4 pt-2">

						<button class="btn btn-primary mt-27 " type = "submit" name = "submit">Submit</button>
					</div>


				</div>


			</div>
		</form>


	 