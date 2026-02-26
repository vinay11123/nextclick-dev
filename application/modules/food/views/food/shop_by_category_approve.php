<?php $vendor_category_id = 4;?>
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Shop by categories to Approve</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Vendor Name</th>
									<th>Unique id</th>
									<th>Category</th>
									<th>Shop by Category</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($shop_by_categories)):?> 
    							<?php $sno = 1; foreach ($shop_by_categories as $shop_by_category):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $shop_by_category['vendor_name']; ?></td>
    									<td><?php echo $shop_by_category['unique_id'];?></td>
    									<td><?php echo $shop_by_category['category'];?></td>
    									<td><?php echo $shop_by_category['sub_category'];?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/sub_category_image/sub_category_<?php echo $shop_by_category['id'];?>.jpg?<?php echo time();?>" class="img-thumb"></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>shop_by_category_approve/approve?id=<?php echo base64_encode(base64_encode($shop_by_category['id']));?>" class="btn btn-success"  >Approve
    										</a> 
    									<?php }
    									?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">Approved Shop by categoriess</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport1"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Vendor Name</th>
									<th>Unique id</th>
									<th>Category</th>
									<th>Shop by Category</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($approved_shop_by_categories)):?> 
    							<?php $sno = 1; foreach ($approved_shop_by_categories as $shop_by_category):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $shop_by_category['vendor_name']; ?></td>
    									<td><?php echo $shop_by_category['unique_id'];?></td>
    									<td><?php echo $shop_by_category['category'];?></td>
    									<td><?php echo $shop_by_category['sub_category'];?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/sub_category_image/sub_category_<?php echo $shop_by_category['id'];?>.jpg?<?php echo time();?>" class="img-thumb"></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>shop_by_category_approve/disapprove?id=<?php echo base64_encode(base64_encode($shop_by_category['id']));?>" class="btn btn-danger"  >Disapprove
    										</a> 
    									<?php }
    									?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>

