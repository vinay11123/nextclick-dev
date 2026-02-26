<div class="main-sidebar sidebar-style-2">
				<aside id="sidebar-wrapper">
					<div class="sidebar-brand">
						<a href="index.html"> <img alt="image" src="<?php echo base_url()?>assets/img/logo.png" class="header-logo" /> 
                            <!--<span class="logo-name">Aegis</span>-->
						</a>
					</div>
					<div class="sidebar-user">
						<div class="sidebar-user-picture">
							<img alt="image" src="<?php echo base_url()?>assets/img/userbig.png">
						</div>
						<div class="sidebar-user-details">
							<div class="user-name"><?php echo $user->email;?></div>
							<div class="user-role"><?php echo $user->first_name.''.$user->last_name;?></div>
						</div>
					</div>
					<ul class="sidebar-menu">
						<li class="menu-header">Main</li>
						<li class="dropdown active"><a href="<?php echo base_url('dashboard');?>" class="nav-link "><i
									data-feather="monitor"></i><span>Dashboard</span>
							</a>
							
						</li>
						<?php if($this->ion_auth_acl->has_permission('withdrawal')):?>
						<li class="dropdown "><a href="<?php echo base_url('wallet_transactions/list');?>" class="nav-link "><i
									data-feather="monitor"></i><span>Transactions</span>
							</a>
						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('vendor')):?>
						<li class="dropdown "><a href="<?php echo base_url('user_services/r');?>" class="nav-link "><i
									data-feather="monitor"></i><span>Our Services</span>
							</a>
							
						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')):?>
						<li class="dropdown">
                            <a href="#" class="nav-link has-dropdown">
                            <i data-feather="briefcase"></i><span>Vendors</span>
                            </a>
							<ul class="dropdown-menu">
								<li><a class="nav-link" href="<?php echo base_url('vendors/all');?>">All Vendors</a></li>
								<li><a class="nav-link" href="<?php echo base_url('vendors/approved');?>">Approved Vendors</a></li>
                                <li><a class="nav-link" href="<?php echo base_url('vendors/pending');?>">Pending Vendors</a></li>
								<li><a class="nav-link" href="<?php echo base_url('vendors/cancelled');?>">Cancelled Vendors</a></li>
							</ul>
						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('admin')):?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="command"></i><span>Listing Master Data</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('list_master')):?>
        								<li><a class="nav-link" href="<?php echo base_url('category/r');?>">Category</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('sub_category/r');?>">Sub Category</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('amenity/r');?>">Amenity</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('service/r');?>">Services</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('state/r');?>">States</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('district/r');?>">Districts</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('list_master')):?>
    									<li><a class="nav-link" href="<?php echo base_url('constituency/r');?>">Constituency</a></li>
    								<?php endif;?>
    							</ul>
    						</li>
						<?php endif;?>
						<!-- <li class="dropdown"><a href="#" class="nav-link ">
                            <i data-feather="mail"></i><span>All Users</span></a>
				        </li> -->
						<!--<li class="menu-header">UI Elements</li>-->
						<?php if($this->ion_auth_acl->has_permission('emp') || $this->ion_auth_acl->has_permission('hr')):?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>Employees</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('emp')):?>
        								<li><a class="nav-link" href="<?php echo base_url('employee/r');?>">Add Employee</a></li>
        								<li><a class="nav-link" href="<?php echo base_url('role/r');?>">Add Role</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('emp') || $this->ion_auth_acl->has_permission('hr')):?>
    									<li><a class="nav-link" href="<?php echo base_url('emp_list/executive')?>">Executives</a></li>
    								<?php endif;?>
    								<!-- <li><a class="nav-link" href="#">Delivery Boys</a></li>
    								<li><a class="nav-link" href="#">Accountants</a></li>
    								<li><a class="nav-link" href="#">HR's</a></li>
    								<li><a class="nav-link" href="#">Controllers</a></li>
                                    <li><a class="nav-link" href="#">Co-ordinators</a></li>
    								<li><a class="nav-link" href="#">Zonal Heads</a></li>
    								<li><a class="nav-link" href="#">CEO's</a></li>
                                    <li><a class="nav-link" href="#">Managing Directors</a></li> -->
    				            </ul>
    						</li>
						<?php  endif;?>
						<?php if($this->ion_auth_acl->has_permission('news')):?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>SMTV</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('news_categories')):?>
        								<li><a class="nav-link" href="<?php echo base_url('news_categories/r');?>">Manage Categories</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('manage_news')):?>
    									<li><a class="nav-link" href="<?php echo base_url('news/r');?>">Manage News</a></li>
    								<?php endif;?>
    				            </ul>
    						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('ecom')):?>
						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>E-Commerce</span></a>
    							<ul class="dropdown-menu">
    									<?php if($this->ion_auth_acl->has_permission('ecom_category')):?>
        									<li><a class="nav-link" href="<?php echo base_url('ecom_category/r');?>">Categories</a></li>
        								<?php endif;?>
        								<?php if($this->ion_auth_acl->has_permission('ecom_sub_category')):?>
    										<li><a class="nav-link" href="<?php echo base_url('ecom_sub_category/r');?>">Sub Categories</a></li>
    									<?php endif;?>
    									<!-- <li><a class="nav-link" href="<?php echo base_url('ecom_sub_sub_category/r');?>">Sub Sub_Categories</a></li> -->
    									<?php if($this->ion_auth_acl->has_permission('ecom_brand')):?>
    										<li><a class="nav-link" href="<?php echo base_url('ecom_brands/r');?>">Brands</a></li>
    									<?php endif;?>
    									<?php if($this->ion_auth_acl->has_permission('ecom_product')):?>
    										<li><a class="nav-link" href="<?php echo base_url('product/r');?>">Products</a></li>
    									<?php endif;?>
    									<?php if($this->ion_auth_acl->has_permission('ecom_orders')):?>
    										<li><a class="nav-link" href="<?php echo base_url('ecom_orders/r');?>">Orders</a></li>
    									<?php endif;?>
    				            </ul>
    						</li>
    					<?php endif;?>
    					<!-- Hospital -->
    					<?php if($this->ion_auth_acl->has_permission('hospital')):?>
						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>Hospitals</span></a>
    							<ul class="dropdown-menu">
    									<?php if($this->ion_auth_acl->has_permission('hosp_doctor')):?>
        									<li><a class="nav-link" href="<?php echo base_url('hosp_doctors/r');?>">Doctors</a></li>
        								<?php endif;?>
        								<?php if($this->ion_auth_acl->has_permission('doctor_specialisation')):?>
    										<li><a class="nav-link" href="<?php echo base_url('hosp_specialization/r');?>">Specialization</a></li>
    									<?php endif;?>
    				            </ul>
    						</li>
    					<?php endif;?>
    					<!-- Hospital End-->
    					<!-- Hospital -->
    					<?php if($this->ion_auth_acl->has_permission('beauty')):?>
						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>Beauty&Spa</span></a>
    							<ul class="dropdown-menu">
    								<?php if($this->ion_auth_acl->has_permission('beauty_package')):?>
        								<li><a class="nav-link" href="<?php echo base_url('beauty_package/r');?>">Package</a></li>
									<?php endif;?>
        							<?php if($this->ion_auth_acl->has_permission('beauty_orders')):?>
										<li><a class="nav-link" href="<?php echo base_url('beauty_order_list');?>">beauty_order</a></li>
        							<?php endif;?>
    				            </ul>
    						</li>
    					<?php endif;?>
    					<!-- Hospital End-->
                        <!-- Groceries Start -->
                        <?php if($this->ion_auth_acl->has_permission('grocery')):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
                                        data-feather="copy"></i><span>Groceries</span></a>
                                <ul class="dropdown-menu">
                                 	<?php if($this->ion_auth_acl->has_permission('groceries_category')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('grocery_category/r');?>">Categories</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('groceries_sub_category')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('grocery_sub_category/r');?>">Sub Categories</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('groceries_brand')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('grocery_brands/r');?>">Brands</a></li>
                                   	<?php endif;?>
                                   	<?php if($this->ion_auth_acl->has_permission('grocery_product')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('grocery_product/r');?>">Products</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('grocery_orders')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('grocery_orders/r');?>">Orders</a></li>
                                   	<?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <!-- Groceries End -->

                        <!-- Food Module Start -->
                        <?php if($this->ion_auth_acl->has_permission('food')):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="copy"></i><span>Food</span></a>
                                <ul class="dropdown-menu">
                                	<?php if($this->ion_auth_acl->has_permission('food_menu')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_menu/r');?>">Menus</a></li>
                                   	<?php endif;?>
                                   	<?php if($this->ion_auth_acl->has_permission('food_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_item/r');?>">Items</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_extra_sections')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_section/r');?>">Extra Section</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_section_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_section_item/r');?>">Section Items</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_settings')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_settings/r');?>">Food Settings</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('vendor_profile/r');?>">Vendor Profile</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_orders')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_orders/r');?>">Orders</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_reports')):?>
                                        <li><a class="nav-link" href="#">Reports</a></li>
                                    <?php endif;?>
                                    
                                </ul>
                            </li>
                        <?php endif;?>
                        <!-- Food Module End -->
                        <!-- Travel Module Start-->
                        <?php if($this->ion_auth_acl->has_permission('travels')):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="copy"></i><span>Travels</span></a>
                                <ul class="dropdown-menu">
                               		<?php if($this->ion_auth_acl->has_permission('vehicle_brand')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('travel_brands/r');?>">Travel Brands</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('travel_accessory')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('travel_accessories/r');?>">Travel Accessories</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('travel_vehicle')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('travel_vehicles/r');?>">Travel Vehicles</a></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <!-- Travel Module End -->
                        <!-- Home Services Module Start-->
                        <?php if($this->ion_auth_acl->has_permission('home_services')):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="copy"></i><span>Home Services</span></a>
                                <ul class="dropdown-menu">
                               		<?php if($this->ion_auth_acl->has_permission('service_type')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('service_type/r');?>">Home Service Types</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('service_person')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('home_service_users/r');?>">Home Service Users</a></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <?php //if($this->ion_auth_acl->has_permission('home_services')):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="copy"></i><span>HR</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link" href="<?php echo base_url('service_type/r');?>">Vendor Filters</a></li>
                                    <li><a class="nav-link" href="<?php echo base_url('home_service_users/r');?>">Vendor banners</a></li>
                                </ul>
                            </li>
                        <?php //endif;?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="copy"></i><span>Settings</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('site_settings')):?>
        								<li><a class="nav-link" href="<?php echo base_url('settings/r');?>">Site Settings</a></li>
    								<?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('vendor_settings/r');?>">Restuarant Settings</a></li>
                                    <?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('slider_settings')):?>
    									<li><a class="nav-link" href="<?php echo base_url('sliders/r');?>">Manage Sliders</a></li>
    								<?php endif;?>
    				            </ul>
    						</li>
					</ul>
				</aside>
			</div>