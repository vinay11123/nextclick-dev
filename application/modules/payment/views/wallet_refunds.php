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
<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Transactions</h4>
          <a href = "<?php echo base_url(); ?>payment/wallet_transactions/c/0" class="btn btn-primary" style="margin-left: 860px;">proceed</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>User Name</th>
                  <th>Txn_ID</th>
                   <th>Unique ID</th>
                  <th>Customer Name</th>
									<th>Email</th>
                  <th>Phone</th>
									<th>Amount</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($transactions)):?>
    							<?php $sno = 1; foreach ($transactions as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $transaction['username'];?></td>
    									<td><?php echo $transaction['track_id'];?></td>
                      <td><?php echo $transaction['unique_id'];?></td>
    									<td><?php echo $transaction['first_name'].'-'.$transaction['last_name'];?></td>
    									<td><?php echo $transaction['email'];?></td>
    									<td><?php echo $transaction['phone'];?></td>
                      <td><?php echo $transaction['total'];?></td>
    									 <td>
                             <a href="<?php echo base_url()?>payment/wallet_transactions/c/0" class="mr-2"> <i class="fas fa-pencil-alt"></i>  </a>       
                       </td>
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Transactions</center></h3></th></tr>
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

	