<!--Category list-->

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

	td:nth-child(3) {
		position: relative;
		width: 12%;
		min-height: 12px;
	}

	.alert-danger {}
</style>
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<?php if (!empty($this->session->flashdata('upload_status'))) {
				?>
					<div class="alert alert-success elementToFadeInAndOut">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Success!</strong> <?php echo $this->session->flashdata('upload_status'); ?>
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
					<h4 class="ven1 col-8">List of Categories</h4>
					<?php if ($this->ion_auth_acl->has_permission('category_create')) : ?>
						<a href="<?php echo base_url() ?>category/c" class="btn btn-primary widfldtd" style="flaot:right">Add Category</a> &nbsp;
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('category_create')) : ?>
						<!-- <a href="<?php echo base_url() ?>admin/bulk_upload/category" class="btn btn-primary widfldtd" style="flaot:right"><i class="fa fa-plus" aria-hidden="true">Category Bulk Upload</a> -->
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Category Name</th>
									<th>Description</th>
									<th class="serw">Brands</th>
									<th class="serw">Services</th>
									<th class="upimgcls">Image</th>
									<th class="csimgcls">Coming Soon</th>
									<th>Is Working?</th>
									<th>Is Having Lead?</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('category_view')) : ?>
									<?php if (!empty($categories)) : ?>
										<?php $sno = 1;
										foreach ($categories as $category) : ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td class="tdcolorone"><?php echo $category['name'] . '[' . $category['id'] . ']'; ?></td>
												<td class="tdcolortwo"><?php echo $category['desc']; ?></td>
												<td class="scrollitem">
													<ul class="scrollitemlist">
														<?php if (isset($category['brands'])) {
															foreach ($category['brands'] as $brand) : ?>
																<li><?php echo $brand['name']; ?></li>
														<?php endforeach;
														} ?>
													</ul>
												</td>
												<td class="scrollitem">
													<ul class="scrollitemlist">
														<?php if (isset($category['services'])) {
															foreach ($category['services'] as $services) : ?>
																<li><?php echo $services['name']; ?></li>
														<?php endforeach;
														} ?>
													</ul>
												</td>
												<td><img src="<?php echo base_url(); ?>uploads/category_image/category_<?php echo $category['id']; ?>.jpg" class="img-thumb"></td>
												<td><img src="<?php echo base_url(); ?>uploads/coming_soon_image/coming_soon_<?php echo $category['id']; ?>.jpg" class="img-thumb"></td>
												<td><input type="checkbox" class="coming_soon_toggle" cat_id="<?php echo $category['id']; ?>" <?php echo ($category['status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"></td>
												<td><input type="checkbox" class="lead_management_toggle" cat_id="<?php echo $category['id']; ?>" <?php echo ($category['is_having_lead_managemet'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"></td>
												<td>
													<?php if ($this->ion_auth_acl->has_permission('category_edit')) : ?>
														<a href="<?php echo base_url() ?>category/edit?id=<?php echo $category['id']; ?>" class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i></a>
													<?php endif; ?>
													<?php if ($this->ion_auth_acl->has_permission('category_delete')) : ?>
														<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $category['id'] ?>, 'category')"><i class="far fa-trash-alt"></i></a>
													<?php endif; ?>
													<a href="<?php echo base_url() ?>category/export?id=<?php echo $category['id']; ?>" class=" mr-2  btn btn-info" type="category">Catalogue.XLSX</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<th colspan='10'>
												<h3>
													<center>No Categories</center>
												</h3>
											</th>
										</tr>
									<?php endif; ?>
								<?php else : ?>
									<tr>
										<th colspan='10'>
											<h3>
												<center>No Access!</center>
											</h3>
										</th>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<!-- Paginate -->
					<div class="row  justify-content-center">
						<div class=" col-12" style='margin-top: 10px;'>
							<?= $pagination; ?>

						</div>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>