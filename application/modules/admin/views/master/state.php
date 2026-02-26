<!--Add State And its list-->
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
		<?php if ($this->ion_auth_acl->has_permission('state_add')): ?>
			<h4 class="ven subcategory">Add State</h4>
			<form class="needs-validation" novalidate="" action="<?php echo base_url('state/c'); ?>" method="post"
				enctype="multipart/form-data">
				<div class="card-header">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>State Name</label> <input type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" class="form-control"
								placeholder="State Name">
							<span id="name1"></span>
							<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
						</div>

						<div class="form-group col-md-6 mt-4 pt-3">
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
					<h4 class="ven">List of States</h4>
					<?php if ($this->ion_auth_acl->has_permission('state_add')): ?>
						<a class="btn btn-outline-dark btn-lg col-3"
							href="<?php echo base_url('state/state_bulk_upload') ?>"><i class="fa fa-plus"
								aria-hidden="true"></i>State bulk upload</a>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>State Name</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('state_view')): ?>
									<?php if (!empty($states)): ?>
										<?php $sno = 1;
										foreach ($states as $state): ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td class="tdcolorone"><?php echo $state['name'] . '[' . $state['id'] . ']'; ?></td>
												<td>
													<?php if ($this->ion_auth_acl->has_permission('state_edit')): ?>
														<a href="<?php echo base_url() ?>state/edit?id=<?php echo $state['id'] ?>"
															class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i></a>
													<?php endif; ?>
													<!-- <?php if ($this->ion_auth_acl->has_permission('state_delete')): ?>
														<a href="#" class="mr-2  text-danger "
															onClick="delete_record(<?php echo $state['id'] ?>, 'state')"> <i
																class="far fa-trash-alt"></i></a>
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