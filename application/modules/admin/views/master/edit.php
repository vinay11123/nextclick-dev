<?php if ($type == 'category') { ?>
    <div class="row">
        <div class="col-md-12">

            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('category/r'); ?>">Listing Filters Data <i class="fa fa-angle-double-left"></i> Category</a>

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="ven editcategory">Edit Category</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('category/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-group row">
                        <div class="form-group col-md-3">
                            <label>Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $category['name']; ?>">
                            <div class="invalid-feedback">Give some Category Name</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

                        <div class="form-group col-md-3">
                            <label>Description</label>
                            <input type="text" name="desc" id="desc" class="form-control" value="<?php echo $category['desc']; ?>">
                            <div class="invalid-feedback">Give Description</div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Services</label>
                            <select id="services_multiselect" class="form-control " name="service_id[]" required="" multiple>
                                <?php foreach ($services as $service) : ?>
                                    <option value="<?php echo $service['id']; ?>" <?php echo (is_array($categories['services']) && in_array($service['id'], array_column($categories['services'], 'id'))) ? 'selected' : ''; ?>>
                                        <?php echo $service['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Brands</label>
                            <select id="brands_multiselect" class="form-control" name="brand_id[]" multiple>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?php echo $brand['id']; ?>" <?php echo (is_array($categories['brands']) && in_array($brand['id'], array_column($categories['brands'], 'id'))) ? 'selected' : ''; ?>>
                                        <?php echo $brand['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Upload Image</label>
                            <input type="file" accept="image/jpeg,image/png" name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/category_image/category_<?php echo $category['id']; ?>.jpg">
                            <br>
                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/category_image/category_<?php echo $category['id']; ?>.jpg">

                            <div class="invalid-feedback">Upload Image?</div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Coming Soon Image</label>
                            <input type="file" accept="image/jpeg,image/png" name="coming_soon_file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/coming_soon_image/category_<?php echo $category['id']; ?>.jpg">
                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/coming_soon_image/coming_soon_<?php echo $category['id']; ?>.jpg">

                            <div class="invalid-feedback">Upload Image?</div>
                        </div>




                        <div class="col-12 col-sm-12 col-md-12 ven2">
                            <label>Terms And Conditions</label>
                            <textarea id="cat_terms" class="ckeditor" name="terms" rows="10" data-sample-short>
                            <?php echo $category['terms']; ?>
                        </textarea>
                            <?php echo form_error('terms', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12 mt-4">
                            <!--                             <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> -->
                            <button class="btn btn-primary mt-27 ">Update</button>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <?php
    if (isset($_GET['mode']) && $_GET['mode'] == 'developer') {
    ?>
        <div class="row">
            <div class="col-12">
                <h4 class="ven">Manage <?= $categories['name']; ?> Category Account</h4>
                <form class="needs-validation" novalidate="" action="<?php echo base_url('category/m'); ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">

                        <div class="form-group row">
                            <?php
                            $manage = $this->db->get_where('manage_account', array('status' => 1))->result_array();
                            $cat_name = $this->db->get_where('manage_account_names', array('status' => 1, 'category_id' => $categories['id']))->result_array();
                            $i = 0;
                            foreach ($manage as $ma) {
                            ?>
                                <div class="form-group col-md-4">
                                    <label><?= $ma['name']; ?></label>
                                    <input type="text" name="<?= $ma['desc']; ?>" class="form-control" value="<?= $cat_name[$i]['name']; ?>">
                                    <div class="invalid-feedback">Enter <?= $ma['name']; ?>?</div>
                                </div>

                                <div class="form-group col-md-8">
                                    <?php if ($ma['field_status'] != '') { ?>
                                        <label>Check</label>
                                        <div class="form-control">
                                            <?php
                                            $che = explode('/', $ma['field_status']);
                                            for ($f = 0; $f < count($che); $f++) {
                                                $fa = explode('-', $che[$f]);
                                            ?>
                                                <label><input type="radio" name="r<?= $ma['desc']; ?>" class="" required="" value="<?= $fa[0]; ?>" <?= ($fa[0] == $cat_name[$i]['field_status'] || $cat_name[$i]['field_status'] == '') ? 'checked' : ''; ?>><?= $fa[1]; ?>&nbsp;&nbsp;&nbsp;</label>
                                            <?php } ?>
                                        </div>
                                        <div class="invalid-feedback">Select <?= $ma['name']; ?>?</div>
                                    <?php } ?>
                                </div>

                            <?php $i++;
                            } ?>
                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                            <div class="form-group col-md-12 mt-4">
                                <!--                             <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> -->
                                <button class="btn btn-primary mt-27 ">Update</button>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    <?php } ?>
<?php } elseif ($type == 'sub_category') { ?>
    <!--sub_category Edit-->

    <div class="row">
        <div class="col-md-12">

            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('sub_category/r/0'); ?>">Listing Filters Data <i class="fa fa-angle-double-left"></i> Sub Category</a>

        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit Sub Category</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('sub_category/u/0'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Sub Categories</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php if (set_value('name') != '') echo set_value('name');
                                                                                                    else echo $sub_categories['name']; ?>">
                            <input type="hidden" name="page" value="<?php echo $this->input->get('page') ; ?>">
                            <div class="invalid-feedback">Give some Title</div>
                            <?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
                            <?php if (isset($this->session->flashdata('upload_status')['error'])) { ?>
                                <div style="color:red"><?php echo $this->session->flashdata('upload_status')['error']; ?></div>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $sub_categories['id']; ?>">

                        <div class="form-group col-md-3">
                            <label>Category</label>
                            <select class="form-control" name="cat_id" id="cat_id" required="">
                                <option value="0" selected disabled>select</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php if (set_value('cat_id') != '') echo ($category['id'] == set_value('cat_id')) ? 'selected' : '';
                                                                                    else echo ($category['id'] == $sub_categories['cat_id']) ? 'selected' : ''; ?>><?php echo $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Description</label>
                            <input type="text" name="desc" id="desc" class="form-control" value="<?php if (set_value('name') != '') echo set_value('name');
                                                                                                    else echo $sub_categories['desc']; ?>">
                            <div class="invalid-feedback">Give some Description</div>
                        </div>

                        <div class="form-group mb-0 col-md-3">
                            <label>Type</label>
                            <select required class="form-control" name="type">
                                <option value="0" selected disabled>--select--</option>
                                <option value="1" <?php if (set_value('type') != '') echo (set_value('type') == 1) ? 'selected' : '';
                                                    else echo ($sub_categories['type'] == 1) ? 'selected' : ''; ?>>Listing Sub Category</option>
                                <option value="2" <?php if (set_value('type') != '') echo (set_value('type') == 2) ? 'selected' : '';
                                                    else echo ($sub_categories['type'] == 2) ? 'selected' : ''; ?>>Shop By Category</option>
                            </select>
                            <?php echo form_error('type', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Veg/Non-veg widget status</label><br>
                            <label class="radio-inline" for="yes"><input type="radio" name="product_type_widget_status" id="yes" class="form-control" <?php if (set_value('product_type_widget_status') != '') echo (set_value('product_type_widget_status') == 1) ? 'checked' : '';
                                                                                                                                                        else echo ($sub_categories['product_type_widget_status'] == 1) ? 'checked' : ''; ?> value="1">Enable</label>
                            <label class="radio-inline" style="float-left: 10px" for="no"><input type="radio" name="product_type_widget_status" id="no" class="form-control" <?php if (set_value('product_type_widget_status') != '') echo (set_value('product_type_widget_status') == 2) ? 'checked' : '';
                                                                                                                                                                                else echo ($sub_categories['product_type_widget_status'] == 2) ? 'checked' : ''; ?> value="2">Disable</label>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Upload Image</label>
                            <input type="file" accept="image/jpeg,image/png" name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/sub_category_image/sub_category_ <?php echo $sub_categories['id']; ?>.jpg">

                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/sub_category_image/sub_category_<?php echo $sub_categories['id']; ?>.jpg">

                            <div class="invalid-feedback">Upload Image?</div>
                        </div>
                        <div class="form-group col-md-2">
                            <button class="btn btn-primary mt-27 mt">Update</button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>


<?php } elseif ($type == 'amenity') { ?>

    <!--Amenity Edit-->
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit Amenity</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('amenity/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Amenity Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $amenity['name']; ?>">
                            <div class="invalid-feedback">Give Amenity Name</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $amenity['id']; ?>">

                        <div class="form-group col-md-4">
                            <label>Category</label>
                            <!-- <input type="file" class="form-control" required="">-->
                            <select class="form-control" name="cat_id">
                                <option value="" selected>--select--</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $amenity['cat_id']) ? 'selected' : ''; ?>><?php echo $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>


                        <div class="form-group col-md-4">
                            <label>Description</label>
                            <input type="text" name="desc" id="desc" class="form-control" value="<?php echo $amenity['desc']; ?>">
                            <div class="invalid-feedback">Give Description</div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Upload Image</label>
                            <input type="file" name="file" accept="image/jpeg, image/png" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/amenity_image/amenity_<?php echo $amenity['id']; ?>.jpg"><br>

                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/amenity_image/amenity_<?php echo $amenity['id']; ?>.jpg">

                            <div class="invalid-feedback">Upload Image?</div>
                        </div>
                        <div class="form-group col-md-2">

                            <button class="btn btn-primary mt-27 mt">Update</button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

<?php } elseif ($type == 'service') { ?>

    <div class="row pb-4">
        <div class="col-md-12">
            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('service/r'); ?>">Listing Filters Data
                <i class="fa fa-angle-double-left"></i>
                Services</a>

        </div>
    </div>

    <!--edit Service -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit Service</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('service/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <input type="hidden" name="page" value="<?php echo $this->input->get('page') ; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Service Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $services['name']; ?>">
                            <div class="invalid-feedback">Give Service Name</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $services['id']; ?>">

                        <div class="form-group col-md-3">
                            <label>Permissions</label>
                            <!-- <input type="file" class="form-control" required=""> -->
                            <select id="services_multiselect" class="form-control" name="perm_id[]" multiple>
                                <?php if (isset($perm_ids)) :   foreach ($permissions as $permission) : ?>
                                        <option value="<?php echo $permission['id']; ?>" <?php echo (in_array($permission['id'], $perm_ids)) ? 'selected' : ''; ?>>
                                            <?php echo $permission['name'] ?>
                                        </option>
                                <?php endforeach;
                                endif; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Description</label>
                            <input type="text" name="desc" id="desc" class="form-control" value="<?php echo $services['desc']; ?>">
                            <div class="invalid-feedback">Give Description</div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Languages</label>
                            <input type="text" name="languages" id="languages" class="form-control" value="<?php echo $services['languages']; ?>">
                            <div class="invalid-feedback">Give Languages</div>
                        </div>


                        <div class="form-group col-md-3">
                            <label>Upload Image</label>
                            <input type="file" name="file" accept="image/jpeg, image/png" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/service_image/service_<?php echo $services['id']; ?>.jpg"><br>

                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/service_image/service_<?php echo $services['id']; ?>.jpg">
                            <div class="invalid-feedback">Upload Image?</div>
                        </div>
                        <div class="form-group col-md-2">

                            <button class="btn btn-primary mt">Update</button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

<?php } elseif ($type == 'state') { ?>
    <div class="row pb-4">
        <div class="col-md-12">
            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('state/r'); ?>">Listing Filters Data
                <i class="fa fa-angle-double-left"></i>
                State</a>

        </div>
    </div>

    <!--Edit State -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit State</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('state/u'); ?> " method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="form-row">
                        <div class="form-group col-md-6">

                            <label>State Name</label>
                            <input type="text" name="name" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" class="form-control" required="" value="<?php echo $state['name']; ?>">
<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>

                            <div class="invalid-feedback">Enter Valid State Name?</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $state['id']; ?>">

                        <div class="form-group col-md-6 mt-4 pt-3">
                            <button class="btn btn-primary mt-27 ">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php } elseif ($type == 'district') { ?>

    <div class="row pb-4">
        <div class="col-md-12">
            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('district/r'); ?>">Listing Filters Data
                <i class="fa fa-angle-double-left"></i>
                District</a>

        </div>
    </div>

    <!--Edit District-->
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit District</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('district/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>District Name</label>
                            <input type="text" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="name" class="form-control" required="" value="<?php echo $district['name']; ?>">
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                            <div class="invalid-feedback">Enter Valid District Name?</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $district['id']; ?>">

                        <div class="form-group col-md-5">
                            <label>State</label>

                            <!-- 						<select class="form-control" name="state_id" required=""> -->
                            <!-- 								<option value="">state1</option> -->
                            <!-- 								<option value="" >state1</option> -->
                            <!-- 								<option value="" selected >state1</option> -->
                            <!-- 								<option value=""  >state1</option> -->

                            <!-- 						</select> -->
                            <select class="form-control" id='state' onchange="state_changed()" name="state_id" required="">
                                <option value="0" selected disabled>--select--</option>
                                <?php foreach ($states as $state) : ?>
                                    <option value="<?php echo $state['id']; ?>" <?php echo ($state['id'] == $district['state_id']) ? 'selected' : ''; ?>><?php echo $state['name'] ?></option>

                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('state_id', '<div class="text-danger">', '</div>'); ?>
                            <div class="invalid-feedback">Belongs to the state?</div>
                        </div>
                        <div class="form-group col-md-2 mt-4 pt-3">
                            <button class="btn btn-primary mt-27 ">Update</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php } elseif ($type == 'constituency') { ?>
    <div class="row pb-4">
        <div class="col-md-12">
            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('constituency/r'); ?>">Listing Filters Data
                <i class="fa fa-angle-double-left"></i>
                Constituency</a>

        </div>
    </div>


    <!-- Edit Constituency -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit Constituency</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('constituency/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Constituency Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $constituency['name']; ?>">
                            <div class="invalid-feedback">Give some Constituency Name</div>
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $constituency['id']; ?>">

                        <div class="form-group col-md-3">
                            <label>State</label>
                            <select class="form-control" id='state' onchange="state_changed()" name="state_id" required="">
                                <option value="0" selected disabled>--select--</option>

                                <?php foreach ($states as $state) : ?>
                                    <option value="<?php echo $state['id']; ?>" <?php echo ($state['id'] == $constituency['state_id']) ? 'selected' : ''; ?>><?php echo $state['name'] ?></option>
                                    <?php echo $state['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select valid state?</div>
                            <?php echo form_error('state_id', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label>District</label>
                            <select id="district" class="form-control" name="dist_id" required="">
                                <option value="0" selected disabled>--select--</option>
                                <?php foreach ($districts as $district) : ?>
                                    <?php if ($district['state_id'] == $constituency['state_id']) : ?>
                                        <option value="<?php echo $district['id']; ?>" <?php echo ($district['id'] == $constituency['district_id']) ? 'selected' : ''; ?>><?php echo $district['name'] ?></option>
                                        <?php echo $district['name'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Belongs to the District?</div>
                            <?php echo form_error('dist_id', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Pincode</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo $constituency['pincode']; ?>">
                            <div class="invalid-feedback">Give some Pincode?</div>
                            <?php echo form_error('pincode', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group col-md-1">

                            <button class="btn btn-primary">Update</button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

<?php } elseif ($type == 'brand') { ?>
    <div class="row">
        <div class="col-md-12">

            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('brands/r/0'); ?>">Listing Filters Data <i class="fa fa-angle-double-left"></i> Brands</a>

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="ven subcategory">Edit Brands</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('brands/u/0'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Brand Name</label>
                            <input type="text" name="name" id="name" class="form-control" 
                            value="<?php if (set_value('name') != '') echo set_value('name'); else echo $ecom_brands['name']; ?>">
                            <input type="hidden" name="page" value="<?php echo $this->input->get('page') ; ?>">
                            <div class="invalid-feedback">Give some Brand Name</div>
                            <?php echo form_error('name', '<div style="color:red">', '</div>'); ?>
                            <?php if (isset($this->session->flashdata('upload_status')['error'])) { ?>
                                <div style="color:red"><?php echo $this->session->flashdata('upload_status')['error']; ?></div>
                            <?php } ?>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Categories </label>
                            <select id="categorys_multiselect" class="form-control" name="categorys_id[]" required multiple>
                                <?php
                                $categories_array = explode(',', $categories[0]->categories_ids);
                                foreach ($categorys as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo (in_array($category['id'], $categories_array)) ? 'selected' : ''; ?>>
                                        <?php echo $category['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $ecom_brands['id']; ?>">
                        <div class="form-group col-md-4">
                            <label>Description</label>
                            <input type="text" name="desc" id="desc" class="form-control" required='' value="<?php if (set_value('desc') != '') echo set_value('desc');
                                                                                                                else echo $ecom_brands['desc']; ?>">
                            <div class="invalid-feedback">Give some Description</div>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Upload Image</label>
                            <input type="file" accept="image/jpeg,image/png" name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/ecom_brands_image/ecom_brands_<?php echo $ecom_brands['id']; ?>.jpg">
                        </div>
                        <div class="form-group col-md-1">
                            <img class="textimgmotion" src="<?php echo base_url(); ?>uploads/brands_image/brands_<?php echo $ecom_brands['id']; ?>.jpg">
                            <div class="invalid-feedback">Upload Image?</div>
                        </div>

                        <div class="form-group col-md-2">
                            <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 mt">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } elseif ($type == 'request') { ?>
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Edit Request</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('request/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group mb-0 col-md-12">
                            <label>Title</label> <input type="text" class="form-control" name="title" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" required="" placeholder="Title" value="<?php echo $request['title'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('title', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">

                        <div class="col col-sm col-md-12">
                            <label>Description</label>
                            <textarea id="request_desc" name="desc" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" class="ckeditor" rows="10" data-sample-short><?php echo $request['desc'] ?></textarea>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12">

                            <button class="btn btn-primary mt-27 ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } elseif ($type == 'specialities') { ?>
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Edit Speciality</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('specialities/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="form-row">
                        <div class="form-group mb-0 col-md-4">
                            <label>Name</label>
                            <input type="text" class="form-control" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="name" required="" placeholder="Title" value="<?php echo $specialities['name'] ?>">
                            <div class="invalid-feedback">Give Name</div>
                            <?php echo form_error('title', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $specialities['id']; ?>">
                        <div class="form-group col-md-4">
                            <label>Image</label>
                            <input type='file' name="file" class="form-control" accept="image/jpg, image/jpeg, image/png" onchange="readURL(this);" id="upload_form" /><?php echo form_error('file', '
                        <div style="color:red">', '</div>'); ?><br>

                        </div>
                        <div class="form-group col-md-1">
                            <img id="blah" src="<?php echo base_url(); ?>uploads/speciality_image/speciality_<?php echo $specialities['id']; ?>.jpg?<?php echo time(); ?>" alt="your image" />
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="col col-sm col-md-12">
                            <label>Description</label>
                            <textarea id="speciality_desc" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="desc" class="ckeditor" rows="10" data-sample-short>
                            <?php echo $specialities['desc'] ?>
                        </textarea>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12">
                            <button class="btn btn-primary mt-27 ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } elseif ($type == 'od_categories') { ?>
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Edit On Demand Category</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('od_categories/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="form-row">
                        <div class="form-group mb-0 col-md-3">
                            <label>Name</label>
                            <input type="text" class="form-control" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="name" required="" placeholder="Title" value="<?php echo $od_categories['name'] ?>">
                            <div class="invalid-feedback">Give Name</div>
                            <?php echo form_error('title', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $od_categories['id']; ?>">
                        <div class="form-group col-md-4">
                            <label>Category List</label>
                            <select class="form-control" name="cat_id" required="" id="cat_id">
                                <option value="0" selected disabled>select</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $od_categories['cat_id']) ? 'selected' : ''; ?>><?php echo $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Image</label>
                            <input type='file' name="file" accept="image/jpeg, image/jpeg, image/png" class="form-control" onchange="readURL(this);" id="upload_form" /><?php echo form_error('file', '
                        <div style="color:red">', '</div>'); ?><br>

                        </div>
                        <div class="form-group col-md-1">
                            <img id="blah" src="<?php echo base_url(); ?>uploads/od_category_image/od_category_<?php echo $od_categories['id']; ?>.jpg?<?php echo time(); ?>" alt="your image" />
                        </div>
                        <div class="col col-sm col-md-12">
                            <label>Description</label>
                            <textarea id="speciality_desc" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="desc" class="ckeditor" rows="10" data-sample-short>
                            <?php echo $od_categories['desc'] ?>
                        </textarea>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12">
                            <button class="btn btn-primary mt-27 ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php } elseif ($type == 'doctors') { ?>
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Update Doctor</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('doctors/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group mb-0 col-md-4">
                            <label>Name</label> <input type="text" class="form-control" name="name" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" required="" placeholder="Title" value="<?php echo $doctors['name'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('title', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $doctors['id']; ?>">
                        <div class="form-group col-md-4"><label>Category</label>
                            <select class="form-control" name="hosp_specialty_id" required="">
                                <option value="0" selected>--select--</option>
                                <?php foreach ($specialities as $speciality) : ?>
                                    <option value="<?php echo $speciality['id']; ?>" <?php echo ($speciality['id'] == $doctors['hosp_specialty_id']) ? 'selected' : ''; ?>><?php echo $speciality['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>

                        <div class="form-group mb-0 col-md-4">
                            <label>Qualification</label> <input type="text" class="form-control" name="qualification" required="" placeholder="Qualification" value="<?php echo $doctors['qualification'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('qualification', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Experience</label> <input type="number" class="form-control" name="experience" required="" placeholder="Experience" value="<?php echo $doctors['experience'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('experience', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Languages</label> <input type="text" class="form-control" name="languages" required="" placeholder="Languages" value="<?php echo $doctors['languages'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('languages', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Fee of Doctor</label> <input type="number" class="form-control" name="fee" required="" placeholder="Fee of Doctor" value="<?php echo $doctors['fee'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('fee', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Discount</label> <input type="number" class="form-control" name="discount" required="" placeholder="Discount" value="<?php echo $doctors['discount'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('discount', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Holidays</label> <input type="text" class="form-control" name="holidays" required="" placeholder="Discount" value="<?php echo $doctors['holidays'] ?>">
                            <div class="invalid-feedback">Give Title</div>
                            <?php echo form_error('holidays', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Image</label>
                            <input type='file' name="file" class="form-control" accept="image/jpeg, image/jpeg, image/png" onchange="readURL(this);" id="upload_form" /><?php echo form_error('file', '
                        <div style="color:red">', '</div>'); ?><br>

                        </div>
                        <div class="form-group col-md-1">
                            <img id="blah" src="<?php echo base_url(); ?>uploads/doctors_image/doctors_<?php echo $doctors['id']; ?>.jpg?<?php echo time(); ?>" alt="your image" />
                        </div>
                        <div class="col col-sm col-md-12">
                            <label>Description</label>
                            <textarea id="doctors_desc" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="desc" class="ckeditor" rows="10" data-sample-short>value="<?php echo $doctors['desc'] ?>"</textarea>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12">

                            <button class="btn btn-primary mt-27 ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } elseif ($type == 'od_services') { ?>
    <div class="row">
        <div class="col-12">
            <h4 class="ven">Add On Demand Service</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('od_services/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Service Name</label> <input type="text" class="form-control" name="name" placeholder=" Name" value="<?php echo $od_servicees['name'] ?>">
                            <div class="invalid-feedback">New Amenity Name?</div>
                            <?php echo form_error('name', '<div style="color:red">', '</div>'); ?>

                        </div>
                        <input type="hidden" name="id" value="<?php echo $od_servicees['id']; ?>">
                        <div class="form-group col-md-4">
                            <label>Category</label>
                            <select class="form-control" required="" name="od_cat_id">
                                <option value="0" selected>--select--</option>
                                <?php foreach ($od_categories as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $od_servicees['od_cat_id']) ? 'selected' : ''; ?>><?php echo $category['name'] ?></option>


                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Select Category Name?</div>
                        </div>


                        <div class="form-group col-md-4">
                            <label>Service Duration</label> <input type="text" class="form-control" name="service_duration" placeholder="Service Duration" required="" value="<?php echo $od_servicees['service_duration'] ?>">
                            <div class="invalid-feedback">New Amenity Name?</div>
                            <?php echo form_error('service_duration', '<div style="color:red">', '</div>'); ?>

                        </div>
                        <div class="form-group col-md-4">
                            <label>Service Price</label> <input type="number" class="form-control" name="price" placeholder="Service Price" required="" value="<?php echo $od_servicees['price'] ?>">
                            <div class="invalid-feedback">New Amenity Name?</div>
                            <?php echo form_error('price', '<div style="color:red">', '</div>'); ?>

                        </div>
                        <div class="form-group mb-0 col-md-4">
                            <label>Discount</label> <input type="number" class="form-control" name="discount" required="" placeholder="Discount" value="<?php echo $od_servicees['discount'] ?>">
                            <div class="invalid-feedback">Give some Description</div>
                            <?php echo form_error('discount', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Image</label>
                            <input type='file' name="file" accept="image/jpeg, image/jpeg, image/png" class="form-control" onchange="readURL(this);" id="upload_form" /><?php echo form_error('file', '
                        <div style="color:red">', '</div>'); ?><br>
                            <img id="blah" src="<?php echo base_url(); ?>uploads/od_service_image/od_service_<?php echo $od_servicees['id']; ?>.jpg?<?php echo time(); ?>" width="180" height="180" alt="your image" />
                        </div>
                        <div class="col col-sm col-md-12">
                            <label>Description</label>
                            <textarea id="od_service_desc" onkeypress="return (event.charCode > 64 && 
	event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode ==32)" name="desc" class="ckeditor" rows="10" data-sample-short><?php echo $od_servicees['desc'] ?></textarea>
                            <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group col-md-12">

                            <button class="btn btn-primary mt-27 ">Submit</button>
                        </div>


                    </div>


                </div>
            </form>



        </div>
    </div>
<?php } ?>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#upload").click(function() {
            var regRexAlpha = /^[a-zA-Z/ -]+$/; //only for alphabet and space

            if ($("#name").val() == "") {
                // alert("Enter the First Name");
                $('#name1').html('Enter the Language Name').css('color', 'red');
                $("#name").focus();
                return false;
            } else if (!regRexAlpha.test($("#name").val())) {
                $("#name").val('');
                $("#name").focus();
                $('#name1').html('Alphabates Only').css('color', 'red');
                return false;

            } else {
                $('#name1').html('');
            }

            if ($("#desc").val() == "") {
                // alert("Enter the First Name");
                $('#desc1').html('Enter the Description').css('color', 'red');
                $("#desc").focus();
                return false;
            } else {
                $('#desc1').html('');
            }


            if ($("#languages").val() == "") {
                // alert("Enter the First Name");
                $('#languages1').html('Enter the language').css('color', 'red');
                $("#languages").focus();
                return false;
            } else if (!regRexAlpha.test($("#languages").val())) {
                $("#languages").val('');
                $("#languages").focus();
                $('#languages1').html('Alphabates Only').css('color', 'red');
                return false;

            } else {
                $('#languages1').html('');
            }




        });
    });
</script>