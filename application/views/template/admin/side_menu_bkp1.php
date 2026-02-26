<div class="main-sidebar sidebar-style-2">
				<aside id="sidebar-wrapper">
					<div class="sidebar-brand">
						<a href="<?php echo base_url()?>dashboard"> <img alt="image" src="<?php echo base_url()?>assets/img/logo.png" class="header-logo" /> 
                            <!--<span class="logo-name">Aegis</span>-->
						</a>
					</div>
					<div class="sidebar-user">
						<div class="sidebar-user-picture">
							<img alt="image" src="<?php echo base_url()?>assets/img/userbig.png">
						</div>
						<div class="sidebar-user-details">
							<div class="user-name"><?php echo $user->email;?></div>
							<div class="user-role"><?php echo (! $this->ion_auth->is_admin())? $user->unique_id : $user->first_name.' '.$user->last_name;;?></div>
						</div>
					</div>
					<ul class="sidebar-menu">
						<li class="menu-header">Main</li>
						<li class="dropdown active"><a href="<?php echo base_url('dashboard');?>" class="nav-link "><i
									data-feather="airplay"></i><span>Dashboard</span>
							</a>
							
						</li>
						<?php if($this->ion_auth_acl->has_permission('withdrawal')):?>
						<li class="dropdown "><a href="<?php echo base_url('wallet_transactions/list');?>" class="nav-link "><i
									data-feather="repeat"></i><span>Transactions</span>
							</a>
						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('vendor')):?>
						<li class="dropdown "><a href="<?php echo base_url('user_services/r');?>" class="nav-link "><i
									data-feather="truck"></i><span>Our Services</span>
							</a>
							
						</li>
						<?php endif;?>
						<?php if($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')):?>
						<li class="dropdown "><a href="<?php echo base_url('vendors_filter/0');?>" class="nav-link "><i
									data-feather="user-check"></i><span>Our Vendors</span>
							</a>
							
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
    									<li><a class="nav-link" href="<?php echo base_url('brands/r');?>">Brands</a></li>
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
                                    <?php if($this->ion_auth_acl->has_permission('list_master')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('request/r');?>">Requests</a></li>
                                    <?php endif;?>
    							</ul>
    						</li>
						<?php endif;?>

                        <!--Doctors Implementation Starts-->
                        <?php if($this->ion_auth_acl->has_permission('admin')):?>
                            <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
                                        data-feather="command"></i><span>Doctors</span></a>
                                <ul class="dropdown-menu">
                                         <li><a class="nav-link" href="<?php echo base_url('doctors/r');?>">Doctors</a></li>
                                         <li><a class="nav-link" href="<?php echo base_url('specialities/r');?>">Specialities</a></li>
                                         <li><a class="nav-link" href="<?php echo base_url('doctors_approve/r');?>">Pending & Approved List</a></li>
                                        <li><a class="nav-link" href="<?php echo base_url('doctors_booking/0');?>">Doctor Booking</a></li>
                                   
                                </ul>
                            </li>
                        <?php endif;?>
                         <!--Doctors Implementation End-->
                          <!--On Demand Services Implementation Starts-->
                        <?php if($this->ion_auth_acl->has_permission('admin')):?>
                            <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
                                        data-feather="command"></i><span>On Demand Services</span></a>
                                <ul class="dropdown-menu">
                                        
                                        <li><a class="nav-link" href="<?php echo base_url('od_services/r');?>">On Demand Service</a></li>
                                        <li><a class="nav-link" href="<?php echo base_url('od_categories/r');?>">On Demand Categories</a></li>
                                        <li><a class="nav-link" href="<?php echo base_url('od_categories_approve/r');?>">Pending & Approved List</a></li>
                                   
                                   
                                </ul>
                            </li>
                        <?php endif;?>
                         <!--On Demand Services Implementation End-->
						<!-- <li class="dropdown"><a href="#" class="nav-link ">
                            <i data-feather="mail"></i><span>All Users</span></a>
				        </li> -->
						<!--<li class="menu-header">UI Elements</li>-->
						<?php if($this->ion_auth_acl->has_permission('emp') || $this->ion_auth_acl->has_permission('hr')):?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="users"></i><span>Users</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('emp')):?>
        								<li><a class="nav-link" href="<?php echo base_url('employee/r/0');?>">Manage Users</a></li>
        								<li><a class="nav-link" href="<?php echo base_url('role/r');?>">Manage Roles</a></li>
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
    									data-feather="tv"></i><span>SMTV</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('news_categories')):?>
        								<li><a class="nav-link" href="<?php echo base_url('news_categories/r');?>">Manage Categories</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('manage_news')):?>
    									<li><a class="nav-link" href="<?php echo base_url('news/r');?>">Manage News</a></li>
    								<?php endif;?>
    								<li><a class="nav-link" href="<?php echo base_url('local_news/r');?>">Local News</a></li>
    				            </ul>
    						</li>
						<?php endif;?>
                        <?php if($this->ion_auth_acl->has_permission('admin')): ?> 
                            <li>
                            <a href="<?=base_url('products_approve/r');?>">
                            <i data-feather="check-square" class="metismenu-state-icon"></i><span>Products Approve</span>
                            </a>
                        </li>
                            <li>
                            <a href="<?=base_url('shop_by_category_approve/r');?>">
                            <i data-feather="check-circle" class="metismenu-state-icon"></i><span>Shop by category Approve</span>
                            </a>
                        </li>

                          <li>
                            <a href="<?=base_url('order_support/r');?>">
                            <i data-feather="shopping-cart" class="metismenu-state-icon"></i><span>Order Support</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <!-- Food Module Start -->
                        <?php if($this->ion_auth_acl->has_permission('food')):
                            $cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                            $vendor_category_id=  4; //$cat_id['category_id'];
                            ?>
<!-- 						<li class="dropdown "><a href="<?php ///echo base_url('user_services/r');?>" class="nav-link "><i
									data-feather="monitor"></i><span>Our Services</span> -->
<!-- 							</a> -->
							
<!-- 						</li> -->
						<?php if($this->ion_auth->is_admin()):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="hard-drive"></i><span>Manage Account</span></a>
                                <ul class="dropdown-menu">
                                	<?php if(! $this->ion_auth->is_admin()):?>
                                        <li><a class="nav-link" href="<?php echo base_url('shop_by_categories/r');?>"><?= ($this->ion_auth->is_admin())? 'Shop by Category' : 'Shop by Category';?></a></li>
                                    <?php endif;?>
                                	<?php if($this->ion_auth_acl->has_permission('food_menu')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_menu/r');?>"><?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_nav_label'));?></a></li>
                                   	<?php endif;?>
                                   	<?php if($this->ion_auth_acl->has_permission('food_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('products/0');?>"><?=(($this->ion_auth->is_admin())? 'Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_nav_label'));?></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_extra_sections')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('sections/0');?>"><?=(($this->ion_auth->is_admin())? 'Extra Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_nav_label'));?></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_section_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('section_items/0');?>"><?=(($this->ion_auth->is_admin())? 'Section Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_nav_label'));?></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_settings')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_settings/r');?>">Order Settings</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_orders')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_orders/r');?>"><?=(($this->ion_auth->is_admin())? 'Orders' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_nav_label'));?></a></li>
                                    <?php endif;?>
                                    <!-- <?php if($this->ion_auth_acl->has_permission('food_reports')):?>
                                        <li><a class="nav-link" href="#">Reports</a></li>
                                    <?php endif;?> -->
                                    
                                </ul>
                            </li>

                            <?php else :?>
                            <?php if(! $this->ion_auth->is_admin()):?>
                                        <li><a class="nav-link" href="<?php echo base_url('shop_by_categories/r');?>"><i data-feather="grid" class="metismenu-state-icon"></i><span><?= ($this->ion_auth->is_admin())? 'Shop by Category' : 'Shop by Category';?></a></li>
                                    <?php endif;?>
                                	<?php if($this->ion_auth_acl->has_permission('food_menu')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_menu/r');?>"><i data-feather="server" class="metismenu-state-icon"></i><span><?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_nav_label'));?></span></a></li>
                                   	<?php endif;?>
                                   	<?php if($this->ion_auth_acl->has_permission('food_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('products/0');?>"><i data-feather="shopping-bag" class="metismenu-state-icon"></i><span><?=(($this->ion_auth->is_admin())? 'Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_nav_label'));?></span></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_extra_sections')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('sections/0');?>"><i data-feather="layers" class="metismenu-state-icon"></i><span><?=(($this->ion_auth->is_admin())? 'Extra Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_nav_label'));?></span></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_section_items')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('section_items/0');?>"><i data-feather="package" class="metismenu-state-icon"></i><span><?=(($this->ion_auth->is_admin())? 'Section Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_nav_label'));?></span></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food_orders')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_orders/r');?>"><i data-feather="truck" class="metismenu-state-icon"></i><span><?=(($this->ion_auth->is_admin())? 'Orders' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_nav_label'));?></span></a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('lead_management')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('lead_management/r');?>"><i data-feather="inbox" class="metismenu-state-icon"></i><span>Leads Management</span></a></li>
                                    <?php endif;?>
                        <?php endif;endif;?>
                        <?php if($this->ion_auth->is_admin()):?>
                        <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
                                        data-feather="settings"></i><span>FAQ's</span></a>
                                <ul class="dropdown-menu">
                                
                                    <li><a class="nav-link" href="<?php echo base_url('faq/r');?>">FAQ </a></li>
                                    
                                    
                                        <li><a class="nav-link" href="<?php echo base_url('vendor_faq/r');?>">Vendor's FAQ </a></li>
                                   
                                </ul>
                            </li>
                            <?php endif;?>
                            <?php if(!($this->ion_auth->is_admin())):?>
                            <li>
                            <a href="<?=base_url('support/c');?>">
                            <i data-feather="user-check" class="metismenu-state-icon"></i><span>Support</span>
                            </a>
                            </li>
                            <?php endif;?>
                            <?php if(!($this->ion_auth->is_admin())):?>
                            <li>
                            <a href="<?=base_url('vendor_faq/r');?>">
                            <i data-feather="edit" class="metismenu-state-icon"></i><span>FAQ's</span>
                            </a>
                            </li>
                            <?php endif;?>
                            <?php if( $this->ion_auth->is_admin()):?>
                           <li>
                            <a href="<?=base_url('support/r');?>">
                            <i data-feather="check-circle" class="metismenu-state-icon"></i><span>Support</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if( !($this->ion_auth->is_admin())):?>
                        <li>
                            <a href="<?=base_url('terms/r');?>">
                            <i data-feather="file-text" class="metismenu-state-icon"></i><span>Terms and Conditions</span>
                            </a>
                        </li>
                         <?php endif;?>
    						<li class="dropdown"><a href="#" class="nav-link has-dropdown"><i
    									data-feather="settings"></i><span>Settings</span></a>
    							<ul class="dropdown-menu">
        							<?php if($this->ion_auth_acl->has_permission('site_settings')):?>
        								<li><a class="nav-link" href="<?php echo base_url('settings/r');?>">Site Settings</a></li>
    								<?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('site_settings')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('promos/r');?>">Promo Codes</a></li>
                                    <?php endif;?>
                                    <?php if($this->ion_auth_acl->has_permission('food')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('vendor_settings/r');?>">Vendor Settings</a></li>
                                    <?php endif;?>
                                     <?php if($this->ion_auth_acl->has_permission('food_settings')):?>
                                        <li><a class="nav-link" href="<?php echo base_url('food_settings/r');?>">Order Settings</a></li>
                                    <?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('slider_settings')):?>
    									<li><a class="nav-link" href="<?php echo base_url('sliders/r');?>">Manage Sliders</a></li>
    								<?php endif;?>
    								<?php if($this->ion_auth_acl->has_permission('slider_settings')):?>
    									<li><a class="nav-link" href="<?php echo base_url('category_banner/r');?>">Manage Category Banner</a></li>
    								<?php endif;?>
    				            </ul>
    						</li>
					</ul>
				</aside>
			</div>