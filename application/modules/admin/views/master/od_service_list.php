<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of On Demand Services</h4>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('od_services/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add On Demand Service</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th> Name</th>
									<th>Description</th>
									<th>Service Duration</th>
									<th>Price</th>
									<th>Discount</th>
									<th>Image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($od_services)):?>
    							<?php $sno = 1; foreach ($od_services as $od_service):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $od_service['name'];?></td>
    									<td><?php echo $od_service['desc'];?></td>
    									<td><?php echo $od_service['service_duration'];?></td>
    									<td><?php echo $od_service['price'];?></td>
    									<td><?php echo $od_service['discount'];?></td>	
    									<td><img
    										src="<?php echo base_url();?>uploads/od_service_image/od_service_<?php echo $od_service['id'];?>.jpg?<?php echo time();?>"
    										class="img-thumb"></td>
    									<td><a href="<?php echo base_url()?>od_services/edit?id=<?php echo $od_service['id'];?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $od_service['id'] ?>, 'od_services')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='5'><h3><center>No Amenities</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>