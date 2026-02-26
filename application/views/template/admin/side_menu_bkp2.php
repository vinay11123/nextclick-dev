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
			</div>
		</div>
		<ul class="sidebar-menu">
			<li class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'dashboard') ? "active" : ""; ?>"><a
					href="<?php echo base_url('dashboard'); ?>" class="nav-link "><i
						data-feather="airplay"></i><span>Dashboard</span></a></li>
			<?php if ($this->ion_auth_acl->has_permission('admin')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'category' || $nav_type == 'sub_category' || $nav_type == 'brands' || $nav_type == 'amenity' || $nav_type == 'service' || $nav_type == 'state' || $nav_type == 'district' || $nav_type == 'constituency') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="filter"></i><span>Listing Filters
							Data</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'category') ? "active" : ""; ?>"
									href="<?php echo base_url('category/r'); ?>">Category</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'sub_category') ? "active" : ""; ?>"
									href="<?php echo base_url('sub_category/r'); ?>">Sub Category</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'brands') ? "active" : ""; ?>"
									href="<?php echo base_url('brands/r'); ?>">Brands</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'amenity') ? "active" : ""; ?>"
									href="<?php echo base_url('amenity/r'); ?>">Amenity</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'service') ? "active" : ""; ?>"
									href="<?php echo base_url('service/r'); ?>">Services</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'state') ? "active" : ""; ?>"
									href="<?php echo base_url('state/r'); ?>">States</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'district') ? "active" : ""; ?>"
									href="<?php echo base_url('district/r'); ?>">Districts</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('list_master')): ?>
							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'constituency') ? "active" : ""; ?>"
									href="<?php echo base_url('constituency/r'); ?>">Constituency</a></li>
						<?php endif; ?>
					</ul>
				</li>
			<?php endif; ?>

			<?php if ($this->ion_auth_acl->has_permission('emp') || $this->ion_auth_acl->has_permission('hr')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'executive' || $nav_type == 'vendors_filter' || $nav_type == 'role' || $nav_type == 'employee' || $nav_type == 'details_by_vendor' || $nav_type == 'delivery_partners') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>Users</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('emp')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'employee') ? "active" : ""; ?>"
									href="<?php echo base_url('employee/r/0'); ?>">All Users</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')): ?>
							<li><a class="nav-link   <?php echo (!empty($nav_type) && $nav_type == 'vendors_filter') ? "active" : ""; ?>"
									href="<?php echo base_url('vendors_filter/0'); ?>"><span>Vendors</span></a></li>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'delivery_partners') ? "active" : ""; ?>"
									href="<?php echo base_url('delivery_partner/r/0') ?>">Delivery partners</a></li>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'details_by_vendor') ? "active" : ""; ?>"
									href="<?php echo base_url('details_by_vendor/r/0') ?>">Details By Vendor</a></li>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'executive') ? "active" : ""; ?>"
									href="<?php echo base_url('emp_list/executive') ?>">Executives</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('emp')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'role') ? "active" : ""; ?>"
									href="<?php echo base_url('role/r'); ?>">Roles</a></li>
						<?php endif; ?>

					</ul>
				</li>
			<?php endif; ?>


			<?php if ($this->ion_auth_acl->has_permission('emp') || $this->ion_auth_acl->has_permission('hr')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'Delivery' || $nav_type == 'Delivery Area' || $nav_type == 'employee' || $nav_type == 'details_by_vendor') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="users"></i><span>Delivery</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('emp')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'Delivery') ? "active" : ""; ?>"
									href="<?php echo base_url('vehicle/r/0'); ?>">Vehicle Type</a></li>
						<?php endif; ?>

						<?php if ($this->ion_auth_acl->has_permission('emp')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'Delivery Area') ? "active" : ""; ?>"
									href="<?php echo base_url('delivery_area/r/0'); ?>">Delivery Area</a></li>
						<?php endif; ?>
					</ul>
				</li>
			<?php endif; ?>

			<!-- Food Module Start -->
			<?php if ($this->ion_auth_acl->has_permission('food')):
				$cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
				$vendor_category_id = 4; //$cat_id['category_id'];
				?>
				<?php if ($this->ion_auth->is_admin()): ?>
					<li
						class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'products_approve' || $nav_type == 'Products' || $nav_type == 'food_order' || $nav_type == 'sec_item' || $nav_type == 'section' || $nav_type == 'food_menu' || $nav_type == 'shop_by_category' || $nav_type == 'shop_by_category_approve') ? "active" : ""; ?>">
						<a href="#" class="nav-link has-dropdown"><i data-feather="shopping-cart"></i><span>Ecommerce</span></a>
						<ul class="dropdown-menu">
							<?php if (!$this->ion_auth->is_admin()): ?>
								<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'shop_by_category') ? "active" : ""; ?>"
										href="<?php echo base_url('shop_by_categories/r'); ?>">
										<?= ($this->ion_auth->is_admin()) ? 'Shop by Category' : 'Shop by Category'; ?>
									</a></li>
							<?php endif; ?>
							<!--  <li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'shop_by_category_approve') ? "active" : ""; ?>" href="<?= base_url('shop_by_category_approve/r'); ?>"><span>Vendor Shop by categories</span></a></li>-->
							<?php if ($this->ion_auth_acl->has_permission('food_menu')): ?>
								<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'food_menu') ? "active" : ""; ?>"
										href="<?php echo base_url('food_menu/r'); ?>">
										<?= (($this->ion_auth->is_admin()) ? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_nav_label')); ?>
									</a>
								</li>
							<?php endif; ?>
							<?php if ($this->ion_auth_acl->has_permission('food_items')): ?>
								<!-- <li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'products') ? "active" : ""; ?>" href="<?php echo base_url('products/0'); ?>"><?= (($this->ion_auth->is_admin()) ? 'Products' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'item_nav_label')); ?></a></li>-->

								<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'Products') ? "active" : ""; ?>"
										href="<?= base_url('food_product/0/r'); ?>"><span>Products</span></a></li>


								<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'products_approve') ? "active" : ""; ?>"
										href="<?= base_url('products_approve/0/r'); ?>"><span>Vendor Products</span></a></li>
							<?php endif; ?>




							<?php //if($this->ion_auth_acl->has_permission('food_extra_sections')):  ?>
							<!-- <li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'section') ? "active" : ""; ?>" href="<?php echo base_url('sections/0'); ?>"><?= (($this->ion_auth->is_admin()) ? 'Extra Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'sec_nav_label')); ?></a></li>-->
							<? php// endif;  ?>
							<?php //if($this->ion_auth_acl->has_permission('food_section_items')):  ?>
							<!--<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'sec_item') ? "active" : ""; ?>" href="<?php echo base_url('section_items/0'); ?>"><?= (($this->ion_auth->is_admin()) ? 'Section Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'seci_nav_label')); ?></a></li>-->
							<?php //endif;  ?>
							<?php if ($this->ion_auth_acl->has_permission('food_orders')): ?>
								<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'food_order') ? "active" : ""; ?>"
										href="<?php echo base_url('food_orders/r/0'); ?>">
										<?= (($this->ion_auth->is_admin()) ? 'Orders' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_nav_label')); ?>
									</a>
								</li>
							<?php endif; ?>

						</ul>

					<li
						class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'Transactions' || $nav_type == 'Create Transactions' || $nav_type == 'Wallet Refunds') ? "active" : ""; ?>">
						<a href="#" class="nav-link has-dropdown"><i data-feather="tv"></i><span>Payment</span></a>
						<ul class="dropdown-menu">

							<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'Transactions') ? "active" : ""; ?>"
									href="<?php echo base_url('payment/wallet_transactions/list/0

                                      '); ?>">Payment Settlement</a></li>

							<!--  <li><a class="nav-link <?php //echo (! empty($nav_type) && $nav_type == 'Wallet Refunds')? "active" : "";  ?>" href="<?php //echo base_url('payment/wallet_refunds/list/0');  ?>">Refunds</a></li>-->
						</ul>
					</li>



				<?php else: ?>
					<?php if (!$this->ion_auth->is_admin()): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'shop_by_category') ? "active" : ""; ?>"
								href="<?php echo base_url('shop_by_categories/r'); ?>"><i data-feather="grid"
									class="metismenu-state-icon"></i><span>
									<?= ($this->ion_auth->is_admin()) ? 'Shop by Category' : 'Shop by Category'; ?></a></li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('food_menu')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'food_menu') ? "active" : ""; ?>"
								href="<?php echo base_url('food_menu/r'); ?>"><i data-feather="server"
									class="metismenu-state-icon"></i><span>
									<?= (($this->ion_auth->is_admin()) ? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'menu_nav_label')); ?>
								</span></a>
						</li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('food_items')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'products') ? "active" : ""; ?>"
								href="<?php echo base_url('products/0'); ?>"><i data-feather="shopping-bag"
									class="metismenu-state-icon"></i><span>
									<?= (($this->ion_auth->is_admin()) ? 'Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'item_nav_label')); ?>
								</span></a>
						</li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('food_extra_sections')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'section') ? "active" : ""; ?>"
								href="<?php echo base_url('sections/0'); ?>"><i data-feather="layers"
									class="metismenu-state-icon"></i><span>
									<?= (($this->ion_auth->is_admin()) ? 'Extra Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'sec_nav_label')); ?>
								</span></a>
						</li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('food_section_items')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'sec_item') ? "active" : ""; ?>"
								href="<?php echo base_url('section_items/0'); ?>"><i data-feather="package"
									class="metismenu-state-icon"></i><span>
									<?= (($this->ion_auth->is_admin()) ? 'Section Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'seci_nav_label')); ?>
								</span></a>
						</li>
					<?php endif; ?>
					<?php if ($this->ion_auth_acl->has_permission('food_orders')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'food_order') ? "active" : ""; ?>"
								href="<?php echo base_url('food_orders/r'); ?>"><i data-feather="truck"
									class="metismenu-state-icon"></i><span>
									<?= (($this->ion_auth->is_admin()) ? 'Orders' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_nav_label')); ?>
								</span></a>
						</li>
					<?php endif; ?>
				<?php endif; endif; ?>
			<?php if ($this->ion_auth_acl->has_permission('lead_management') && !$this->ion_auth->is_admin()): ?>
				<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'constituency') ? "active" : ""; ?>"
						href="<?php echo base_url('lead_management/r'); ?>"><i data-feather="inbox"
							class="metismenu-state-icon"></i><span>Leads Management</span></a></li>
			<?php endif; ?>


			<!--Promotions Implementation Starts-->
			<?php if ($this->ion_auth_acl->has_permission('admin')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'promotion_codes' || $nav_type == 'promotion_banners' || $nav_type == 'promotion_codes') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="heart"></i><span>Promotions</span></a>
					<ul class="dropdown-menu">
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'promotion_banners') ? "active" : ""; ?>"
								href="<?php echo base_url('promotion_banners/r'); ?>">Promotion banners</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'promotion_codes') ? "active" : ""; ?>"
								href="<?php echo base_url('promotion_codes/r'); ?>">Promotion codes</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'banner_images') ? "active" : ""; ?>"
								href="<?php echo base_url('banner_images/r'); ?>">Banner Images</a></li>
					</ul>
				</li>
			<?php endif; ?>
			<!--Promotions Implementation End-->


			<!--Subscriptions Implementation Starts-->
			<?php if ($this->ion_auth_acl->has_permission('admin')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'subscriptions_packages' || $nav_type == 'vendor_packages' || $nav_type == 'subscriptions_packages') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i
							data-feather="dollar-sign"></i><span>Subscriptions</span></a>
					<ul class="dropdown-menu">
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'subscriptions_packages') ? "active" : ""; ?>"
								href="<?php echo base_url('subscriptions_packages/r'); ?>">Subscription Packages</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'vendor_packages') ? "active" : ""; ?>"
								href="<?php echo base_url('vendor_packages/r'); ?>">Vendor Packages</a></li>
					</ul>
				</li>
			<?php endif; ?>
			<!--Subscriptions Implementation End-->

			<!--Doctors Implementation Starts-->
			<?php if ($this->ion_auth_acl->has_permission('admin')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'specialty' || $nav_type == 'vendor_doctors' || $nav_type == 'doctor' || $nav_type == 'doctors_booking') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="heart"></i><span>Doctors</span></a>
					<ul class="dropdown-menu">
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'specialty') ? "active" : ""; ?>"
								href="<?php echo base_url('specialities/r'); ?>">Specialties</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'doctor') ? "active" : ""; ?>"
								href="<?php echo base_url('doctors/r'); ?>">Doctors</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'vendor_doctors') ? "active" : ""; ?>"
								href="<?php echo base_url('doctors_approve/r'); ?>">Vendor Doctors</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'doctors_booking') ? "active" : ""; ?>"
								href="<?php echo base_url('admin/master/bookings/r/0?service_id=11') ?>">Bookings</a></li>
					</ul>
				</li>
			<?php endif; ?>
			<!--Doctors Implementation End-->

			<!--On Demand Services Implementation Starts-->
			<?php if ($this->ion_auth_acl->has_permission('admin')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'od_service_booking' || $nav_type == 'od_service' || $nav_type == 'od_category' || $nav_type == 'vendor_od_services') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="anchor"></i><span>On Demand
							Services</span></a>
					<ul class="dropdown-menu">
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'od_category') ? "active" : ""; ?>"
								href="<?php echo base_url('od_categories/r'); ?>">On Demand Categories</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'od_service') ? "active" : ""; ?>"
								href="<?php echo base_url('od_services/r'); ?>">On Demand Services</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'vendor_od_services') ? "active" : ""; ?>"
								href="<?php echo base_url('od_categories_approve/r'); ?>">Vendor On Demand Services</a></li>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'od_service_booking') ? "active" : ""; ?>"
								href="<?php echo base_url('admin/master/bookings/r/0?service_id=8'); ?>">Bookings</a></li>
					</ul>
				</li>
			<?php endif; ?>
			<!--On Demand Services Implementation End-->


			<?php if ($this->ion_auth_acl->has_permission('news')): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'news_categories' || $nav_type == 'news' || $nav_type == 'local_news') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="tv"></i><span>SMTV</span></a>
					<ul class="dropdown-menu">
						<?php if ($this->ion_auth_acl->has_permission('news_categories')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'news_categories') ? "active" : ""; ?>"
									href="<?php echo base_url('news_categories/r'); ?>">News Categories</a></li>
						<?php endif; ?>
						<?php if ($this->ion_auth_acl->has_permission('manage_news')): ?>
							<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'news') ? "active" : ""; ?>"
									href="<?php echo base_url('news/r'); ?>">News</a></li>
						<?php endif; ?>
						<li><a class="nav-link  <?php echo (!empty($nav_type) && $nav_type == 'local_news') ? "active" : ""; ?>"
								href="<?php echo base_url('local_news/r'); ?>">Local News</a></li>
					</ul>
				</li>
			<?php endif; ?>

			<?php if ($this->ion_auth->is_admin()): ?>
				<li
					class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'request' || $nav_type == 'support') ? "active" : ""; ?>">
					<a href="#" class="nav-link has-dropdown"><i data-feather="message-square"></i><span>Support</span></a>
					<ul class="dropdown-menu">
						<!-- <li><a class="nav-link   <?php //echo (! empty($nav_type) && $nav_type == 'request')? "active" : "";  ?>" href="<?php //echo base_url('request/r');  ?>">Query Types List</a></li>-->
						<li><a class="nav-link   <?php echo (!empty($nav_type) && $nav_type == 'support') ? "active" : ""; ?>"
								href="<?= base_url('general/support/support_queries/r/0'); ?>"><span>Queries</span></a></li>
					</ul>
				</li>
			<?php else: ?>
				<li calss="<?php echo (!empty($nav_type) && $nav_type == 'support') ? "active" : ""; ?>"><a
						href="<?= base_url('support/c'); ?>"><i data-feather="message-square"
							class="metismenu-state-icon"></i><span>Support</span></a></li>
			<?php endif; ?>

			<?php if ($this->ion_auth->is_admin()): ?>
				<li class="<?php echo (!empty($nav_type) && $nav_type == 'termsconditions') ? "active" : ""; ?>"><a
						href="<?php echo base_url('termsconditions/r'); ?>"><i
							data-feather="book-open"></i><span>Terms&Conditions</span></a>
				</li>
			<?php endif; ?>

			<?php if ($this->ion_auth->is_admin()): ?>
				<li class="<?php echo (!empty($nav_type) && $nav_type == 'faq') ? "active" : ""; ?>"><a
						href="<?php echo base_url('faq/r'); ?>"><i data-feather="book-open"></i><span>FAQ's</span></a>
				</li>
			<?php else: ?>
				<li class="<?php echo (!empty($nav_type) && $nav_type == 'faq') ? "active" : ""; ?>"><a
						href="<?= base_url('vendor_faq/r'); ?>"><i data-feather="book-open"
							class="metismenu-state-icon"></i><span>FAQ's</span></a></li>
			<?php endif; ?>

			<?php if (!($this->ion_auth->is_admin())): ?>
				<li class="<?php echo (!empty($nav_type) && $nav_type == 'terms') ? "active" : ""; ?>"><a
						href="<?= base_url('terms/r'); ?>"><i data-feather="file-text"
							class="metismenu-state-icon"></i><span>Terms & Conditions</span></a></li>
			<?php endif; ?>
			<li
				class="dropdown <?php echo (!empty($nav_type) && $nav_type == 'food_settings' || $nav_type == 'vendor_settings' || $nav_type == 'settings' || $nav_type == 'category_banner' || $nav_type == 'sliders') ? "active" : ""; ?>">
				<a href="#" class="nav-link has-dropdown"><i data-feather="settings"></i><span>Settings</span></a>
				<ul class="dropdown-menu">
					<?php if ($this->ion_auth_acl->has_permission('slider_settings')): ?>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'sliders') ? "active" : ""; ?>"
								href="<?php echo base_url('sliders/r'); ?>">App Home Sliders</a></li>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'category_banner') ? "active" : ""; ?>"
								href="<?php echo base_url('category_banner/r'); ?>">App Category Banner</a></li>
						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'settings') ? "active" : ""; ?>"
								href="<?php echo base_url('settings/r'); ?>">General Settings</a></li>

						<li><a class="nav-link <?php echo (!empty($nav_type) && $nav_type == 'stock_settings') ? "active" : ""; ?>"
								href="<?php echo base_url('stock_settings/r/0'); ?>">Stock Settings</a></li>

					<?php endif; ?>
					<?php /*if($this->ion_auth_acl->has_permission('food')):?>
															<li><a class="nav-link  <?php echo (! empty($nav_type) && $nav_type == 'vendor_settings')? "active" : "";?>" href="<?php echo base_url('vendor_settings/r');?>">Vendor Settings</a></li>
														<?php endif;?>
														 <?php if($this->ion_auth_acl->has_permission('food_settings')):?>
															<li><a class="nav-link <?php echo (! empty($nav_type) && $nav_type == 'food_settings')? "active" : "";?>" href="<?php echo base_url('food_settings/r');?>">Order Settings</a></li>
														<?php endif;?>

														<?php if($this->ion_auth_acl->has_permission('food_settings')):?>
															<li><a class="nav-link <?php echo (! empty($nav_type) && $nav_type == 'food_settings')? "active" : "";?>" href="<?php echo base_url('delivery_area/r/0');?>">rrrrr</a></li>
														<?php endif; */ ?>


				</ul>
			</li>
		</ul>
	</aside>
</div>