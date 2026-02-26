<!--Add Amenity -->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Amenity</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('amenity/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">

					<div class="form-group mb-0 col-md-4">
						<label>Amenity Name</label> <input type="text" class="form-control" name="name" id="name" required="" placeholder="Name" <?php echo set_value('name')?>>
						<div class="invalid-feedback">Give Amenity Name</div>
						<?php echo form_error('title','<div style="color:red">','</div>');?>
					</div>
					
					<div class="form-group col-md-4">
						<label>Category</label>
						<select class="form-control" name="cat_id" required ="" >
								<option value="" selected disabled>--select--</option>
    							<?php foreach ($categories as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">Select Category</div>
						<?php echo form_error('cat_id', '<div style="color:red">', '</div>');?>
					</div>

					<div class="form-group mb-0 col-md-4">
						<label>Description</label> <input type="text" class="form-control" name="desc" id="desc" required="" placeholder="Description" <?php echo set_value('desc')?>>
						<div class="invalid-feedback">Give Description</div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group mb-0 col-md-3">
						<label>Upload Image</label> 
						<input type="file"  accept="image/jpeg, image/png" name="file" required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);">
							<div class="invalid-feedback">Upload Image</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-1">
					<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/amenity_image/amenity_<?php echo $amenity['id']; ?>.jpg">
						
					
					</div>
					
					<div class="form-group mb-0 col-md-2">

						<button class="btn btn-primary mt-27 mt" type="submit" name="upload" id="upload">Submit</button>
					</div>


				</div>


			</div>
		</form>
    </div>


</div>