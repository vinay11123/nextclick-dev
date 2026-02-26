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
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('vendor_crm/myInventory/Pending_list'); ?>">Pending List</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo base_url('vendor_crm/myInventory/new_product'); ?>" class="btn btn-success float-right">Request New Product</a>
                        </div>
                        <div class="col-md-12">
                            <?php if (!empty($this->session->flashdata('success_status'))) { ?>
                                <div class="alert alert-success">
                                    <h5><?php echo $this->session->flashdata('success_status'); ?></h5>
                                </div>
                            <?php } elseif (!empty($this->session->flashdata('delete_status'))) { ?>
                                <div class="alert alert-danger">
                                    <h5><?php echo $this->session->flashdata('delete_status'); ?></h5>
                                </div>
                            <?php } ?>
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
                                        <div class="card-block">
                                            <form method="post" id="form_search" action="<?php echo base_url('vendor_crm/myInventory/Pending_list') ?>">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input class="form-control" name="search" id="search" placeholder="Search" value="<?php if ($_POST['search'] != '') echo $_POST['search']; ?>">
                                                    </div>

                                                    <div class="col-md-1 text-center">
                                                        or
                                                    </div>

                                                    <div class="col-md-3">
                                                        <select name="sub_cat_id" id="sub_cat_id" class="form-control" onchange="shop_by_category_changed1(this.value)">
                                                            <option value="" selected disabled>Shop by Category</option>
                                                            <?php foreach ($sub_categories as $key_sc => $sub_categorie) { ?>
                                                                <option value="<?php echo $sub_categorie['id']; ?>" <?php if ($_POST['sub_cat_id'] == $sub_categorie['id']) {
                                                                                                                        echo "selected";
                                                                                                                    } ?>><?php echo $sub_categorie['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="menu_id" id="menu_id" class="form-control">
                                                            <option value="" selected disabled>Select Menu</option>
                                                            <?php if ($_POST['sub_cat_id']) { ?>
                                                                <option value="all" <?php if ($_POST['menu_id'] == 'all') {
                                                                                        echo "selected";
                                                                                    } ?>>All</option>
                                                                <?php
                                                                $menu1 = $this->food_menu_model->where('sub_cat_id', $_POST['sub_cat_id'])->get_all();
                                                                foreach ($menu1 as $key_m => $menu) {
                                                                ?>
                                                                    <option value="<?php echo $menu['id']; ?>" <?php if ($_POST['menu_id'] == $menu['id']) {
                                                                                                                    echo "selected";
                                                                                                                } ?>><?php echo $menu['name']; ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header text-dark bg-dark">
                                            <h5><i class="feather icon-home"></i> Pending (<?php echo count($pending_lists) ?>)</h5>
                                        </div>
                                        <div class="card-block border-bottom-info" style="margin-top: 30px;">

                                            <div class="row">
                                                <?php foreach ($pending_lists as $key => $pending_list) { ?>
                                                    <div class="col-xl-4 col-md-12">
                                                        <div class="card coin-price-card table-card">
                                                            <div class="coin-title bg-c-blue">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <h6 class="text-white m-b-0"><?php echo $pending_list['name']; ?></h6>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="card-block p-b-0">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <img src="<?php echo base_url() ?>uploads/food_item_image/food_item_<?php echo $pending_list['image_id'] ?>.<?php echo $pending_list['image_ext']; ?>" width="100" height="auto" alt="" />
                                                                    </div>

                                                                    <div class="col-md-8">
                                                                        <p class="mb-0">Category: <?php echo $pending_list['sub_cat_name'] ?></p>
                                                                        <a href="#" onclick="delete_record('<?php echo $pending_list['id'] ?>')" class="btn btn-success btn-sm mb-2"> Delete </a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
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
    function shop_by_category_changed1(sub_cat_id) {
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
                var options = '<option value="" selected disabled>Select Menu</option><option value="all">All</option>';
                if (data) {
                    for (var i = 0; i < data[0].length; i++) {
                        options += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>'
                    }
                }
                document.getElementById("menu_id").innerHTML = options;
            }
        })
    }

    function delete_record(id) {
        if (confirm('Do you want to delete..?')) {

            $.ajax({

                url: '<?php echo base_url('vendor_crm/myInventory/deleteproduct') ?>',
                type: 'post',
                data: {
                    id: id
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    }
</script>
<?php $this->load->view('vendorCrm/footer'); ?>