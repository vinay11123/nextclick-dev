<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>

<style>
	.table.invoice-detail-table th {
		padding: 5px;
		margin: 0;
	}
</style>

<div id="DivIdToPrint">
	<div class="pcoded-content">
		<!-- [ breadcrumb ] start -->
		<div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-12">

						<ul class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
									<i class="feather icon-home"></i>
								</a>
							</li>
							<li class="breadcrumb-item">Ecommerce</li>
							<li class="breadcrumb-item">eComm Order Details</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- [ breadcrumb ] end -->

		<!-- Main-body start -->
		<div class="main-body">
			<div class="page-wrapper">

				<!-- Page-body start -->
				<div class="page-body">

					<div class="card">
						<div class="invoice-contact">
							<div class="row">
								<div class="col-md-6">
									<div class="invoice-box">
										<div class="col-sm-12">
											<table
												class="table table-responsive invoice-table table-borderless mb-0 pb-0">
												<tbody>
													<tr>
														<td>
															<img src="<?php echo base_url() ?>assets/img/logo.png"
																width="200" height="auto" alt="" />
														</td>
													</tr>

													<tr>
														<!-- <td>Address: 401, 4th Floor, New Mark House Hitech City, Patrika
															Nagar.<br> -->
														<h3 class="mt-2">Invoice <span>#
																<?php echo $orderst[0]['id']; ?>
															</span></h4>
														</h3>
														<p>Invoice Date:
															<?php $InvoiceDate = $orderst[0]['created_at']; ?>
															<?php echo date('d-M-Y', strtotime($InvoiceDate)); ?>
														</p>

														</td>
													</tr>

												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="invoice-box">
										<div>
											<table
												class="table table-responsive invoice-table table-borderless mb-0 pb-0">
												<tbody>
													<tr>
														<br>
														<td class="font-weight-bold text-primary">
															<h5><?php echo $orderst[0]['vendor_name']; ?></h5>
														</td>
														<!-- <td>
															<img src="<?php echo base_url() . 'uploads/list_banner_image/list_banner_' . $orderst[0]['vendor_id'] . '.jpg?' . time(); ?>"
																width="100" height="auto" alt="" />
														</td> -->
													</tr>

													<tr>
														<td><?php echo stripslashes(nl2br($orderst[0]['vendor_address'])); ?></span>
														</td>
													</tr>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-block">
							<div class="row invoive-info">
								<div class="col-md-6 col-xs-12 invoice-client-info">
									<h6>Shipping To:</h6>
									<h6 class="m-0"><?php echo $orderst[0]['first_name']; ?></h6>
									<p class="m-0 m-t-10"><?php echo stripslashes(nl2br($orderst[0]['address'])); ?></p>
									<p class="m-0"><?php echo $orderst[0]['phone']; ?></p>
									<p><?php echo $orderst[0]['email']; ?></p>
									<p>Status : <span class="label label-warning">
											<?php echo $orderst[0]['order_status']; ?></span></p>
									<p class="text-danger">TXN ID: <?php echo $orderst[0]['track_id']; ?> </p>
								</div>
								<div class="col-md-3 col-sm-6">
									<p>Product Pickup Image:</p>
									<img src="<?php echo base_url() . 'uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $orderst[0]['dj_id'] . '.jpg?' . time(); ?>"
										width="200" height="auto" alt="" />

								</div>
								<div class="col-md-3 col-sm-6">
									<p>Product Delivery Image:</p>
									<img src="<?php echo base_url() . 'uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $orderst[0]['dj_id'] . '.jpg?' . time(); ?>"
										width="200" height="auto" alt="" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table  invoice-detail-table">
											<thead>
												<tr class="bg-c-blue">
													<th>Order ID</th>
													<th width="150">Product Name</th>
													<th>Product Image</th>
													<th>Quantity</th>
													<th>Rate</th>
													<th>Tax</th>
													<th>Promo Discount</th>
													<th>Promo Banner Discount</th>
													<th>Coupon Discount</th>
													<th>Free Delivery</th>
													<th>Payment Type</th>
													<th>Total</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$dis = 0.00;
												$tx = 0.00;
												$sno = 1;
												$subtot = 0.00;
												if (!empty($custprod)):
													foreach ($custprod as $order): ?>
														<tr>
															<td>#<?php echo $order['track_id']; ?></td>
															<td>
																<h6 class="mb-0"><?php echo $order['food_name']; ?></h6> <span
																	class="text-muted">
																	<?php echo $order['product_quantity']; ?> </span>
															</td>
															<td><img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $order['image_id']; ?>.jpg"
																	width="200" style="width: 200px !important ;"></td>
															<td><?php echo $order['qty']; ?></td>
															<td>₹<?php echo $order['price']; ?></td>
															<td>₹<?php echo $order['tax']; ?></td>
															<td>₹<?php if ($order['promocode_discount'] > 0) {
																echo $order['promocode_discount'];
															} else {
																echo '0';
															}
															; ?>
															</td>
															<td>₹<?php if ($order['promotion_banner_discount'] > 0) {
																echo $order['promotion_banner_discount'];
															} else {
																echo '0';
															}
															; ?>

															<td>₹<?php if ($orderst[0]['cupon_discount'] > 0) {
																echo $orderst[0]['cupon_discount'];
															} else {
																echo '0';
															}
															; ?>
															<td><?php echo ($orderst[0]['cupon_id'] == 1) ? 'Yes' : 'No'; ?>
															</td>
															<td><?php echo $orderst[0]['payment_method_name']; ?></td>
															<td><span
																	class="font-weight-semibold">₹<?php echo $order['total']; ?></span>
															</td>
														</tr>
														<?php
														$dis = (float) $dis + (float) $order['promocode_discount'] + (float) $order['promotion_banner_discount'] + (float) $orderst[0]['cupon_discount'];
														$tx = (float) $tx + (float) $order['tax'];
														$subtot = (float) $subtot + (float) $order['total'];
														?>
													<?php endforeach;
												endif; ?>
											</tbody>
										</table>
									</div>

									<div class="invoicecard-body">
										<div class="d-md-flex flex-md-wrap">
											<div class="col-6">

												<div class="card">
													<div class="card-block">
														<div class="row invoive-info mb-3">
															<div class="col-md-8 col-xs-12 invoice-client-info">
																<h6>Order Status</h6><br>

																<div class="table-responsive">

																	<?php
																	$orderPlacedStatus = 0;
																	$orderPreparingStatus = 0;
																	$deliveryBoyAssignedStatus = 0;
																	$deliveryBoyReachedPickupPointStatus = 0;
																	$deliveryBoyPickedOrderStatus = 0;


																	$outDeliveryStatus = 0;
																	$reachedDeliveryPointStatus = 0;
																	$orderDeliveredStatus = 0;


																	$customerCancelled = 0;
																	$vendorRejected = 0;
																	$rejectedByDeliveryPartner = 0;

																	?>
																	<?php foreach ($order_status_result as $statusObj):
																		?>
																		<?php $status = $statusObj['order_status_id']; ?>
																		<?php $status_name = $statusObj['order_status_name']; ?>
																		<?php if (($status == ORDER_STATUS_ORDER_IS_PLACED_ID) && $orderPlacedStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $orderPlacedStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_ORDER_HAS_BEEN_PREPARING_ID) && $orderPreparingStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $orderPreparingStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_DELIVERY_BOY_ASSIGNED_ID) && $deliveryBoyAssignedStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $deliveryBoyAssignedStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_REACHED_TO_PICKUP_POINT_ID) && $deliveryBoyReachedPickupPointStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $deliveryBoyReachedPickupPointStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_PICKED_THE_ORDER_FROM_VENDOR_ID) && $deliveryBoyPickedOrderStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $deliveryBoyPickedOrderStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_OUT_FOR_DELIVERY_ID) && $outDeliveryStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $outDeliveryStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_REACHED_TO_DELIVERY_POINT_ID) && $reachedDeliveryPointStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $reachedDeliveryPointStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_ORDER_DELIVERED_ID) && $orderDeliveredStatus == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $orderDeliveredStatus++; endif; ?>

																		<?php if (($status == ORDER_STATUS_REJECTED_BY_VENDOR_ID) && $customerCancelled == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $customerCancelled++; endif; ?>

																		<?php if (($status == ORDER_STATUS_CANCELLED_BY_CUSTOMER_ID) && $vendorRejected == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $vendorRejected++; endif; ?>

																		<?php if (($status == ORDER_STATUS_REJECTED_BY_DELIVERY_PARTNER_ID) && $rejectedByDeliveryPartner == 0): ?>
																			<p><span class="label label-success"><i
																						class="feather icon-check text-white"></i></span>
																				<?php echo $status_name; ?></p>
																			<?php $rejectedByDeliveryPartner++; endif; ?>


																	<?php endforeach; ?>


																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="table-responsive">
													<table class="table">
														<tbody>
															<tr>
																<th class="text-left">Subtotal:</th>
																<td class="text-right">
																	₹<?php echo number_format($subtot, 2); ?></td>
															</tr>
															<tr>
																<th class="text-left">Discount:</th>
																<td class="text-right">
																	₹<?php echo number_format($dis, 2); ?></td>
															</tr>
															<tr>
																<th class="text-left">Tax:</th>
																<td class="text-right">
																	₹<?php echo number_format($tx, 2); ?></td>
															</tr>
															<tr>
																<th class="text-left">Shipping And Handling:</th>
																<td class="text-right">
																	<?php echo '₹' . $orderst[0]['delivery_fee']; ?>
																</td>
															</tr>
															<tr>
																<th class="text-left">Total:</th>
																<td class="text-right text-primary">
																	<h5 class="font-weight-semibold">
																		₹<?php echo $orderst[0]['grand_total']; ?></h5>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


					</div>
				</div>
				<!-- Invoice card end -->
				<div class=" text-center">
					<div class="col-sm-12 invoice-btn-group text-center">
						<?php

						$pickupFilename = $_SERVER["DOCUMENT_ROOT"] . '/uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $orderst[0]['dj_id'] . '.jpg';

						$deliveryFilename = $_SERVER["DOCUMENT_ROOT"] . '/uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $orderst[0]['dj_id'] . '.jpg';

						if (file_exists($pickupFilename) && file_exists($deliveryFilename)):
							?>
							<a href="#" data-type="vendor" data-orderid="<?php echo $orderst[0]['id']; ?>"
								class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">
								Send Invoice to Vendor<i class='feather icon-mail'></i>
							</a>

							<a href="#" data-type="vendor_download" data-orderid="<?php echo $orderst[0]['id']; ?>"
								class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">Download
								Vendor Invoice<i class='feather icon-download'></i></a>

							<a href="#" data-type="user_download" data-orderid="<?php echo $orderst[0]['id']; ?>"
								class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">
								Download User Invoice<i class='feather icon-download'></i>
							</a>

							<a href="#" data-type="user" data-orderid="<?php echo $orderst[0]['id']; ?>"
								class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">
								Send Invoice to User<i class='feather icon-mail'></i>
							</a>
							<?php
						endif;
						?>


						<!-- <button type="button" onclick="printDiv('DivIdToPrint')"
							class="btn btn-primary btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20">Print</button> -->
						<a href="<?= base_url('ecom_ecom_orders') ?>"
							class="btn btn-danger waves-effect m-b-10 btn-sm waves-light">Back</a>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	$(document).ready(function () {
		$('.send-invoice').click(function (e) {
			e.preventDefault();
			var type = $(this).data('type');
			var orderId = $(this).data('orderid');

			var url = '<?php echo base_url("eecom_orders/pdf"); ?>?id=' + encodeURIComponent(btoa(btoa(orderId))) + '&ctype=' + type;

			var $button = $(this);
			$button.attr('disabled', true).addClass('disabled');
			$button.find('.feather').removeClass('icon-mail').addClass('icon-loader');

			$.ajax({
				url: url,
				type: 'GET',
				success: function (response) {
					if (type == 'vendor_download' || type == 'user_download') {
						var filePath = JSON.parse(response);
						var link = document.createElement('a');
						link.href = filePath;
						link.download = filePath.split('/').pop();
						document.body.appendChild(link);
						link.click();
						document.body.removeChild(link);
					} else if (type == 'user' || type == 'vendor') {
						console.log("Email sent successfully");
					}

					$button.attr('disabled', false).removeClass('disabled');
					$button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
				},
				error: function (xhr, status, error) {
					console.error('Error sending email:', error);
					$button.attr('disabled', false).removeClass('disabled');
					$button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
				}
			});

		});

	});
</script>

<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>