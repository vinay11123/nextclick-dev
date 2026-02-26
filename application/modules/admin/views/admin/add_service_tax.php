<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Service Charge</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('service_tax/s');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">

				<div class="form-group col-md-4">
						<label>Category</label>
						<select required class="form-control" onChange="category_changed1(this.value);"  id="cat_id" name="cat_id"  >
								<option value="0" selected disabled>--select--</option>
								<!-- <option value="all">All</option> -->
    							<?php foreach ($categories as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
					</div>
					
					<div class="form-group col-md-4">
					
						<label>Shop By categories</label>
						<select class="form-control" onChange="shop_by_category_changed1(this.value);" id="sub_cat_id" name="sub_cat_id" required=""  id="cars">
							<option value="" selected disabled>--select--</option>
						</select>
							<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
							<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>

					<div class="form-group col-md-4">
						<label>Menu</label>
							<select class="form-control " id="menu_id" name="menu_id" onChange="menu_changed(this.value);" required="" >
								<option value="" selected disabled>--select--</option>
									
							</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>
					</div>
					<div class="form-row">
                    <div class="form-group col-md-4">
						<input type="hidden" name="imgvalue" id="imgvalue">
						<label>State</label> <select class="form-control" id="state_id" name="state_id"
							required="">
								<option value="0" selected disabled>--select--</option>
								<?php foreach ($states as $state):?>
									<option value="<?php echo $state['id'];?>"><?php echo $state['name']?></option>
								<?php endforeach;?>
						</select>
						<div class="invalid-feedback">state?</div>
						<?php echo form_error('state','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label>District</label> 
						<select class="form-control " id="district_id" name="district_id" required="" >
							<option value="" selected disabled>--select--</option>
						</select>
						<div class="invalid-feedback">District?</div>
						<?php echo form_error('district','<div style="color:red">','</div>');?>
					</div>
                    
                    <div class="form-group col-md-4">
						<label>Constituency</label> 
							<select class="form-control " id="constituancy_id" name="constituancy_id" required="" >
								<option value="">--select--</option>
							</select>
						<div class="invalid-feedback">Constituency?</div>
						<?php echo form_error('constituency','<div style="color:red">','</div>');?>
					</div>
					</div>
					<div class="form-row">
					
					<div class="form-group col-md-4">
						<label>Service Charge In %</label> <input type="number" name="service_tax"
							required="" value="<?php echo set_value('service_tax')?>"
							class="form-control" min="1" set="0.01">
						<div class="invalid-feedback">Service Charge?</div>
						<?php echo form_error('service_tax', '<div style="color:red">', '</div>');?>
					</div>
                    <!-- <div class="form-group col-md-4">
						<label>Rate</label> <input type="number" name="rate"
							required="" value="<?php echo set_value('rate')?>"
							class="form-control" min="1" set="0.01">
						<div class="invalid-feedback">Rate?</div>
						<?php echo form_error('rate', '<div style="color:red">', '</div>');?>
					</div> -->
                    </div>
				
					<div class="col-md-6">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>