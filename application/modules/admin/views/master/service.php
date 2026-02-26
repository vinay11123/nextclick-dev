
<!--Service list-->
<style>
.elementToFadeInAndOut {
    display:block;
    -webkit-animation: fadeinout 10s linear forwards;
    animation: fadeinout 10s linear forwards;
}
@-webkit-keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
@keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
td:nth-child(3){
	position: relative;
	width:12%;
   min-height:12px;
}
</style>
<div class="row">
	<div class="col-12">
	<div class="card-body">
			<div class="card">
			<?php if (!empty($this->session->flashdata('upload_status'))) {
                ?>
                    <div class="alert alert-success elementToFadeInAndOut">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('upload_status'); ?>
                    </div>
                <?php
                } ?>
				<?php if (!empty($this->session->flashdata('delete_status'))) {
                ?>
                    <div class="alert alert-danger elementToFadeInAndOut">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('delete_status'); ?>
                    </div>
                <?php
                } ?>
				<div class="card-header">
					<h4 class="col-10 ven1">List of Services</h4>
					<?php if($this->ion_auth_acl->has_permission('service_add')):?>
					<a href="<?php echo base_url()?>service/c" class="btn btn-primary widfldtd">Add Services</a>
					<?php endif;?>
				
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Service Name</th>
									 <th>Description</th> 
									 <th>Languages</th>
									<th>Permissions</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if($this->ion_auth_acl->has_permission('service_view')):?>
								<?php if(!empty($services)):?>
    							<?php $sno = 1; foreach ($services as $service):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
										<td class="tdcolorone"><?php echo $service['name'].'['.$service['id'].']';?></td>
										<td class="tdcolortwo"><?php echo $service['desc'];?></td>  
    									<td><?php echo $service['languages'];?></td>  
    									<td class="scrollitem">
										<ul  class="scrollitemlist">
										<?php if(isset($service['permissions'])){ foreach ($service['permissions'] as $permission):?>
											<li><?php echo $permission['perm_name'];?></li>
										<?php endforeach;}?>
										</ul></td>
    									 <td><img
    										src="<?php echo base_url();?>uploads/service_image/service_<?php echo $service['id'];?>.jpg"
    										class="img-thumb"></td> 
    									<td >
    									<?php if($this->ion_auth_acl->has_permission('service_edit')):?>
        									<a href="<?php echo base_url()?>service/edit?id=<?php echo $service['id']?>&page=<?php echo $this->uri->segment(3); ?>" class=" mr-2  " > <i class="fas fa-pencil-alt"></i></a>
        								<?php endif;?>
        								<?php if($this->ion_auth_acl->has_permission('service_delete')):?> 
        									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $service['id'] ?>, 'service')"> <i class="far fa-trash-alt"></i></a>
        								<?php endif;?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Services</center></h3></th></tr>
							<?php endif;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Access!</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>


