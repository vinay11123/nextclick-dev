
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of On Demand categories to Approve</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Description</th>
									<th>Duration</th>
									<th>Price</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($pending_list)):?> 
    							<?php $sno = 1; foreach ($pending_list as $pend_list):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $pend_list['name']; ?></td>
    									<td><?php echo $pend_list['desc'];?></td>
    									<td><?php echo $pend_list['service_duration'];?></td>
    									<td><?php echo $pend_list['price'];?></td>
    									<!-- <td><img
    										src="<?php //echo base_url();?>uploads/od_service_image/od_service_<?php //echo $pend_list['id'];?>.jpg?<?php //echo time();?>"></td> -->
    										<td><img
    										src="<?php echo base_url();?>uploads/od_service_image/od_service_<?php echo $pend_list['id'];?>.jpg?<?php echo time();?>"
    										class="img-thumb"></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>od_categories_approve/approve?id=<?php echo $pend_list['od_service_id'];?>" class="btn btn-success">Approve
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

<!-- <div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">Approved On Demand categoriess</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport1"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Description</th>
									<th>Duration</th>
									<th>Price</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($approved_list)):?> 
    							<?php $sno = 1; foreach ($approved_list as $approve_list):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $approve_list['name']; ?></td>
    									<td><?php echo $approve_list['desc'];?></td>
    									<td><?php echo $approve_list['service_duration'];?></td>
    									<td><?php echo $approve_list['price'];?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/od_service_image/od_service_<?php echo $approve_list['id'];?>.jpg?<?php echo time();?>" class="img-thumb" ></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>od_categories_approve/disapprove?id=<?php echo $approve_list['od_service_id'];?>" class="btn btn-danger">Disapprove
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
</div> -->
<div class="card-body">
			<div class="card">
				<div class="card-header">	
					<h4 class="ven">Approved On Demand categoriess</h4>
					
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Description</th>
									<th>Duration</th>
									<th>Price</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($approved_list)):?> 
    							<?php $sno = 1; foreach ($approved_list as $approve_list):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $approve_list['name']; ?></td>
    									<td><?php echo $approve_list['desc'];?></td>
    									<td><?php echo $approve_list['service_duration'];?></td>
    									<td><?php echo $approve_list['price'];?></td>
    									<td style="width:100px"><img
    										src="<?php echo base_url();?>uploads/od_service_image/od_service_<?php echo $approve_list['id'];?>.jpg?<?php echo time();?>" class="img-thumb" ></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>od_categories_approve/disapprove?id=<?php echo $approve_list['od_service_id'];?>" class="btn btn-danger">Disapprove
    										</a> 
    									<?php }
    									?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='10'><h3>
											<center>Sorry!! No Data Found!!!</center>
										</h3></th>
								</tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>
