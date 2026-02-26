<?php
$this->load->model('notifications_model');
$notificationSummary = $this->notifications_model->getAdminNotifications();
?>
<nav class="navbar navbar-expand-lg main-navbar">
	<div class="form-inline mr-auto">
		<ul class="navbar-nav mr-3">
			<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
			<li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
					<i data-feather="maximize"></i>
				</a></li>
			<!-- <li>
							<form class="form-inline mr-auto">
								<div class="search-element">
									<input class="form-control" type="search" placeholder="Search" aria-label="Search"
										data-width="200">
									<button class="btn" type="submit">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</form>
						</li> -->
		</ul>
	</div>
	<ul class="navbar-nav">
		<li class="dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true" id="dropdownMenuButton">
				<i class="far fa-bell notification-icon"></i>
				<span class="badge badge-warning navbar-badge"><?php echo $notificationSummary['overall_count']; ?></span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="left: inherit; right: 0px;">
				<span class="dropdown-item dropdown-header notification-header"><?php echo $notificationSummary['overall_count']; ?> Notifications</span>
				<?php 
				// print_r($notificationSummary);exit;
					for($i=0; $i<count($notificationSummary['data']); $i++){
						$rec = $notificationSummary['data'][$i];
				?>
				<?php	} ?>
				
				<div class="dropdown-divider"></div>
				<?php if($this->ion_auth->is_admin() && $rec['key'] =='manual_payments'){ ?>
					<a href="<?php echo base_url('manage_manual_payments');?>" class="dropdown-item">
						<i class="fas fa-money-check-alt mr-2"></i> <?php echo $rec['count']." ".$rec['title']; ?>
					</a>
				<?php	} ?>
				<?php 
				
				if($notificationSummary['vendor'][0]['count'] > 0){
					for($i=0; $i<count($notificationSummary['vendor']); $i++){
						$rec = $notificationSummary['vendor'][$i];
				?>
				<?php	} ?>
				
				<div class="dropdown-divider"></div>
				<?php if($this->ion_auth->is_admin() && $rec['key'] =='new_vendor_created'){ ?>
					<a href="<?php echo base_url('vendors_filter/0');?>" class="dropdown-item vendor" id="vendor" onClick="showDiv(1);">
						<i class="fas fa-money-check-alt mr-2"></i> <?php echo $rec['count']." ".$rec['title']; ?>
					</a>
				<?php	} }?>
				<?php 
				
				if($notificationSummary['Partner'][0]['count'] > 0){
					for($i=0; $i<count($notificationSummary['Partner']); $i++){
						$rec = $notificationSummary['Partner'][$i];
				?>
				<?php	} ?>
				
				<div class="dropdown-divider"></div>
				<?php if($this->ion_auth->is_admin() && $rec['key'] =='new_partner_created'){ ?>
					<a href="<?php echo base_url('vendors_filter/0');?>" class="dropdown-item Dboy" id="Dboy" onClick="changePartnerStatus(1);">
						<i class="fas fa-money-check-alt mr-2"></i> <?php echo $rec['count']." ".$rec['title']; ?>
					</a>
				<?php	} }?>
				<?php 
				
				if($notificationSummary['Product'][0]['count'] > 0){
					for($i=0; $i<count($notificationSummary['Product']); $i++){
						$rec = $notificationSummary['Product'][$i];
				?>
				<?php	} ?>
				
				<div class="dropdown-divider"></div>
				<?php if($this->ion_auth->is_admin() && $rec['key'] =='new_product_created'){ ?>
					<a href="<?php echo base_url('vendor_req_product/0/r');?>" class="dropdown-item Dboy" id="Dboy">
						<i class="fas fa-money-check-alt mr-2"></i> <?php echo $rec['count']." ".$rec['title']; ?>
					</a>
				<?php	} }?>
				<!-- <div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<i class="fas fa-users mr-2"></i> 8 friend requests
					<span class="float-right text-muted text-sm">12 hours</span>
				</a> -->
				<!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
			</div>
			
		</li>
		
	</ul>
	<!-- <li class="nav-item dropdown show">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> -->
	<ul class="navbar-nav navbar-right">
		<li class="dropdown dropdown-list-toggle">
			<!-- <a href="#" data-toggle="dropdown"
						
							class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
							<span class="badge headerBadge1">
								6 </span> </a> -->
			<div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
				<!-- <div class="dropdown-header">
								Messages
								<div class="float-right">
									<a href="#">Mark All As Read</a>
								</div>
							</div> -->
				<div class="dropdown-list-content dropdown-list-message">
					<a href="#" class="dropdown-item"> <span class="dropdown-item-avatar
											text-white"> <img alt="image" src="<?php echo base_url() ?>assets/img/users/user-1.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">John
								Deo</span>
							<span class="time messege-text">Please check your mail !!</span>
							<span class="time">2 Min Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
							<img alt="image" src="<?php echo base_url() ?>assets/img/users/user-2.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">Sarah
								Smith</span> <span class="time messege-text">Request for leave
								application</span>
							<span class="time">5 Min Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
							<img alt="image" src="<?php echo base_url() ?>assets/img/users/user-5.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">Jacob
								Ryan</span> <span class="time messege-text">Your payment invoice is
								generated.</span> <span class="time">12 Min Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
							<img alt="image" src="<?php echo base_url() ?>assets/img/users/user-4.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">Lina
								Smith</span> <span class="time messege-text">hii John, I have upload
								doc
								related to task.</span> <span class="time">30
								Min Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
							<img alt="image" src="<?php echo base_url() ?>assets/img/users/user-3.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">Jalpa
								Joshi</span> <span class="time messege-text">Please do as specify.
								Let me
								know if you have any query.</span> <span class="time">1
								Days Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar text-white">
							<img alt="image" src="<?php echo base_url() ?>assets/img/users/user-2.png" class="rounded-circle">
						</span> <span class="dropdown-item-desc"> <span class="message-user">Sarah
								Smith</span> <span class="time messege-text">Client Requirements</span>
							<span class="time">2 Days Ago</span>
						</span>
					</a>
				</div>
				<div class="dropdown-footer text-center">
					<a href="#">View All <i class="fas fa-chevron-right"></i></a>
				</div>
			</div>
		</li>
		<li class="dropdown dropdown-list-toggle">
			<!-- <a href="#" data-toggle="dropdown"
							class="nav-link notification-toggle nav-link-lg"><i data-feather="bell"></i>
							<span class="badge headerBadge2">
								3 </span> </a> -->
			<div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
				<!-- <div class="dropdown-header">
								Notifications
								<div class="float-right">
									<a href="#">Mark All As Read</a>
								</div>
							</div> -->
				<div class="dropdown-list-content dropdown-list-icons">
					<!-- <a href="#" class="dropdown-item dropdown-item-unread"> <span
										class="dropdown-item-icon bg-primary text-white"> <i class="fas
												fa-code"></i>
									</span> <span class="dropdown-item-desc"> Template update is
										available now! <span class="time">2 Min
											Ago</span>
									</span>
								</a> -->
					<!-- <a href="#" class="dropdown-item"> <span
										class="dropdown-item-icon bg-info text-white"> <i class="far
												fa-user"></i>
									</span> <span class="dropdown-item-desc"> <b>You</b> and <b>Dedik
											Sugiharto</b> are now friends <span class="time">10 Hours
											Ago</span>
									</span> -->
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-success text-white"> <i class="fas
												fa-check"></i>
						</span> <span class="dropdown-item-desc"> <b>Kusnaedi</b> has
							moved task <b>Fix bug header</b> to <b>Done</b> <span class="time">12
								Hours
								Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-danger text-white"> <i class="fas fa-exclamation-triangle"></i>
						</span> <span class="dropdown-item-desc"> Low disk space. Let's
							clean it! <span class="time">17 Hours Ago</span>
						</span>
					</a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-info text-white"> <i class="fas
												fa-bell"></i>
						</span> <span class="dropdown-item-desc"> Welcome to Aegis
							template! <span class="time">Yesterday</span>
						</span>
					</a>
				</div>
				<div class="dropdown-footer text-center">
					<a href="#">View All <i class="fas fa-chevron-right"></i></a>
				</div>
			</div>
		</li>
		<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="<?php echo base_url() ?>assets/img/user.png" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
			<div class="dropdown-menu dropdown-menu-right pullDown">
				<div class="dropdown-title">Hello <?php echo (!$this->ion_auth->is_admin()) ? $user->unique_id : $user->first_name . '' . $user->last_name;; ?></div>
				<?php if (!$this->ion_auth->is_admin()) { ?>
					<a href="<?php echo base_url(); ?>vendor_profile/edit?id=<?php echo $this->db->where('vendor_user_id', $this->ion_auth->get_user_id())->get('vendors_list')->row()->id; ?>" class="dropdown-item has-icon"><i class="far fa-user" style="font-size: 17px;"></i> Profile
					</a>
				<?php } ?>
				<?php if($this->ion_auth->is_admin()) { ?>
				<a href="<?php echo base_url('profile/r') ?>" class="dropdown-item has-icon">
					<span class="fa-passwd-reset fa-stack" style="    margin-top: 5px;">
						<i class="fa fa-undo fa-stack-0x" style="font-size:1.25rem;"></i>
						<i class="fa fa-lock fa-stack-1x" style="margin-left: 4px;font-size: 8px;margin-top: -4px;"></i>
					</span>
					<label style="margin-left:-7px;">Reset Password</label>
				</a>
				<?php } ?>
				<!-- <a href="timeline.html" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i>
								Activities
							</a>  -->
				<?php if ($this->ion_auth_acl->has_permission('settings')) : ?>
					<a href="<?php echo base_url('settings/r') ?>" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
						Settings
					</a>
				<?php endif; ?>
				<div class="dropdown-divider"></div>
				<a href="<?php echo base_url(); ?>auth/logout" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i> Logout
				</a>
			</div>
		</li>
	</ul>
</nav>
<style>

td>a>i.far.fa-trash-alt {
    margin-top: 23px; 
}
</style>