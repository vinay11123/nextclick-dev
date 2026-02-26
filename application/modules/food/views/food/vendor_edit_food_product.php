<div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('vendor_req_product/0/r');?>">Ecommerce
<i class="fa fa-angle-double-left"></i> 
Products</a> 
   
    </div>
    </div>

        <div class="row">
            <div class="col-12">
                <h4 class="ven subcategory"><?=(($this->ion_auth->is_admin())? 'Edit Product' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label'));?></h4>
                <form class="needs-validation" novalidate="" action="<?php echo base_url('vendor_req_product/0/u');?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">

                <div class="form-row">
                <input type="hidden" name="id" value="<?php echo $sub_items['id'] ; ?>">
            <input type="hidden" name="section_id" value="<?php echo $food_sec[0]['id'] ; ?>">

                <div class="form-group col-md-4">
                <label>Shop By categories</label>
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
                        <option value="<?php echo $item['id'];?>" <?php if($item['id']== $sub_items['sub_cat_id']){ echo "selected";} ?> ><?php echo $item['name']?></option>
                    <?php endforeach;?>
                            <?php }?>
                        </select>
                        </div>
 

                        <div class="form-group col-md-4">
                        <label>Menu</label>
                  <!-- <label>
                            <?=(($this->ion_auth->is_admin() || $user->primary_intent=='vendor')? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>
                        </label> -->
                        <select class="form-control " id="menu_id" name="menu_id" required="" >
                            <option value="" selected disabled>--select--</option>
                                <?php foreach ($items as $item):?>
                    <option value="<?php echo $item['id'];?>" <?php echo ($item['id'] == $sub_items['menu_id'])? 'selected': '';?>>
                        <?php echo ($item['id'] == $sub_items['menu_id'])? $item['name']: $item['name'];?>
                    </option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>


                            <div class="form-group col-md-4">
                            <label> Product Name</label>
                                <!--<label>
                                    <?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?>
                            </label>-->
                                <input type="text" class="form-control" name="name" required="" value="<?php echo $sub_items['name'];?>">
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Enter valid  Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?>?</div>
                            </div>

                       <div class="form-group col-md-4">
                        <label> Brands </label>
                        <select class="form-control " id="brand_id" name="brand_id" required="" >
                            <option value="" selected disabled>--select--</option>


                            <?php foreach ($brands as $item):?>
                                      <option value="<?php echo $item['id'];?>" <?php echo ($item['id'] == $sub_items['brand_id'])? 'selected': '';?>><?php echo ($item['id'] == $sub_items['brand_id'])? $item['name']: $item['name'];?></option>
                                        <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>
 
                    <div class="form-group mb-0 col-md-4">
                    <label>Product Status</label>
                        <!--<label>
                            <?=(($this->ion_auth->is_admin())? 'Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_status'));?>
                        </label> -->
                        <div  class="form-control"> 
                        <label><input type="radio" name="status" required="" value="1"  <?=($sub_items['status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2" <?=($sub_items['status'] == 2)? 'checked' : '';?>> Not-Available</label>
                        </div>
                    </div>
                    
 					<div class="form-group mb-0 col-md-4">
						<label>Type</label> 
						<div  class="form-control"> 
						<label><input type="radio" name="item_type" required="" value="1" <?=($sub_items['item_type'] == 1)? 'checked' : '';?>> Veg </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_type" required="" value="2" <?=($sub_items['item_type'] == 2)? 'checked' : '';?>> Non veg</label>
						&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_type"  required="" value="3" <?=($sub_items['item_type'] == 3)? 'checked' : '';?>> Other</label>
						</div>
					</div>
<!-- 
                    <?php
                    if($vendor_category_id == 6){
                    ?>
                      <div class="form-group mb-0 col-md-2">
                        <label>Experience<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="number" class="form-control" name="exp" required="" value="<?php echo $sub_items['exp']?>" min="1">
                        <div class="invalid-feedback">Experience</div>
                        <?php echo form_error('exp','<div style="color:red">','</div>');?>
                    </div>
                      <div class="form-group mb-0 col-md-2">
                        <label>Qualification</label> 
                        <input type="text" class="form-control" name="qualification" required="" value="<?php echo $sub_items['qualification']?>">
                        <div class="invalid-feedback">Enter Qualification</div>
                        <?php echo form_error('qualification','<div style="color:red">','</div>');?>
                    </div>
                <?php }?> -->

                            <div class="form-group col-md-4">
                            <label>Upload Image</label>
                            <!-- <label>
                                    <?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?>
                                </label>-->
                                <input type="file" name="item_images[]" class="form-control"   value="" multiple>
                            <br>

                     
<!--                                 <input type="file" class="form-control" name="file"> -->
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?>?</div>
                            </div>
                            <div style = "margin-left: 248px;">
                            <?php foreach($img as $i){ ?>
                            <img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $i['id']; ?>.jpg?<?php echo time();?>" style="width: 150px;" />
<?php }  ?>  
</div>
<div class="form-group mb-0 col-md-12">
    <label>Description</label>
                                <!-- <label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></label> -->
                                <textarea class="form-control ckeditor"  name="desc" data-sample-short placeholder="Product Details" required=""><?php echo $sub_items['desc']?></textarea>
                                <!-- <input type="text" class="form-control" name="desc" required="" value="<?php echo $sub_items['desc']?>"> -->
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></div>
                            </div>


<div class="container">
 
  <a class="btn btn-info" id="addrows" style = "margin-bottom: 10px;margin-top: 10px;"> Add Variant</a>
       
  <table class="table" id = "mytable">
    <thead>
      <tr>
        
        <th>Name</th>
        <th>Price</th>
        <th>Weight</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>


        <?php foreach($sec_item1 as $si) { ?>
     <tr>
          <input type="hidden"class="form-control" name="id1[]"  value="<?php echo $si['id']; ?>"> 

        <td><input type="text"class="form-control" name="proname[]"  value="<?php echo $si['name']; ?>"></td>
        <td><input type="number"class="form-control" name="proprice[]"  value="<?php echo $si['price']; ?>"></td>
        <td><input type="number"class="form-control" name="proweight[]"  value="<?php echo $si['weight']; ?>"></td>

       <td>
        <a href = "<?php echo base_url()?>vendor_req_product/0/sec_item?id=<?php echo base64_encode(base64_encode($si['id'])); ?>" class="btn btn-danger" id = "rowdelete">
        <i class="fa fa-trash-o"></i>
        Delete  
         </a>  
         </td>  
       
      </tr> 
  <?php } ?>
     
    </tbody>
  </table>
   
  
</div>
 

                            <div class="form-group col-md-12 mt-4 pt-2">

                                <button class="btn btn-primary mt-27 ">Update</button>
                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </div>


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 
<script>
$(document).ready(function(){

    $("#addrows").click(function () {
    

    $('tbody').append('<tr>' +
        '<td class="col-xs-1"><input type="text"class="form-control" name="proname[]" placeholder = "Enter Variant Name"  value=""></td>' +
        '<td class="col-xs-4"><input type="number"class="form-control" name="proprice[]" placeholder = "Enter Price" value=""></td>' +
        '<td class="col-xs-5"><input type="number"class="form-control" name="proweight[]" placeholder = "Enter Weight"  value=""></td>' +
        '<td class="col-xs-2">' +
        '<button class="btn btn-danger">' +
        '<i class="fa fa-trash-o"></i>' +
        'Delete' +
        '</button>' +
        '</td>' +
        '</tr>');
});
});


$(document).on('click', '.btn', function() {
    $(this).parent().parent('tr').remove();
});

 </script>