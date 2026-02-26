<!--Add Category-->
<div class="row">
	<div class="col-12">
		<h4 class="ven venclr">Add Category</h4>
		  
		<form class="needs-validation" novalidate="" action="<?php echo base_url('category/c');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-group row">

					<div class="form-group mb-0 col-md-3">
						<label>Category Name</label> <input type="text" class="form-control" name="name" id="name"  required="" placeholder="Name" <?php echo set_value('name')?>>
						<div class="invalid-feedback">Give Category Name</div>
						<?php echo form_error('name','<div style="color:red">','</div>');?>
					</div>
					
					<div class="form-group mb-0 col-md-3">
						<label>Description</label> <input type="text" class="form-control" name="desc" id="desc" required="" placeholder="Description" <?php echo set_value('desc')?>>
						<div class="invalid-feedback">Give Description</div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group col-md-3">
						<label>Services</label>
						<select id="services_multiselect" class="form-control" id="service_id[]" name="service_id[]" required="" multiple>
    							<?php  foreach ($services as $service): ?>
    								<option value="<?php echo $service['id'];?>"><?php echo $service['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Services?</div>
						<?php echo form_error('service_id', '<div style="color:red">', '</div>');?>
					</div>
					
					<div class="form-group col-md-3">
						<label>Brands</label>
						<select id="brands_multiselect" class="form-control" id="brand_id[]"
							name="brand_id[]"  multiple>
    							<?php  foreach ($brands as $brand): ?>
    								<option value="<?php echo $brand['id'];?>"><?php echo $brand['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Brands Name?</div>
						<?php echo form_error('brand_id', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group col-md-3">
						<label>Upload Image</label> <input type="file"  accept="image/jpeg, image/png" name="file" id="file"
							required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);"> <br> 
							<div class="invalid-feedback">Upload Image?</div>
							<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>

					<div class="form-group col-md-1">
					<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/category_image/category_<?php echo $category['id']; ?>.jpg?<?php echo time();?>">
						
					
					</div> 
					
					<div class="form-group col-md-3">
						<label>Coming Soon Image</label> 
						<input type="file" accept="image/jpeg, image/png" name="coming_soon_file"
						required="" value="<?php echo set_value('coming_soon_file')?>"
							class="form-control" onchange="readURL(this);"> <br>
						<div class="invalid-feedback">Coming soon Image?</div>
						<?php echo form_error('coming_soon_file', '<div style="color:red">', '</div>');?>
					</div>

					<div class="form-group col-md-1">
					<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/coming_soon_image/coming_soon_<?php echo $category['id']; ?>.jpg?<?php echo time();?>">
						
					
					</div> 

					 <div class="col col-sm col-md-12 ven2" ><label>Terms And Conditions</label>
          				<textarea id="terms" class="ckeditor" name="terms" rows="10" data-sample-short>Terms And Conditions</textarea>
						
        			</div>
					<div class="invalid-feedback">Terms and Conditions?</div>
          				<?php echo form_error('terms', '<div style="color:red">', '</div>');?>
        			
					<div class="form-group col-md-2 mt-4">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>

			</div>
			
		</form>
    </div>
</div>