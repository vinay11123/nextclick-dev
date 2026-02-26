
<!--Add Service -->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Service</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('service/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">

					<div class="form-group mb-0 col-md-3">
						<label>Service Name</label> <input type="text" class="form-control" name="name" id="name" required="" placeholder="Name" <?php echo set_value('name')?>>
						<div class="invalid-feedback">Give Service Name</div>
						<?php echo form_error('name','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group mb-0 col-md-3">
						<label>Description</label> <input type="text" class="form-control" name="desc" id="desc" required="" placeholder="Description" <?php echo set_value('name')?>>
						<div class="invalid-feedback">Give Description</div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group mb-0 col-md-3">
						<label>Language</label>
						<input type="text" class="form-control" name="languages" id="languages" placeholder="Language" value="" required="">
					<span id="languages1"></span>
					<div class="invalid-feedback">Give Language</div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
	
					</div> 
                   
					<div class="form-group mb-0 col-md-3">
						<label>Permissions</label>
						<select id="services_multiselect" class="form-control"
							name="perm_id[]" multiple>
								<?php foreach ($permissions as $per):?>
                                  <option value="<?php echo $per['id'];?>"><?php echo $per['name']?></option>
                                <?php endforeach;?>
    							
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-3">
						<label>Upload Image</label> <input type="file" accept="image/jpg, image/jpeg, image/png"
							class="form-control" name="file" required="" value="<?php echo set_value('file')?>">
							<div class="invalid-feedback">Please Upload Image</div>
							<?php echo form_error('file', '<div style="color:red">', '</div>');?>
						</div>
						<div class="form-group mb-0 col-md-1">
							<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/service_image/service_<?php echo $services['id']; ?>.jpg">
								
						</div>
						<div class="form-group mb-0 col-md-2 ">
						<button class="btn btn-primary mt" type="submit" name="upload" id="upload" >Submit</button>
					</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>