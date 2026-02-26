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
                                    <a href="index.php">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('vendor_crm/myInventory/my_inventory'); ?>">Products</a></li>
                                <li class="breadcrumb-item">Request New Product</li>
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

                                <div class="col-xl-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-dark">
                                            <h5><i class="feather icon-home"></i> Request New Product</h5>
                                            <span class="fa-pull-right"><a href="<?php echo base_url('vendor_crm/myInventory/my_inventory'); ?>" class="text-white">Back</a></span>
                                        </div>
                                        <form method="post" id="form_search" enctype="multipart/form-data" action="<?php echo base_url('vendor_crm/myInventory/new_product_add') ?>">
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-md-12 mt-2">
                                                        <label>Product Image<span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" name="image" id="image" required accept="image/jpeg, image/png">
                                                        <img src="#" width="180" height="auto" alt="" class="mt-2" id="img" />
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label>Product Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="Product Name" name="product_name" id="product_name" required oninput="duplicate_check()">
                                                        <p id="error_name" style="color:red;"></p>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label>Shop by Category<span class="text-danger">*</span></label>
                                                        <select name="sub_cat_id" id="sub_cat_id" class="form-control" onchange="menu_get(this.value)" required>
                                                            <option value="" selected disabled>--Category Name--</option>
                                                            <?php foreach ($sub_categories as $sub_categorie) { ?>
                                                                <option value="<?php echo $sub_categorie['id'] ?>"><?php echo $sub_categorie['name'] ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label>Menu<span class="text-danger">*</span></label>
                                                        <select name="menu_id" id="menu_id" class="form-control" required onchange="duplicate_check()">
                                                            <option value="" selected disabled>--Menu--</option>
                                                        </select>

                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label>Brands<span class="text-danger">*</span></label>
                                                        <select name="brand_id" id="brand_id" class="form-control" required onchange="duplicate_check()">
                                                            <option value="" selected disabled>--Brand--</option>
                                                        </select>

                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <label>Description<span class="text-danger">*</span></label>
                                                        <textarea type="text" class="form-control" name="desc" id="desc" required></textarea>
                                                    </div>
                                                    <div class="col-md-12 mt-4">
                                                        <h5>Options</h5>
                                                    </div>
                                                    <div class="col-md-4 mt-2">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" oninput="duplicate_variant()" name="variant_name[]" id="variant_name_0" required>
                                                    </div>
                                                    <div class="col-md-4 mt-2">
                                                        <label>Weight</label>
                                                        <input type="text" class="form-control" name="variant_weight[]" id="variant_weight" required>
                                                    </div>
                                                    <div class="col-md-2 mt-2">
                                                        <label>Price</label>
                                                        <input type="text" class="form-control" name="variant_price[]" id="variant_price" required>
                                                    </div>
                                                    <div class="col-md-2 mt-3">
                                                        <a class="btn btn-primary mt-3" onclick="addNewVariant()">Add Varient</a>
                                                    </div>
                                                    <div class="col-md-12 mt-3" id="error_message_0" style="color: red;"></div>
                                                    <div class="col-md-12 mt-3" id="variant_add_div">
                                                        <input type="hidden" name="new_index" id="new_index" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-danger" id="submit" name="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    $(function() {
        $('#image').change(function() {
            var input = this;
            var url = $(this).val();
            console.log(input.value);
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    });

    function menu_get(sub_cat_id) {
        $.ajax({
            url: '<?php echo base_url('vendor_crm/catalogue/menu_by_category') ?>',
            type: 'post',
            data: {
                sub_cat_id: sub_cat_id
            },
            dataType: 'json',
            success: function(data) {
                if (data == 1) {
                    data = null;
                }
                var options = '<option value="" selected disabled>--Menu--</option>';
                if (data) {
                    for (var i = 0; i < data[0].length; i++) {
                        options += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>'
                    }
                }
                document.getElementById("menu_id").innerHTML = options;

                var options1 = '<option value="0" selected disabled>--Brand--</option>';
                for (var i = 0; i < data[1].length; i++) {
                    options1 += '<option value="' + data[1][i].id + '">' + data[1][i].name + '</option>'
                }
                document.getElementById("brand_id").innerHTML = options1;
            }
        })
    }

    function addNewVariant() {
        var input_val = $('#new_index').val();
        var input_val_inc = ++input_val;
        $('#new_index').val(input_val_inc);
        document.querySelector('#variant_add_div').insertAdjacentHTML(
            'beforeend',
            `<div class="row" id="new_variant` + input_val + `">
            <div class="col-md-4 mt-2">
                 <label>Name</label>
                 <input type="text" class="form-control" name="variant_name[]" oninput="duplicate_variant()" id="variant_name_` + input_val + `" required>
             </div>
             <div class="col-md-4 mt-2">
                 <label>Weight</label>
                 <input type="text" class="form-control" name="variant_weight[]" id="variant_weight` + input_val + `" required>
             </div>
             <div class="col-md-2 mt-2">
                 <label>Price</label>
                 <input type="text" class="form-control" name="variant_price[]" id="variant_price` + input_val + `" required>
             </div>
             <div class="col-md-2 mt-3">
                 <button class="btn btn-danger mt-3" onclick="removeNewVariant('new_variant` + input_val + `')">Remove Varient</a>
             </div>
             <div class="col-md-12 mt-3" id="error_message_` + input_val + `" style="color: red;"></div>
             `
        )
    }

    function removeNewVariant(div_id) {
        var input_val = $('#new_index').val();
        var input_val_inc = (input_val - 1);
        $('#new_index').val(input_val_inc);
        document.getElementById(div_id).remove();
    }

    function duplicate_variant() {
        var val_input = $('#new_index').val();
        let myarray = [];
        for (i = 0; i <= val_input; i++) {
            document.getElementById("error_message_" + i).innerHTML = "";
            myarray[i] = document.getElementById("variant_name_" + i).value;
        }
        for (i = 0; i <= val_input; i++) {
            let flag = false;
            $("#submit").removeAttr('disabled');
            for (j = 0; j <= val_input; j++) {
                if (i == j || myarray[i] == "" || myarray[j] == "")
                    continue;
                if (myarray[i] == myarray[j]) {
                    flag = true;
                    $("#submit").attr("disabled", true);
                    document.getElementById("error_message_" + i).innerHTML += "Its identical to the variant name " + (j + 1);
                }
            }
            if (flag == false)
                document.getElementById("error_message_" + i).innerHTML = "";
        }
    }

    function duplicate_check() {
        var product_name = $('#product_name').val();
        var sub_cat_id = $('#sub_cat_id').val();
        var menu_id = $('#menu_id').val();
        var brand_id = $('#brand_id').val();
        $("#submit").removeAttr('disabled');
        document.getElementById("error_name").innerHTML = "";

        if (product_name != '' && sub_cat_id != '' && menu_id != '' && brand_id != '') {
            $.ajax({
                url: '<?php echo base_url('vendor_crm/myInventory/duplucate_check') ?>',
                type: 'post',
                data: {
                    product_name: product_name,
                    sub_cat_id: sub_cat_id,
                    menu_id: menu_id,
                    brand_id: brand_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data > 0) {
                        $("#submit").attr("disabled", true);
                        document.getElementById("error_name").innerHTML = "Product already exist";

                    }

                }
            })
        }
    }
</script>
<?php $this->load->view('vendorCrm/footer'); ?>