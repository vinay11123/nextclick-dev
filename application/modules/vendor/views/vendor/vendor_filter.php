<?php 
 
error_reporting(); ?>

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
<!--Add Category And its list-->
<!--Add Category And its list-->
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Vendors Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('vendors_filter/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Name</label>
    						<input type="text" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="q" id="q" placeholder="Name" value="<?php echo $q;?>" class="form-control">
    					</div>
    					<div class="form-group col-2">
    						<label for="exe">Executive Id</label>
    						<input type="text" id="exe" name="exe" placeholder="Unique Id" value="<?php echo $exe;?>" class="form-control">
    					</div>
    					<div class="form-group col-3">
    						<label for="mobile">Mobile</label>
    						<input type="text" id="mobile" name="mobile" placeholder="Mobile" pattern="^[0-9]*$"  value="<?php echo $mobile;?>" class="form-control">
    					</div>
    					<div class="form-group col-2">
                            <label for="status">Status</label>
                            <select calss="form-control" name="status" class="form-control">
								<option value="0" <?php echo (!$status)? "selected" : ""?>>All</option>
                            	<option value="1" <?php echo ($status == 1)? "selected" : ""?>>Approved</option>
                            	<option value="2" <?php echo ($status == 2)? "selected" : ""?>>Disapproved</option>
                            	<option value="3" <?php echo ($status == 3)? "selected" : ""?>>Deleted</option>
                            </select>
							
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
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('vendors_filter/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
    				<select calss="form-control" name="status" style="display: none" class="form-control">
                            	<option value="1" >All</option>
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
					<h4 class="col-10 ven1">List of Vendors</h4>
					<?php if($this->ion_auth_acl->has_permission('vendor_bulk_upload')):?>
						<a class="btn btn-outline-dark btn-lg col-2 pull-right" href="<?php echo base_url('vendor_excel_import')?>"> Add Vendor</a>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover"
						id="tableExportNoPagination" style="width: 100%;">
						<thead>
							<tr>
								<th>Sno</th>
								<th>Executive Id</th>
								<th>Identity</th>
								<th>Description</th>
								<th>Address</th>
								<th>Constituency</th>
								<th>Contact</th>
								<th>Category</th>
								<!-- <th>Sub Category</th> -->
								<th>Created On</th>
								<?php  if( $this->ion_auth_acl->has_permission('vendor_approve')):?>
									<th>Approve</th>
								<?php endif;?>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php  if($this->ion_auth_acl->has_permission('vendor_view')):?>
        							<?php if(!empty($vendors)):?>
            							<?php $sno = 1; foreach ($vendors as $vendor):?>
            				<tr>
								<td><?php echo $sno++;?></td>
								<td><?php echo $vendor['executive_user_id']; ?></td>
								<td class="tdcolorone"><?php echo $vendor['name']."<br/> ( <b>".$vendor['id']." </b>)";?></td>
								<td class="tdcolorone"><?php echo $vendor['business_description'];?></td>
								<td class="tdcolortwo"><?php echo $vendor['location_address'];?></td>
								<td class="tdcolorone"><?php foreach ($constituency as $con): if($vendor['constituency_id'] == $con['id']):?>
            									<?php echo $con['name'];?>
            									<?php endif;endforeach;?></td>
								<td class="tdcolortwo">
									<?php 
									// echo (array_search($vendor['id'], array_column($contacts, 'list_id')) !== FALSE)? $vendor['phone'] : ""; 
									echo $vendor['phone'];
									echo "<br />".$vendor['email'];?>
								</td>
								<td class="tdcolorone"><?php foreach ($categories as $category): if($vendor['category_id'] == $category['id']):?>
            									<?php echo $category['name'];?>
            									<?php endif;endforeach;?></td>
								<!-- Ram -->				
								<!-- <td class="tdcolortwo">
									<ul>
										<?php foreach ($vendor_subcategories as $subcategory):?>
											<li><?php echo $subcategory['subcategory']['name']?></li>
										<?php  endforeach;?>
									</ul>
								</td> -->
								<!-- Ram -->
								<td><?php echo date('M d, Y (H:i)', strtotime($vendor['created_at']));?></td>
								<?php  if( $this->ion_auth_acl->has_permission('vendor_approve')):?>
    								<td id="selected"> <input type="checkbox" class="approve_toggle"
    									vendor_id="<?php echo $vendor['id'];?>"
    									user_id="<?php echo $this->session->userdata('user_id');?>"
    									<?php echo ($vendor['status'] == 1) ? 'checked':'' ;?>
    									data-toggle="toggle" data-style="ios" data-on="Approved"
    									data-off="Dispprove" data-onstyle="success"
    									data-offstyle="danger"></td>
								<?php endif;?>
								<td class="dflex">
									<?php if($this->ion_auth_acl->has_permission('vendor_delete')):?>
										<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $vendor['id'];?>, 'vendors')"><i class="far fa-trash-alt"></i></a>
									<?php endif;?>
									<?php if($this->ion_auth_acl->has_permission('vendor_edit')):?> 
										<a href="<?php echo base_url();?>vendor_profile/edit?id=<?php echo $vendor['id']; ?>&page=<?php echo $this->uri->segment(3); ?>" class="mr-2  "> <i class="fas fa-pencil-alt"></i></a>
									<?php endif;?>
									<?php if($this->ion_auth_acl->has_permission('vendor_account')):?> 
										<a href="<?=base_url('vendor_payments/r?vendor_id=').$vendor['id']."&id=".$vendor['vendor_user_id'];?>" target="_blank" class=" mr-2  " type="category"> <i class="fa fa-book"></i></a>
									<?php endif;?>
								</td>

							</tr>
            							<?php endforeach;?>
        							<?php else :?>
        							<tr>
								<th colspan='9'><h3>
										<center>No Vendor</center>
									</h3></th>
							</tr>
        							<?php endif;?>
        							<?php else :?>
        							<tr>
								<th colspan='9'><h3>
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
