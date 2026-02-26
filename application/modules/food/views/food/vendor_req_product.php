 
 <style>
.page-item>a {
	position: relative;
	display: block;
	padding: .5rem .75rem;
	margin-left: -1px;
	line-height: 1.25;
	color: #007bff;
	background-color: #fff;
	border: 1px solid #dee2e6;
}

a {
	color: #007bff;
	text-decoration: none;
	background-color: transparent;
}

.pagination>li.active>a {
	background-color: orange !important;
}

.dataTables_filter {
	display: none;
}
.or{
    text-align: center;
}
</style>
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
td:nth-child(8){
	position: relative;
	width:12% !important;
   min-height:12px;
}
</style>

 <?php if(! empty($this->session->flashdata('upload_status')['success'])){?>
            <div class="alert alert-success"><h5><?php echo $this->session->flashdata('upload_status')['success'];?></h5></div>
       

<?php } ?>
<!--div -->
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Vendor Requested Product List</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('vendor_req_product/0/r');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Search</label>
    						<input type="text" name="q" id="q" placeholder="Name" value="<?php echo $q;?>" class="form-control">
    					</div>
                        <div class="form-group col-3">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>

    					<!-- <div class="form-group col-2">
    						<label for="noofrows">Availability</label>
    						  <select class="form-control"   id="statusdata" name="statusdata">
                          
                            <option value="1" >Available</option>
                             <option value="0" >Unavailable</option>
                        </select>
    					</div>-->

   						<div class="form-group col-3">
    						<label for="noofrows">Shop By Category</label>
    	  					<select class="form-control" onChange="shop_by_category_changed1(this.value);" id="sub_cat_id" name="sub_cat_id" required="" >
                            <option value="" selected disabled>--select--</option>
                            <?php
                            if ($this->ion_auth->is_admin()){
                            for($l=0;$l<count($sub_categories);$l++){
                            ?>
                            <optgroup label="<?=$sub_categories[$l]['name'];?>">
								<?php
								$sl=$sub_categories[$l]['sub_categories'];
								
								if($sl != ''){
									for($r=0;$r<count($sl);$r++){
								?>
								<option value="<?php echo $sl[$r]['id']; ?>" <?php if($sl[$r]['id']== $sub_items['sub_cat_id']){ echo "selected";} ?>><?=$sl[$r]['name'];?></option>
							<?php }}?>
							</optgroup>
									<?php
								}
									}else{
									?>
								<?php 
								foreach ($sub_categories as $item):?>
							<option value="<?php echo $item['id'];?>"  ><?php echo $item['name']?></option>
								<?php endforeach;?>
														<?php }?>
													</select>					 
							</div>

						<div class="form-group col-md-3">
							 <label>Menu</label>
						 <!-- <label>
							 <?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>
							</label>  -->
                        		<select class="form-control " id="menu_id" name="menu_id" onChange="menu_changed(this.value);" required="" >
									<option value="" selected disabled>--select--</option>
										
								</select>
							<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
							<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
						</div>
                    
 
					</div>
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('vendor_req_product/0/r');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
                    <input type="hidden" id="noofrows" name="noofrows" placeholder="rows" value="10" class="form-control">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
		</div>
	</div>


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
				
        <!--        <form enctype="multipart/form-data" method = "post" action="<?php //echo base_url('food_product/0/l')?>">
  <button type = "submit" name = "submit" class="btn btn-info pull-right"   style = "margin-top: 16px;margin-right: 40px;"><i class="glyphicon glyphicon-plus"></i>Import</button>  

<input type="file" name="uploadFile" id="uploadFile" size="300"  class = "pull-right" style = "margin-top: 23px;margin-left: 18px;">
   
  

</form> -->

<!-- <div class="card-header">
                
             </div> -->

 

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover"  
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Vendor Unique ID</th>
                                    <th>Name</th>
									<th>Shop By Category</th>                                   
                                    <th>Brand</th>
									<th>Menu</th>
                                    <th>Image</th>
									<?php if($user->primary_intent!='vendor')
									{ ?>
                                    <th>status</th>
									<?php } ?>
									<th>Action</th>
								</tr>
							</thead>
                                <tbody>
                                <?php  
								if($this->ion_auth_acl->has_permission('inventory_view')):?>
								<?php if(!empty($products)):?> 
    							<?php $sno = 1; foreach ($products as $vp):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									
    									<td><a href="<?php 
										$vname = $this->vendor_list_model->where('vendor_user_id', $vp['created_user_id'])->get();
										
										echo base_url()?>vendor/vendor_profile/edit?id=<?php echo $vname['id'];?>" class=" mr-2">  <?php 
										
										echo $vname['business_name'].'['.$vname['id'].']';?>
                                        </a> </td>


                                        <td>
                                            <?php echo $vp['name'];?><br>
                                            <?php    if($vp['food_status'] == 1){ ?>                       
                                                        <span class="badge badge-info">Catalogue</span>        
                                            <?php } ?>
                                            <?php    if($vp['food_status'] == 2){ ?>                       
                                                        <span class="badge badge-success">Approved</span>        
                                            <?php } ?> 
                                            <?php    if($vp['food_status'] == 3){ ?>                       
                                                        <span class="badge badge-danger">Pending</span>        
                                            <?php } ?>                   
                                        </td>
    									<td><?php echo (! empty($vp['sub_category']))? $vp['sub_category']['name'] : 'NA';?></td>
    									<td><?php 
										$bname = $this->brand_model->where('id', $vp['brand_id'])->get();
										echo $bname['name'] ;?></td>
                                        <td><?php echo (! empty($vp['menu']))? $vp['menu']['name'] : 'NA';?></td>
                      <td>
									<?php $images = $this->food_item_image_model->where('item_id', $vp['id'])->get();
									?>
										<img src=" <?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $images['id']; ?>.jpg" class="img-thumb">
								</td>
										<?php if($user->primary_intent!='vendor')
											{ ?>
										<td>
											 <input type="checkbox" class="approve_product1"
    									item_id="<?php echo $vp['id'];?>"
    									vendor_id="<?php echo $vp['created_user_id'];?>"
    									<?php echo ($vp['status'] == 2) ? 'checked':'' ;?>
    									data-toggle="toggle" data-style="ios" data-on="Approved"
    									data-off="Dispprove" data-onstyle="success"
    									data-offstyle="danger">
										</td>
										<?php } ?>
    									<td>
        									<?php  if($this->ion_auth_acl->has_permission('inventory_details')):?>
        										<a href="<?php echo base_url()?>food/food/inventory/view_details/0?id=<?php echo base64_encode(base64_encode($vp['id']));?>&vendor_user_id=<?php echo base64_encode(base64_encode($vp['created_user_id']))?>" class=" mr-2"> <i class="fa fa-eye" aria-hidden="true"></i></a>
        									<?php endif;?>
    									</td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
							<?php endif;?>
							<?php else :?>
							<tr ><th colspan='11'><h3><center>No Access!</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>

                    <div class="row  justify-content-center">
                        <div class=" col-12" style='margin-top: 10px;'>
                           <?= $pagination; ?>
                        </div>
                    </div>
				</div>
			</div>
        </div>

	</div>
</div>

