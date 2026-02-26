<style>
    .btn-orange {
        background-color: orange;
        border-color: orange;
    }
</style>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->
        <nav class="pcoded-navbar">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navigation</div>
                    <?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin') { ?>
                        <a class="btn btn-sm btn-orange ml-3 text-center" href="<?php echo base_url() ?>dashboard"
                            style="color: #333; font-weight: bold;">Go to Old Dashboard</a>


                    <?php } ?>
                    <ul class="pcoded-item pcoded-left-item">

                        <?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'vendor') { ?>
                            <li
                                class="<?php echo (!empty($nav_type) && $nav_type == 'dashboard') ? "pcoded-trigger active" : ""; ?>">
                                <a href="<?php echo base_url('vendor_crm/dashboard'); ?>" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                    <span class="pcoded-mtext">Dashboard</span>
                                </a>

                            </li>

                            <li
                                class="<?php echo (!empty($nav_type) && $nav_type == 'catalogue') ? "pcoded-trigger active" : ""; ?>">
                                <a href="<?php echo base_url('vendor_crm/catalogue/catalogue_upload'); ?>"
                                    class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                    <span class="pcoded-mtext">Bulk Catalogue</span>
                                </a>

                            </li>
                            <?php
                            if (!$this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) {
                                $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $this->ion_auth->get_user_id() . ";")->result_array()[0]['min_stock'];
                            } else {
                                $min_stock = 0;
                            }
                            $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['category_id'];
                            $sub_cat_ids_sql = "SELECT id FROM sub_categories WHERE cat_id=$vendor_cat_id and type=2";
                            $query = $this->db->query($sub_cat_ids_sql);
                            $sub_cat_ids = $query->result_array();
                            $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));

                            $catalogue_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
                        JOIN food_menu fm on fm.id=fi.menu_id
                        JOIN sub_categories sc on sc.id=fi.sub_cat_id
                        JOIN categories c on c.id=sc.cat_id
                        JOIN food_item_images fii on fii.item_id=fi.id
                        WHERE fi.status = 1 and fi.availability = 1 and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null";
                            $query = $this->db->query($catalogue_sql);
                            $catalogue_lists = $query->result_array();
                            $data['inventory_instock_count'] = $this->db->query("select count(*) as inventory_count from vendor_product_variants where stock > " . $min_stock . " and vendor_user_id = " . $this->ion_auth->get_user_id() . " and deleted_at is null;")->result_array()[0]['inventory_count'];
                            $data['inventory_outofstock_count'] = $this->db->query("select count(*) as inventory_count from vendor_product_variants where stock <= " . $min_stock . " and vendor_user_id = " . $this->ion_auth->get_user_id() . " and deleted_at is null;")->result_array()[0]['inventory_count'];
                            $data['pendig_count'] = $this->db->query("select count(*) as pending_count from food_item where status = 3 and created_user_id = " . $this->ion_auth->get_user_id() . " and deleted_at is null;")->result_array()[0]['pending_count'];
                            $data['approved_count'] = $this->db->query("select count(*) as approved_count from food_item where status = 2 and created_user_id = " . $this->ion_auth->get_user_id() . " and deleted_at is null;")->result_array()[0]['approved_count'];
                            ?>
                            <li
                                class="pcoded-hasmenu <?php echo ((!empty($nav_type) && $nav_type == 'my_inventory') || (!empty($nav_type) && $nav_type == 'catalogue_list') || (!empty($nav_type) && $nav_type == 'approved') || (!empty($nav_type) && $nav_type == 'pending')) ? "pcoded-trigger" : ""; ?>">
                                <a href="javascript:void(0)" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="feather icon-list"></i></span>
                                    <span class="pcoded-mtext">Products</span>
                                </a>
                                <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'my_inventory') || (!empty($nav_type) && $nav_type == 'catalogue_list') || (!empty($nav_type) && $nav_type == 'approved') || (!empty($nav_type) && $nav_type == 'pending'))
                                    echo "block";
                                else
                                    echo "none"; ?>">
                                    <li
                                        class="<?php echo (!empty($nav_type) && $nav_type == 'my_inventory') ? "active" : ""; ?>">
                                        <a href="<?php echo base_url('vendor_crm/myInventory/my_inventory'); ?>"
                                            class="waves-effect waves-dark">
                                            <span class="pcoded-mtext">My Inventory (
                                                <?php echo $data['inventory_instock_count'] + $data['inventory_outofstock_count'] ?>
                                                )
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="<?php echo (!empty($nav_type) && $nav_type == 'catalogue_list') ? "active" : ""; ?>">
                                        <a href="<?php echo base_url('vendor_crm/catalogue/catalogue_list'); ?>"
                                            class="waves-effect waves-dark">
                                            <span class="pcoded-mtext">Catalogue (
                                                <?php echo count($catalogue_lists) ?> )
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="<?php echo (!empty($nav_type) && $nav_type == 'approved') ? "active" : ""; ?>">
                                        <a href="<?php echo base_url('vendor_crm/myInventory/approved_list'); ?>"
                                            class="waves-effect waves-dark">
                                            <span class="pcoded-mtext">Approved (
                                                <?php echo $data['approved_count'] ?> )
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="<?php echo (!empty($nav_type) && $nav_type == 'pending') ? "active" : ""; ?>">
                                        <a href="<?php echo base_url('vendor_crm/myInventory/Pending_list'); ?>"
                                            class="waves-effect waves-dark">
                                            <span class="pcoded-mtext">Pending (
                                                <?php echo $data['pendig_count'] ?> )
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li
                                class="<?php echo (!empty($nav_type) && $nav_type == 'vOrders') ? "pcoded-trigger active" : ""; ?>">
                                <a href="<?php echo base_url('vendorOrders'); ?>"
                                    class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                    <span class="pcoded-mtext">Orders</span>
                                </a>

                            </li>


                            <!-- <li>
                            <a href="dispatchers.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                <span class="pcoded-mtext">Dispatcher</span>
                            </a>

                        </li>

                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-list"></i></span>
                                <span class="pcoded-mtext">Restaurants List</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="all-restaurants.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">All Restaurants</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="pending-restaurants.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Pending Restaurants</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="rejected-restaurants.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Rejected Restaurants</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                                <span class="pcoded-mtext">Delivery People</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="all-delivery-people.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">All Delivery People</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="pending-delivery-people.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Pending People</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="rejected-delivery-people.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Rejected People</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="users.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                <span class="pcoded-mtext">Users</span>
                            </a>

                        </li>
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-file"></i></span>
                                <span class="pcoded-mtext">Order Deliveries</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="pending-deliveries.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Pending Deliveries</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="approved-deliveries.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Approved Deliveries</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="process-deliveries" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Process Deliveries</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="ongoing-deliveries.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Ongoing Deliveries</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="completed-deliveries.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Completed Deliveries</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="cancelled-deliveries.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Cancelled Deliveries</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="world-cities.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-map"></i></span>
                                <span class="pcoded-mtext">World Cities</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="cusines.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                <span class="pcoded-mtext">Cusines</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="promocode.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-codepen"></i></span>
                                <span class="pcoded-mtext">Promocode</span>
                            </a>
                        </li>

                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-user-check"></i></span>
                                <span class="pcoded-mtext">Reviews</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="user-reviews.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">User Reviews</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="driver-reviews.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Driver Reviews</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-mail"></i></span>
                                <span class="pcoded-mtext">Earning Reports</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="admin-reports.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Admin Reports</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="restaurant-reports.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Restaurant Reports</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="driver-reports.php" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Driver Reports</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <?php } else if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin') { ?>

                                <li
                                    class="<?php echo (!empty($nav_type) && $nav_type == 'dashboard') ? "pcoded-trigger active" : ""; ?>">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                        <span class="pcoded-mtext">Dashboard</span>
                                    </a>

                                </li>

                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && ($nav_type == 'pickup_earnings' || $nav_type == 'ecom_earnings' || $nav_type == 'day_wise_pickup_earnings' || $nav_type == 'day_wise_ecom_earnings')) ? "pcoded-trigger" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> NC Earnings
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'pickup_earnings') || (!empty($nav_type) && $nav_type == 'ecom_earnings') || (!empty($nav_type) && $nav_type == 'day_wise_pickup_earnings') || (!empty($nav_type) && $nav_type == 'day_wise_ecom_earnings'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'ecom_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('ecom_earnings'); ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Ecom Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'pickup_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('pickup_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Pickup Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'day_wise_pickup_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('day_wise_pickup_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Day Wise Pickup
                                                    Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'day_wise_ecom_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('day_wise_ecom_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Day Wise Ecom
                                                    Earnings</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && ($nav_type == 'delivery_pickup_earnings' || $nav_type == 'delivery_ecom_earnings' || $nav_type == 'day_wise_delivery_pickup_earnings' || $nav_type == 'day_wise_delivery_ecom_earnings')) ? "active" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> DC Earnings
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'delivery_pickup_earnings') || (!empty($nav_type) && $nav_type == 'delivery_ecom_earnings') || (!empty($nav_type) && $nav_type == 'day_wise_delivery_pickup_earnings') || (!empty($nav_type) && $nav_type == 'day_wise_delivery_ecom_earnings'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">


                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'delivery_ecom_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('delivery_ecom_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Ecom Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'delivery_pickup_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('delivery_pickup_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Pickup Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'day_wise_delivery_pickup_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('day_wise_delivery_pickup_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Day Wise Pickup
                                                    Earnings</span>
                                            </a>
                                        </li>
                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'day_wise_delivery_ecom_earnings') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('day_wise_delivery_ecom_earnings'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Day Wise Ecom
                                                    Earnings</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>



                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && ($nav_type == 'pickup_orders') || ($nav_type == 'ecom_orders')) ? "pcoded-trigger" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> Orders
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'pickup_orders') || (!empty($nav_type) && $nav_type == 'ecom_orders'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'pickup_orders') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('ecom_pickup_orders'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Pickup Orders</span>
                                            </a>
                                        </li>

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'ecom_orders') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('ecom_ecom_orders'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Ecom Orders</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>


                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && $nav_type == 'agreements') ? "pcoded-trigger" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> Masters
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'agreements'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'agreements') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('agreements/r'); ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Agreements</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && $nav_type == 'vendor_agreements') ? "pcoded-trigger" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> Reports
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'vendor_agreements'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'vendor_agreements') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('vendor_agreements/r'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Vendor Agreements</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                    class="<?php echo (!empty($nav_type) && $nav_type == 'terms_conditions') ? "pcoded-trigger active" : ""; ?>">
                                    <a href="<?php echo base_url('terms_conditions/r'); ?>" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> Terms & Conditions
                                    </a>
                                </li>

                                <?php
                                $executive_vendors_sql = "SELECT count(id) AS executive_vendors From vendors_list where executive_user_id IS NOT NULL";
                                $query = $this->db->query($executive_vendors_sql);
                                $executive_vendors = $query->result_array();
                                $executive_delivery_sql = "SELECT count(id) AS executive_delivery From delivery_boy_address where executive_user_id IS NOT NULL";
                                $query = $this->db->query($executive_delivery_sql);
                                $executive_delivery = $query->result_array();
                                $executive_users_sql = "SELECT count(id) AS executive_users From users where executive_user_id IS NOT NULL";
                                $query = $this->db->query($executive_users_sql);
                                $executive_users = $query->result_array();

                                $executive_count_sql = "SELECT COUNT(*) AS executive_count FROM users AS e INNER JOIN users_groups AS ug ON e.id = ug.user_id INNER JOIN groups AS g ON ug.group_id = g.id AND g.name = 'executive'";
                                $query_executive = $this->db->query($executive_count_sql);
                                $executive_count = $query_executive->result_array();


                                $wallet_total_sql = "SELECT SUM(executive_referral_amount) AS executive_total FROM executive_earning_view";
                                $query_wallet = $this->db->query($wallet_total_sql);
                                $executive_wallet = $query_wallet->result_array();


                                ?>
                                <li
                                    class="pcoded-hasmenu <?php echo (!empty($nav_type) && $nav_type == 'executives' || $nav_type == 'vendors' || $nav_type == 'delivery_captains' || $nav_type == 'users' || $nav_type == 'wallet') ? "pcoded-trigger" : ""; ?>">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <i data-feather="filter"></i> Executives
                                    </a>

                                    <ul class="pcoded-submenu" style="display:<?php if ((!empty($nav_type) && $nav_type == 'executives' || $nav_type == 'vendors' || $nav_type == 'delivery_captains' || $nav_type == 'users' || $nav_type == 'wallet'))
                                        echo "block";
                                    else
                                        echo "none"; ?>">

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'executives') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('executive_list/executive'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Executive List
                                                    (<?php echo $executive_count[0]['executive_count'] ?>)</span>
                                            </a>
                                        </li>

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'vendors') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('executive_list/vendors'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Vendors
                                                    (<?php echo $executive_vendors[0]['executive_vendors'] ?>)</span>
                                            </a>
                                        </li>

                                        <li
                                            class="<?php echo (!empty($nav_type) && $nav_type == 'delivery_captains') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('executive_list/delivery_captains'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Delivery Captains
                                                    (<?php echo $executive_delivery[0]['executive_delivery'] ?>)</span>
                                            </a>
                                        </li>

                                        <li class="<?php echo (!empty($nav_type) && $nav_type == 'users') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('executive_list/users'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Users
                                                    (<?php echo $executive_users[0]['executive_users'] ?>)</span>
                                            </a>
                                        </li>

                                        <li class="<?php echo (!empty($nav_type) && $nav_type == 'wallet') ? "active" : ""; ?>">
                                            <a href="<?php echo base_url('executive_list/wallet'); ?>"
                                                class="waves-effect waves-dark">
                                                <span class="pcoded-mtext">Wallet
                                                    (<?php echo isset($executive_wallet[0]['executive_total']) && !empty($executive_wallet[0]['executive_total']) ? $executive_wallet[0]['executive_total'] : 0; ?>)</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>