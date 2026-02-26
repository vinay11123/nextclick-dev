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
					<h4 class="col-9 ven1">List of Banner Rates </h4>
						<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('add_banner_cost/c/0')?>" style="float: right;"> Add Banners Rates</a>
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
									<th>Banner Type</th>
									<th>Cost per day</th>
	                                <th>Action </th>

								</tr>
							</thead>
							<tbody>
							 
								<?php if(!empty($bannerrates)):?>
    							<?php $sno = 1; foreach($bannerrates as $bannerrate):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td >
                                        <?php

                    $st = $this->state_model->where('id', $bannerrate['state_id'])->get();

if($bannerrate['district_id'] == null)
{
  $did = 0;
}else
{
  $did = $bannerrate['district_id'];
}

                    $dt = $this->district_model->where('id', $did)->get();

if($bannerrate['constituency_id'] == null)
{
  $tid = 0;
}else
{
  $tid = $bannerrate['constituency_id'];
}
                    $cs = $this->constituency_model->where('id', $tid)->get();
                         echo $st['name'];?></td>
    									<td><?php echo empty($dt['name']) ? "All" : $dt['name'];?></td>
    									<td><?php echo empty($cs['name']) ? "All" :  $cs['name'];?></td>
    									<td class="tdcolorone"><?php 
												if($bannerrate['banner_type'] ==1){
													$b_type='Big Offer';
												}
												else{
													$b_type='Vendor offer';
												}
												echo $b_type;?></td>
    									<td class="tdcolorone"><?php echo $bannerrate['rate'];?></td>
										<td>
											<a href="<?php echo base_url();?>banner_cost/u/<?php  echo $bannerrate['id']; ?>" id = "delivery_toggle" class=""> <i class="fas fa-pencil-alt"></i></a>
     										<a href="<?php echo base_url();?>banner_cost/d/<?php  echo $bannerrate['id']; ?>" onClick="delete_record(<?php echo $bannerrate['id'] ?>, 'banner_cost')" class=" text-danger " > <i class="far fa-trash-alt"></i></a>  </td>	  
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='7'><h3><center>No Banner Rates Added</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	
 

	</div>
	