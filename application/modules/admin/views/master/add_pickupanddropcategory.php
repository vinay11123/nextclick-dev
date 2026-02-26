<!--Add Category-->
<div class="row">
	<div class="col-12">
		<h4 class="ven venclr">Add Pickup and drop Category</h4>
		  
		<form class="needs-validation" novalidate="" action="<?php echo base_url('pickanddropcategories/c');?>" method="post" enctype="multipart/form-data">
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
					<div class="form-group col-md-6">
						<label>Flat Distance (in Km)</label> <input type="number" class="form-control" placeholder="Flat Distance in KM" name="flatdistance" id="flatdistance" required="">
					</div>
					<div class="form-group col-md-6">
						<label>Delivery Boy Flat Rate</label> <input type="text" class="form-control" placeholder="Flat Rate" name="rlatrate" id="rlatrate" required="">
					</div>
					<div class="form-group col-md-6">
						<label>NC Flat Rate</label> <input type="text" class="form-control" placeholder="NC Flat Rate" name="nc_flat_rate" id="nc_flat_rate" required="">
					</div>
					<div class="form-group col-md-6">
						<label>Delivery Boy Rate Per Km After Flat Distance</label> <input type="text"
							class="form-control" name="per_km" id="Perkm" placeholder="Enter Rate Per Km"
							required="">
						 
					</div>
					<div class="form-group col-md-6">
						<label>NC Rate Per Km After Flat Distance</label> <input type="text"
							class="form-control" name="nc_per_km" id="nc_per_km" placeholder="Enter Rate Per Km"
							required="">
						 
					</div>
					<div class="form-group col-md-3">
						<label>Upload Image</label> <input type="file"  accept="image/jpeg, image/png" name="file" id="file"
							required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);"> <br> 
					</div>

					<div class="form-group col-md-1">
					<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/category_image/category_<?php echo $category['id']; ?>.jpg?<?php echo time();?>">
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					
					</div> 
					
					
					 <div class="col col-sm col-md-12 ven2" ><label>Terms And Conditions</label>
          				<textarea id="cat_terms" class="ckeditor" name="terms" rows="10" data-sample-short>Terms And Conditions</textarea>
          				<?php echo form_error('terms', '<div style="color:red">', '</div>');?>
        			</div>
        			
					<div class="form-group col-md-2 mt-4">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>

			</div>
			
		</form>
    </div>
</div>