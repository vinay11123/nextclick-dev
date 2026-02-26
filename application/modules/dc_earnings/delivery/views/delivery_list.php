<style>
.list {
  display: table;
  border-spacing: 0 10px;
  padding: 0.5em 0;
}

.list > li {
  background-color: #e0e0e1;
  border-radius: 5px;
  color: #6c777f;
  display: table-row;
  width: 100%;
}
.list > li > label {
  border-bottom-left-radius: 5px;
  border-top-left-radius: 5px;
  background-color: #a1aab0;
  color: white;
  display: table-cell;
  min-width: 40%;
  padding: .5em;
  text-transform: capitalize;
}

.list > li > span {
  border-radius: 0 5px 5px 0;
  background-color: #e0e0e1;
  display: table-cell;
  padding: .5em;
}
td:nth-child(3){
	position: relative;
	width:12%;
   min-height:12px;
}

</style>

<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Delivery Vehicle</h4>
					<?php if($this->ion_auth_acl->has_permission('vehicle_add')):?>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('vehicle/c/0')?>" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add Vehicle</a>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Name</th>
									<th>Description</th>
									<th>Min_Capacity</th>
									<th>Max_Capacity</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
							 	<?php if($this->ion_auth_acl->has_permission('vehicle_view')):?>
								<?php if(!empty($vehicledata)):?>
    							<?php $sno = 1; foreach($vehicledata as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $transaction['name'];?></td>
    									<td><?php echo $transaction['desc'];?></td>
    									<td class="tdcolorone"><?php echo ($transaction['min_capacity']/1000).' '.'kgs';?></td>
    									<td class="tdcolortwo"><?php echo ($transaction['max_capacity_end']/1000).' '.'kgs';?></td>
										<td>
										<?php if($this->ion_auth_acl->has_permission('vehicle_edit')):?>
    										<a href="<?php echo base_url();?>vehicle/u/<?php  echo $transaction['id']; ?>" id = "delivery_toggle" class="mr-2"> <i class="fas fa-pencil-alt"></i>
        									</a> 
        								<?php endif;?>
        								<?php if($this->ion_auth_acl->has_permission('vehicle_delete')):?>
         									<a href="#" class="mr-2  text-danger " onClick="delete_recordvehicle(<?php echo $transaction['id'] ?>, 'vehicle')"> <i
        											class="far fa-trash-alt"></i> 
        									</a>  
        								<?php endif;?>
										</td>	  
    									 
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Delivery vehicle</center></h3></th></tr>
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
	