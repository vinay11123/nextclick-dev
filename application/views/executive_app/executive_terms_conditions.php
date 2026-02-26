<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<main class="content_wrapper">
				<!--page title start-->
				
				<!--page title end-->
				<div class="container-fluid">
					<!-- state start-->
					<div class="row">
						
						<div class="col-12">
							<div class="panel">
								
								<div class="panel-content panel-about">
									<h6>Terms & Conditions <span class="pull-right"><a href="<?php echo base_url('executive/dashboard'); ?>">Back</a></span></h6>

								<p align="justify">
                                <?= $termandconditions[0]['desc']; ?></p>	
								</div>
								
							</div>
							
							
							
							
						</div>
					</div>
					<!-- state end-->
				</div>
			</main>
</div>

<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>