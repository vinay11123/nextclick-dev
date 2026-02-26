<!DOCTYPE html>
    <html>
    <body>
    <div class="row">
    <div class="col-12">
    <h4 class="ven">Edit Promotion Codes</h4>
    <form class="needs-validation" novalidate=""
    action="<?php echo base_url('promotion_codes/u');?>" method="post"
    enctype="multipart/form-data">
        <div class="card-header">
           <div class="form-row">
               <input type="hidden" name="id" value="<?php echo $promos['id'] ; ?>">
                <div class="form-group mb-0 col-md-4">
                    <label>Promo Title</label> 
                    <input type="text" name="promo_title" id="promo_title" class="form-control"  value="<?php echo $promos['promo_title'];?>">
                    <div class="invalid-feedback">Give some Title</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>Promo Code</label> 
                    <input type="text" name="promo_code" id="promo_code" class="form-control"  value="<?php echo $promos['promo_code'];?>">
                    <div class="invalid-feedback">Give some Promo Code</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                <label>Promo Type</label>
                    <select class="form-control" name="promo_type" id="promo_type" onchange="return promo_to_check(this.value)" required="">
                        <option value="1" <?php if ($promos['promo_type'] == 1) { echo 'selected';} ?>>Nextclick</option>
                        <option value="2" <?php if ($promos['promo_type'] == 2) {echo 'selected';} ?>>All Vendors</option>
                        <option value="3" <?php if ($promos['promo_type'] == 3) {echo 'selected';} ?>>Few Vendors</option>
                    </select>
                    <div class="invalid-feedback">Promo Type?</div>
                    <?php echo form_error('promo_type','<div style="color:red>"','</div>');?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                        <select class="form-control" id = "cat_id" name="cat_id" onChange="category_changed1(this.value);" required=""  >
                            <option value="0" selected>--select--</option>
                            <?php foreach ($categories as $category):?>
                            <option value="<?php echo $category['id'];?>" <?php echo ($category['id'] == $promos['category'])? 'selected': '';?>><?php echo $category['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Name?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Sub Category</label>
                    <select id="district" class="form-control" onChange="shop_by_category_changed1(this.value);" name="sub_cat_id"  id = "sub_cat_id" required="">
                        <option value="0" selected disabled>--select--</option>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <?php if ($subcategory['id'] == $promos['shop_by_category']):?>
                                <option value="<?php echo $subcategory['id'];?>" <?php echo ($subcategory['id'] == $promos['shop_by_category'])? 'selected': '';?>><?php echo $subcategory['name']?></option>
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
                            <?php foreach ($menus as $menu):?>
                            <option value="<?php echo $menu['id'];?>" <?php echo ($menu['id'] == $promos['menu'])? 'selected': '';?>><?php echo $menu['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Menu?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Brand</label>
                        <select class="form-control" id = "brand_id" name="brand_id" required=""  >
                            <option value="0" selected>--select--</option>
                            <?php foreach ($brands as $brand):?>
                            <option value="<?php echo $brand['id'];?>" <?php echo ($brand['id'] == $promos['brand'])? 'selected': '';?>><?php echo $brand['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Menu?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Product</label>
                        <select class="form-control" id = "product_id" name="product_id" onChange="product_changed(this.value);" required=""  >
                            <option value="0" selected>--select--</option>
                            <?php foreach ($products as $product):?>
                            <option value="<?php echo $product['id'];?>" <?php echo ($product['id'] == $promos['promo_products'][0]['product_id'])? 'selected': '';?>><?php echo $product['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Menu?</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Product Variants</label>
                        <select class="form-control" id = "varient_id" name="varient_id" required=""  >
                            <option value="0" selected>--select--</option>
                            <?php foreach ($variants as $variant):?>
                            <option value="<?php echo $variant['section_item_id'];?>" <?php echo ($variant['section_item']['id'] == $promos['promo_products'][0]['vendor_product_variant_id'])? 'selected': '';?>><?php echo $variant['section_item']['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Menu?</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Discount type</label>
                    <select class="form-control" name="discount_type" id="discount_type" onchange="return promo_to_check(this.value)" required="">
                        <option value="1" <?php if ($promos['discount_type'] == 1) { echo 'selected';} ?>>Amount</option>
                        <option value="2" <?php if ($promos['discount_type'] == 2) {echo 'selected';} ?>>Percentage</option>
                    </select>
                    <div class="invalid-feedback">Select Discount type?</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>Discount</label>
                    <input type="number" name="discount" class="form-control"  value="<?php echo $promos['discount'];?>">
                    <div class="invalid-feedback">Give some Discount</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>No.of Uses</label>
                    <input type="number" name="discount" class="form-control"  value="<?php echo $promos['uses'];?>">
                    <div class="invalid-feedback">Give some Discount</div>
                </div>
            </div>

            <div class="form-row">
            
                <div class="form-group mb-0 col-md-4">
                    <label>Start Date</label> 
                    <input type="text" name="start_date" id="start_date" class="form-control"  value="<?php echo $promos['valid_from'];?>">
                    <div class="invalid-feedback">Give some Published ON</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>End Date</label> 
                    <input type="text" name="end_date" id="end_date" class="form-control"  value="<?php echo $promos['valid_to'];?>">
                    <div class="invalid-feedback">Give some Expired ON</div>
                </div>
                <div class="form-group col-md-4">
                    <label>Promo Status</label>
                        <select class="form-control" name="discount_type" id="discount_type" onchange="return promo_to_check(this.value)" required="">
                            <option value="1" <?php if ($promos['status'] == 1) { echo 'selected';} ?>>Active</option>
                            <option value="2" <?php if ($promos['status'] == 2) {echo 'selected';} ?>>In Active</option>
                        </select>
                    <div class="invalid-feedback">Promo Status?</div>
                    <?php echo form_error('status','<div style="color:red>"','</div>');?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button class="btn btn-primary mt-27 ">Update</button>
                </div>
            </div>

        </div>
    </form>



    </div>
    </div>

    <script type="text/javascript">
        function promo_to_check(promo_type) {
            $('#vendors_list').hide();
            if(promo_type==3){
                $('#vendors_list').show();
            }
        }
    </script>
    </body>
    </html>