
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
									<th>Price</th>
									<th>Quantity </th>
									<th>Discount</th>
									<th>Total</th>
									<th>Booking Date</th>
								<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($service_booking_item)):?>
    							<?php  $sno = 1; foreach ($service_booking_item as $booking_item): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									
    									<td><?php echo $booking_item['price'];?></td>
    									<td><?php echo $booking_item['qty'];?></td>
    									<td><?php echo $booking_item['discount'];?></td>
    									<td><?php echo $booking_item['total'];?></td>
    									<td><?php echo $booking_item['booking_date'];?></td>

    									
									
									<td><!-- 
										<button type="submit" href="<?php echo base_url()?>doctors_booking/view?id=<?php echo $speciality['id']; ?>" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button> -->
										<a
									href="#"
									target="_blank" class=" mr-2  " type="category"> <i
										class="fa fa-book"></i>Search</a>
									</td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>Sorry!! No Specialities!!!</center>
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