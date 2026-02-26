<div class="row">
	<div class="col-12">
		<h4 class="ven Add subcategory">Add Sub Category</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('sub_category/c/0'); ?>" method="post" enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group mb-0 col-md-3">
						<label>Sub Category Name<span class="text-danger">*</span></label> <input type="text" class="form-control" name="name" id="name" trim="" required="" placeholder="Name" value='<?php echo set_value('name') ?>'>
						<div class="invalid-feedback"> Enter Sub Category name</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
						<?php if (isset($this->session->flashdata('upload_status')['error'])) { ?>
							<div style="color:red"><?php echo $this->session->flashdata('upload_status')['error']; ?></div>
						<?php } ?>
					</div>

					<div class="form-group col-md-3">
						<label for="cat_id">Category<span class="text-danger">*</span></label>
						<select class="form-control" required="" id="cat_id" name="cat_id">
							<option value="" selected disabled>--select--</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo $category['id']; ?>" <?php if (set_value('cat_id') == $category['id']) echo 'selected'; ?>><?php echo $category['name'] ?></option>
							<?php endforeach; ?>
						</select>
						<div class="invalid-feedback">select Category Name</div>
						<?php echo form_error('cat_id', '<div style="color:red>"', '</div>'); ?>
					</div>

					<div class="form-group mb-0 col-md-3">
						<label>Description<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="desc" id="desc" required="" placeholder="Description" value='<?php echo set_value('desc') ?>'>
						<div class="invalid-feedback">Enter Description</div>
						<?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-3">
						<label>Type<span class="text-danger">*</span></label>
						<select class="form-control" name="type" id="type" required="">
							<option value="" selected disabled>--select--</option>
							<option value="1" <?php if (set_value('type') == '1') echo 'selected'; ?>>Listing Sub Category</option>
							<option value="2" <?php if (set_value('type') == '2') echo 'selected'; ?>>Shop By Category</option>
						</select>
						<div class="invalid-feedback">Select the Type</div>
						<?php echo form_error('type', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-3">
						<label>Upload Image<span class="text-danger">*</span></label>
						<input type="file" accept="image/jpeg, image/png" name="file" required="" value="<?php echo set_value('file') ?>" class="form-control" onchange="readURL(this);"> <br>
						<div class="invalid-feedback">Upload the Image</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>'); ?>
					</div>
					<div class="form-group col-md-1">
						<img id="blah" class="textimgmotion" src="<?php echo base_url(); ?>uploads/sub_category_image/sub_category_<?php echo $sub_categories['id']; ?>.jpg?<?php echo time(); ?>">


					</div>
					<div class="form-group col-md-2">
						<button class="btn btn-primary mt-27 mt" name="submit" id="submit1">Submit</button>
					</div>
				</div>


			</div>
		</form>
	</div>
</div>