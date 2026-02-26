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
</style>


<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of Delivery Area Rates </h4>
					<?php if($this->ion_auth_acl->has_permission('delivery_area_add')):?>
						<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('delivery_area/c/0')?>" style="float: right;"> Add Delivery Area Rates</a>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="table-responsive">

            
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">

							<thead>
								<tr>
									<th>S.no</th>
									<th>State Name</th>
									<th>District Name</th>
									<th>Constituencies</th>
									<th>Vechile Type</th>
									<th>Flat Distance in Km</th>
									<th>DB Flat Rate</th>
									<th>NC Flat Rate</th>
                                    <th>DB Per km Rate</th>
									<th>NC Per km Rate</th>
                                    <th>Vendor to User Max Distance in km</th>
									<th>Vendor to Delivery Boy Max Distance in km</th>
                                    <th>Action </th>

								</tr>
							</thead>
							<tbody>
							 
							 	<?php if($this->ion_auth_acl->has_permission('delivery_area_view')):?>
								<?php if(!empty($arearate)):?>
    							<?php $sno = 1; foreach($arearate as $transaction):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td >
                                        <?php

                    $st = $this->state_model->where('id', $transaction['state_id'])->get();

if($transaction['district_id'] == null)
{
  $did = 0;
}else
{
  $did = $transaction['district_id'];
}

                    $dt = $this->district_model->where('id', $did)->get();

if($transaction['constituency_id'] == null)
{
  $tid = 0;
}else
{
  $tid = $transaction['constituency_id'];
}
                    $cs = $this->constituency_model->where('id', $tid)->get();
 
       

                 $vt = $this->vehicle_model->where('id', $transaction['vehicle_type_id'])->get();
             
                        echo $st['name'];?></td>
    									<td><?php echo empty($dt['name']) ? "All" : $dt['name'];?></td>
    									<td><?php echo empty($cs['name']) ? "All" :  $cs['name'];?></td>
    									<td class="tdcolorone"><?php echo $vt['name'];?></td>
                                        <td class="tdcolortwo"><?php echo $transaction['flat_distance'];?> km</td>
                                        <td class="tdcolortwo"><?php echo $transaction['flat_rate'];?></td>
										<td class="tdcolortwo"><?php echo $transaction['nc_flat_rate'];?></td>
                                        <td class="tdcolorone"><?php echo $transaction['per_km'];?></td>
										<td class="tdcolorone"><?php echo $transaction['nc_per_km'];?></td>
                                        <td class="tdcolorone"><?php echo $transaction['vendor_to_user_max_distance'];?></td>
										<td class="tdcolorone"><?php echo $transaction['vendor_to_delivery_captain_max_distance'];?></td>
										<td>
										<?php if($this->ion_auth_acl->has_permission('delivery_area_edit')):?>
											<a href="<?php echo base_url();?>delivery_area/u/<?php  echo $transaction['id']; ?>" id = "delivery_toggle" class="mr-2"> <i class="fas fa-pencil-alt"></i></a>
										<?php endif;?> 
    									<?php if($this->ion_auth_acl->has_permission('delivery_area_delete')):?>
     										<a href="<?php echo base_url();?>delivery_area/d/<?php  echo $transaction['id']; ?>" class="mr-2  text-danger " > <i class="far fa-trash-alt"></i></a>  </td>	  
    									<?php endif;?>
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
	