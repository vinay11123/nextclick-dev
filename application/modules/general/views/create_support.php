<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Request</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('general/support/support_queries/c/0');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">

					 <div class="form-group col-md-4">
						<label>Token No </label> <input type="text"
							class="form-control" name="token" placeholder="token" required="" value="<?php echo set_value('token')?>">
						 <div class="invalid-feedback">Enter token number</div>
						<?php echo form_error('token','<div style="color:red">','</div>')?>
					</div>

					
					<div class="form-group col-md-4">
						<label>Relate To:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="req_id"  >
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($request_type as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['title']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
					</div>


						<div class="form-group col-md-4">
						<label>App Details:</label>
						<!-- <input type="file" class="form-control" required="">-->
						<select required class="form-control" name="app_details_id"  >
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($app_details as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['app_name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
					</div>

                    <div class="form-group col-md-4">
						<label>Contact Mail</label> <input type="text"
							class="form-control" name="email" placeholder="Contact Mail" required="" value="<?php echo set_value('email')?>">
						<div class="invalid-feedback">Enter MailID</div>
						<?php echo form_error('email','<div style="color:red">','</div>')?>
					</div>

					 <div class="form-group col-md-4">
						<label>Mobile</label> <input type="number"
							class="form-control" name="mobile" placeholder="mobile" required="" value="<?php echo set_value('mobile')?>">
						<div class="invalid-feedback">Enter Mobile Number</div>
						<?php echo form_error('mobile','<div style="color:red">','</div>')?>
					</div>

					 <div class="form-group col-md-4">
						<label>Subject</label> <input type="text"
							class="form-control" name="subject" placeholder="subject" required="" value="<?php echo set_value('subject')?>">
						<div class="invalid-feedback">Enter subject</div>
						<?php echo form_error('subject','<div style="color:red">','</div>')?>
					</div>

					
					 <div class="col col-sm col-md-12" >
          <label>Description</label>
            <textarea id="request_desc" name="req_content" class="ckeditor" rows="10" data-sample-short></textarea>
           <?php echo form_error('req_content', '<div style="color:red">', '</div>');?>
         </div>
					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>
	</div>
</div>

