<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;"> 
							<thead>
								<tr>
									<th>Id</th>
									<th>Order</th>
									<th>Customer</th>
									<th>Vendor</th>
									<th>Delivery boy</th>
									<th>Reason</th>
									<th>Status</th>
									<th>Created At</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if($this->ion_auth_acl->has_permission('order_veiw')):?>
							<?php if(!empty($rejected_orders)):?>
    							<?php  $sno = 1; foreach ($rejected_orders as $rejected_order): ?>
    								<tr>
									<td><?php echo $sno;?></td>
									<td><?php echo $rejected_order['order_details']['track_id'];?></td>
									<td>
    									<ul>
    										<li>Name: <b><?php echo $rejected_order['order_details']['shipping_address']['name']; ?></b></li>
    										<li>Phone: <b><?php echo $rejected_order['order_details']['shipping_address']['phone']; ?></b></li>
    										<li>Email: <b><?php echo $rejected_order['order_details']['shipping_address']['email']; ?></b></li>
    									</ul>
    								</td>
    								<td>
    									<ul>
    										<li>Name: <b><?php echo $rejected_order['order_details']['vendor']['name']; ?></b></li>
    										<li>Whatsapp: <b><?php echo $rejected_order['order_details']['vendor']['whats_app_no']; ?></b></li>
    										<li>Phone: <b><?php echo $rejected_order['order_details']['vendor']['secondary_contact']; ?></b></li>
    									</ul>
    								</td>
    								<td>
    									<ul>
    										<li>Name: <b><?php echo $rejected_order['delivery_boy']['display_name']; ?></b></li>
    										<li>Phone: <b><?php echo $rejected_order['delivery_boy']['phone']; ?></b></li>
    										<li>Email: : <b><?php echo $rejected_order['delivery_boy']['email']; ?></b></li>
    									</ul>
    								</td>
									<td><?php echo $rejected_order['rejection_reason'];?></td>
									<td><?php 
									if($rejected_order['status'] == 0){
									    echo 'Pending';
									}elseif ($rejected_order['status'] == 1){
									    echo 'Accepted';
									}elseif ($rejected_order['status'] == 2){
									    echo 'Cancelled';
									}elseif ($rejected_order['status'] == 3){
									    echo 'Reachable';
									}
									?></td>
									<td><?php echo $rejected_order['created_at'];?></td>
									<td><?php if($rejected_order['status'] == 0){ ?>
									    <a class="btn btn-success" href="<?php echo base_url();?>accept_dj_rejection?id=<?php echo base64_encode(base64_encode($rejected_order['id']))?>">Accept</a>
										<a class="btn btn-danger" href="<?php echo base_url();?>cancel_dj_rejection?id=<?php echo base64_encode(base64_encode($rejected_order['id']))?>">Cancel</a>
									<?php }?>
									</td>
								</tr>
    							<?php $sno++; endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='10'><h3>
									<center>No Rejected orders</center>
										</h3></th>
								</tr>
							<?php endif;?>
							<?php else :?>
							<tr>
								<th colspan='10'>
								<h3><center>No Access!</center></h3></th>
							</tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
					<!-- Paginate -->
                <div class="row  justify-content-center">
                    <div class=" col-12" style='margin-top: 10px;'>
                    	 <?= $pagination; ?>
                    </div>
                </div>
				</div>