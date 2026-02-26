<footer class="main-footer">
				<div class="footer-left">
					Copyright &copy; 2024
					<div class="bullet bulletnun"></div>
					<span class="designby">Design By</span> <a href="#"><span class="nextclickfoter">Nextclick</span></a>
				</div>
				<div class="footer-right"></div>
			</footer>
			
			
<!-- Modal -->
<div class="modal fade" id="exampleModalvendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1"> </h5>
        
      </div>
      <div class="modal-body">
        Dear <?=$user->first_name.' '.$user->last_name;?> Please login into mobile application to activate the package and comeback soon.
        <a href="<?php echo base_url(); ?>auth/logout"  >Close</a>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> </h5>
        
      </div>
      <div class="modal-body">
        Dear <?=$user->first_name.' '.$user->last_name;?> Your Dont have access Please login into the mobile application.
        <a href="<?php echo base_url(); ?>auth/logout"  >Close</a>
      </div>

    </div>
  </div>
</div>


<div id="prodModal1" class="prodModal modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" >
<div class="modal-dialog" role="document">
  
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
           <h5 class="modal-title" id="exampleModalLabel2"> </h5>
  
    </div>
    <div class="modal-body">
      <p>Some text in the Modal Body</p>
      <p>Some other text...</p>
    </div>
    <div class="modal-footer">
      <h3>Modal Footer</h3>
    </div>
  </div>

</div>
</div>





