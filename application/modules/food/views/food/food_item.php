<?php
$this->load->view('food_scripts');
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id = 4; //$cat_id['category_id'];
$vegnonveg='';
if(!$this->ion_auth->is_admin()){
$vegnonveg= $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg','field_status');
}
//echo $vendor_category_id;

?>
<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Add Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label'));?></h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('food_item/c');?>" method="post"
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
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?></label>
						<select class="form-control " id="menu_id" name="menu_id" required="" >
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($food_items as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?></label> <input type="text"
							class="form-control" name="name" required="" value="<?php echo set_value('name')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?>?</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></label> <input type="number" class="form-control" name="price" required="" value="<?php echo set_value('price')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></div>
						<?php echo form_error('price','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group mb-0 col-md-2">
						<label><?=(($this->ion_auth->is_admin())? 'Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></label> 
						<input type="number" class="form-control" name="quantity" required="" value="1" min="1">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Atleast 1 Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></div>
						<?php echo form_error('quantity','<div style="color:red">','</div>');?>
					</div>
					<?php
                    if($this->ion_auth->is_admin() || $vegnonveg == 1){
                    ?>
					<div class="form-group mb-0 col-md-3">
						<label><?=(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg'));?></label> 
						<?php $veg=explode('/',(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg')));?>
						<div  class="form-control"> 
						<label><input type="radio" name="item_type" required="" value="1" checked=""> <?=$veg[0];?> </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_type" required="" value="2"> <?=$veg[1];?></label>
						</div>
					</div>
					<?php }else{
						?>
						<input type="hidden" name="item_type" required="" value="1" checked="">
						<?php
					}?>
					<!-- <div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_sec_price'));?></label>
						<select class="form-control" name="menu_id" required="" >
							<option value="1" selected>Item Price Replace With Section Price</option>
							<option value="2">Section Price Add To Item Price</option>
							<option value="3">No Section Price</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div> -->
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_status'));?></label> 
						<div  class="form-control"> 
						<label><input type="radio" name="status" required="" value="1" checked=""> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2"> Not-Available</label>
						</div>
					</div>
					<div class="form-group mb-0 col-md-2">
						<label> <?=(($this->ion_auth->is_admin())? 'Discount in' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_discount'));?>%</label> 
						<input type="number" class="form-control" name="discount" required="" value="0" min="0">
					</div>
					<!-- <?php
                    if($vendor_category_id == 6){
                    ?>
                      <div class="form-group mb-0 col-md-2">
                        <label>Experience<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="number" class="form-control" name="exp" required="" value="<?php echo set_value('exp')?>" min="1">
                        <div class="invalid-feedback">Experience</div>
                        <?php echo form_error('exp','<div style="color:red">','</div>');?>
                    </div>
                      <div class="form-group mb-0 col-md-4">
                        <label>Qualification<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="text" class="form-control" name="qualification" required="" value="<?php echo set_value('qualification')?>">
                        <div class="invalid-feedback">Enter Qualification</div>
                        <?php echo form_error('qualification','<div style="color:red">','</div>');?>
                    </div>
                <?php }?> -->
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?></label> 
						
						<input type="file" name="file" required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);">
						<img id="blah" src="#" alt=""> 
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?>?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-12">
						<label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></label> 
						<textarea class="form-control ckeditor"  name="desc" data-sample-short placeholder="Product Details" required=""><?php echo set_value('desc')?></textarea>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group col-md-12 mt-4 pt-2">

						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>