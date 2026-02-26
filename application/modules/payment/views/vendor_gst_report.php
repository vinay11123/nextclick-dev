<!-- Wallet transactins css -->
<!-- <link href="https://demo.dashboardpack.com/architectui-html-free/main.css" rel="stylesheet"> -->
<style>
	.main-content {
		background-color: #f1f3f4;
	}
</style>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-header">Vendor GST Reports</div>
					<div class="table-responsive">
						<table class="align-middle mb-0 table table-borderless table-striped table-hover">
							<thead>
								<tr>
									<th class="text-center">Si.No</th>
									<th class="text-center">Vendor Name</th>
									<th class="text-center">Vendor Business Name</th>
									<th class="text-center">Total Tax</th>
								</tr>
							</thead>
							<tbody>
								<?php $sno = 1;
								if (!empty($vendor_reports)) :
									foreach ($vendor_reports as $key => $txn) :
								?>
										<tr>
											<td><?php echo $sno++; ?></td>
											<td class="text-center"><?php echo $txn['first_name']; ?></td>
											<td class="text-center"><?php echo $txn['business_name'] ?></td>
											<td>
												<a href="<?php echo base_url() ?>payment/vendor_reports/show?id=<?php echo $txn['vendor_user_id']; ?>" class=" mr-2">
													<?php echo $txn['total_tax']; ?>
												</a>
											</td>
										</tr>
									<?php endforeach;
								else : ?>
									<tr>
										<th colspan='10'>
											<h3>
												<center>No Transactions</center>
											</h3>
										</th>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="d-block text-center card-footer">
						<?php echo $pagination; ?>
					</div>
					<!-- <script type="text/javascript">
						//$(".modal-dialog").hide();
						function load_marks(stu_id) {
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('payment/vendor_reports_modal'); ?>",
								data: "stu_id=" + stu_id,
								success: function(response) {
									$(".displaycontent").html(response);

								}
							});
						}
					</script> -->

					<div class="modal fade displaycontent" id="myModal">

						<?php include('modal.php'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Wallet transaction page css -->
	<script type="text/javascript" src="https://demo.dashboardpack.com/architectui-html-free/assets/scripts/main.js"></script>