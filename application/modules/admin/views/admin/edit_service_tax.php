
 <div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('service_tax/r');?>">Service Charge
<i class="fa fa-angle-double-left"></i> 
Service Charge</a> 
   
    </div>
    </div>


<div class="row">
	<div class="col-12">
		<h4 class="ven">Edit Service Charge</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('service_tax/u');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
				<input type="hidden" name="id" value="<?php echo $service_tax['id'] ; ?>">
                <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                        <select class="form-control" id = "cat_id" name="cat_id" onChange="category_changed1(this.value);" required=""  >
                            <option value="0">--select--</option>
                            <option value="all" selected>All</option>
                            <?php foreach ($categories as $category):?>
                            <option value="<?php echo $category['id'];?>" <?php echo ($category['id'] == $service_tax['cat_id'])? 'selected': '';?>><?php echo $category['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Name?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Sub Category</label>
                    <select id="district" class="form-control" onChange="shop_by_category_changed1(this.value);" name="sub_cat_id"  id = "sub_cat_id" required="">
                        <option value="0" selected disabled>--select--</option>
                        <option value="all" selected>All</option>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <?php if ($subcategory['id'] == $service_tax['sub_cat_id']):?>
                                <option value="<?php echo $subcategory['id'];?>" <?php echo ($subcategory['id'] == $service_tax['sub_cat_id'])? 'selected': '';?>><?php echo $subcategory['name']?></option>
                            <?php echo $district['name']?>
                                </option>
                            <?php endif;?>
                                <?php endforeach;?>
                    </select>
                    <div class="invalid-feedback">Belongs to the Sub Category?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Menu</label>
                        <select class="form-control" id = "menu_id" name="menu_id" onChange="menu_changed(this.value);" required=""  >
                            <option value="0" selected>--select--</option>
                            <option value="all" selected>All</option>
                            <?php foreach ($menus as $menu):?>
                            <option value="<?php echo $menu['id'];?>" <?php echo ($menu['id'] == $service_tax['menu_id'])? 'selected': '';?>><?php echo $menu['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Menu?</div>
                </div>
                     <div class="form-group col-md-4">
                        <label>State</label>
                        <select class="form-control" id='state_id' onchange="state_changed()" name="state_id" required="">
                            <option value="0" selected disabled>--select--</option>
                            <?php foreach ($states as $state):?>
                                <option value="<?php echo $state['id'];?>" <?php echo ($state['id'] == $service_tax['state_id'])? 'selected': '';?>><?php echo $state['name']?></option>
                                <?php echo $state['name']?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Select valid state?</div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>District</label>
                        <select id="district_id" class="form-control" name="district_id" required="">
                            <option value="0" selected disabled>--select--</option>
                            <?php foreach ($districts as $district): ?>
                                <?php if ($district['state_id'] == $service_tax['constituency']['state_id']):?>
                                    <option value="<?php echo $district['id'];?>" <?php echo ($district['id'] == $service_tax['district_id'])? 'selected': '';?>><?php echo $district['name']?></option>
                                <?php echo $district['name']?>
                                    </option>
                                <?php endif;?>
                                    <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Belongs to the District?</div>
                    </div>
                
					<div class="form-group col-md-4">
                        <label>Constituency</label>
                            <select class="form-control" id = "constituancy_id" name="constituancy_id">
                                <option value="0" selected>--select--</option>
                                <?php foreach ($constituencies as $constituency):?>
                                <option value="<?php echo $constituency['id'];?>" <?php echo ($constituency['id'] == $service_tax['constituency_id'])? 'selected': '';?>><?php echo $constituency['name']?></option>
                                <?php endforeach;?>
                            </select>
                        <div class="invalid-feedback">Select Discount type?</div>
                    </div>
                	<div class="form-group col-md-6">
						<label>Service Charge In %</label>
						<input type="number" name="service_tax" required="" id="service_tax" class="form-control"  value="<?php echo $service_tax['service_tax'];?>">
						<div class="invalid-feedback">Service Tax?</div>
					</div>
					<!-- <div class="form-group mb-0 col-md-4">
						<label>Rate</label>
						<input type="number" name="rate" id="rate" class="form-control"  value="<?php echo $service_tax['rate'];?>">
						<div class="invalid-feedback">Rate?</div>
					</div> -->
					
                </div>
				
					<div class="form-group col-md-12"><button class="btn btn-primary mt-27 ">Update</button>
				</div>
			</div>
		</form>
    </div>
</div>