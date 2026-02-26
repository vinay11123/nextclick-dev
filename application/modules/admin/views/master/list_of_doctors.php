
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Pending Doctors</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" >
							<thead>
								<tr>
									<th>Sno</th>
									<th>Name</th>
									<th>Qualification</th>
									<th>Experience</th>
									<th>Image</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($pending_list)):?>
								<?php $sno=1 ; foreach ($pending_list as $p_list): ?>
								<tr>
									<td>
										<?php echo $sno++;?>
									</td>
									<td>
										<?php echo $p_list[ 'name'];?>
									</td>
									<td>
										<?php echo $p_list[ 'qualification'];?>
									</td>
									<td>
										<?php echo $p_list[ 'experience'];?>
									</td>
									<td>
										<img src="<?php echo base_url();?>uploads/doctors_image/doctors_<?php echo $p_list['id'];?>.jpg?<?php echo time();?>" class="img-thumb">
									</td>
										<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    												<a href="<?php echo base_url()?>doctors_approve/approve?id=<?php echo $p_list['hosp_doctor_id'];?>" class="btn btn-success"  >Approve
    										</a> 
    									<?php }
    									?>
    									</td>
								</tr>
								<?php endforeach;?>
								<?php else :?>
								<tr>
									<th colspan='5'>
										<h3>
											<center>Sorry!! No Pending Doctors!!!</center>
										</h3>
									</th>
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
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Approved Doctors</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Name</th>
									<th>Qualification</th>
									<th>Experience</th>
									<th>Image</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($approved_list)):?>
								<?php $sno=1 ; foreach ($approved_list as $p_list): ?>
								<tr>
									<td>
										<?php echo $sno++;?>
									</td>
									<td>
										<?php echo $p_list[ 'name'];?>
									</td>
									<td>
										<?php echo $p_list[ 'qualification'];?>
									</td>
									<td>
										<?php echo $p_list[ 'experience'];?>
									</td>
									<td >
										<img src="<?php echo base_url();?>uploads/doctors_image/doctors_<?php echo $p_list['id'];?>.jpg?<?php echo time();?>" class="img-thumb" style = "width:104px;">
									</td>
									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>doctors_approve/disapprove?id=<?php echo $p_list['hosp_doctor_id'];?>" class="btn btn-danger">Disapprove
    										</a> 
    									<?php }
    									?>
    									</td>
								</tr>
								<?php endforeach;?>
								<?php else :?>
								<tr>
									<th colspan='5'>
										<h3>
											<center>Sorry!! No Approved Doctors!!!</center>
										</h3>
									</th>
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