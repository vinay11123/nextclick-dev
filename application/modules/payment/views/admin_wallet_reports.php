<!-- Wallet transactins css -->
<link href="https://demo.dashboardpack.com/architectui-html-free/main.css" rel="stylesheet">
<style>
.main-content{
    background-color: #f1f3f4;
}
</style>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-4">
				<div class="card-shadow-danger mb-3 widget-chart widget-chart2 text-left card">
					<div class="widget-content">
						<div class="widget-content-outer">
							<div class="widget-content-wrapper">
								<div class="widget-content-left pr-2 fsize-1">
									<div class="widget-numbers mt-0 fsize-3 text-danger">&#8377; <?php echo $wallet_details['wallet'];?></div>
								</div>
								
							</div>
							<div class="widget-content-left fsize-1">
								<div class="text-muted opacity-6">Earnings Wallet</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="card-shadow-success mb-3 widget-chart widget-chart2 text-left card">
					<div class="widget-content">
						<div class="widget-content-outer">
							<div class="widget-content-wrapper">
								<div class="widget-content-left pr-2 fsize-1">
									<div class="widget-numbers mt-0 fsize-3 text-success">&#8377; <?php echo $wallet_details['floating_wallet'];?></div>
								</div>
							</div>
							<div class="widget-content-left fsize-1">
								<div class="text-muted opacity-6">Floating Wallet</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="card-shadow-warning mb-3 widget-chart widget-chart2 text-left card">
					<div class="widget-content">
						<div class="widget-content-outer">
							<div class="widget-content-wrapper">
								<div class="widget-content-left pr-2 fsize-1">
									<div class="widget-numbers mt-0 fsize-3 text-warning">&#8377; <?php echo $wallet_details['income_wallet'];?></div>
								</div>
							</div>
							<div class="widget-content-left fsize-1">
								<div class="text-muted opacity-6">Income Wallet</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-header">Filters</div>
					<div class="card-body">
						<form class="form-inline" novalidate="" action="<?php echo base_url();?>admin_wallet_reports/0" method="post">
                          <!-- <div class="form-group col-3">
                            <label for="search">Search:</label>
                            <input type="text" name="<?php echo (empty($q))? '' : $q;?>" class="form-control" id="search">
                          </div>-->
                          <div class="form-group col-3">
                            <label for="start_date">Start date:</label>
                            <input type="text" name="<?php echo (empty($start_date))? '' : $start_date;?>" class="form-control" id="start_date">
                          </div>
                          <div class="form-group col-3">
                            <label for="end_date">End date:</label>
                            <input type="text" name="<?php echo (empty($end_date))? '' : $end_date;?>" class="form-control" id="end_date">
                          </div>
                          <div class="form-group col-3">
                            <label for="noofrows">Rows count:</label>
                            <input type="text" name="<?php echo (empty($noofrows))? '' : $noofrows;?>" class="form-control" id="noofrows">
                          </div>
                          <div class="col-md-12 mt-3">
            				<div class="form-group col-2">
                          		<button type="submit" class="btn btn-lg btn-outline-primary">Submit</button>
                          	</div>
            			</div>
					</div>
				</div>
			</div>
			</form>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-header">All transactions</div>
					<div class="table-responsive">
						<table class="align-middle mb-0 table table-borderless table-striped table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th>Wallet</th>
									<th class="text-center">User</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Balance</th>
									<th class="text-center">Message</th>
									<th class="text-center">Order</th>
									<th class="text-center">At</th>
									<th class="text-center">Status</th>
									<th class="text-center">View In Details</th>
								</tr>
							</thead>
							<tbody>
								<?php if(! empty($transactions)):
								    foreach ($transactions as $key => $txn):
								?>
								<tr>
								<td class="text-center text-muted">#<?php echo $txn['txn_id'];?></td>
								<td class="text-center">
									<?php if($txn['status'] == 1){
									    echo 'Earnings wallet';
									}elseif($txn['status'] == 2){ 
									    echo 'Floating wallet';
									}elseif($txn['status'] == 3){ 
									    echo 'Income wallet';
									}?>
								</td>
								<td>
									<div class="widget-content p-0">
										<div class="widget-content-wrapper">
											<div class="widget-content-left mr-3">
											</div>
											<div class="widget-content-left flex2">
												<div class="widget-heading"><?php echo (empty($txn['user_account']['display_name']))? $txn['user_account']['first_name'] : $txn['user_account']['display_name']?></div>
												<div class="widget-subheading opacity-7"><?php echo $txn['user_account']['phone']?></div>
											</div>
										</div>
									</div>
								</td>
								<td class="text-center"><?php echo $txn['amount']?></td>
								<td class="text-center"><?php echo $txn['balance']?></td>
								<td class="text-center">
									<?php if(!empty($txn['track_id'])){
									    echo 'Order Amount';
									}elseif(!empty($txn['message'])){
									    echo $txn['message'];
									}else{
										echo '';
									}?>
								</td>
								<td class="text-center"><?php echo $txn['track_id']?></td>
								<td class="text-center"><?php echo date('d-M-Y H:i', strtotime($txn['created_at']));?></td>
								<td class="text-center">
									<?php if($txn['type'] == 'CREDIT'){?>
										<div class="badge badge-success">CREDIT</div>
									<?php }else{ ?>
										<div class="badge badge-danger">DEBIT</div>
									<?php  }?>
								</td>
																<td class="text-center">
										<?php 
											$track_id=$txn['track_id'];

											if($txn['track_id']!=null){ 
											$this->db->select('id');
											$this->db->from('ecom_orders');
											$this->db->where('track_id', $track_id);
											$query = $this->db->get();
											 $rows = $query->result();
											foreach ($rows as $row){
												$order_id= $row->id;              //This is line 183
											}
											$enorder_id=base64_encode(base64_encode($order_id));
												?>
										
										<a href="<?php echo base_url();?>admin/payout_detials/edit/<?=$enorder_id;?>" class="mr-2" > <i class="fas fa-eye"></i></a>
										<?php } ?>
								</td>
								</tr>
								<?php endforeach;else :?>
								<tr>
									<th colspan='10'><h3><center>No Transactions</center></h3></th>
								</tr>
								<?php endif;?>
							</tbody>
						</table>
					</div>
					<div class="d-block text-center card-footer">
						<?php echo $pagination;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wallet transaction page css -->
<script type="text/javascript" src="https://demo.dashboardpack.com/architectui-html-free/assets/scripts/main.js"></script>