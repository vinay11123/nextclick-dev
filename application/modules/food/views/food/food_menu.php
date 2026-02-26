<?php
$cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id = 4; //$cat_id['category_id'];
?>
<!--Add Category And its list-->
<style>
	.elementToFadeInAndOut {
		display: block;
		-webkit-animation: fadeinout 10s linear forwards;
		animation: fadeinout 10s linear forwards;
	}

	@-webkit-keyframes fadeinout {

		0%,
		100% {
			opacity: 0;
		}

		50% {
			opacity: 1;
		}
	}

	@keyframes fadeinout {

		0%,
		100% {
			opacity: 0;
		}

		50% {
			opacity: 1;
		}
	}
</style>
<div class="row">
	<div class="col-12">
		<?php if ($this->ion_auth_acl->has_permission('menu_add')) : ?>
			<h4 class="ven subcategory"><?= (($this->ion_auth->is_admin()) ? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_label')); ?></h4>
			<form class="needs-validation" novalidate="" action="<?php echo base_url('food_menu/c'); ?>" method="post" enctype="multipart/form-data">
				<div class="card-header">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>Sub category<span class="text-danger">*</span></label>
							<select class="form-control" name="sub_cat_id" required="" id="cars">
								<option value="" selected disabled>--select--</option>
								<?php
								if ($this->ion_auth->is_admin()) {
									for ($l = 0; $l < count($sub_categories); $l++) {
								?>
										<optgroup label="<?= $sub_categories[$l]['name']; ?>">
											<?php
											$sl = $sub_categories[$l]['sub_categories'];
											if ($sl != '') {
												for ($r = 0; $r < count($sl); $r++) {
											?>
													<option value="<?= $sl[$r]['id']; ?>" <?php if (set_value('sub_cat_id') == $sl[$r]['id']) echo 'selected'; ?>><?= $sl[$r]['name']; ?></option>
											<?php }
											} ?>
										</optgroup>
									<?php
									}
								} else {
									?>
									<?php foreach ($sub_categories as $item) : ?>
										<option value="<?php echo $item['id']; ?>" <?php if (set_value('sub_cat_id') == $item['id']) echo 'selected'; ?>><?php echo $item['name'] ?></option>
									<?php endforeach; ?>
								<?php } ?>
							</select>
							<div class="invalid-feedback">Enter New Sub Category name</div>
							<?php echo form_error('sub_cat_id', '<div style="color:red>"', '</div>'); ?>
						</div>
						<div class="form-group col-md-6">
							<label><?= (($this->ion_auth->is_admin()) ? 'Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_name')); ?><span class="text-danger">*</span></label> <input type="text" name="name" required="" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" value="<?php echo set_value('name') ?>" class="form-control">
							<input type="hidden" name="vendor_id" value="<?= $this->ion_auth->get_user_id(); ?>">
							<div class="invalid-feedback"><?= (($this->ion_auth->is_admin()) ? 'Enter Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_name')); ?></div>
							<?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
							<?php if (isset($this->session->flashdata('upload_status')['error'])) { ?>
								<div style="color:red"><?php echo $this->session->flashdata('upload_status')['error']; ?></div>
							<?php } ?>
						</div>
						<div class="form-group mb-0 col-md-6">
							<label><?= (($this->ion_auth->is_admin()) ? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_desc')); ?><span class="text-danger">*</span></label> <input type="text" name="desc" required="" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" value="<?php echo set_value('desc') ?>" class="form-control">
							<div class="invalid-feedback"><?= (($this->ion_auth->is_admin()) ? 'Enter Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_desc')); ?></div>
							<?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
						</div>
						<!-- <div class="form-group col-md-6">
							<label><?= (($this->ion_auth->is_admin()) ? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_image')); ?><span class="text-danger">*</span></label> <input type="file" name="file" required="" accept="image/jpeg, image/jpeg, image/png" value="<?php echo set_value('file') ?>" class="form-control" onchange="readURL(this);"> <br> <img id="blah" src="#" alt="" style="width: 216px;">
							<div class="invalid-feedback"><?= (($this->ion_auth->is_admin()) ? 'Please upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_image')); ?></div>
							<?php echo form_error('file', '<div style="color:red">', '</div>'); ?>
						</div> -->
						<div class="form-group col-md-2">
							<button type="submit" name="upload" id="upload" value="Apply" class="btn mt-27" style="background-color:#f26b35;margin-top:40px;margin-left:10px;">Submit</button>
						</div>
					</div>
				</div>
			</form>
		<?php endif; ?>
		<div class="card-body">
			<div class="card">
				<?php if (!empty($this->session->flashdata('upload_status')['success'])) {
				?>
					<div class="alert alert-success elementToFadeInAndOut">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Success!</strong> <?php echo $this->session->flashdata('upload_status')['success']; ?>
					</div>
				<?php
				} ?>
				<?php if (!empty($this->session->flashdata('delete_status'))) {
				?>
					<div class="alert alert-danger elementToFadeInAndOut">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Success!</strong> <?php echo $this->session->flashdata('delete_status'); ?>
					</div>
				<?php
				} ?>
				<div class="card-header">
					<h4 class="ven">List of <?= (($this->ion_auth->is_admin()) ? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_label')); ?></h4>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin/bulk_upload/menu_upload') ?>"><i class="fa fa-plus" aria-hidden="true"></i>Menu bulk upload</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Menu</th>
									<th>Shop by category</th>
									<th>Description</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('menu_view')) : ?>
									<?php if (!empty($food_items)) : ?>
										<?php $sno = 1;
										foreach ($food_items as $food_item) : ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td class="tdcolorone"><?php echo $food_item['name'] . '[' . $food_item['id'] . ']'; ?></td>
												<td><?php echo $food_item['shop_by_category']['name'] ?></td>
												<td><?php echo $food_item['desc']; ?></td>

												<td>
													<?php if ($this->ion_auth_acl->has_permission('menu_edit')) : ?>
														<a href="<?php echo base_url() ?>food_menu/edit?id=<?php echo base64_encode(base64_encode($food_item['id'])); ?>" class=" mr-2  " type="ecom_category"> <i class="fas fa-pencil-alt"></i>
														</a>
													<?php endif; ?>
													<?php if ($this->ion_auth_acl->has_permission('menu_delete')) : ?>
														<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $food_item['id'] ?>, 'food_menu')">
															<i class="far fa-trash-alt"></i>
														</a>
													<?php endif; ?>
												</td>

											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<th colspan='7'>
												<h3>
													<center>No Data Found</center>
												</h3>
											</th>
										</tr>
									<?php endif; ?>
								<?php else : ?>
									<tr>
										<th colspan='7'>
											<h3>
												<center>No Access!</center>
											</h3>
										</th>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>