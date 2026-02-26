<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<div class="content_wrapper">
	<div class="container-fluid">
		<!-- breadcrumb -->

		<!-- breadcrumb_End -->

		<!-- Section -->
		<section class="chart_section">

			<div class="row">
				<div class="col-12 mb-4">

					<div class="card card-shadow mb-4">
						<div class="card-header">
							<div class="card-title text-danger">
								Pending Vendors(<?= $vendor_pending ?>)
							</div>
							<span class="pull-right" style="margin-top:-27px;"><a class="btn-primary btn-sm"
									href="<?php echo base_url('executive/vendors'); ?>">Back</a></span>
						</div>
						<div class="card-body">
							<table class="table">
								<thead class="thead-light">
									<tr>
										<th scope="col">#</th>
										<th scope="col">Details</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($vendor_pending_list as $key => $vendor): ?>
										<tr>
											<th scope="row"><?php echo $key + 1; ?></th>
											<td>
												Shop Name: <span
													class="text-primary"><?php echo $vendor->business_name; ?></span><br>
												Vendor Name: <span
													class="text-danger"><?php echo $vendor->owner_name; ?></span><br>
												Ph No: <span
													class="text-warning"><?php echo $vendor->whats_app_no; ?></span><br>
												Location: <span
													class="text-success"><?php echo $vendor->location; ?></span><br>

											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>


				</div>



			</div>

		</section>
		<!-- Section_End -->

	</div>
</div>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>