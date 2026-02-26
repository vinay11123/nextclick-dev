<!--Amenity list-->
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
</style>
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
					<h4 class="col-10 ven1">List of Amenities</h4>
					<?php if($this->ion_auth_acl->has_permission('amenity_add')):?>
					<a href="<?php echo base_url()?>amenity/c" class="btn btn-primary widfldtd" style="flaot:right">Add Amenities</a>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Amenity Name</th>
									<th>Category</th>
									<th>Description</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if($this->ion_auth_acl->has_permission('amenity_view')):?>
								<?php if(!empty($amenities)):?>
    							<?php $sno = 1; foreach ($amenities as $amenity):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $amenity['name'];?></td>
    									<td class="tdcolorone"><?php foreach ($categories as $category):?>
    									<?php echo ($category['id'] == $amenity['cat_id'])? $category['name']:'';?>
    									<?php endforeach;?></td>
    									<td class="tdcolortwo"><?php echo $amenity['desc'];?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/amenity_image/amenity_<?php echo $amenity['id'];?>.jpg"
    										class="img-thumb"></td>
    									<td>
    									<?php if($this->ion_auth_acl->has_permission('amenity_edit')):?>
        									<a href="<?php echo base_url()?>amenity/edit?id=<?php echo $amenity['id'];?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i></a>
        								<?php endif;?>
        								<?php if($this->ion_auth_acl->has_permission('amenity_delete')):?>
        									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $amenity['id'] ?>, 'amenity')"> <i class="far fa-trash-alt"></i></a>
        									<?php endif;?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='5'><h3><center>No Amenities</center></h3></th></tr>
							<?php endif;?>
							<?php else :?>
							<tr ><th colspan='5'><h3><center>No Access!</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>




