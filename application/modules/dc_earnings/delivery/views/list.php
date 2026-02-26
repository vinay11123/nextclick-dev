<style>
.list {
  display: table;
  border-spacing: 0 10px;
  padding: 0.5em 0;
}

.list > li {
  background-color: #e0e0e1;
  border-radius: 5px;
  color: #6c777f;
  display: table-row;
  width: 100%;
}
.list > li > label {
  border-bottom-left-radius: 5px;
  border-top-left-radius: 5px;
  background-color: #a1aab0;
  color: white;
  display: table-cell;
  min-width: 40%;
  padding: .5em;
  text-transform: capitalize;
}

.list > li > span {
  border-radius: 0 5px 5px 0;
  background-color: #e0e0e1;
  display: table-cell;
  padding: .5em;
}
</style>
<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Pending Transactions</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Id</th>
									<th>Amount</th>
									<th>Type</th>
									<th>Paytm</th>
									<th>Upi</th>
									<th>Bank</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($transactions)):?>
    							<?php $sno = 1; foreach ($transactions as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $transaction['unique_id'];?></td>
    									<td><?php echo $transaction['cash'];?></td>
    									<td><?php echo $transaction['type'];?></td>
    									<td><?php echo $transaction['paytm'];?></td>
    									<td><?php echo $transaction['upi'];?></td>
    									<td>
    									<?php if(isset($transaction['bank'])){?>
                                            <ul class="list">
                                                <li>
                                                  <label>Name </label>
                                                  <span><?php echo $transaction['bank']['name'];?></span>
                                                </li>
                                                <li>
                                                  <label>Bank name</label>
                                                  <span><?php echo $transaction['bank']['bank_name'];?></span>
                                                </li>
                                                <li>
                                                  <label>A/C</label>
                                                  <span><?php echo $transaction['bank']['ac'];?></span>
                                                </li>
                                                <li>
                                                  <label>IFSC</label>
                                                  <span><?php echo $transaction['bank']['ifsc'];?></span>
                                                </li>
                                              </ul>
                                         <?php }?>
                                        </td>
    									<td>
    										 <select  class="form-control border pay_status" id="<?php echo $transaction['id']?>">
                                                <option  disabled>..Select..</option>
                                                <?php if($transaction['status'] == '0'){?>
                                                    <option value="0" selected>Pending</option>
                                                    <option value="1">Success</option>
                                                <?php }else{?>
                                                	<option value="0" >Pending</option>
                                                    <option value="1" selected>Success</option>
                                                <?php }?>
                                            </select>
    									</td>
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Transactions</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	
<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Completed Transactions</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport1"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Id</th>
									<th>TXN Id</th>
									<th>Amount</th>
									<th>Type</th>
									<th>Paytm</th>
									<th>Upi</th>
									<th>Bank</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($completed_transactions)):?>
    							<?php $sno = 1; foreach ($completed_transactions as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $transaction['unique_id'];?></td>
    									<td><?php echo $transaction['txn_id'];?></td>
    									<td><?php echo $transaction['cash'];?></td>
    									<td><?php echo $transaction['type'];?></td>
    									<td><?php echo $transaction['paytm'];?></td>
    									<td><?php echo $transaction['upi'];?></td>
    									<td>
    									<?php if(isset($transaction['bank'])){?>
                                            <ul class="list">
                                                <li>
                                                  <label>Name </label>
                                                  <span><?php echo $transaction['bank']['name'];?></span>
                                                </li>
                                                <li>
                                                  <label>Bank name</label>
                                                  <span><?php echo $transaction['bank']['bank_name'];?></span>
                                                </li>
                                                <li>
                                                  <label>A/C</label>
                                                  <span><?php echo $transaction['bank']['ac'];?></span>
                                                </li>
                                                <li>
                                                  <label>IFSC</label>
                                                  <span><?php echo $transaction['bank']['ifsc'];?></span>
                                                </li>
                                              </ul>
                                         <?php }?>
                                        </td>
    									<td>
    										 <select  class="form-control border " disabled id="<?php echo $transaction['id']?>">
                                                <option  disabled>..Select..</option>
                                                <?php if($transaction['status'] == '0'){?>
                                                    <option value="0" selected>Pending</option>
                                                    <option value="1">Success</option>
                                                <?php }else{?>
                                                	<option value="0" >Pending</option>
                                                    <option value="1" selected>Success</option>
                                                <?php }?>
                                            </select>
    									</td>
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='8'><h3><center>No Transactions</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
	