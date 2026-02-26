<?php
$this->load->view('food_scripts');
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id = 4; //$cat_id['category_id'];
?>
<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Add Section Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_label'));?></h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('food_section_item/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-4">
				<label>Shop By categories</label>
				<select class="form-control" onChange="shop_by_category_changed(this.value);" id="sub_cat_id" name="sub_cat_id" required=""  id="cars">
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
    <option value="<?=$sl[$r]['id'];?>"><?=$sl[$r]['name'];?></option>
<?php }}?>
  </optgroup>
							<?php
						}
							}else{
							?>
    							<?php foreach ($sub_categories as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
    						<?php }?>
						</select>
						</div>
					<div class="form-group col-md-3">
						<label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_menu'));?></label>
						<select class="form-control" name="menu_id" id="menu_id" onChange="menu_changed(this.value);" required="" onchange="get_sub_item(this.value)">
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($food_items as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-3">
						<label><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_item'));?></label>
						<select class="form-control" name="item_id" required="" onChange="item_changed(this.value);" id="item_id">
							<option value="" selected disabled>--select--</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_item'));?>?</div>
						<?php echo form_error('item_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?></label>
						<select class="form-control" name="sec_id" required="" id="sec_list">
							<option value="" selected disabled>--select--</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?>?</div>
						<?php echo form_error('sec_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?></label> <input type="text" name="name"
							required="" value="<?php echo set_value('name')?>"
							class="form-control">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?>?</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></label> <input type="number" class="form-control" name="price" required="" value="<?php echo set_value('price')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></div>
						<?php echo form_error('price','<div style="color:red">','</div>');?>
					</div>
					 <div class="form-group mb-0 col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_status'));?></label> 
                        <div  class="form-control"> 
                        <label><input type="radio" name="status" required="" value="1" checked=""> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2"> Not-Available</label>
                        </div>
                    </div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></label> <input type="text" class="form-control" name="desc" required="" value="<?php echo set_value('desc')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group col-md-12">
<br/>
						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>
