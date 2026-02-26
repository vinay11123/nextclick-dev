<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Products</li>
                                <li class="breadcrumb-item">Edit Product</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="pcoded-inner-content">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">

                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">
                                <form method="post" id="product_update" action="<?php echo base_url('vendor_crm/myInventory/stock_update') ?>">
                                    <div class="col-xl-12 col-md-12">
                                        <?php foreach ($catalogue_products as $key => $catalogue_product) { ?>
                                            <div class="card">
                                                <?php if ($key == 0) { ?>
                                                    <div class="card-header bg-dark">
                                                        <h5><i class="feather icon-home"></i> Edit Product</h5>
                                                        <span class="fa-pull-right"><a class="text-white" href="<?php echo base_url('vendor_crm/myInventory/my_inventory'); ?>">Back</a></span>
                                                    </div>
                                                <?php } ?>
                                                <div class="card-block">


                                                    <div class="row">

                                                        <div class="col-md-12 mt-2">
                                                            <label class="col-md-12 mt-2">Product Image<span class="text-danger">*</span></label>
                                                            <!-- <img src="<?php echo base_url() ?>vendor_crm/files/assets/images/light-box/l4.jpg" width="180" height="auto" alt="" class="mt-2" /> -->
                                                            <img src="<?php echo base_url() ?>uploads/food_item_image/food_item_<?php echo $catalogue_product['image_id'] ?>.<?php echo $catalogue_product['image_ext']; ?>" width="180" height="auto" alt="" class="mt-2" />
                                                        </div>


                                                        <div class="col-md-6 mt-2">
                                                            <label>Name<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="product_name" id="product_name" value="<?php echo $catalogue_product['name'] ?>" placeholder="XXX Soap" readonly>
                                                        </div>



                                                        <div class="col-md-6 mt-2">
                                                            <label>Shop by Category<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="sub_cat_name" id="sub_cat_name" value="<?php echo $catalogue_product['sub_cat_name'] ?>" placeholder="XXX Soap" readonly>
                                                        </div>


                                                        <div class="col-md-6 mt-2">
                                                            <label>Brands<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="brand_name" id="brand_name" value="<?php echo $catalogue_product['brand_name'] ?>" placeholder="XXX Soap" readonly>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label>Menu<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="menu_name" id="menu_name" value="<?php echo $catalogue_product['menu_name'] ?>" placeholder="XXX Soap" readonly>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label>Product Status<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="availability" id="availability" value="<?php if ($catalogue_product['availability'] == 1) echo "Available";
                                                                                                                                                    elseif ($catalogue_product['availability'] == 2) echo "Not-Available"; ?>" placeholder=" XXX Soap" readonly>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label>Type<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="item_type" id="item_type" value="<?php if ($catalogue_product['item_type'] == 1) echo "Veg";
                                                                                                                                            elseif ($catalogue_product['item_type'] == 2) echo "Non-Veg";
                                                                                                                                            else echo "Others" ?>" placeholder="XXX Soap" readonly>
                                                        </div>

                                                        <div class="col-md-12 mt-2">
                                                            <label>Description<span class="text-danger">*</span></label>
                                                            <textarea type="text" class="form-control" readonly name="desc" id="desc"><?php echo $catalogue_product['desc'] ?></textarea>
                                                        </div>

                                                        <input type="hidden" value="<?php echo $catalogue_product['id'] ?>" name="item_id[]" id="item_id">
                                                        <?php
                                                        $product_variant_sql = "select fsi.id,vpv.vendor_user_id,fsi.name variant_name,fsi.weight variant_weight,fsi.price variant_selling_price,vpv.price vendor_price,vpv.discount variant_discount,vpv.stock,vpv.tax_id,tt.id tax_type_id,vpv.status from food_sec_item fsi
                                                        JOIN vendor_product_variants vpv on vpv.section_item_id=fsi.id
                                                        LEFT JOIN taxes t on t.id=vpv.tax_id
                                                        LEFT JOIN tax_types tt on tt.id=t.type_id
                                                        where vpv.item_id=" . $catalogue_product['id'] . " and vpv.vendor_user_id=" . $this->ion_auth->get_user_id();
                                                        $query_result = $this->db->query($product_variant_sql);
                                                        $product_variants_array = $query_result->result_array();
                                                        foreach ($product_variants_array as $key1 => $product_variant_array) { ?>

                                                            <div class="col-md-12 mt-4">
                                                                <h5><?php echo $product_variant_array['variant_name'] ?></h5>
                                                            </div>
                                                            <input type="hidden" value="<?php echo $product_variant_array['id'] ?>" name="variations[<?php echo $key; ?>][variation_id][]" id="variation_id">


                                                            <div class="col-md-1 mt-2">
                                                                <label>Weight</label>
                                                                <input type="text" class="form-control" name="weight" id="weight" value="<?php echo $product_variant_array['variant_weight'] ?>" readonly>
                                                            </div>

                                                            <div class="col-md-1 mt-2">
                                                                <label>Act Price</label>
                                                                <input type="text" class="form-control number_class" name="variations[<?php echo $key; ?>][price][]" id="variations_price_<?php echo $key; ?>_<?php echo $key1; ?>" value="<?php if ($product_variant_array['vendor_price'] != '') echo $product_variant_array['vendor_price'];
                                                                                                                                                                                                                                            else echo number_format((float)$product_variant_array['variant_selling_price'], 2, '.', ''); ?>" oninput="priceUpdate('variations_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                            </div>

                                                            <div class="col-md-1 mt-2">
                                                                <label>Discount(%)</label>
                                                                <input type="text" class="form-control number_class js_number" maxlength="2" name="variations[<?php echo $key; ?>][discount][]" id="variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>" placeholder="0.00" value="<?php echo $product_variant_array['variant_discount'] ?>" oninput="discountPriceUpdate('variations_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                            </div>

                                                            <div class="col-md-1 mt-2">
                                                                <label>Stock</label>
                                                                <input type="text" class="form-control number_point_class" name="variations[<?php echo $key; ?>][stock][]" id="stock" placeholder="0" value="<?php echo $product_variant_array['stock'] ?>">
                                                            </div>

                                                            <div class="col-md-2 mt-2">
                                                                <label>Selling Price</label>
                                                                <input type="text" class="form-control number_class" readonly name="variations[<?php echo $key; ?>][s_price][]" id="variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>" value="<?php if ($product_variant_array['vendor_price'] != '' && $product_variant_array['variant_discount'] != '') echo round(($product_variant_array['vendor_price'] - ($product_variant_array['vendor_price'] * ($product_variant_array['variant_discount'] / 100))), 2);
                                                                                                                                                                                                                                                        else if ($product_variant_array['vendor_price'] != '' && $product_variant_array['variant_discount'] == '') echo $product_variant_array['vendor_price'];
                                                                                                                                                                                                                                                        else echo number_format((float)$product_variant_array['variant_selling_price'], 2, '.', ''); ?>" oninput="priceUpdate('variations_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                            </div>

                                                            <div class="col-md-2 mt-2">
                                                                <label>Tax Types</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][tax_type_id][]" id="variations_tax_type_<?php echo $key; ?>_<?php echo $key1; ?>" onchange="tax_type(this.value,'variations_tax_id_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                                    <option value="" selected disabled>--Taxes--</option>
                                                                    <?php foreach ($tax_types as $tax_type) { ?>
                                                                        <option value="<?php echo $tax_type['id'] ?>" <?php if ($product_variant_array['tax_type_id'] == $tax_type['id']) echo "selected"; ?>><?php echo $tax_type['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>Taxes</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][tax_id][]" id="variations_tax_id_<?php echo $key; ?>_<?php echo $key1; ?>">
                                                                    <option value="" selected>--Taxes--</option>
                                                                    <?php if ($product_variant_array['tax_id'] != '' && $product_variant_array['tax_type_id'] != '') { ?>
                                                                        <?php foreach ($taxes as $tax) { ?>
                                                                            <option value="<?php echo $tax['id'] ?>" <?php if ($product_variant_array['tax_id'] == $tax['id']) echo "selected"; ?>><?php echo $tax['tax'] ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>Status</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][status][]" id="variations_status_<?php echo $key; ?>_<?php echo $key1; ?>">
                                                                    <option value="" disabled>--Status--</option>
                                                                    <option value="1" <?php if ($product_variant_array['status'] == 1) echo "selected"; ?>>Active</option>
                                                                    <option value="2" <?php if ($product_variant_array['status'] == 2) echo "selected"; ?>>In Active</option>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <input type="hidden" name="key_value_<?php echo $key ?>" id="key_value_<?php echo $key ?>" value="<?php echo $key ?>">
                                                    <input type="hidden" name="new_variant_<?php echo $key ?>" id="new_variant_<?php echo $key ?>" value="0">
                                                    <div class="col-md-12 text-right mt-2">
                                                        <button class="btn-danger p-1" type="button" onclick="addNewVariant('new_variant_<?php echo $key ?>','variant_add_div_<?php echo $key ?>','key_value_<?php echo $key ?>','new_variant_item_id_<?php echo $key ?>')"><i class="feather icon-plus"></i></button>
                                                    </div>

                                                    <div class="row bg-grey p-2 mt-2" id="variant_add_div_<?php echo $key ?>">
                                                        <input type="hidden" value="<?php echo $catalogue_product['id'] ?>" name="new_variant_item_id_<?php echo $key ?>" id="new_variant_item_id_<?php echo $key ?>">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <div class="card">
                                            <button type="submit" class="btn btn-danger" id="button_update">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Page-body end -->
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<script>
    function addNewVariant(new_variant_id, variant_div_id, key_value, item_id) {
        var key_val = $('#' + key_value).val();
        var item_id_val = $('#' + item_id).val();
        var input_val = $('#' + new_variant_id).val();
        var input_val_inc = ++input_val;
        $('#' + new_variant_id).val(input_val_inc)
        $("#button_update").attr("disabled", true);
        document.querySelector('#' + variant_div_id).insertAdjacentHTML(
            'beforeend',
            `<div class="row" id="new_variant` + key_val + `_` + input_val + `">
                 <div class="col-md-2 mb-3">
                     <label>Name</label>
                     <input type="text" class="form-control" name="new_variant_name_` + key_val + `[]" id="new_variant_name_` + key_val + `_` + input_val + `" placeholder="Variant Name" oninput="variant_duplicate_check_function('new_variant_name_` + key_val + `_` + input_val + `','new_variant_weight_` + key_val + `_` + input_val + `','new_variant_price_` + key_val + `_` + input_val + `','new_variant_stock_` + key_val + `_` + input_val + `','` + item_id_val + `','new_variant_ptag_` + key_val + `_` + input_val + `','new_variant_discount_` + key_val + `_` + input_val + `','new_variant_s_price_` + key_val + `_` + input_val + `')">
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Weight</label>
                     <input type="text" class="form-control number_class" name="new_variant_weight_` + key_val + `[]" id="new_variant_weight_` + key_val + `_` + input_val + `" placeholder="0.00" oninput="variant_duplicate_check_function('new_variant_name_` + key_val + `_` + input_val + `','new_variant_weight_` + key_val + `_` + input_val + `','new_variant_price_` + key_val + `_` + input_val + `','new_variant_stock_` + key_val + `_` + input_val + `','` + item_id_val + `','new_variant_ptag_` + key_val + `_` + input_val + `','new_variant_discount_` + key_val + `_` + input_val + `','new_variant_s_price_` + key_val + `_` + input_val + `')">
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Act Price</label>
                     <input type="text" class="form-control number_class" name="new_variant_price_` + key_val + `[]" id="new_variant_price_` + key_val + `_` + input_val + `" placeholder="0.00" oninput="variant_duplicate_check_function('new_variant_name_` + key_val + `_` + input_val + `','new_variant_weight_` + key_val + `_` + input_val + `','new_variant_price_` + key_val + `_` + input_val + `','new_variant_stock_` + key_val + `_` + input_val + `','` + item_id_val + `','new_variant_ptag_` + key_val + `_` + input_val + `','new_variant_discount_` + key_val + `_` + input_val + `','new_variant_s_price_` + key_val + `_` + input_val + `')">
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Discount(%)</label>
                     <input type="text" class="form-control number_class" name="new_variant_discount_` + key_val + `[]" id="new_variant_discount_` + key_val + `_` + input_val + `" placeholder="0.00" oninput="variant_duplicate_check_function('new_variant_name_` + key_val + `_` + input_val + `','new_variant_weight_` + key_val + `_` + input_val + `','new_variant_price_` + key_val + `_` + input_val + `','new_variant_stock_` + key_val + `_` + input_val + `','` + item_id_val + `','new_variant_ptag_` + key_val + `_` + input_val + `','new_variant_discount_` + key_val + `_` + input_val + `','new_variant_s_price_` + key_val + `_` + input_val + `')">
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Stock</label>
                     <input type="text" class="form-control number_point_class" name="new_variant_stock_` + key_val + `[]" id="new_variant_stock_` + key_val + `_` + input_val + `" placeholder="0" oninput="variant_duplicate_check_function('new_variant_name_` + key_val + `_` + input_val + `','new_variant_weight_` + key_val + `_` + input_val + `','new_variant_price_` + key_val + `_` + input_val + `','new_variant_stock_` + key_val + `_` + input_val + `','` + item_id_val + `','new_variant_ptag_` + key_val + `_` + input_val + `','new_variant_discount_` + key_val + `_` + input_val + `','new_variant_s_price_` + key_val + `_` + input_val + `')">
                 </div>
                 <div class="col-md-2 mb-3">
                     <label>Selling Price</label>
                     <input type="text" class="form-control number_class" readonly name="new_variant_s_price_` + key_val + `[]" id="new_variant_s_price_` + key_val + `_` + input_val + `" placeholder="0.00" onchange="">
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Tax Types</label>
                     <select class="form-control" name="new_variant_tax_type_id_` + key_val + `[]" id="new_variant_tax_type_id_` + key_val + `_` + input_val + `" onchange="tax_type(this.value,'new_variant_tax_id_` + key_val + `_` + input_val + `')">
                         <option value="" selected>--Tax Type--</option>
                         <?php foreach ($tax_types as $tax_type) { ?>
                             <option value="<?php echo $tax_type['id'] ?>"><?php echo $tax_type['name'] ?></option>
                         <?php } ?>
                     </select>
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Taxes</label>
                     <select class="form-control" name="new_variant_tax_id_` + key_val + `[]" id="new_variant_tax_id_` + key_val + `_` + input_val + `">
                         <option value="" selected>--Taxes--</option>
                     </select>
                 </div>
                 <div class="col-md-1 mb-3">
                     <label>Status</label>
                     <select class="form-control" name="new_variant_status_` + key_val + `[]" id="new_variant_status_` + key_val + `_` + input_val + `">
                         <option value="">--Status--</option>
                         <option value="1" selected>Active</option>
                         <option value="2">In Active</option>
                     </select>
                 </div>
                 <div class="col-md-1 mb-3 pt-4 text-center" style="margin-top: 10px;">
                 <button class="btn-danger" type="button" onclick="removeRow('new_variant` + key_val + `_` + input_val + `','` + new_variant_id + `','new_variant_ptag_` + key_val + `_` + input_val + `')"><i class="feather icon-minus"></i></button>
                 </div>
             </div>
             <p id="new_variant_ptag_` + key_val + `_` + input_val + `" style="color:red;display:none;" ></p>
             `
        )
    }

    function removeRow(div_id, new_variant_count_id, ptag_id) {
        var input_val = $('#' + new_variant_count_id).val();
        var input_val_inc = (input_val - 1);
        $('#' + new_variant_count_id).val(input_val_inc)
        document.getElementById(div_id).remove();
        document.getElementById(ptag_id).remove();
    }

    function variant_duplicate_check_function(variant_name_id, variant_weight_id, variant_price_id, variant_stock_id, item_id, ptag_id, discount_id, s_price_id) {
        $('#' + ptag_id).html('Please fill these variant name,weight,price and stock all fileds')
        $('#' + ptag_id).css('display', 'block');
        $("#button_update").attr("disabled", true);
        var variant_name = $('#' + variant_name_id).val();
        var variant_weight = $('#' + variant_weight_id).val();
        var variant_price = $('#' + variant_price_id).val();
        var variant_stock = $('#' + variant_stock_id).val();
        var variant_discount = $('#' + discount_id).val();

        if (variant_name != '' && variant_weight != '' && variant_price != '' && variant_stock != '') {
            $("#button_update").removeAttr('disabled');
            $('#' + ptag_id).css('display', 'none');
        }

        if (variant_name != '') {
            $.ajax({
                type: 'POST',
                url: "<?PHP echo base_url('vendor_crm/myInventory/duplicate_check_variant_name'); ?>",
                dataType: 'json',
                data: {
                    variant_name: variant_name,
                    item_id: item_id,

                },
                success: function(data) {
                    if (data > 0) {
                        $('#' + ptag_id).html('This variant name is already exist');
                        $('#' + ptag_id).css('display', 'block');
                        $("#button_update").attr("disabled", true);
                    }
                }
            });
        }
        if (variant_price != '') {
            var discount_price = parseFloat(variant_price - (variant_price * (variant_discount / 100))).toFixed(2);
            $('#' + s_price_id).val(discount_price)
        }
        if (variant_discount != '') {
            var discount_price = parseFloat(variant_price - (variant_price * (variant_discount / 100))).toFixed(2);
            $('#' + s_price_id).val(discount_price)
        }

    }
    $(".number_class").keypress(function(e) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $(".number_point_class").keypress(function(e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
    });
    $('.js_number').unbind('keyup change input paste').bind('keyup change input paste', function(e) {
        var $this = $(this);
        var val = $this.val();
        var valLength = val.length;
        var maxCount = $this.attr('maxlength');
        if (valLength > maxCount) {
            $this.val($this.val().substring(0, maxCount));
        }
    });

    function tax_type(tax_type, tax_id) {
        $.ajax({
            url: '<?php echo base_url('vendor_crm/catalogue/tax_type') ?>',
            type: 'post',
            data: {
                tax_type: tax_type
            },
            dataType: 'json',
            success: function(data) {
                if (data == 1) {
                    data = null;
                }
                var options = '<option value="" selected>--Taxes--</option>';
                if (data) {
                    for (var i = 0; i < data[0].length; i++) {
                        options += '<option value="' + data[0][i].id + '">' + data[0][i].tax + '</option>'
                    }
                }
                document.getElementById(tax_id).innerHTML = options;
            }
        })
    }

    function priceUpdate(price_id, s_price_id, discount_id) {
        var price_val = $('#' + price_id).val();
        var discount = $('#' + discount_id).val();

        var discount_price = parseFloat(price_val - (price_val * (discount / 100))).toFixed(2);
        $('#' + s_price_id).val(discount_price)
    }

    function discountPriceUpdate(price_id, s_price_id, discount_id) {
        var price_val = parseFloat($('#' + price_id).val());
        var discount = $('#' + discount_id).val();

        var discount_price = parseFloat(price_val - (price_val * (discount / 100))).toFixed(2);
        $('#' + s_price_id).val(discount_price);
    }
</script>

<?php $this->load->view('vendorCrm/footer'); ?>