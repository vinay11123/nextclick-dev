<!--Add District And its list-->
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
		<?php if ($this->ion_auth_acl->has_permission('district_add')): ?>
			<h4 class="ven subcategory">Add District</h4>
			<form class="needs-validation" novalidate="" action="<?php echo base_url('district/c'); ?>" method="post"
				enctype="multipart/form-data">
				<div class="card-header">
					<div class="form-row">
						<div class="form-group col-md-5">
							<label>District Name</label> <input type="text" name="name"
								value="<?php echo set_value('name'); ?>" class="form-control" id="name"
								placeholder="District Name">
							<span id="name1"></span>
							<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
						</div>

						<div class="form-group col-md-5">
							<label>State</label>
							<!-- 						 <input type="file" class="form-control" required=""> -->
							<select class="form-control" name="state_id" required="">
								<option value="0" selected disabled>--select--</option>
								<?php foreach ($states as $state): ?>
									<?php $selected = ($state['id'] == $this->input->post('state_id')) ? 'selected' : ''; ?>
									<option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>>
										<?php echo $state['name'] ?>
									</option>
								<?php endforeach; ?>
							</select>
							<?php echo form_error('state_id', '<div class="text-danger">', '</div>'); ?>
							<div class="invalid-feedback">Belongs to the state?</div>
						</div>

						<div class="form-group col-md-2 mt-4 pt-3">
							<button class="btn btn-primary mt-27 " name="submit" id="submit1">Submit</button>
						</div>
					</div>
				</div>
			</form>
		<?php endif; ?>

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
					<h4 class="ven">List of Districts</h4>
					<?php if ($this->ion_auth_acl->has_permission('district_add')): ?>
						<a class="btn btn-outline-dark btn-lg col-3"
							href="<?php echo base_url('district/district_bulk_upload') ?>"><i class="fa fa-plus"
								aria-hidden="true"></i>District bulk upload</a>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>District Name</th>
									<th>State</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('district_view')): ?>
									<?php if (!empty($districts)): ?>
										<?php $sno = 1;
										foreach ($districts as $district): ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td class="tdcolorone">
													<?php echo $district['name'] . '[' . $district['id'] . ']'; ?>
												</td>
												<td><?php foreach ($states as $state): ?>
														<?php if ($state['id'] == $district['state_id']): ?>
															<?php echo $state['name'] . '[' . $state['id'] . ']'; ?>
														<?php endif; ?>
													<?php endforeach; ?>
												</td>
												<td>
													<?php if ($this->ion_auth_acl->has_permission('district_edit')): ?>
														<a href="<?php echo base_url() ?>district/edit?id=<?php echo $district['id']; ?>"
															class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i></a>
													<?php endif; ?>
													<!-- <?php if ($this->ion_auth_acl->has_permission('district_delete')): ?>
														<a href="#" class="mr-2  text-danger "
															onClick="delete_record(<?php echo $district['id'] ?>, 'district')"> <i
																class="far fa-trash-alt"></i></a>
													<?php endif; ?> -->
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<th colspan='5'>
												<h3>
													<center>No Districts</center>
												</h3>
											</th>
										</tr>
									<?php endif; ?>
								<?php else: ?>
									<tr>
										<th colspan='5'>
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