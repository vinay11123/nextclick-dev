<div class="row">
	<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Executives</h4>
					<?php if($this->ion_auth->is_admin()):?>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('employee/c/0')?>" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add Vehicle</a>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Emp Id</th>
									<th>Name</th>
									<th>email</th>
									<th>Created On</th>
									<th>No of Vendors</th>
									<?php if($this->ion_auth_acl->has_permission('executive_approval')):?>
									<th>Approve</th>
									<?php endif;?>
									<?php if($this->ion_auth_acl->has_permission('executive_details')):?>
									<th>Actions</th>
									<?php endif;?>

								</tr>
							</thead>
							<tbody>
							<?php if($this->ion_auth_acl->has_permission('executive_view')):?>
							<?php if(!empty($executives)):?>
    							<?php  $sno = 1; foreach ($executives as $executive): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $executive['id'];?></td>
									<td><?php echo $executive['first_name'].' '.$executive['last_name'];?></td>
									<td><?php echo $executive['email'];?></td>
									<td><?php echo $executive['created_at'];?></td>
									<td><?php if(! empty($executive['vendors'])){echo count($executive['vendors']);}else{echo "0";}?></td>
									
									<?php if($this->ion_auth_acl->has_permission('executive_approval')):?>
    									<td> <input type="checkbox" class="approve_executive"
    									id="<?php echo $executive['id'];?>"
    									<?php echo ($executive['status'] == 1) ? 'checked':'' ;?>
    									data-toggle="toggle" data-style="ios" data-on="Approved"
    									data-off="Dispprove" data-onstyle="success"
    									data-offstyle="danger">
    									</td>
									<?php endif;?>
									<td>
									<?php if($this->ion_auth_acl->has_permission('executive_details')):?>
										<a href="<?php echo base_url()?>emp_list/executive?exe_id=<?php echo $executive['id']?>" class="mr-2" type="executive"> <i class="fas fa-user"></i></a>
										
									<?php endif;?>	
									<?php if($this->ion_auth_acl->has_permission('executive_details')):?>
										<a href="<?php echo base_url()?>emp_list/executive?eye_id=<?php echo $executive['id']?>"  class="mr-2" type="executive"> <i class="fas fa-eye"></i></a>
									<?php endif;?>	
									
									</td>
								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='6'><h3>
											<center>No Executives</center>
										</h3></th>
								</tr>
							<?php endif;?>
							<?php else :?>
							<tr>
									<th colspan='10'><h3>
											<center>No Access!</center>
										</h3></th>
								</tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>
