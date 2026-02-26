
<!--Add Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">All Vendor Transactions</h4><br>
		<div class="flex-row">
			<div class="d-flex justify-content-center">
				<form class="form-inline" method="post" action="<?php echo base_url('vendor_payments/r')?>">
                  <label for="email" class="mr-sm-2">Start Date:</label>
                  <input type="text" class="form-control" required name="start_date" placeholder="yyyy-mm-dd" id="start_date" value="<?php echo (empty($this->session->flashdata('txn_search')['start_date']))? '' : $this->session->flashdata('txn_search')['start_date'];?>">
                  <input type="hidden" name="id" value="<?php echo (empty($this->session->flashdata('txn_search')['id']))? $_GET['id'] : $this->session->flashdata('txn_search')['id'];?>" />
                  <label for="pwd" class="mr-sm-2">End Date:</label>
                  <input type="text" class="form-control" required name="end_date" placeholder="yyyy-mm-dd" id="end_date" value="<?php echo (empty($this->session->flashdata('txn_search')['end_date']))? '' : $this->session->flashdata('txn_search')['end_date'];?>">
                  <button type="submit" class="btn btn-primary ml-sm-2">Submit</button>
                </form>
			</div>
		</div>
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven col-10"></h4>
					<button class=" col-2 btn btn-outline-warning">Wallet(₹): <?php echo $vendor['wallet']?></button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Date</th>
									<th>Order Id</th>
									<th>Transaction Id</th>
									<th>Description</th>
									<th>Credit</th>
									<th>Debit</th>
									<th>Balance</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($transactions)):?>
    							<?php $sno = 1; foreach ($transactions as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo date('d-m-Y', strtotime($transaction['created_at']));?></td>
    									<td><?php echo $transaction['order_id'];?></td>
    									<td><?php echo $transaction['txn_id'];?></td>
    									<td><?php echo $transaction['description'];?></td>
    									<td><?php echo (strcmp($transaction['type'], 'DEBIT'))? $transaction['cash']:'';?></td>
    									<td><?php echo (strcmp($transaction['type'], 'CREDIT'))? $transaction['cash']:'';?></td>
    									<td><?php echo $transaction['balance'];?></td>
    									<td>
        									<?php if($transaction['status'] == 0){
        									   echo "Pending";
        									}elseif ($transaction['status'] == 1){
        									    echo "Success";
        									}else{
        									    echo "Failed";
        									}?>
    									</td>
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='9'><h3><center>No Transactions</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>