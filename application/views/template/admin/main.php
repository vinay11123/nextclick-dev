
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
	<?php $this->load->view('template/admin/topcss');?>
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url()?>assets/img/nextclickicon.png' />
	<script type="text/javascript">
		var base_url = "<?php echo base_url();?>";
		var vendor_id = "<?php echo $this->ion_auth->get_user_id();?>";
	</script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>
.blur   {
    filter: blur(5px);
    -webkit-filter: blur(5px);
    -moz-filter: blur(5px);
    -o-filter: blur(5px);
    -ms-filter: blur(5px);
}
</style>
<body>
	<div class="loader"></div> 
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<div class="navbar-bg"></div>
			<?php $this->load->view('template/admin/header');?>
			<?php $this->load->view('template/admin/side_menu');?>
			<!-- Main Content -->
			<div class="main-content">
				<section class="section">
					<?php $this->load->view($content);?>
				</section>
			</div>
            <?php $this->load->view('template/admin/footer');?>
		</div>
	</div>
	<?php $this->load->view('template/admin/scripts');?>
</body>
<?php 
if(isset($user->user_id)) {
	$data=$this->db->query("SELECT * FROM `vendor_packages` where created_user_id=$user->user_id order by id DESC limit 1")->result_array();
}

$status_vendor = $data[0]['status'];


?>
<script>
var user_type='<?php echo $user->primary_intent;?>';
if(user_type=='user' || user_type=='delivery_partner')
{
    $(document).ready(function(){
		var user_name='<?php echo $user->first_name;?>';
        $("#exampleModal").modal('show');
    });
} 

var vendor_status=parseInt('<?php echo $status_vendor; ?>');
	
if(vendor_status=='2')
{
		$(document).ready(function(){
		var user_name='<?php echo $user->first_name;?>';
        $("#exampleModalvendor").modal('show');
		});
}


const openModalBtn = document.querySelector(".pbtn");
const modal = document.querySelector(".prodModal");
const closeModalBtn = document.querySelector(".close");

openModalBtn.addEventListener("click", function () {
  modal.style.display = "block";
});

closeModalBtn.addEventListener("click", function () {
  modal.style.display = "none";
});

</script>

</html>