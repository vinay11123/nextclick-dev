<!-- <?php
echo "<pre>";
print_r($vendor_details);
?>
<div class="row">
	<div class="col-12">
		<form class="needs-validation" novalidate="" action="<?=base_url('vendor_profile/u/bank_details');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<h4>Profile</h4>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="field-1" class="control-label">List Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name" required="" value="<?=$vendor_details['name'];?>" autocomplete="off">
					</div>
					<div class="form-group col-6">
						<label for="field-1" class="control-label">Cover Photo</label>
                       <input type='file' name="file" onchange="news_image(this);" />
                       <?php echo form_error('file', '<div style="color:red">', '</div>');?>
                          <img id="blah" src="<?php echo base_url(); ?>uploads/food_menu_image/food_menu_<?php echo $bank_details['id']; ?>.jpg" width="180" height="180" alt="your image" />
                      </div>
                      <div class="form-group col-6">
                      	<label for="field-1" class="control-label">Banner Photo</label>
                       <input type='file' name="file" onchange="news_image(this);" />
                       <?php echo form_error('file', '<div style="color:red">', '</div>');?>
                          <img id="blah" src="<?php echo base_url(); ?>uploads/food_menu_image/food_menu_<?php echo $bank_details['id']; ?>.jpg" width="180" height="180" alt="your image" />
                      </div>
                      <div class="form-group col-md-12">
						<label for="field-1" class="control-label">Location</label>
                    <textarea class="form-control" name="bank_branch" placeholder="Bank Branch" required="" autocomplete="off"><?=$bank_details['bank_branch'];?></textarea>
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Bank Branch</label>
                    <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" required="" value="<?=$bank_details['bank_branch'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">IFSC Code</label>
                    <input type="text" class="form-control" name="ifsc" placeholder="IFSC" required="" value="<?=$bank_details['ifsc'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class=" control-label">Account Holder Name</label>
                    <input type="text" class="form-control" name="ac_holder_name" placeholder="Account Holder Name" required="" value="<?=$bank_details['ac_holder_name'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class="control-label">Account Number</label>
                    <input type="number" class="form-control" name="ac_number" placeholder="Account Number" required="" value="<?=$bank_details['ac_number'];?>" autocomplete="off">
					</div>			
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Update</button>
					</div>
				</div>
			
		</form>
	</div>
</div>
<br/>
<br/> -->
<div class="row">
	<div class="col-12">
		<form class="needs-validation" novalidate="" action="<?=base_url('vendor_profile/u/bank_details');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<h4 class="ven">Bank Details</h4>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Bank Name</label>
                    <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" required="" value="<?=$bank_details['bank_name'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Bank Branch</label>
                    <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" required="" value="<?=$bank_details['bank_branch'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">IFSC Code</label>
                    <input type="text" class="form-control" name="ifsc" placeholder="IFSC" required="" value="<?=$bank_details['ifsc'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class=" control-label">Account Holder Name</label>
                    <input type="text" class="form-control" name="ac_holder_name" placeholder="Account Holder Name" required="" value="<?=$bank_details['ac_holder_name'];?>" autocomplete="off">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class="control-label">Account Number</label>
                    <input type="number" class="form-control" name="ac_number" placeholder="Account Number" required="" value="<?=$bank_details['ac_number'];?>" autocomplete="off">
					</div>			
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Update</button>
					</div>
				</div>
			
		</form>
	</div>
</div>

