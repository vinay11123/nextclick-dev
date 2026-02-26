<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add request</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('general/support/customer/u/0');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

<input type = "hidden" name = "id" value ="<?php echo $support_requests[0]['id']; ?>" readonly>

				<div class="form-row">
					 <div class="form-group col-md-12">
						<label>Request Type: </label> <input type="text"
							class="form-control" name="request_type" placeholder="request_type" required="" value="<?php echo $support_requests[0]['request_type']; ?>" readonly>
						<div class="invalid-feedback">Enter token Number</div>
						<?php echo form_error('token','<div style="color:red">','</div>')?>
					</div>

					


					<div class="form-group col-md-12">
						<label>App Details:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="app_details_id"  disabled>
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($app_details as $category):?>
    								<option value="<?php echo $category['id'];?>" <?php if($category['id'] == $support_requests[0]['app_details_id']){ echo "selected";} ?>><?php echo $category['app_name']?></option>
    							<?php endforeach;?>
						</select> 
					</div>

                    <div class="form-group col-md-12">
						<label>EMail Id</label> <input type="text"
							class="form-control" name="email" placeholder="Contact Mail" required="" value="<?php echo $users[0]['email']; ?>" readonly>
						<div class="invalid-feedback">Enter MailID</div>
						<?php echo form_error('email','<div style="color:red">','</div>')?>
					</div>

					 <div class="form-group col-md-12">
						<label>Mobile</label> <input type="text"
							class="form-control" name="mobile" placeholder="mobile" required="" value="<?php echo $users[0]['phone']; ?>" readonly>
						<div class="invalid-feedback">Enter Mobile Number</div>
						<?php echo form_error('mobile','<div style="color:red">','</div>')?>
					</div>

					 <div class="form-group col-md-12">
						<label>Subject</label> <input type="text"
							class="form-control" name="subject" placeholder="subject" required="" value="<?php echo $support_requests[0]['title']; ?>" readonly>
						<div class="invalid-feedback">Enter subject</div>
						<?php echo form_error('subject','<div style="color:red">','</div>')?>
					</div>

					
					 <div class="col col-sm col-md-12" >
          <label>Description</label>

            <textarea id="request_desc" name="req_content" class="ckeditor" rows="10" data-sample-short readonly><?php echo $support_requests[0]['description']; ?></textarea>
           <?php echo form_error('req_content', '<div style="color:red">', '</div>');?>
         </div>
		 
		 <div class="form-group col-md-12">
						<label>Severity:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="severity" >
									<option value="0" <?php if($support_requests[0]['severity']==0) { echo 'selected="selected"'; }?> >Low</option>
									<option value="1" <?php if($support_requests[0]['severity']==1) { echo 'selected="selected"'; }?>>Medium</option>
									<option value="2" <?php if($support_requests[0]['severity']==2) { echo 'selected="selected"'; }?>>High</option>
									<option value="3" <?php if($support_requests[0]['severity']==3) { echo 'selected="selected"'; }?>>Critical</option>
    							
						</select> 
					</div>
					
					<div class="form-group col-md-12">
						<label>Status:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="status" >
									<option value="1" <?php if($support_requests[0]['status']==1) { echo 'selected="selected"'; }?>>Open</option>
									<option value="2" <?php if($support_requests[0]['status']==2) { echo 'selected="selected"'; }?>>Working</option>
									<option value="3" <?php if($support_requests[0]['status']==3) { echo 'selected="selected"'; }?>>Closed</option>
    							
						</select> 
					</div>

					<div class="form-group col-md-12">
						<label>Assign To:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="assigned_to" >
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($users_tech as $tech_user):?>
    								<option value="<?php echo $tech_user['id'];?>" <?php if($tech_user['id'] == $support_requests[0]['assigned_to']){ echo "selected";} ?>><?php echo $tech_user['first_name']?></option>
    							<?php endforeach;?>
						</select> 
					</div>
					
										 <div class="col col-sm col-md-12" >
          <label>Comment</label>

            <textarea id="comment" name="comment" class="ckeditor" rows="10" data-sample-short><?php echo $support_requests[0]['comment']; ?></textarea>
           <?php echo form_error('comment', '<div style="color:red">', '</div>');?>
         </div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>
	</div>
</div>

