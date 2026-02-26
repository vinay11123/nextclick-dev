<style>
	/*
.elementToFadeInAndOut {
    display:block;
    -webkit-animation: fadeinout 10s linear forwards;
    animation: fadeinout 10s linear forwards;
}
@-webkit-keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
@keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
td:nth-child(5){
	position: relative;
	width:12%;
   min-height:12px;
}*/
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
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
		<div class="card-header">
			<h4 class="ven subcategory">Sub Categories</h4>
			<form class="" novalidate="" action="<?php echo base_url('sub_category/r/0'); ?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="form-group col-3">
						<label for="q">Name</label>
						<input type="text" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="q" id="q" placeholder="Name" value="<?php echo $q; ?>" class="form-control">
					</div>

					<div class="form-group col-2">
						<label for="noofrows">rows</label>
						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows; ?>" class="form-control">
					</div>

				</div>
				<div class="row">
					<div class="col-md-12">

						<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 bordernobg"><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>

					</div>
				</div>
			</form>
			<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('sub_category/r/0'); ?>" method="post" enctype="multipart/form-data">
				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
			</form>
		</div>
	</div>
</div>
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
			
					<h4 class="col-7 ven1">List of Sub Categories</h4>
					<a href="<?= base_url('admin/master/export_all'); ?>" 
                   class="btn btn-outline-dark btn-lg">
                   Excel Export
                </a> 
                &nbsp;

					<?php if ($this->ion_auth_acl->has_permission('subcategory_add')) : ?>
						<a class="btn btn-outline-dark btn-lg " href="<?php echo base_url() ?>sub_category/c/0" class="btn btn-primary widfldtd">Add Sub Categories</a>
						&nbsp;<a class="btn btn-outline-dark btn-lg " href="<?php echo base_url('admin/bulk_upload/sub_category_upload') ?>"><i class="fa fa-plus" aria-hidden="true"></i>Sub Category Bulk Upload</a>
					<?php endif; ?>

	
				</div>
						<div class="card-body">

					<div class="table-responsive">

						<table class="table table-striped table-hover" style="width: 100%;" >
							<thead>

								<tr>
									<th>Id</th>
									<th>Created By</th>
									<th>Sub Category Name</th>
									<th>Category</th>
									<th>Description</th>
									<th>Type</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('subcategory_view')) : ?>
									<?php if (!empty($sub_categories)) : ?>
										<?php $sno = 1;
										foreach ($sub_categories as $sub_cat) : ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td><?php echo (!empty($sub_cat['users']['unique_id'])) ? $od_category['users']['unique_id'] : 'NA'; ?></td>
												<td><?php echo $sub_cat['name'] . '[' . $sub_cat['id'] . ']'; ?></td>
												<td class="tdcolorone"><?php foreach ($categories as $category) : ?>
														<?php echo ($category['id'] == $sub_cat['cat_id']) ? $category['name'] : ''; ?>
													<?php endforeach; ?></td>
												<td class="tdcolortwo ">
													<ul class="scrollitemlist">
														<li>
															<?php echo $sub_cat['desc']; ?></li>
													</ul>
												</td>
												<td><?php echo ($sub_cat['type'] == 1) ? 'Listing Sub Category' : 'Shop By Category'; ?></td>
												<td><img class="img-thumb" src="<?php echo base_url(); ?>uploads/sub_category_image/sub_category_<?php echo $sub_cat['id']; ?>.jpg?<?php echo time(); ?>"></td>
												<td>
													<?php if ($this->ion_auth_acl->has_permission('subcategory_edit')) : ?>
														<a href="<?php echo base_url() ?>sub_category/edit/0?id=<?php echo $sub_cat['id']; ?>&page=<?php echo $this->uri->segment(3); ?>" class=" mr-2  "> <i class="fas fa-pencil-alt"></i></a>
													<?php endif; ?>
													<?php if ($this->ion_auth_acl->has_permission('subcategory_delete')) : ?>
														<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $sub_cat['id'] ?>, 'sub_category')"> <i class="far fa-trash-alt"></i></a>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<th colspan='6'>
												<h3>
													<center>No Sub_Category</center>
												</h3>
											</th>
										</tr>
									<?php endif; ?>
								<?php else : ?>
									<tr>
										<th colspan='6'>
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