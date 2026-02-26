<!--Add Brands-->
<div class="row">
	<div class="col-12">
		<h4 class="ven subcategory">Add Brands</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('brands/c/0'); ?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Brand Name</label> <input type="text" class="form-control" name="name" id="name" required="" placeholder="Name" value="<?php echo set_value('name') ?>">
						<div class="invalid-feedback">Give Brand Name</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
						<?php if (isset($this->session->flashdata('upload_status')['error'])) { ?>
							<div style="color:red"><?php echo $this->session->flashdata('upload_status')['error']; ?></div>
						<?php } ?>
					</div>
					<div class="form-group col-md-6">
						<label>Categories</label>
						<div>
						<select id="categorys_multiselect" class="form-control" name="categorys_id[]" required multiple>
							<?php foreach ($categorys as $category) : ?>
								<option value="<?php echo $category['id']; ?>"><?php echo $category['name'] ?></option>
							<?php endforeach; ?>
						</select>
							</div>
						<div class="invalid-feedback">New Categories Name?</div>
						<?php echo form_error('categorys_id', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-6">
						<label>Description</label>
						<input type="text" class="form-control" name="desc" id="desc" required="" placeholder="Description" value="<?php echo set_value('desc') ?>">
						<div class="invalid-feedback">Give Description</div>
						<?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-3">
						<label>Upload Image</label>
						<input type="file" name="file" accept="image/jpeg, image/png" required value="<?php echo set_value('file') ?>" class="form-control" onchange="readURL(this);"> <br>
					</div>
					<div class="form-group col-md-1">
						<img id="blah" class="textimgmotion" src="#" alt="">
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-2">
						<button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 mt">Submit</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>