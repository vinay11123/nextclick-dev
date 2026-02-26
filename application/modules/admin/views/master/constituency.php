<!--Add Constituency And its list-->
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
		<?php if ($this->ion_auth_acl->has_permission('constituency_add')): ?>
			<h4 class="ven subcategory">Add Constituency1</h4>
			<form class="needs-validation" novalidate="" action="<?php echo base_url('constituency/c'); ?>" method="post"
				enctype="multipart/form-data">
				<div class="card-header">

					<div class="form-row">
						<div class="form-group mb-0 col-md-3">
							<label>Constituency Name11</label>
							<input type="text" class="form-control" name="name" id="name" required="" placeholder="Name"
								value="<?php echo set_value('name'); ?>">
							<div class="invalid-feedback">Give Constituency Name</div>
							<?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
						</div>

						<div class="form-group col-md-3">
							<label>State</label> <select class="form-control" id='state' onchange="state_changed()"
								name="state_id" required="">
								<option value="" selected disabled>--select--</option>
								<?php foreach ($states as $state): ?>
									<?php $selected = ($state['id'] == $this->input->post('state_id')) ? 'selected' : ''; ?>
									<option value="<?php echo $state['id']; ?>" <?php echo $selected; ?>>
										<?php echo $state['name'] ?>
									</option>
								<?php endforeach; ?>
							</select>
							<div class="invalid-feedback">Belongs to the state?</div>
							<?php echo form_error('state_id', '<div style="color:red">', '</div>'); ?>
						</div>

						<div class="form-group col-md-3">
							<label>District</label>
							<select id="district" class="form-control" name="dist_id" required="">
								<option value="" selected>--select--</option>
								<?php foreach ($districts as $district): ?>
									<?php $selected = ($district['id'] == $this->input->post('dist_id')) ? 'selected' : ''; ?>
									<option value="<?php echo $district['id']; ?>" <?php echo $selected; ?>>
										<?php echo $district['name']; ?>
									</option>
								<?php endforeach; ?>
							</select>
							<div class="invalid-feedback">Belongs to the District?</div>
							<?php echo form_error('dist_id', '<div style="color:red">', '</div>'); ?>
						</div>

						<div class="form-group mb-0 col-md-2">
							<label>Pincode</label>
							<input type="text" class="form-control" name="pincode" id="pincode" required=""
								placeholder="Pincode" value="<?php echo set_value('pincode'); ?>">
							<div class="invalid-feedback">Give Pincode</div>
							<?php echo form_error('pincode', '<div style="color:red">', '</div>'); ?>
						</div>
						<div class="form-group col-md-1">
							<button class="btn btn-primary" name="submit" id="submit1">Submit</button>
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
					<h4>List of Constituencies</h4>
					<?php if ($this->ion_auth_acl->has_permission('constituency_add')): ?>
						<a class="btn btn-outline-dark btn-lg col-3"
							href="<?php echo base_url('constituency/constituency_bulk_upload') ?>"><i class="fa fa-plus"
								aria-hidden="true"></i>Constituency bulk upload</a>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Constituency Name</th>
									<th>District</th>
									<th>State</th>
									<th>Pincode</th>

									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('constituency_view')): ?>
									<?php if (!empty($constituencies)): ?>
										<?php $sno = 1;
										foreach ($constituencies as $constituency): ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td class="tdcolorone">
													<?php echo $constituency['name'] . '[' . $constituency['id'] . ']'; ?>
												</td>
												<td class="tdcolortwo"><?php foreach ($districts as $district): ?>
														<?php if ($district['id'] == $constituency['district_id']): ?>
															<?php echo $district['name'] . '[' . $district['id'] . ']'; ?>
														<?php endif; ?>
													<?php endforeach; ?>
												</td>
												<td><?php foreach ($states as $state): ?>
														<?php if ($state['id'] == $constituency['state_id']): ?>
															<?php echo $state['name'] . '[' . $state['id'] . ']'; ?>
														<?php endif; ?>
													<?php endforeach; ?>
												</td>
												<td><?php echo $constituency['pincode']; ?></td>
												<td>
													<?php if ($this->ion_auth_acl->has_permission('constituency_edit')): ?>
														<a href="<?php echo base_url() ?>constituency/edit?id=<?php echo $constituency['id']; ?>"
															class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i></a>
													<?php endif; ?>
													<!-- <?php if ($this->ion_auth_acl->has_permission('constituency_delete')): ?>
														<a href="#" class="mr-2  text-danger "
															onClick="delete_record(<?php echo $constituency['id'] ?>, 'constituency')">
															<i class="far fa-trash-alt"></i></a>
													<?php endif; ?> -->
												</td>

											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<th colspan='5'>
												<h3>
													<center>No States</center>
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