 <style>
.page-item>a {
	position: relative;
	display: block;
	padding: .5rem .75rem;
	margin-left: -1px;
	line-height: 1.25;
	color: #007bff;
	background-color: #fff;
	border: 1px solid #dee2e6;
}

a {
	color: #007bff;
	text-decoration: none;
	background-color: transparent;
}

.pagination>li.active>a {
	background-color: orange !important;
}

.dataTables_filter {
	display: none;
}
.or{
    text-align: center;
}
</style>
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Vendors Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('doctors_booking/r/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				
    					<div class="form-group col-2">
    						<label for="exe">Vendor Id</label>
    						<input type="text" id="exe" name="vendor_id" placeholder="Unique Id" value="<?php echo $vendor_id;?>" class="form-control">
    					</div>
    					<div class="form-group col-2">
                            <label for="status">Status</label>
                            <select calss="form-control" name="booking_status" class="form-control">
                            	<option value="0" <?php echo ($booking_status == 0)? "selected" : ""?>>Cancelled</option>
                            	<option value="1" <?php echo ($booking_status == 1)? "selected" : ""?>>Received</option>
                            	<option value="2" <?php echo ($booking_status == 2)? "selected" : ""?>>Accepted</option>
                            	<option value="3" <?php echo ($booking_status == 3)? "selected" : ""?>>Servicing</option>
                            	<option value="4" <?php echo ($booking_status == 4)? "selected" : ""?>>Completed</option>
                            	<option value="5" <?php echo ($booking_status == 5)? "selected" : ""?>>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group col-2">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>
					</div>
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp; Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php //echo base_url('doctors_booking/r/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
    				<select calss="form-control" name="booking_status" style="display: none" class="form-control">
                            	<option value="1" >Received</option>
                    </select>
                    <input type="hidden" id="noofrows" name="noofrows" placeholder="rows" value="10" class="form-control">
      
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
		</div>
	</div>
	<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Booking</h4>
					
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Track Id</th>
									<th>Vendor Id</th>
									<th>Payment Method </th>
									<th>Sub Total</th>
									<th>Discount</th>
									<th>Tax</th>
									<th>Total</th>
									<th>Status</th>
								<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($booking_service)):?>
    							<?php  $sno = 1; foreach ($booking_service as $service): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									
    									<td><?php echo $service['track_id'];?></td>
    									<td><?php echo $service['vendor_id'];?></td>
    									<td>
    										<?php if ($service['payment_method_id'] == 1){ ?>
										    <?php echo "Cash On Delivery"?>
										    <?php }  ?>
    									</td>
    									<td><?php echo $service['sub_total'];?></td>
    									<td><?php echo $service['discount'];?></td>
    									<td><?php echo $service['tax'];?></td>
    									<td><?php echo $service['total'];?></td>
    									<td><?php if ($service['booking_status'] == 0){ ?>
											  <?php echo "Cancelled"?>
											  <?php }  ?>
											  <?php if ($service['booking_status'] == 1){ ?>
											 <?php echo "Received"?><br>
											  <?php } ?>
											  <?php  if  ($service['booking_status'] == 2){ ?>
											  <?php echo "Accepted"?>
											  <?php } ?>
											  <?php  if  ($service['booking_status'] == 3){ ?>
											 <?php echo "Servicing"?>
											 <?php } ?>
											  <?php  if  ($service['booking_status'] == 4){ ?>
											  <?php echo "Completed"?>
											  <?php } ?>
											  <?php  if  ($service['booking_status'] == 5){ ?>
											 <?php echo "Rejected"?>
											  <?php } ?></td>
									
									<td>
									<!-- 	<button type="submit" href="<?php echo base_url()?>doctors_booking/view?id=<?php echo $speciality['id']; ?>" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>	 --><a
									href="<?php echo base_url()?>od_service_booking/view/0?id=<?php echo $service['id']; ?>"
									target="_blank" class=" mr-2  " type="od_service_booking"> <i
										class="fa fa-book"></i>Search</a>
									</td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='10'><h3>
											<center>Sorry!! No Bookings!!!</center>
										</h3></th>
								</tr>
							<?php endif;?>
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