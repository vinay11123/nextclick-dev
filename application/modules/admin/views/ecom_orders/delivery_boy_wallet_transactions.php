<style>
	.list {
		display: table;
		border-spacing: 0 10px;
		padding: 0.5em 0;
	}

	.list>li {
		background-color: #e0e0e1;
		border-radius: 5px;
		color: #6c777f;
		display: table-row;
		width: 100%;
	}

	.list>li>label {
		border-bottom-left-radius: 5px;
		border-top-left-radius: 5px;
		background-color: #a1aab0;
		color: white;
		display: table-cell;
		min-width: 40%;
		padding: .5em;
		text-transform: capitalize;
	}

	.list>li>span {
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

	.or {
		text-align: center;
	}
</style>

<?php
if ($this->session->flashdata('error')) { ?>
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $this->session->flashdata('error'); ?>
	</div>

<?php } ?>

<div class="row h-100 justify-content-center align-items-center">
   <div class="col-12">
	
<h2 class="ven">Payment Details</h2>
     <div class="card-header">
       <form class="" novalidate="" action="<?php echo base_url('food_orders/r/0'); ?>" method="post" enctype="multipart/form-data">
         <div class="row">
           <div class="form-group col-3">
             <label for="q">Delivery Boy Name</label>
             
             <select class="form-control" name="delivery_boy_name" id="delivery_boy_name">
               <option value="">--Select--</option>
               <?php
                foreach ($delivery_boy_names as $a) { ?>
                 <option value="<?php echo $a['id']; ?>" <?= set_value('delivery_boy_name') == $a['id'] ? 'selected' : '';?>><?php echo $a['first_name']; ?></option>
               <?php } ?>
             </select>
           </div>

         </div>
         <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
       </form>
       <form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('food_orders/r/0'); ?>" method="post">
         <input type="hidden" name="cname" id="cname" placeholder="Customer Name" value="" class="form-control">
         <input type="hidden" name="vname" id="vname" placeholder="Vendor Name" value="" class="form-control">
         <input type="hidden" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
         <button type="submit" name="submit" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
       </form>
     </div>
   </div>
 </div>
<div class="card-body">
	<div class="card">
		<div class="form-group card-header">
			<h4 class="ven col-10" style="text-align:left">List of Delivery Transactions</h4>

		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
					<thead>
						<tr>
							<th>S.no</th>
							<th>Order Id</th>
							<th>Delivery Boy Name</th>
							<th>Transaction ID</th>
							<th>Type<br>(Debit/Credit)</th>
							<th>Amount</th>
							<th>Balance</th>
							<th>Message</th>

						</tr>
					</thead>
					<tbody>
						<?php if ($this->ion_auth_acl->has_permission('order_veiw')) : ?>
							<?php if (!empty($transactions)) : ?>
								<?php $sno = 1;
								foreach ($transactions as $transaction) : ?>
									<tr>
										<td><?php echo $sno++; ?></td>
										<td><?php echo $transaction['id']; ?></td>
										<td><?php echo $transaction['first_name']; ?></td>
										<td><?php echo $transaction['txn_id']; ?></td>
										<td><?php echo $transaction['type']; ?></td>
										<td><?php echo $transaction['amount']; ?></td>
										<td><?php echo $transaction['balance']; ?></td>
										<td><?php echo $transaction['message']; ?></td>

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