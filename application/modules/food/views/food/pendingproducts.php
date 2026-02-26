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

<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Pending Product List</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('food/food/pendingproducts/r/0');?>" method="post" >
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Search</label>
    						<input type="text" name="q" id="q"  value="<?php echo $q;?>" class="form-control">
    					</div>
                        <div class="form-group col-3">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>

                        <div class="form-group col-3">
    						<label for="noofrows">Shop By Category</label>
    	                    <select class="form-control" onChange="shop_by_category_changed1(this.value);"  id="sub_cat_id" name="sub_cat_id" required=""  id="cars">
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
                        </label> -->
                        <select class="form-control " id="menu_id" name="menu_id" required="" >
                            <option value="" selected disabled>--select--</option>
								
                                <?php foreach ($food_items as $item):?>
                    <option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
                                <?php endforeach;?>
                        </select>
                    </div>
                    

 
					</div>
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('food/pendingproducts/r/0');?>" method="post">

    			<input type="hidden" name="q" id="q"  value="" class="form-control">
                <input type="hidden"  name="noofrows"  value="" class="form-control">
                <input type="hidden"  name="noofrows"  value="" class="form-control">
                <input type="hidden"  name="group"  value="" class="form-control">
                 <input type="hidden"  name="menu_id"  value="" class="form-control">
    				<button type="submit" name="submit"  class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
		</div>
	</div>

 
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">Pending Product List</h4>
                 <!--   <a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add food product</a>
				&nbsp;&nbsp;	<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/l')?>"><i class="fa fa-plus" aria-hidden="true"></i> excel</a>
                   -->

				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover"  
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
                                    <th>Name</th>
									<th>Shop By Category</th>                                   
                                    <th>Brand</th>
									<th>Menu</th>
                                    <th>Image</th>
								</tr>
							</thead>
                                <tbody>
                                <?php 
 if($this->ion_auth_acl->has_permission('inventory_view')):?>
								<?php if(!empty($results)):?> 
    							<?php $sno = 1; foreach ($results as $vp):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
                                        <td>
                                            <?php echo $vp['name'];?><br>
                                                              
                                        </td>
    									<td><?php echo (! empty($vp['sub_category']))? $vp['sub_category']['name'] : 'NA';?></td>
    									<td><?php echo (! empty($vp['brand']))? $vp['brand']['name'] : 'NA';?></td>
                                        <td><?php echo (! empty($vp['menu']))? $vp['menu']['name'] : 'NA';?></td>
                                        <td><img class="img-thumb" src=" <?php echo $vp['image']; ?>" width = "30"></td>
    
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

 

