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
td:nth-child(5){
	position: relative;
	width:12%;
   min-height:12px;
}

</style>
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Vendors Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('products/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Search</label>
    						<input type="text" name="q" id="q" placeholder="Name" value="<?php echo $q;?>" class="form-control">
    					</div>
                        <div class="form-group col-2">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>
					</div>
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp; Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('products/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
                    <input type="hidden" id="noofrows" name="noofrows" placeholder="rows" value="10" class="form-control">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
		</div>
	</div>
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven subcategory">List of products</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_item/r')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Product</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Shop by category</th>
									<th>Menu</th>
									<th>Description</th>
									<th>Price</th>
									<th>Discount(%)</th>
									<th>Qty</th>
									<th>Image</th>
									<th>Status</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($products)):?> 
    							<?php $sno = 1; foreach ($products as $product):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $product['name'];?></td>
    									<td><?php echo $product['sub_category']['name'];?></td>
    									<td><?php echo $product['menu']['name'];?></td>
    									<td><?php echo $product['desc'];?></td>
    									<td><?php echo $product['price'];?></td>
    									<td><?php echo $product['discount'];?></td>
    									<td><?php echo $product['quantity'];?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/food_item_image/food_item_<?php echo $product['id'];?>.jpg?<?php echo time();?>" class="img-thumb"></td>
    									<td><?php echo ($product['status']==1)? 'Available' : 'Not Available' ;?></td>
    									<td><a href="<?php echo base_url()?>food_item/edit?id=<?php echo base64_encode(base64_encode($product['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a>
    									<?php
    									if($this->ion_auth->get_user_id() == $product['menu']['vendor_id']){
    									?> 
    									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $product['id'] ?>, 'food_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a>
    								<?php }else{?>
    									<a href="#" class="mr-2  text-danger " onClick="admin_item_delete_record(<?php echo $product['id'] ?>, 'food_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a>
    								<?php }?>
    								<?php
    									if($product['approval_status'] == 2){
    										?>
    										<button class="btn-danger">Not-Approved</button>
    									<?php }elseif($product['approval_status'] == 1){
    										?>
    										<button class="btn-success">Approved</button>
    										<?php
    									}?>
    								</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
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

