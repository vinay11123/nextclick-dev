<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1,
			shrink-to-fit=no" name="viewport">
	<title><?php echo $title;?>-<?php echo $this->config->item('site_settings')->system_name;?></title>
	<script src="env.js?ver=1.1"></script>  
    <script type="text/javascript" src="simpleLoader.js?ver=1.1"></script>  
    <script type="text/javascript" src="init.js?ver=1.1"></script> 
	<?php $this->load->view('template/home/topcss');?>
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url()?>assets/img/nextclickicon.png' />
	<script type="text/javascript">
		var base_url = "<?php echo base_url();?>";
	</script>
</head>

<body>
	<!-- <div class="loader"></div> -->
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<div class="navbar-bg"></div>
			<?php $this->load->view('template/home/header');?>
			<!-- Main Content -->
			<div class="main-content">
				<section class="section">
					<?php $this->load->view($content);?>
				</section>
			</div>
            <?php $this->load->view('template/home/footer');?>
		</div>
	</div>
	<?php $this->load->view('template/home/scripts');?>
</body>

</html>