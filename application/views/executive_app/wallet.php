<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<main class="content_wrapper">
	<!--page title start-->

	<!--page title end-->
	<div class="container-fluid">
		<!-- state start-->
		<!--<div class="row">
						
						<div class="col-12">
									<div class="card card-shadow mb-4">
										<div class="card-header">
											<div class="card-title">
												<ul class="nav nav-pills nav-pill-custom nav-pills-sm " id="pills-tab" role="tablist">
													<li class="nav-item">
														<a class="nav-link active" id="pills-today-tab" data-toggle="pill" href="#pills-today" role="tab" aria-controls="pills-today"
															aria-selected="true">WITHDRAW</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="pills-week-tab" data-toggle="pill" href="#pills-week" role="tab" aria-controls="pills-week" aria-selected="false">TRANSACTIONS</a>
													</li>
													
												</ul>

											</div>
										</div>
										<div class="card-body">
											<div class="tab-content" id="pills-tabContent">
												<div class="tab-pane fade show active" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">
													
												<h3 class="text-primary mb-15">Withdraw from Wallet</h3>	
													
												<form>
										<div class="form-group">
											<label for="exampleInputEmail1">Name</label>
											<input type="text" class="form-control" >
										</div>
									   
										 <div class="form-group">
											<label for="exampleInputEmail1">A/C Number</label>
											<input type="text" class="form-control" >
										</div>			
													
										 <div class="form-group">
											<label for="exampleInputEmail1">Bank Name</label>
											<input type="text" class="form-control" >
										</div>			
										
										<div class="form-group">
											<label for="exampleInputEmail1">IFSC Code</label>
											<input type="text" class="form-control" >
										</div>
													
										 <div class="form-group">
											<label for="exampleInputEmail1">Amount to Withdraw</label>
											<input type="text" class="form-control" >
										</div>
													
										<button type="submit" class="btn btn-primary">Submit</button>
									</form>	
													
													
													
													
													
												</div>
												<div class="tab-pane fade" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab">
													<h3>Transactions</h3>
													<div class="table-responsive">
													<table class="table table-stripped">
														<tr>
															<td><small class="text-success">CREDIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-success">+10</strong></td>
														</tr>
														<tr>
															<td><small class="text-success">CREDIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-success">+10</strong></td>
														</tr>
														<tr>
															<td><small class="text-danger">DEBIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-danger">-10</strong></td>
														</tr>
														<tr>
															<td><small class="text-danger">DEBIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-danger">-10</strong></td>
														</tr>
														<tr>
															<td><small class="text-success">CREDIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-success">+10</strong></td>
														</tr>
														<tr>
															<td><small class="text-success">CREDIT</small></td>
															<td>
																<small class="text-success">Success</small><br>
																<small>10/04/2024</small><br>
																<small class="text-default">for: Bhavani Groceries</small>
															</td>
															<td><strong class="text-success">+10</strong></td>
														</tr>
													</table>
													
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
						
					</div>-->
		<!-- state end-->

		<div class="row ">
			<div class="col-12">
				<div class="card  mb-4">
					<div class="card-header text-white bg-success border-0">
						<div class="media ">
							<div class="media-body text-white text-center">

								<h3 class="text-white">Wallet Amount</h3>

								<h2 class="text-white f30 mt-2">
									Rs.<?= isset($total_all_amount) ? $total_all_amount : 0; ?></h2>
							</div>
						</div>
					</div>

					<div class="card-footer text-center bg-white p-4">
						<div class="row">
							<div class="col">
								<h4 class="text-success weight-600">
									Rs.<?= isset($total_vendor_amount) ? $total_vendor_amount : 0; ?></h4>
								<span class="small">Vendors</span>
							</div>
							<div class="col">
								<h4 class="text-success weight-600">
									Rs.<?= isset($total_delivery_boy_amount) ? $total_delivery_boy_amount : 0; ?></h4>
								<span class="small">Delivery Boy's</span>
							</div>
							<div class="col">
								<h4 class="text-success weight-600">
									Rs.<?= isset($total_user_amount) ? $total_user_amount : 0; ?></h4>
								<span class="small">Users</span>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-12">
				<div class="btn-demo mb-4">
					<a href="<?php echo base_url('executive/withdraw_amount'); ?>" type="button"
						class="btn btn-primary btn-lg btn-block text-white">Withdraw Amount</a>
					<a href="<?php echo base_url('executive/transactions/r'); ?>" type="button"
						class="btn btn-secondary btn-lg btn-block text-white">Transactions</a>
					<a href="<?php echo base_url('executive/bank_account/r'); ?>" type="button"
						class="btn btn-success btn-lg btn-block text-white">Add Bank Account</a>
				</div>
			</div>
		</div>






	</div>
</main>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>