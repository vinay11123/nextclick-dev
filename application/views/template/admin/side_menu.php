<?php error_reporting(E_ERROR | E_PARSE); ?>

<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="<?php echo base_url() ?>dashboard"> <img alt="image"
					src="<?php echo base_url() ?>assets/img/logo.png" style="width: 75%" />
				<!--<span class="logo-name">Aegis</span>-->
			</a>
		</div>
		<div class="sidebar-user">
			<div class="sidebar-user-picture">

			</div>
			<div class="sidebar-user-details">
				<div class="user-name">
					<?php echo $user->email; ?>
				</div>
				<div class="user-role">
					<?php echo (!$this->ion_auth->is_admin()) ? $user->unique_id : $user->first_name . ' ' . $user->last_name;
					; ?>
				</div>
				<div>
					<?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin') { ?>
						<a class="btn btn-primary" href="<?php echo base_url() ?>vendor_crm/dashboard">Go to New
							Dashboard</a>
					<?php } ?>
				</div>
			</div>
		</div>
		<ul class="sidebar-menu">
			<li class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'dashboard') ? "active" : ""; ?>"><a
					href="<?php echo base_url('dashboard'); ?>" class="nav-link "><i
						data-feather="airplay"></i><span>Dashboard</span></a></li>
			<?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin') { ?>
				<li class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'catalogue') ? "active" : ""; ?>"><a
						href="<?php echo base_url('catalogue/catalogue_upload'); ?>" class="nav-link "><i
							data-feather="airplay"></i><span>Bulk Catalogue</span></a></li>
			<?php } ?>

			<!-- <?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin') { ?>
				<li
					class="nav-item dropdown <?php echo (!empty ($nav_type) && ($nav_type == 'pickup_earnings' || $nav_type == 'ecom_earnings' || $nav_type == 'day_wise_pickup_earnings' || $nav_type == 'day_wise_ecom_earnings')) ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown">
						<i data-feather="filter"></i> NC Earnings
					</a>
					<ul class="dropdown-menu">
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'ecom_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('ecom_earnings'); ?>">Ecom Earnings</a></li>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'pickup_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('pickup_earnings'); ?>">Pickup Earnings</a></li>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'day_wise_pickup_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('day_wise_pickup_earnings'); ?>">Day Wise Pickup Earnings</a></li>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'day_wise_ecom_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('day_wise_ecom_earnings'); ?>">Day Wise Ecom Earnings</a></li>

					</ul>
				</li>
			<?php } ?>

			<?php if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin'): ?>
				<li
					class="nav-item dropdown <?php echo (!empty ($nav_type) && ($nav_type == 'delivery_pickup_earnings' || $nav_type == 'delivery_ecom_earnings' || $nav_type == 'day_wise_delivery_pickup_earnings' || $nav_type == 'day_wise_delivery_ecom_earnings')) ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown">
						<i data-feather="filter"></i> DC Earnings
					</a>
					<ul class="dropdown-menu">

						<li>
							<a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'delivery_ecom_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('delivery_ecom_earnings'); ?>">
								Ecom Earnings
							</a>
						</li>
						<li>
							<a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'delivery_pickup_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('delivery_pickup_earnings'); ?>">
								Pickup Earnings
							</a>
						</li>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'day_wise_delivery_pickup_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('day_wise_delivery_pickup_earnings'); ?>">Day Wise Pickup
								Earnings</a></li>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'day_wise_delivery_ecom_earnings') ? "active" : ""; ?>"
								href="<?php echo base_url('day_wise_delivery_ecom_earnings'); ?>">Day Wise Ecom Earnings</a>
						</li>

					</ul>
				</li>
			<?php endif; ?>
 -->


			<?php if (
				$this->ion_auth_acl->has_permission('category_view')
				|| $this->ion_auth_acl->has_permission('subcategory_view')
				|| $this->ion_auth_acl->has_permission('brand_view')
				|| $this->ion_auth_acl->has_permission('amenity_view')
				|| $this->ion_auth_acl->has_permission('service_view')
				|| $this->ion_auth_acl->has_permission('state_view')
				|| $this->ion_auth_acl->has_permission('district_view')
				|| $this->ion_auth_acl->has_permission('constituency_view')
			): ?>

				<li
					class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'category' || $nav_type == 'sub_category' || $nav_type == 'sub_category_upload' || $nav_type == 'brands' || $nav_type == 'amenity' || $nav_type == 'service' || $nav_type == 'state' || $nav_type == 'district' || $nav_type == 'constituency') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="filter"></i><span>Listing Filters
							Data</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('category_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'category') ? "active" : ""; ?>"
									href="<?php echo base_url('category/r'); ?>">Category</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('subcategory_view') || $this->ion_auth_acl->has_permission('sub_category_upload')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'sub_category') ? "active" : ""; ?>"
									href="<?php echo base_url('sub_category/r/0'); ?>">Sub Category</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('brand_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'brands') ? "active" : ""; ?>"
									href="<?php echo base_url('brands/r/0'); ?>">Brands</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('amenity_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'amenity') ? "active" : ""; ?>"
									href="<?php echo base_url('amenity/r'); ?>">Amenity</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('service_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'service') ? "active" : ""; ?>"
									href="<?php echo base_url('service/r'); ?>">Services</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('state_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'state') ? "active" : ""; ?>"
									href="<?php echo base_url('state/r'); ?>">States</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('district_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'district') ? "active" : ""; ?>"
									href="<?php echo base_url('district/r'); ?>">Districts</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('constituency_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'constituency') ? "active" : ""; ?>"
									href="<?php echo base_url('constituency/r'); ?>">Constituency</a></li>
						<?php endif; ?>
					</ul>
				</li>

			<?php endif; ?>

			<?php
			if (
				$this->ion_auth_acl->has_permission('all_users_view')
				|| $this->ion_auth_acl->has_permission('vendor_view')
				|| $this->ion_auth_acl->has_permission('delivery_partner_view')
				|| $this->ion_auth_acl->has_permission('executive_view')
				|| $this->ion_auth_acl->has_permission('role_view')
			): ?>
				<li
					class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'executive' || $nav_type == 'vendors_filter' || $nav_type == 'role' || $nav_type == 'employee' || $nav_type == 'details_by_vendor' || $nav_type == 'delivery_partners') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>Users</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('all_users_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'employee') ? "active" : ""; ?>"
									href="<?php echo base_url('employee/r/0'); ?>">All Users</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('vendor_view')): ?>
							<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'vendors_filter') ? "active" : ""; ?>"
									href="<?php echo base_url('vendors_filter/0'); ?>"><span>Vendors</span></a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('delivery_partner_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'delivery_partners') ? "active" : ""; ?>"
									href="<?php echo base_url('delivery_partner/r/0') ?>">Delivery Partners</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('constituency_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'details_by_vendor') ? "active" : ""; ?>"
									href="<?php echo base_url('details_by_vendor/r/0') ?>">Details By Vendor</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('executive_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'executive') ? "active" : ""; ?>"
									href="<?php echo base_url('emp_list/executive') ?>">Executives</a></li>
										<li class="<?php echo (!empty ($nav_type) && $nav_type == 'exc_role') ? "active" : ""; ?>"><a
					href="<?php echo base_url('admin/exc_role/r'); ?>"><span>Marketing App</span></a>
			</li>
						<?php endif; ?>
						
						<?php if ($this->ion_auth_acl->has_permission('role_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'role') ? "active" : ""; ?>"
									href="<?php echo base_url('role/r'); ?>">Roles</a></li>
						<?php endif; ?>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'vendor_payout') ? "active" : ""; ?>"
								href="<?php echo base_url('vendor/payouts'); ?>">Vendor Payouts</a></li>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'delivery_partner_payout') ? "active" : ""; ?>"
								href="<?php echo base_url('admin/delivery_partner/payouts'); ?>">Delivery Partner
								Payouts</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			
        	<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'appusers' || $nav_type == 'appvendors' ) ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>APP Downloaded Users</span></a>
				<ul class="dropdown-menu">
					
				<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'appusers') ? "active" : ""; ?>"
						href="<?php echo base_url('allusers'); ?>">All App Users</a></li>

				<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'appvendors') ? "active" : ""; ?>"
						href="<?php echo base_url('allorders'); ?>"><span>All Orders</span></a></li>

					</li>

				</ul>
			</li>

			<?php if (
				$this->ion_auth_acl->has_permission('vehicle_view')
				|| $this->ion_auth_acl->has_permission('delivery_area_view')
			): ?>
				<li
					class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'Delivery' || $nav_type == 'Delivery Area' || $nav_type == 'shift_filter' || $nav_type == 'delivery_insentive' || $nav_type == 'pending_insentives') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>Delivery</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('vehicle_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'Delivery') ? "active" : ""; ?>"
									href="<?php echo base_url('vehicle/r/0'); ?>">Vehicle Type</a></li>
						<?php endif; ?>

						<?php if ($this->ion_auth_acl->has_permission('delivery_area_view')): ?>
							<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'Delivery Area') ? "active" : ""; ?>"
									href="<?php echo base_url('delivery_area/r/0'); ?>">Delivery Area</a></li>
						<?php endif; ?>

						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'shift_filter') ? "active" : ""; ?>"
								href="<?php echo base_url('shift'); ?>">Manage Shifts</a></li>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'delivery_insentive') ? "active" : ""; ?>"
								href="<?php echo base_url('delivery_insentive'); ?>">Incentive Config</a></li>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'pending_insentives') ? "active" : ""; ?>"
								href="<?php echo base_url('delivery_insentive/pending'); ?>">Pending Incentives</a></li>
					</ul>
				</li>
			<?php endif; ?>
			<!-- Food Module Start -->
			<?php
			if (
				$this->ion_auth_acl->has_permission('menu_view')
				|| $this->ion_auth_acl->has_permission('product_view')
				|| $this->ion_auth_acl->has_permission('order_veiw')
			): ?>


				<?php if ($this->ion_auth->is_admin()): ?>
					<li
						class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'inventory' || $nav_type == 'Products' || $nav_type == 'pickup_orders' || $nav_type == 'food_order' || $nav_type == 'sec_item' || $nav_type == 'section' || $nav_type == 'food_menu' || $nav_type == 'shop_by_category' || $nav_type == 'shop_by_category_approve' || $nav_type == 'product_upload' || $nav_type == 'rejected_order_veiw' || $nav_type == 'accepted_orders_list' || $nav_type == 'delivery_boy_wallet_transactions') ? "active" : ""; ?>">
						<a href="#" class="nav-link has-dropdown"><i data-feather="shopping-cart"></i><span>ecomerce</span></a>
						<ul class="dropdown-menu">
							<?php if ($this->ion_auth_acl->has_permission('menu_view')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'food_menu') ? "active" : ""; ?>"
										href="<?php echo base_url('food_menu/r'); ?>">Menus</a></li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('product_view') || $this->ion_auth_acl->has_permission('product_upload')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'Products') ? "active" : ""; ?>"
										href="<?= base_url('food_product/0/r'); ?>"><span>Products</span></a></li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('inventory_view')): ?>
								<!--<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'inventory') ? "active" : ""; ?>" href="<?= base_url('food/food/inventory/r/0'); ?>"><span>Vendor Inventory</span></a></li> -->

								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'inventory') ? "active" : ""; ?>"
										href="<?= base_url('vendor_req_product/0/r'); ?>"><span>Vendor Requested Product</span></a>
								</li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('order_veiw')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'food_order') ? "active" : ""; ?>"
										href="<?php echo base_url('food_orders/r/0'); ?>">Orders</a></li>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'pickup_orders') ? "active" : ""; ?>"
										href="<?php echo base_url('pickup_orders/r/0'); ?>">Pickup Orders</a></li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('order_veiw')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'accepted_orders_list') ? "active" : ""; ?>"
										href="<?php echo base_url('delivery_job_accept_requests'); ?>">Delivery boy accept
										orders</a>
								</li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('order_veiw')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'delivery_boy_wallet_transactions') ? "active" : ""; ?>"
										href="<?php echo base_url('delivery_boy_wallet_transactions/r/0'); ?>">Delivery boy
										Transactions</a></li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('order_veiw')): ?>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'rejected_order_veiw') ? "active" : ""; ?>"
										href="<?php echo base_url('delivery_job_rejection_requests'); ?>">Delivery boy rejected
										orders</a></li>
							<?php endif; ?>
						</ul>
					<?php else: ?>
						<?php if ($this->ion_auth_acl->has_permission('product_view')): ?>
							<!--<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'Products') ? "active" : ""; ?>" href="<?= base_url('food_product/0/r'); ?>"><span>My Products</span></a></li> -->
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('inventory_view')):

							$my_product_count = $this->db->query("SELECT * FROM `vendor_product_variants` where status=1 and vendor_user_id=" . $user->id)->num_rows();
							$instock = $this->db->query("SELECT * FROM `vendor_product_variants` where status=1 and stock>0 and vendor_user_id=" . $user->id)->num_rows();
							$outstock = $this->db->query("SELECT * FROM `vendor_product_variants` where status=1 and stock=0 and vendor_user_id=" . $user->id)->num_rows();

							$catalogue = $this->db->query("SELECT * FROM `food_item` where created_user_id=1")->num_rows();
							$approve = $this->db->query("SELECT * FROM `food_item` where created_user_id=" . $user->id . " and status=2")->num_rows();
							$pending = $this->db->query("SELECT * FROM `food_item` where created_user_id=" . $user->id . " and status=3")->num_rows();

							?>
						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><span>My Product (
									<?= $my_product_count; ?>)
								</span></a>
							<ul class="dropdown-menu">
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'inventory') ? "active" : ""; ?>"
										href="<?= base_url('food/food/inventory/r/0'); ?>"><span>Instock(
											<?= $instock; ?>)
										</span></a></li>
								<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'inventory') ? "active" : ""; ?>"
										href="<?= base_url('food/food/inventory/outstock/0'); ?>"><span>Out of Stock(
											<?= $outstock; ?>)
										</span></a></li>
						</li>
					</ul>
				<?php endif; ?>
				<?php if ($user->primary_intent == 'vendor') { ?>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'catalogue') ? "active" : ""; ?>"
							href="<?= base_url('food/food/catalogue/r/0'); ?>"><span>catalogue (
								<?= $catalogue; ?>)
							</span></a></li>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'approved') ? "active" : ""; ?>"
							href="<?= base_url('food/food/approved/r/0'); ?>"><span>Approved (
								<?= $approve; ?>)
							</span></a></li>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'pendingproducts') ? "active" : ""; ?>"
							href="<?= base_url('food/food/pendingproducts/r/0'); ?>"><span>Pending (
								<?= $pending; ?>)
							</span></a></li>
				<?php } ?>
				<?php if ($this->ion_auth_acl->has_permission('order_veiw')): ?>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'food_order') ? "active" : ""; ?>"
							href="<?php echo base_url('food_orders/r/0'); ?>">Orders</a></li>
				<?php endif; ?>
			<?php endif; endif; ?>
		<?php /* if($user->primary_intent=='vendor')
											  { ?>
													  <li><a class="nav-link <?php echo (! empty($nav_type) && $nav_type == 'ongoing_orders')? "active" : "";?>" href="<?php echo base_url('ongoing_orders/r/0');?>">Ongoing Orders</a></li>
													  <li><a class="nav-link <?php echo (! empty($nav_type) && $nav_type == 'pending_orders')? "active" : "";?>" href="<?php echo base_url('pending_orders/r/0');?>">Pending Orders</a></li>
										  <?php }*/
		?>
		<?php if ($this->ion_auth_acl->has_permission('lead_view')): ?>
			<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'constituency') ? "active" : ""; ?>"
					href="<?php echo base_url('lead_management/r'); ?>"><i data-feather="inbox"
						class="metismenu-state-icon"></i><span>Leads Management</span></a></li>
		<?php endif; ?>


		<?php if ($this->ion_auth->is_admin()): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'pickanddropcategories') ? "active" : ""; ?>"><a
					href="<?php echo base_url('pickanddropcategories/r'); ?>"><i data-feather="book-open"></i><span>Pick And
						Drop Categories</span></a>
			</li>
		<?php endif; ?>

		<?php if ($this->ion_auth_acl->has_permission('payment_view')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'Transactions' || $nav_type == 'vendor_gst_reports' || $nav_type == 'delivery_boy_gst_reports' || $nav_type == 'Create Transactions' || $nav_type == 'Wallet Refunds' || $nav_type == 'payment_reports') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="tv"></i><span>Payment</span></a>
				<ul class="dropdown-menu">
					<?php if ($this->ion_auth_acl->has_permission('payment_view')): ?>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'Transactions') ? "active" : ""; ?>"
								href="<?php echo base_url('payment/wallet_transactions/list/0'); ?>">Payment Settlement</a></li>
					<?php endif; ?>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'payment_reports') ? "active" : ""; ?>"
							href="<?php echo base_url('admin_wallet_reports/0'); ?>">Reports</a></li>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'vendor_gst_reports') ? "active" : ""; ?>"
							href="<?php echo base_url('vendor_gst_reports/0'); ?>">Vendor GST Reports</a></li>
					<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'delivery_boy_gst_reports') ? "active" : ""; ?>"
							href="<?php echo base_url('delivery_boy_gst_reports'); ?>">Delivery Boy GST Reports</a></li>
				</ul>
			</li>
		<?php endif; ?>
		<!--Promotions Implementation Starts-->
		<?php if ($this->ion_auth_acl->has_permission('payment_view')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'promotion_codes' || $nav_type == 'promotion_banners' || $nav_type == 'admin_banners' || $nav_type == 'promotion_codes') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="heart"></i><span>Promotions</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'promotion_banners') ? "active" : ""; ?>"
							href="<?php echo base_url('promotion_banners/r/0'); ?>">Promotion (Big Offers)</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'vendor_promotion_banners') ? "active" : ""; ?>"
							href="<?php echo base_url('vendor_promotion_banners/r'); ?>">Vendor Promotion Banners</a></li>
					<!--<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'promotion_codes') ? "active" : ""; ?>" href="<?php echo base_url('promotion_codes/r'); ?>">Promotion codes</a></li>-->
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'banner_images') ? "active" : ""; ?>"
							href="<?php echo base_url('banner_images/r'); ?>">Vendor Banner Images</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'admin_banners') ? "active" : ""; ?>"
							href="<?php echo base_url('admin_banners/r'); ?>">Admin Banners</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'banner_cost') ? "active" : ""; ?>"
							href="<?php echo base_url('banner_cost/r/0'); ?>">Banner Cost</a></li>
				</ul>
			</li>
		<?php endif; ?>
		<!--Promotions Implementation End-->


		<!--Subscriptions Implementation Starts-->
		<?php if ($this->ion_auth->is_admin()): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'subscriptions_packages' || $nav_type == 'manual_payments' || $nav_type == 'vendor_packages' || $nav_type == 'subscriptions_packages') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="dollar-sign"></i><span>Subscriptions</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'subscriptions_packages') ? "active" : ""; ?>"
							href="<?php echo base_url('subscriptions_packages/r'); ?>">Subscription Packages</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'vendor_packages') ? "active" : ""; ?>"
							href="<?php echo base_url('vendor_packages/r'); ?>">Vendor Packages</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'master_package_setting') ? "active" : ""; ?>"
							href="<?php echo base_url('master_package_setting'); ?>">Master Package Settings</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'manual_payments') ? "active" : ""; ?>"
							href="<?php echo base_url('manual_payments_list'); ?>">Manage Payments List</a></li>
				</ul>
			</li>
		<?php endif; ?>
		<!--Subscriptions Implementation End-->


		<!--Returns Implementation Starts-->
		<?php if ($this->ion_auth->is_admin()): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'return_policies') ? "active" : ""; ?>"><a
					href="<?php echo base_url('return_policies/r'); ?>"><i
						data-feather="book-open"></i><span>Returns</span></a>
			</li>
		<?php endif; ?>
		<!--Returns Implementation End-->

		<!--Service Tax Implementation Starts-->
		<?php if ($this->ion_auth->is_admin()): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'service_tax') ? "active" : ""; ?>"><a
					href="<?php echo base_url('service_tax/r'); ?>"><i data-feather="book-open"></i><span>Service
						Charge</span></a>
			</li>
		<?php endif; ?>
		<!--Service Tax Implementation End-->
				<!--Service Tax Implementation Starts-->
		<?php if ($this->ion_auth->is_admin()): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'exc_role') ? "active" : ""; ?>"><a
					href="<?php echo base_url('admin/exc_role/r'); ?>"><i data-feather="book-open"></i><span>Marketing App</span></a>
			</li>
		<?php endif; ?>
		<!--Service Tax Implementation End-->
<li class="<?php echo (!empty($nav_type) && $nav_type == 'exc_cities') ? "active" : ""; ?>">
    <a href="<?= base_url('admin/exc_cities/r'); ?>">
        <i data-feather="map-pin"></i><span>Cities</span>
    </a>
</li>

		<!--Doctors Implementation Starts-->
		<?php if ($this->ion_auth_acl->has_permission('doctors')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'specialty' || $nav_type == 'vendor_doctors' || $nav_type == 'doctor' || $nav_type == 'doctors_booking') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="heart"></i><span>Doctors</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'specialty') ? "active" : ""; ?>"
							href="<?php echo base_url('specialities/r'); ?>">Specialties</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'doctor') ? "active" : ""; ?>"
							href="<?php echo base_url('doctors/r'); ?>">Doctors</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'vendor_doctors') ? "active" : ""; ?>"
							href="<?php echo base_url('doctors_approve/r'); ?>">Vendor Doctors</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'doctors_booking') ? "active" : ""; ?>"
							href="<?php echo base_url('admin/master/bookings/r/0?service_id=11') ?>">Bookings</a></li>
				</ul>
			</li>
		<?php endif; ?>
		<!--Doctors Implementation End-->

		<!--On Demand Services Implementation Starts-->
		<?php if ($this->ion_auth_acl->has_permission('od_services')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'od_service_booking' || $nav_type == 'od_service' || $nav_type == 'od_category' || $nav_type == 'vendor_od_services') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="anchor"></i><span>On Demand Services</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'od_category') ? "active" : ""; ?>"
							href="<?php echo base_url('od_categories/r'); ?>">On Demand Categories</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'od_service') ? "active" : ""; ?>"
							href="<?php echo base_url('od_services/r'); ?>">On Demand Services</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'vendor_od_services') ? "active" : ""; ?>"
							href="<?php echo base_url('od_categories_approve/r'); ?>">Vendor On Demand Services</a></li>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'od_service_booking') ? "active" : ""; ?>"
							href="<?php echo base_url('admin/master/bookings/r/0?service_id=8'); ?>">Bookings</a></li>
				</ul>
			</li>
		<?php endif; ?>
		<!--On Demand Services Implementation End-->

		<?php if ($this->ion_auth_acl->has_permission('news')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'news_categories' || $nav_type == 'news' || $nav_type == 'local_news') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="tv"></i><span>SMTV</span></a>
				<ul class="dropdown-menu">
					<?php if ($this->ion_auth_acl->has_permission('news_categories')): ?>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'news_categories') ? "active" : ""; ?>"
								href="<?php echo base_url('news_categories/r'); ?>">News Categories</a></li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('manage_news')): ?>
						<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'news') ? "active" : ""; ?>"
								href="<?php echo base_url('news/r'); ?>">News</a></li>
					<?php endif; ?>
					<li><a class="nav-link  <?php echo (!empty ($nav_type) && $nav_type == 'local_news') ? "active" : ""; ?>"
							href="<?php echo base_url('local_news/r'); ?>">Local News</a></li>
				</ul>
			</li>
		<?php endif; ?>

		<?php if ($this->ion_auth->is_admin()): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'request' || $nav_type == 'support' || $nav_type == 'customer_support') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="message-square"></i><span>Support</span></a>
				<ul class="dropdown-menu">
					<!-- <li><a class="nav-link   <?php //echo (! empty($nav_type) && $nav_type == 'request')? "active" : "";                  ?>" href="<?php //echo base_url('request/r');                  ?>">Query Types List</a></li>
									  <li><a class="nav-link   <?php echo (!empty ($nav_type) && $nav_type == 'support') ? "active" : ""; ?>" href="<?= base_url('general/support/support_queries/r/0'); ?>"><span>Queries</span></a></li>-->
					<li><a class="nav-link   <?php echo (!empty ($nav_type) && $nav_type == 'customer_support') ? "active" : ""; ?>"
							href="<?= base_url('general/support/customer/r/0'); ?>"><span>Customer Support</span></a></li>
				</ul>
			</li>
		<?php else: ?>
			<?php
			if ($user->primary_intent == 'user' || $user->primary_intent == 'delivery_partner' || $user->primary_intent == 'vendor') {
			} else { ?>
				<li calss="<?php echo (!empty ($nav_type) && $nav_type == 'support') ? "active" : ""; ?>"><a
						href="<?= base_url('general/support/customer/r/0'); ?>"><i data-feather="message-square"
							class="metismenu-state-icon"></i><span>Support</span></a></li>
			<?php } ?>
		<?php endif; ?>

		<?php if ($this->ion_auth->is_admin()): ?>
			<!-- <li class="<?php echo (!empty ($nav_type) && $nav_type == 'termsconditions') ? "active" : ""; ?>"><a
					href="<?php echo base_url('termsconditions/r'); ?>"><i
						data-feather="book-open"></i><span>Terms&Conditions</span></a>
			</li> -->
		<?php endif; ?>

		<?php if ($this->ion_auth->is_admin()): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'faq') ? "active" : ""; ?>"><a
					href="<?php echo base_url('faq/r'); ?>"><i data-feather="book-open"></i><span>FAQ's</span></a>
			</li>
		<?php else: ?>
			<!--<li class="<?php echo (!empty ($nav_type) && $nav_type == 'faq') ? "active" : ""; ?>"><a href="<?= base_url('vendor_faq/r'); ?>"><i data-feather="book-open" class="metismenu-state-icon"></i><span>FAQ's</span></a></li>-->
		<?php endif; ?>

		<?php if (!($this->ion_auth->is_admin())): ?>
			<!--<li class="<?php echo (!empty ($nav_type) && $nav_type == 'terms') ? "active" : ""; ?>"><a href="<?= base_url('terms/r'); ?>"><i data-feather="file-text" class="metismenu-state-icon"></i><span>Terms & Conditions</span></a></li>-->
		<?php endif; ?>
		<?php if ($this->ion_auth_acl->has_permission('settings_general')): ?>
			<li
				class="dropdown <?php echo (!empty ($nav_type) && $nav_type == 'food_settings' || $nav_type == 'vendor_settings' || $nav_type == 'settings' || $nav_type == 'category_banner' || $nav_type == 'sliders') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="settings"></i><span>Settings</span></a>
				<ul class="dropdown-menu">
					<?php if ($this->ion_auth_acl->has_permission('settings_general')): ?>
						<li><a class="nav-link <?php echo (!empty ($nav_type) && $nav_type == 'settings') ? "active" : ""; ?>"
								href="<?php echo base_url('settings/r'); ?>">General Settings</a>

							<br />
							<br />
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>
		<?php if (!($this->ion_auth->is_admin())): ?>
			<li class="<?php echo (!empty ($nav_type) && $nav_type == 'stock_settings') ? "active" : ""; ?>"><a
					href="<?php echo base_url('stock_settings/r/0'); ?>"><span>Stock Setting</span></a>
			</li>
		<?php endif; ?>
		</ul>
	</aside>
</div>