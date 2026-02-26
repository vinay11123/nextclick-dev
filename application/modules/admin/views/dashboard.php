<style>
	.flash1 {

		color: rgba(225, 225, 225, .1);
		/* background-image: url("https://s31.postimg.cc/yaze1agjv/abstract-background-canvas-249798.jpg"); */
		background-image: url("https://media.giphy.com/media/FE0WTM8BG754I/giphy.gif");
		background-repeat: repeat-x;
		background-position: bottom;
		background-sizxe: cover;
		-webkit-background-clip: text;
		animation: animate 15s linear infinite;

		/* margin-top:2%;
  text-transform: uppercase; */
		font-weight: 900;
	}

	@keyframes animate {
		0% {
			background-position: left 0px top 0px;
		}

		30% {
			background-position: left 100px top 0px;
		}

		60% {
			background-position: left 300px top 0px;
		}

		80% {
			background-position: left 600px top 0px;
		}

		100% {
			background-position: left 800px top 0px;
		}

		.card:hover .fon {
			opacity: 0;
		}
	}
</style>


<?php if (
	$this->ion_auth_acl->has_permission('all_users_view')
	|| $this->ion_auth_acl->has_permission('vendor_view')
	|| $this->ion_auth_acl->has_permission('delivery_partner_view')
	|| $this->ion_auth_acl->has_permission('executive_view')
): ?>
	<h3 class="flash1">Users</h3>

	<div class="row">
		<?php if ($this->ion_auth_acl->has_permission('all_users_view')): ?>
			<div class="col-xl-3  col-md-4 col-sm-4">
				<a href="<?php echo base_url('employee/r/0'); ?>">
					<div class="card pulse" style="background-color:#cd113bd1;">

						<div class="card-bg">

							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">All users</h6>
								</div>
								<!-- <i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i> -->
							</div>
							<!-- <canvas id="cardChart1" height="80"></canvas> -->
							<br />
							<div class="alert alert-sm alert-primary color">
								<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
										class="fas fa-user-circle card-icon font-20 p-r-30">
										<?php echo $this->db->query('SELECT COUNT(*) AS `no_of_users` FROM `users` as u WHERE u.deleted_at is null')->row()->no_of_users; ?>
									</i></center>
							</div>

						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>
		<?php if ($this->ion_auth_acl->has_permission('vendor_view')): ?>
			<div class="col-xl-3  col-md-4 col-sm-4">
				<a href="<?php echo base_url('vendors_filter/0'); ?>">
					<div class="card pulse" style="background-color:#52006aad; color:white">
						<div class="card-bg">
							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">Vendors</h6>
								</div>
							</div>
							<br />
							<div class="alert alert-sm alert-primary color">
								<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><b><i
											class="fas fa-check-circle card-icon font-20 p-r-30 " title="Active Vendors">
											<?php echo $this->db->query('SELECT COUNT(*) AS active FROM vendors_list WHERE status=1 and deleted_at is null')->row()->active; ?>
										</i></b><b><i class="fas fa-times-circle card-icon font-20 p-r-30"
											title="Inactive Vendors">
											<?php echo $this->db->query('SELECT COUNT(*) AS inactive FROM vendors_list WHERE status=2')->row()->inactive; ?>
										</i></b></center>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>
		<?php if ($this->ion_auth_acl->has_permission('executive_view')): ?>
			<div class="col-xl-3  col-md-4 col-sm-4">
				<a href="<?php echo base_url('employee/r/0'); ?>">
					<div class="card shakeY" style="background-color:#ffa900c7;">
						<div class="card-bg">
							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">HR</h6>
								</div>
								<!-- <i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i> -->
							</div>
							<!-- <canvas id="cardChart1" height="80"></canvas> -->
							<br />
							<div class="alert alert-sm alert-primary color">
								<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
										class="fas fa-user-circle card-icon font-20 p-r-30">
										<?php echo $this->db->query('SELECT COUNT(*) AS `no_of_hrs` FROM `users` as u LEFT JOIN users_groups as ug ON u.id = ug.user_id WHERE u.deleted_at is null and ug.group_id = (SELECT id FROM `groups` WHERE name = "hr")')->row()->no_of_hrs; ?>
									</i></center>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>
		<?php if ($this->ion_auth_acl->has_permission('executive_view')): ?>
			<div class="col-xl-3  col-md-4 col-sm-4">
				<a href="<?php echo base_url('employee/r/0'); ?>">
					<div class="card " style="background-color:#007569bf; color:white">
						<div class="card-bg">
							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">Divisional Heads</h6>
								</div>
								<!-- <i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i> -->
							</div>
							<!-- <canvas id="cardChart1" height="80"></canvas> -->
							<br />
							<div class="alert alert-sm alert-primary color">
								<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
										class="fas fa-user-circle card-icon font-20 p-r-30">
										<?php echo $this->db->query('SELECT COUNT(*) AS `no_of_dhs` FROM `users` as u LEFT JOIN users_groups as ug ON u.id = ug.user_id WHERE u.deleted_at is null and ug.group_id = (SELECT id FROM `groups` WHERE name = "dh")')->row()->no_of_dhs; ?>
									</i></center>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<hr class="dashboard-hr" />
<?php
if (
	$this->ion_auth_acl->has_permission('product_view')
	|| $this->ion_auth_acl->has_permission('reports_overall_sale')
	|| $this->ion_auth_acl->has_permission('reports_today_sales')

): ?>

	<?php if ($user->primary_intent == 'vendor') {
	} else { ?>
		<h3 class="flash1">Ecommerce</h3>
		<div class="row">
			<?php if ($this->ion_auth_acl->has_permission('product_view')): ?>
				<div class="col-xl-4  col-md-4 col-sm-4">
					<a href="<?php echo base_url('food_product/0/r'); ?>">
						<!-- <div class="card"  style="background-color: #1081add1;"> -->
						<div class="card" style="background-color:#fafafa;">
							<div class="card-bg">
								<div class="p-t-20 d-flex justify-content-between">
									<div class="col">
										<h6 class="mb-0" style="color:#ffa900c7">Vendor Products</h6>
									</div>
								</div>
								<br />
								<div class="alert alert-sm alert-primary color">
									<center style="position: relative;float: left;padding-bottom: 30px;"><b><i
												class="fas fa-check-circle card-icon font-20 p-r-30 " title="Active Vendors"
												style="color:#52006aad">
												<?php echo $this->db->query('SELECT COUNT(*) AS active FROM food_item WHERE status=1 and deleted_at is null')->row()->active; ?>
											</i></b></<br><b><i class="fas fa-times-circle card-icon font-20 p-r-30"
												title="Inactive Vendors" style="color:#cd113bd1">
												<?php echo $this->db->query('SELECT COUNT(*) AS inactive FROM food_item WHERE status=2')->row()->inactive; ?>
											</i></b></center>
								</div>
							</div>
						</div>
					</a>
				</div>
			<?php endif; ?>
			<?php if ($this->ion_auth_acl->has_permission('reports_overall_sale')): ?>
				<div class="col-xl-4  col-md-4 col-sm-4 ">
					<a href="<?php echo base_url('food_orders/r/0'); ?>">
						<!-- <div class="card" style="background-color:#cd113bd1;"> -->
						<div class="card tada" style="background-color:#fafafa;">
							<div class="card-bg">
								<div class="p-t-20 d-flex justify-content-between">
									<div class="col">
										<h6 class="mb-0" style="color:#cd113bd1;">Overall sales</h6>
										<span class="font-weight-bold mb-0 font-20"></span>
									</div>
								</div>
								<!-- <canvas id="cardChart4" height="80"></canvas> -->
								<br />
								<div class="alert alert-sm alert-primary color">
									<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
											class="fas fa-chart-bar card-icon font-20 p-r-30" style="color:#ffa900c7;">
											<?php echo number_format((float) $this->db->query('SELECT SUM(total) AS total FROM `ecom_orders` WHERE `order_status_id` = 9')->row()->total, 2, '.', ''); ?>₹
										</i></center>
								</div>
							</div>
						</div>
					</a>
				</div>
			<?php endif; ?>
			<?php if ($this->ion_auth_acl->has_permission('reports_today_sales')): ?>
				<div class="col-xl-4  col-md-4 col-sm-4">
					<a href="<?php echo base_url('food_orders/today/0'); ?>">
						<!-- <div class="card" style="background-color:#52006aad; color:white"> -->
						<div class="card pulse" style="background-color:#fafafa;">
							<div class="card-bg">
								<div class="p-t-20 d-flex justify-content-between">
									<div class="col">
										<h6 class="mb-0" style="color:#52006aad;">Today sales</h6>
										<span class="font-weight-bold mb-0 font-20"></span>
									</div>
								</div>
								<!-- <canvas id="cardChart4" height="80"></canvas> -->
								<br />
								<div class="alert alert-sm alert-primary color">
									<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
											class="fas fa-chart-bar card-icon font-20 p-r-30" style="color:#cd113bd1;">
											<?php echo ($this->db->query('SELECT SUM(total) AS total FROM `ecom_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status_id = 12')->row()->total) ? number_format((float) $this->db->query('SELECT SUM(total) AS total FROM `ecom_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status_id = 12')->row()->total, 2, '.', '') : 0; ?>₹
										</i></center>
								</div>
							</div>
						</div>
					</a>
				</div>
			<?php endif; ?>
		</div>
	<?php } ?>
<?php endif; ?>
<hr class="dashboard-hr" />
<?php if (
	$this->ion_auth_acl->has_permission('doctor_view')
): ?>
	<h3 class="flash1">Doctors</h3>
	<div class="row">
		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('doctors_approve/r'); ?>">
				<!-- <div class="card" style="background-color: #007569bf;"> -->
				<div class="card rotateIn" style="background-color:#fafafa;">
					<div class="card-bg">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color:#007569bf;">Vendor Doctors</h6>
							</div>
						</div>
						<br />
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><b><i
										class="fas fa-check-circle card-icon font-20 p-r-30 " title="Active Vendors"
										style="color: #ffa900c7;">
										<?php echo $this->db->query('SELECT COUNT(*) AS active FROM hosp_doctors_details WHERE deleted_at is null and  status=1')->row()->active; ?>
									</i></b></<br><b><i class="fas fa-times-circle card-icon font-20 p-r-30"
										title="Inactive Vendors" style="color:#cd113bd1;">
										<?php echo $this->db->query('SELECT COUNT(*) AS inactive FROM hosp_doctors_details WHERE deleted_at is null and  status=2')->row()->inactive; ?>
									</i></b></center>
						</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('doctors_booking/r'); ?>">
				<!-- <div class="card" style="background-color:#ffa900c7;"> -->
				<div class="card slidOutLeft" style="background-color:#fafafa;">
					<div class="card-bg">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color:#52006aad;">Overall sales</h6>
								<span class="font-weight-bold mb-0 font-20"></span>
							</div>
						</div>
						<!-- <canvas id="cardChart4" height="80"></canvas> -->
						<br />
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
									class="fas fa-chart-bar card-icon font-20 p-r-30" style="color:#007569bf;">
									<?php echo number_format((float) $this->db->query('SELECT SUM(b.total) as total FROM `booking_items` as bi JOIN bookings as b on b.id = bi.booking_id where bi.service_id = 11 AND b.booking_status = 4')->row()->total, 2, '.', ''); ?>₹
								</i></center>
						</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('doctors_booking/r'); ?>">
				<!-- <div class="card" style="background-color: #ee6935e3;"> -->
				<div class="card pulse" style="background-color:#fafafa;">
					<div class="card-bg">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color: #ffa900c7;">Today sales</h6>
								<span class="font-weight-bold mb-0 font-20"></span>
							</div>
						</div>
						<!-- <canvas id="cardChart4" height="80"></canvas> -->
						<br />
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
									class="fas fa-chart-bar card-icon font-20 p-r-30" style="color: #52006aad;">
									<?php echo ($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6')->row()->total) ? number_format((float) $this->db->query('SELECT SUM(b.total) as total FROM `booking_items` as bi JOIN bookings as b on b.id = bi.booking_id where bi.service_id = 11 AND b.booking_status = 4 AND CURRENT_DATE = DATE(bi.created_at)')->row()->total, 2, '.', '') : 0; ?>₹
								</i></center>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
<?php endif; ?>
<hr class="dashboard-hr" />
<?php if (
	$this->ion_auth_acl->has_permission('od_service_view')
): ?>
	<h3 class="flash1">On Demand Services</h3>
	<div class="row">
		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('od_categories_approve/r'); ?>">
				<!-- <div class="card" style="background-color: #52006aad;"> -->
				<div class="card" style="background-color:#fafafa;">
					<div class="card-bg">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color: #ffa900c7;">On Demand Services</h6>
							</div>
						</div>
						<br />
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><b><i
										class="fas fa-check-circle card-icon font-20 p-r-30 " title="Active Vendors"
										style="color: #007569bf;">
										<?php echo $this->db->query('SELECT COUNT(*) AS active FROM od_services_details WHERE deleted_at is null and status=1')->row()->active; ?>
									</i></b></<br><b><i class="fas fa-times-circle card-icon font-20 p-r-30"
										title="Inactive Vendors" style="color:#cd113bd1;">
										<?php echo $this->db->query('SELECT COUNT(*) AS inactive FROM od_services_details WHERE deleted_at is null and  status=2')->row()->inactive; ?>
									</i></b></center>
						</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('services_booking/r'); ?>">
				<!-- <div class="card" style="background-color:#cd113bd1;"> -->
				<div class="card" style="background-color:#fafafa;">
					<div class="card-bg">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color: #cd113bd1;">Overall sales</h6>
								<span class="font-weight-bold mb-0 font-20"></span>
							</div>
						</div>
						<!-- <canvas id="cardChart4" height="80"></canvas> -->
						<br />
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
									class="fas fa-chart-bar card-icon font-20 p-r-30" style="color:#ffa900c7;">
									<?php echo number_format((float) $this->db->query('SELECT SUM(b.total) as total FROM `booking_items` as bi JOIN bookings as b on b.id = bi.booking_id where bi.service_id = 8 AND b.booking_status = 4')->row()->total, 2, '.', ''); ?>₹
								</i></center>
						</div>
					</div>
				</div>
			</a>
		</div>

		<div class="col-xl-4  col-md-4 col-sm-4">
			<a href="<?php echo base_url('services_booking/r'); ?>">
				<!-- <div class="card" style="background-color: #007569bf;"> -->
				<div class="card" style="background-color:#fafafa;">
					<div class="card-bg pulse">
						<div class="p-t-20 d-flex justify-content-between">
							<div class="col">
								<h6 class="mb-0" style="color: #52006aad;">Today sales</h6>
								<span class="font-weight-bold mb-0 font-20"></span>
							</div>
						</div>
						<!-- <canvas id="cardChart4" height="80"></canvas> -->
						<br />
						<!-- <div class="alert alert-sm alert-primary "><center><i class="fas fa-chart-bar card-icon font-20 p-r-30">  <?php //echo ($this->db->query('SELECT SUM(b.total) as total FROM `booking_items` as bi JOIN bookings as b on b.id = bi.booking_id where bi.service_id = 8 AND b.booking_status = 4 AND CURRENT_DATE = DATE(bi.created_at)')->row()->total)? number_format((float)$this->db->query('SELECT SUM(total) AS total FROM `booking_items` WHERE CURRENT_DATE = DATE(created_at) AND booking_status = 6')->row()->total, 2, '.', ''): 0;       ?>₹</i></center></div> -->
						<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
									class="fas fa-chart-bar card-icon font-20 p-r-30" style="color: #007569bf;">
									<?php echo ($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6')->row()->total) ? number_format((float) $this->db->query('SELECT SUM(b.total) as total FROM `booking_items` as bi JOIN bookings as b on b.id = bi.booking_id where bi.service_id = 8 AND b.booking_status = 4 AND CURRENT_DATE = DATE(bi.created_at)')->row()->total, 2, '.', '') : 0; ?>₹
								</i></center>
						</div>

					</div>
				</div>

			</a>
		</div>
	</div>
<?php endif; ?>



<hr class="dashboard-hr" />
<h3 class="flash1">App Downloaded Users</h3>
	<div class="row">
		<?php if ($this->ion_auth_acl->has_permission('all_users_view')): ?>
			<div class="col-xl-3  col-md-4 col-sm-4">
				<a href="<?php echo base_url('allusers'); ?>">
					<div class="card pulse" style="background-color:#a17c84d1;">

						<div class="card-bg">

							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">Installed Users</h6>
								</div>
								<!-- <i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i> -->
							</div>
							<!-- <canvas id="cardChart1" height="80"></canvas> -->
							<br />
							<div class="alert alert-sm alert-primary color">
								<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><i
										class="fas fa-user-circle card-icon font-20 p-r-30">
										<?php echo $this->db->query('SELECT COUNT(*) AS `no_of_users` FROM `users` as u WHERE u.deleted_at is null')->row()->no_of_users; ?>
									</i></center>
							</div>

						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>
		<?php if ($this->ion_auth_acl->has_permission('vendor_view')): ?>
			<div class="col-xl-5  col-md-8 col-sm-8">
				<a href="<?php echo base_url('allorders'); ?>">
					<div class="card pulse" style="background-color:#489384ad; color:white">
						<div class="card-bg">
							<div class="p-t-20 d-flex justify-content-between">
								<div class="col">
									<h6 class="mb-0" style="color:white">Orders</h6>
								</div>
							</div>
							<br />
							<?php
							$query = "
                           SELECT COUNT(*) AS users_count
                            FROM (
                                SELECT u.id
                                FROM users u
                                INNER JOIN ecom_orders eo 
                                    ON u.id = eo.created_user_id
                                    AND eo.deleted_at IS NULL
                                GROUP BY u.id
                                HAVING COUNT(eo.id) > 3
                            ) AS t;";
                            
                            $result = $this->db->query($query)->row();
                            
                            	$query2 = "
                           SELECT COUNT(*) AS users_count
                            FROM (
                                SELECT u.id
                                FROM users u
                                INNER JOIN ecom_orders eo 
                                    ON u.id = eo.created_user_id
                                    AND eo.deleted_at IS NULL
                                GROUP BY u.id
                                HAVING COUNT(eo.id) < 3
                            ) AS t;";
                            
                            $result2 = $this->db->query($query2)->row();
                            
                            
                            ?>
							<div class="alert alert-sm alert-primary color">
							<center style="color: white;position: relative;float: left;padding-bottom: 30px;"><b><i
										class="fas fa-check-circle card-icon font-20 p-r-30 " title="Top Orders">
										<?php echo $result->users_count; ?>
									</i></b><b><i class="fas fa-times-circle card-icon font-20 p-r-30"
										title="Low Orders">
										<?php echo $result2->users_count; ?>
									</i></b></center>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>


		</div>
<script>

</script>