<!--Add Brands list-->
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

	.tdcolortwo:nth-child(3) {
		position: relative;
		width: 10%;
		min-height: 12px;
	}
</style>
<style>
	.page-item>a {
		position: relative;
		display: block;
		padding: .5rem .75rem;
		margin-left: -1px;
		line-height: 1.25;
		color: #007bff;
		background-color: #fff;
		border: 1px solid #dee2e6;
	}

	a {
		color: #007bff;
		text-decoration: none;
		background-color: transparent;
	}

	.pagination>li.active>a {
		background-color: orange !important;
	}

	.dataTables_filter {
		display: none;
	}

	.or {
		text-align: center;
	}
</style>
<div class="row">
	<div class="col-12">
		<div class="card-header">
			<h4 class="ven subcategory">Brands Filter</h4>
			<form id="resetForm" novalidate="" action="<?php echo base_url('brands/r/0'); ?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="form-group col-3">
						<label for="q">Search</label>
						<input type="text" name="q" id="q" placeholder="Name or Description or Brand ID" value="<?php echo $q; ?>" class="form-control">
					</div>
					<div class="form-group col-3">
						<label for="status">Featured Brands</label>
						<select calss="form-control" name="group" class="form-control">
							<option value="0" <?php echo ($group == 0) ? "selected" : "" ?>>All</option>
							<option value="1" <?php echo ($group == 1) ? "selected" : "" ?>>Yes</option>
							<option value="2" <?php echo ($group == 2) ? "selected" : "" ?>>No</option>
							<option value="3" <?php echo ($group == 3) ? "selected" : "" ?>>Waiting For Approval</option>
						</select>
					</div>
					<!-- <div class="form-group col-md-2">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows; ?>" class="form-control">
    					</div> -->
				</div>
				<div class="row">
					<div class="col-md-12">
						<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27">
							<i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
					</div>
				</div>
			</form>
			<!-- <div class="form-group col-md-12"> -->
			<!-- <form class="needs-validation" novalidate="" action="<?php //echo base_url('vendors_filter/0');?>" method="post" enctype="multipart/form-data"> -->
				<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php //echo base_url('vendors_filter/0');
																																?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
			</form>

			<!-- </div> -->
		</div>
	</div>
</div>
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
			<?php if (!empty($this->session->flashdata('delete_status'))) { ?>
				<div class="alert alert-danger elementToFadeInAndOut">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success!</strong> <?php echo $this->session->flashdata('delete_status'); ?>
				</div>
			<?php } ?>
			<div class="card-header">
				<h4 class="col-8 ven1">List of Brands</h4>
				<?php if ($this->ion_auth_acl->has_permission('brand_add')) : ?>
					<a href="<?php echo base_url() ?>brands/c/0" class="btn btn-primary widfldtd">Add Brands</a>
					&nbsp;<a class="btn btn-outline-dark btn-lg" href="<?php echo base_url('admin/bulk_upload/brands_upload') ?>"><i class="fa fa-plus" aria-hidden="true"></i>Brands Bulk Upload</a>
				<?php endif; ?>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th>S.no</th>
								<th>Brand Name</th>
								<th>Description</th>
								<th>Featured brands</th>
								<th>Image</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($this->ion_auth_acl->has_permission('brand_view')) : ?>
								<?php if (!empty($ecom_brands)) : ?>
									<?php $sno = 1;
									foreach ($ecom_brands as $ecom_brand) : ?>
										<tr>
											<td><?php echo $sno++; ?></td>
											<td class="tdcolorone"><?php echo $ecom_brand['name'] . '[' . $ecom_brand['id'] . ']'; ?></td>
											<td class="tdcolortwo"><?php echo $ecom_brand['desc']; ?></td>
											<td><input type="checkbox" class="featured_toggle" brand_id="<?php echo $ecom_brand['id']; ?>" <?php echo ($ecom_brand['status'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"></td>
											<td><img src="<?php echo base_url(); ?>uploads/brands_image/brands_<?php echo $ecom_brand['id']; ?>.jpg?<?php echo time(); ?>" class="img-thumb"></td>
											<td>
												<?php if ($this->ion_auth_acl->has_permission('brand_edit')) : ?>
													<a href="<?php echo base_url() ?>brands/edit/0?id=<?php echo $ecom_brand['id']; ?>&page=<?php echo $this->uri->segment(3); ?>" class="" type="ecom_brands"> <i class="fas fa-pencil-alt"></i></a>
												<?php endif; ?>
												<?php if ($this->ion_auth_acl->has_permission('brand_delete')) : ?>
													<a href="#" class="text-danger " onClick="delete_record(<?php echo $ecom_brand['id'] ?>, 'brands')"><i class="fas fa-trash-alt"></i></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<th colspan='10'>
											<h3>
												<center>No Brands</center>
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