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
<div class="row h-100 justify-content-center align-items-center" >
	<div class="col-12" >
    		<div class="card-header" >
    			<h4 class="ven subcategory">Vendor Payment Distribution</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('vendor/payouts/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Business Name</label>
    						<input type="text" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="q" id="q" placeholder="Business Name" value="<?php echo $q;?>" class="form-control">
    					</div>
    					
                        <div class="form-group col-2">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>
    					
					</div>
					<div class="row">
					<div class="col-md-12">

					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 bordernobg"><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
				
					</div>
						</div>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('vendor/payouts/0');?>" method="post" enctype="multipart/form-data">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
<div class="card-body">
    <div class="card">
        <div class="card-header">
            <h4 class="col-9 ven1">Vendor Payment Distribution</h4>
            <label class="right">Est. Total: Rs. <?php ((float) $total_payout >0) ? print(number_format((float)$total_payout, 2, '.', '')) : print '0' ; ?></label>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if (!empty($vendor_payouts)) { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Est. Account Status</th>
                                <th>Amount</th>
                                <th>View Payout Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($vendor_payouts as $vendor_payout) { ?>
                                <tr>
                                    <td> <?php echo $i; ?> </td>
                                    <td> <?php echo $vendor_payout['id'] ?> </td>
                                    <td> <?php echo $vendor_payout['first_name']." ".$vendor_payout['last_name'] ?> </td>
                                    <td> <?php echo $vendor_payout['business_name'] ?> </td>
                                    <td> <?php echo $vendor_payout['external_id'] ? "<span style='color: green'>Can be Processed</span>" : "<span style='color: red'>Cannot be Processed</span>" ?> </td>
                                    <td> <?php echo $vendor_payout['wallet'] ?> </td>
                                    <td> <a href="<?=base_url();?>vendor/payout_detials/details/0?vendor_user_id=<?=base64_encode(base64_encode($vendor_payout['id']))?>"><i class="fas fa-eye"></i></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                    <form role="form" method="post" action="<?php echo site_url() ?>vendor/process_payout">
                        <button class="btn btn-primary right">Process</button>
                    </form>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <strong>No Vendors Found!</strong>
                    </div>
                <?php } ?>
            </div>
			<!-- Paginate -->
    				<div class="row  justify-content-center">
    					<div class=" col-12" style='margin-top: 10px;'>
                           <?= $pagination; ?>
                        </div>
    				</div>
        </div>
    </div>
</div>