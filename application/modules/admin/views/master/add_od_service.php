<!--Add Amenity And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add On Demand Service</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('od_services/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Service Name</label> <input type="text"
							class="form-control" name="name" placeholder=" Name" required="" value=<?php echo set_value('name')?>>
						<div class="invalid-feedback">New Amenity Name?</div>
						<?php echo form_error('name','<div style="color:red">','</div>');?>
						
					</div>

					<div class="form-group col-md-4">
						<label>Od Category</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select class="form-control" name="od_cat_id" required="">
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($od_categories as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id', '<div style="color:red">', '</div>');?>
					</div>

						<div class="form-group col-md-4">
						<label>Service Duration</label> <input type="text"
							class="form-control" name="service_duration" placeholder="Service Duration" required="" value=<?php echo set_value('service_duration')?>>
						<div class="invalid-feedback">New Amenity Name?</div>
						<?php echo form_error('service_duration','<div style="color:red">','</div>');?>
						
					</div>
						<div class="form-group col-md-4">
						<label>Service Price</label> <input type="number"
							class="form-control" name="price" placeholder="Service Price" required="" value=<?php echo set_value('price')?>>
						<div class="invalid-feedback">New Amenity Name?</div>
						<?php echo form_error('price','<div style="color:red">','</div>');?>
						
					</div>
					<div class="form-group mb-0 col-md-4">
						<label>Discount</label> <input type="number" class="form-control"
							name="discount" required="" placeholder="Discount" <?php set_value('discount')?>>
						<div class="invalid-feedback">Give some Description</div>
						<?php echo form_error('discount','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group col-md-4">
						<label>Upload Image</label> <input type="file"
							class="form-control" name="file" required="" value="<?php echo set_value('file')?>">
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					 <div class="col col-sm col-md-12" >
			          <label>Description</label>
			            <textarea id="od_service_desc" name="desc" class="ckeditor" rows="10" data-sample-short></textarea>
			           <?php echo form_error('desc', '<div style="color:red">', '</div>');?>
			         </div>
					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>

		

	</div>
</div>

