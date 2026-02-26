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
    			<h4 class="ven subcategory">Delivery Partner Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('delivery_partner/r/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Search</label>
    						<input type="text" name="q" id="q" placeholder="mobile number" value="<?php echo $q;?>" class="form-control">
    					</div>
    					<div class="form-group col-3">
    						<label for="exe">User Id</label>
    						<input type="text" id="exe" name="unique_id" placeholder="Unique Id" value="<?php echo $unique_id;?>" class="form-control">
    					</div>
                        <div class="form-group col-3">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>
						<div class="form-group col-3">
						<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 mt"><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>	
						
						</div>
					</div>
				
					
        		</form>
				
        		<form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php //echo base_url('vendors_filter/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt7"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
											
			</div>
		</div>
	</div>
	
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Delivery Partner</h4>
					<!--<a class="btn btn-outline-dark btn-lg col-2" href="<?php //echo base_url('delivery_partner/c/0')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Delivery Partner</a>-->
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>User Id</th>
									<th>User Name</th>
									<th>Wallet(RS)</th>
									<th>Mobile</th>
									<th>Email</th>
									<th>Created On</th>
									<?php if($this->ion_auth_acl->has_permission('delivery_partner_approval')):?>
                                    <th>Status</th>
                                    <?php endif;?>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if($this->ion_auth_acl->has_permission('delivery_partner_view')):?>
                        	<?php $i = 1; foreach ($users as $user):?>
                          <tr>
									<td><?php echo $i++;?></td>
									<td><?php echo $user['id'];?></td>
									<td class="tdcolorone"><?php echo $user['first_name'].' '.$user['last_name'];?></td>
									<td class="tdcolortwo"><?php echo $user['wallet'];?></td>
									<td><?php echo $user['phone'];?></td>
									<td><?php echo $user['email'];?></td>
									<td><?php echo $user['created_at'];?></td>
									<?php if($this->ion_auth_acl->has_permission('delivery_partner_approval')):?>
    									<td>
    										<input type="checkbox"   class="delivery_toggle_status"
    												user_id="<?php echo $user['id'];?>"
    												<?php echo ($user['delivery_partner_approval_status'] == 1) ? 'checked':'' ;?>
    												data-toggle="toggle" data-style="ios" data-on="Approve"
    												data-off="Disapprove" data-onstyle="success"
    												data-offstyle="danger">
    									</td>
									<?php endif;?>
									<td>
									<?php if($this->ion_auth_acl->has_permission('delivery_partner_details')):?>
										<a href="<?php echo base_url()?>admin/delivery_partner/eye?id=<?php echo $user['id']; ?>" id = "delivery_toggle" class="mr-2"><i class="fas fa-eye"></i>
									</a> 
									<?php endif;?>

									<!-- <a href="<?php echo base_url()?>admin/delivery_partner/edit?id=<?php echo $user['id']; ?>" id = "delivery_toggle" class="mr-2"><i class="fas fa-pencil-alt"></i>
									</a>  -->
<!-- 									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $user['id'] ?>, 'employee')"> <i
											class="far fa-trash-alt"></i> 
									</a>   <a href="#" class="mr-2   "> <i
											class="fas fa-align-justify"></i>
									</a> --></td>

								</tr>
                          <?php endforeach;?>
                          <?php else :?>
							<tr>
									<th colspan='5'><h3>
									<center>No Access!</center>
										</h3></th>
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
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
	    $('#delivery_toggle').click(function() {
    
     /*	if(confirm('Do You Want To Change Approve Status..?')){

    		let vendor_id = $(this).attr('vendor_id');
    		let user_id = $(this).attr('user_id');
    		let is_checked = $(this).is(':checked');
    		$.ajax({
    			url: base_url+'vendors/change_status',
    			type: 'post',
    			dataType: 'json',
    			data: {vendor_id : vendor_id, user_id : user_id, is_checked : is_checked},
    			success: function(data){
    				console.log(data);
    			}
    		});
    	}*/
    })
});
</script>