	<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Booking Item</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Service</th>
									<th>Price</th>
									<th>Quantity </th>
									<th>Discount</th>
									<th>Total</th>
									<th>Date</th>
									<th>Start at</th>
									<th>End at</th>
							
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($booking_items)):?>
    							<?php  $sno = 1; foreach ($booking_items as $booking_item): ?>
    								<tr>

									<td><?php echo $sno++;?></td>
										<td><?php echo $booking_item['name'];?></td>
    									<td><?php echo $booking_item['price'];?></td>
    									<td><?php echo $booking_item['qty'];?></td>
    									<td><?php echo $booking_item['discount'];?></td>
    									<td><?php echo $booking_item['total'];?></td>
    									<td><?php echo $booking_item['booking_date'];?></td>
    									<td><?php echo $booking_item['start_time'];?></td>
    									<td><?php echo $booking_item['end_time'];?></td>
								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='10'><h3>
											<center>Sorry, No Bookings!!!</center>
										</h3></th>
								</tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>