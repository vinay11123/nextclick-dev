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
                                <form method="post" id="product_update" action="<?php echo base_url('vendor_crm/catalogue/catalogue_update') ?>">
                                    <div class="col-xl-12 col-md-12">
                                        <?php foreach ($catalogue_products as $key => $catalogue_product) { ?>
                                            <div class="card">
                                                <?php if ($key == 0) { ?>
                                                    <div class="card-header bg-dark">
                                                        <h5><i class="feather icon-home"></i> Edit Product</h5>
                                                        <span class="fa-pull-right"><a class="text-white" href="<?php echo base_url('vendor_crm/catalogue/catalogue_list'); ?>">Back</a></span>
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
                                                            <textarea type="text" readonly class="form-control" name="desc" id="desc"><?php echo $catalogue_product['desc'] ?></textarea>
                                                        </div>

                                                        <input type="hidden" value="<?php echo $catalogue_product['id'] ?>" name="item_id[]" id="item_id">
                                                        <?php
                                                        $product_variant_sql = "select * from food_sec_item where item_id=" . $catalogue_product['id'];
                                                        $query = $this->db->query($product_variant_sql);
                                                        $product_variants = $query->result_array();
                                                        foreach ($product_variants as $key1 => $product_variant) { ?>

                                                            <div class="col-md-12 mt-4">
                                                                <h5><?php echo $product_variant['name'] ?></h5>
                                                            </div>

                                                            <input type="hidden" value="<?php echo $product_variant['id'] ?>" name="variations[<?php echo $key; ?>][variation_id][]" id="variation_id">

                                                            <div class="col-md-1 mt-2">
                                                                <label>Weight</label>
                                                                <input type="text" class="form-control" name="weight" id="weight" value="<?php echo $product_variant['weight'] ?>" readonly>
                                                            </div>

                                                            <div class="col-md-1 mt-2">
                                                                <label>Act Price</label>
                                                                <input type="text" class="form-control number_class" name="variations[<?php echo $key; ?>][price][]" id="variations_price_<?php echo $key; ?>_<?php echo $key1; ?>" value="<?php echo $product_variant['price'] ?>" oninput="priceUpdate('variations_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                            </div>
                                                            <div class=" col-md-1 mt-2">
                                                                <label>Discount(%)</label>
                                                                <input type="text" class="form-control number_class js_number" maxlength="2" name="variations[<?php echo $key; ?>][discount][]" id="variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>" placeholder="0.00" oninput="discountPriceUpdate('variations_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>','variations_discount_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                            </div>
                                                            <div class="col-md-1 mt-2">
                                                                <label>Stock</label>
                                                                <input type="text" class="form-control number_point_class" name="variations[<?php echo $key; ?>][stock][]" id="variations_stock_<?php echo $key; ?>_<?php echo $key1; ?>" placeholder="0">
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>Selling Price</label>
                                                                <input type="text" readonly class="form-control number_class" name="variations[<?php echo $key; ?>][s_price][]" id="variations_s_price_<?php echo $key; ?>_<?php echo $key1; ?>" value="<?php echo $product_variant['price'] ?>.00">
                                                            </div>

                                                            <div class="col-md-2 mt-2">
                                                                <label>Tax Type</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][tax_type_id][]" id="variations_tax_type_<?php echo $key; ?>_<?php echo $key1; ?>" onchange="tax_type(this.value,'variations_tax_id_<?php echo $key; ?>_<?php echo $key1; ?>')">
                                                                    <option value="" selected disabled>--Tax Type--</option>
                                                                    <?php foreach ($tax_types as $tax_type) { ?>
                                                                        <option value="<?php echo $tax_type['id'] ?>"><?php echo $tax_type['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>Taxes</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][tax_id][]" id="variations_tax_id_<?php echo $key; ?>_<?php echo $key1; ?>">
                                                                    <option value="" selected>--Taxes--</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>Status</label>
                                                                <select class="form-control" name="variations[<?php echo $key; ?>][status][]" id="variations_status_<?php echo $key; ?>_<?php echo $key1; ?>">
                                                                    <option value="" disabled>--Status--</option>
                                                                    <option value="1" selected>Active</option>
                                                                    <option value="2">In Active</option>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <div class="card">
                                            <button type="submit" class="btn btn-danger">Update</button>
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
        $('#' + s_price_id).val(discount_price);
    }

    function discountPriceUpdate(price_id, s_price_id, discount_id) {
        var price_val = parseFloat($('#' + price_id).val());
        var discount = $('#' + discount_id).val();

        var discount_price = parseFloat(price_val - (price_val * (discount / 100))).toFixed(2);
        $('#' + s_price_id).val(discount_price);
    }
</script>

<?php $this->load->view('vendorCrm/footer'); ?>