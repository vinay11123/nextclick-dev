<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div>
	<h4 class="ven">Payment Details</h4>
	<?php if ($this->ion_auth_acl->has_permission('payment_make')) : ?>
		<a href="<?php echo base_url(); ?>payment/wallet_transactions/c/0" class="btn btn-primary col-md-2">Make Payment</a>
	<?php endif; ?>
</div><br><br>
<div class="container">
	<ul class="nav nav-tabs">
		<!-- <li class="active"><a data-toggle="tab" href="#home">All</a></li> -->
		<li class="active"><a data-toggle="tab" href="#menu1">Delivery Boy Earnings</a></li>
		<li><a data-toggle="tab" href="#menu2">Vendor Earnings</a></li>
		<li><a data-toggle="tab" href="#menu3">Delivery Boy Floating Cash</a></li>
		<li><a data-toggle="tab" href="#menu4">Delivery Boy's Wallet Amounts</a></li>
		<li><a data-toggle="tab" href="#menu5">Vendor's Wallet Amounts</a></li>
	</ul>

	<div class="tab-content">

		<div id="menu1" class="tab-pane fade in active">
			<div class="row">
				<div class="col-md-12">
					<div class="main-card mb-3 card">
						<div class="card-header">Filters</div>
						<div class="card-body">
							<form class="form-inline" novalidate="" action="<?php echo base_url(); ?>payment/wallet_transactions/list/0" method="post">

								<div class="form-group col-3">
									<label for="start_date">Start date:</label>
									<input type="text" name="start_date" value="<?php echo set_value('start_date') ?>" class="form-control" id="start_date">
								</div>
								<div class="form-group col-3">
									<label for="end_date">End date:</label>
									<input type="text" name="end_date" value="<?php echo set_value('end_date') ?>" class="form-control" id="end_date">
								</div>
								<div class="form-group col-3">
									<label for="end_date">Payment Type</label>
									<select class="form-control" name="type" id="type">
										<option value="">--Select--</option>
										<option value="CREDIT" <?= set_value('type') == 'CREDIT' ? 'selected' : ''; ?>>CREDIT</option>
										<option value="DEBIT" <?= set_value('type') == 'DEBIT' ? 'selected' : ''; ?>>DEBIT</option>

									</select>
								</div>

								<div class="col-md-12 mt-3">
									<div class="form-group col-2">
										<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-lg btn-outline-primary">Submit</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- <h4 class="ven col-10" style="text-align:left">List of Delivery Boy Transactions</h4> -->
			<div class="card-body">
				<div class="card">
					<div class="card-header">
						<h4 class="col-9 ven1">List of Delivery Boy Transactions</h4>

						<!-- <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="PaymentDatatable" class="table table-striped table-hover" style="width: 100%;">
								<thead>
									<tr>
										<th>S.no</th>
										<th>User Name</th>
										<th>Transaction ID</th>
										<th>Order ID</th>
										<th>Type</th>
										<th>Amount</th>
										<th>At</th>
										<th>Message</th>

									</tr>
								</thead>
								<tbody>
									<?php if ($this->ion_auth_acl->has_permission('payment_view')) : ?>
										<?php if (!empty($delivery_boy_transactions)) : ?>
											<?php $sno = 1;
											foreach ($delivery_boy_transactions as $transaction) : ?>
												<tr>
													<td><?php echo $sno++; ?></td>
													<td><?php echo $transaction['first_name']; ?></td>
													<td><?php echo $transaction['txn_id']; ?></td>
													<td><?php echo $transaction['track_id']; ?></td>
													<td><?php echo $transaction['type']; ?></td>
													<td><?php echo $transaction['amount']; ?></td>
													<td class="text-center"><?php echo date('d-M-Y H:i', strtotime($transaction['created_at'])); ?></td>

													<td><?php echo $transaction['message']; ?></td>
													<td></td>
													<!--<td>
    										 <select  class="form-control border pay_status" id="<?php echo $transaction['id'] ?>">
                                                <option  disabled>..Select..</option>
                                                <?php if ($transaction['status'] == '0') { ?>
                                                    <option value="0" selected>Pending</option>
                                                    <option value="1">Success</option>
                                                <?php } else { ?>
                                                	<option value="0" >Pending</option>
                                                    <option value="1" selected>Success</option>
                                                <?php } ?>
                                            </select>
    									</td>-->
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<th colspan='7'>
													<h3>
														<center>No Transactions</center>
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
						<div class="row  justify-content-center">
							<div class=" col-12" style='margin-top: 10px;'>
								<?= $pagination; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="menu2" class="tab-pane fade">
			<!-- <h4 class="ven col-10" style="text-align:left">List of Vendor Transactions</h4> -->
			<div class="row">
				<div class="col-md-12">
					<div class="main-card mb-3 card">
						<div class="card-header">Filters</div>
						<div class="card-body">
							<form class="form-inline" novalidate="" action="<?php echo base_url(); ?>payment/wallet_transactions/list/0" method="post">

								<div class="form-group col-3">
									<label for="start_date">Start date:</label>
									<input type="text" name="v_start_date" value="<?php echo set_value('v_start_date') ?>" class="form-control" id="start_date">
								</div>
								<div class="form-group col-3">
									<label for="end_date">End date:</label>
									<input type="text" name="v_end_date" value="<?php echo set_value('v_end_date') ?>" class="form-control" id="end_date">
								</div>
								<div class="form-group col-3">
									<label for="type">Payment Type</label>
									<select class="form-control" name="v_type" id="type">
										<option value="">--Select--</option>
										<option value="CREDIT" <?= set_value('v_type') == 'CREDIT' ? 'selected' : ''; ?>>CREDIT</option>
										<option value="DEBIT" <?= set_value('v_type') == 'DEBIT' ? 'selected' : ''; ?>>DEBIT</option>

									</select>
								</div>

								<div class="col-md-12 mt-3">
									<div class="form-group col-2">
										<button type="submit" name="v_submit" id="upload" value="VendorApply" class="btn btn-lg btn-outline-primary">Submit</button>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="card-header">
					<h4 class="col-9 ven1">List of Vendor Transactions</h4>

					<!-- <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a> -->
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Vendor Name</th>
									<th>Transaction ID</th>
									<th>Order ID</th>
									<th>Type</th>
									<th>Amount</th>
									<th>At</th>

									<th>Message</th>

								</tr>
							</thead>
							<tbody>
								<?php if ($this->ion_auth_acl->has_permission('payment_view')) : ?>
									<?php if (!empty($vendor_transactions)) : ?>
										<?php $sno = 1;
										foreach ($vendor_transactions as $transaction) : ?>
											<tr>
												<td><?php echo $sno++; ?></td>
												<td><?php echo $transaction['first_name']; ?></td>
												<td><?php echo $transaction['txn_id']; ?></td>
												<td><?php echo $transaction['track_id']; ?></td>
												<td><?php echo $transaction['type']; ?></td>
												<td><?php echo $transaction['amount']; ?></td>
												<td class="text-center"><?php echo date('d-M-Y H:i', strtotime($transaction['created_at'])); ?></td>

												<td><?php echo $transaction['message']; ?></td>
												<td></td>
												<!--<td>
    										 <select  class="form-control border pay_status" id="<?php echo $transaction['id'] ?>">
                                                <option  disabled>..Select..</option>
                                                <?php if ($transaction['status'] == '0') { ?>
                                                    <option value="0" selected>Pending</option>
                                                    <option value="1">Success</option>
                                                <?php } else { ?>
                                                	<option value="0" >Pending</option>
                                                    <option value="1" selected>Success</option>
                                                <?php } ?>
                                            </select>
    									</td>-->
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<th colspan='7'>
												<h3>
													<center>No Transactions</center>
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
				<div class="row  justify-content-center">
					<div class=" col-12" style='margin-top: 10px;'>
						<?= $pagination; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="menu3" class="tab-pane fade">
			<!-- <h4 class="ven col-10" style="text-align:left">List of Users Transactions</h4> -->
			<div class="card-body">
				<div class="card">
					<div class="card-header">
						<h4 class="col-9 ven1">List of Delivery Boy Floatings</h4>

						<!-- <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="PaymentDatatable" class="table table-striped table-hover" style="width: 100%;">
								<thead>
									<tr>
										<th>S.no</th>
										<th> Name</th>
										<th>Transaction ID</th>
										<th>Order ID</th>
										<th>Type</th>
										<th>Amount</th>
										<th>At</th>
										<th>Message</th>

									</tr>
								</thead>
								<tbody>
									<?php if ($this->ion_auth_acl->has_permission('payment_view')) : ?>
										<?php if (!empty($delivery_boy_floatings)) : ?>
											<?php $sno = 1;
											foreach ($delivery_boy_floatings as $transaction) : ?>
												<tr>
													<td><?php echo $sno++; ?></td>
													<td><?php echo $transaction['first_name']; ?></td>
													<td><?php echo $transaction['txn_id']; ?></td>
													<td><?php echo $transaction['track_id']; ?></td>
													<td><?php echo $transaction['type']; ?></td>
													<td><?php echo $transaction['amount']; ?></td>
													<td class="text-center"><?php echo date('d-M-Y H:i', strtotime($transaction['created_at'])); ?></td>

													<td><?php echo $transaction['message']; ?></td>
													<td></td>
													<!--<td>
    										 <select  class="form-control border pay_status" id="<?php echo $transaction['id'] ?>">
                                                <option  disabled>..Select..</option>
                                                <?php if ($transaction['status'] == '0') { ?>
                                                    <option value="0" selected>Pending</option>
                                                    <option value="1">Success</option>
                                                <?php } else { ?>
                                                	<option value="0" >Pending</option>
                                                    <option value="1" selected>Success</option>
                                                <?php } ?>
                                            </select>
    									</td>-->
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<th colspan='7'>
													<h3>
														<center>No Transactions</center>
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
						<div class="row  justify-content-center">
							<div class=" col-12" style='margin-top: 10px;'>
								<?= $pagination; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="menu4" class="tab-pane fade">
			<!-- <h4 class="ven col-10" style="text-align:left">List of Users Transactions</h4> -->
			<div class="card-body">
				<div class="card">
					<div class="card-header">
						<h4 class="col-9 ven1">List of Delivery Boy's Wallet Amounts</h4>

						<!-- <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="PaymentDatatable" class="table table-striped table-hover" style="width: 100%;">
								<thead>
									<tr>
										<th>S.no</th>
										<th>Name</th>
										<th>Phone Number</th>										
										<th>Earnings Amount</th>
										<th>COD Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($this->ion_auth_acl->has_permission('payment_view')) : ?>
										<?php if (!empty($delivery_boy_wallets)) : ?>
											<?php $sno = 1;
											foreach ($delivery_boy_wallets as $delivery_boy_wallet) : ?>
												<tr>
													<td><?php echo $sno++; ?></td>
													<td><?php echo $delivery_boy_wallet['first_name']; ?></td>
													<td><?php echo $delivery_boy_wallet['phone']; ?></td>
													<td><?php echo $delivery_boy_wallet['delivery_boy_earning_wallet']; ?></td>
													<td><?php echo $delivery_boy_wallet['floating_wallet']; ?></td>													
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<th colspan='5'>
													<h3>
														<center>No List</center>
													</h3>
												</th>
											</tr>
										<?php endif; ?>
									<?php else : ?>
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
						<div class="row  justify-content-center">
							<div class=" col-12" style='margin-top: 10px;'>
								<?= $pagination; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="menu5" class="tab-pane fade">
			<!-- <h4 class="ven col-10" style="text-align:left">List of Users Transactions</h4> -->
			<div class="card-body">
				<div class="card">
					<div class="card-header">
						<h4 class="col-9 ven1">List of Vendor's Wallet Amounts</h4>

						<!-- <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="PaymentDatatable" class="table table-striped table-hover" style="width: 100%;">
								<thead>
									<tr>
										<th>S.no</th>										
										<th>Vendor Name</th>
										<th>Vendor Businss Name</th>	
										<th>Phone Number</th>																			
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($this->ion_auth_acl->has_permission('payment_view')) : ?>
										<?php if (!empty($vendor_wallets)) : ?>
											<?php $sno = 1;
											foreach ($vendor_wallets as $vendor_wallet) : ?>
												<tr>
													<td><?php echo $sno++; ?></td>
													<td><?php echo $vendor_wallet['first_name']; ?></td>
													<td><?php echo $vendor_wallet['business_name']; ?></td>
													<td><?php echo $vendor_wallet['phone']; ?></td>
													<td><?php echo $vendor_wallet['vendor_earning_wallet']; ?></td>													
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<th colspan='5'>
													<h3>
														<center>No List</center>
													</h3>
												</th>
											</tr>
										<?php endif; ?>
									<?php else : ?>
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
</div>