<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Return Policy</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('return_policies/s');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">

					<div class="form-group col-md-6">
						<label>Category</label>
						<select required class="form-control" onChange="category_changed1(this.value);"  id="cat_id" name="cat_id"  >
								<option value="0" selected disabled>--select--</option>
    							<?php foreach ($categories as $category):?>
    								<option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-6">
					
						<label>Shop By categories</label>
						<select class="form-control" onChange="shop_by_category_changed1(this.value);" id="sub_cat_id" name="sub_cat_id" required="">
							<option value="" selected disabled>--select--</option>
						</select>
							<?php echo form_error('sub_cat_id','<div style="color:red>"','</div>');?>
					</div>

					<div class="form-group col-md-6">
						<lable>Menu</lable>
							<select class="form-control " id="menu_id" name="menu_id" required="">
								<option value="" selected disabled>--select--</option>
									
							</select>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>

					<div class="form-group col-md-6">
						<label>No.Of Days</label> <input type="number" name="return_days"
							required="" value="<?php echo set_value('return_days')?>"
							class="form-control" min="1">
						<div class="invalid-feedback">Uses?</div>
						<?php echo form_error('return_days', '<div style="color:red">', '</div>');?>
					</div>
					<div class="col col-sm col-md-12 ven2" ><label>Return Policy Conditions</label>
          				<textarea id="return_terms" class="ckeditor" name="return_terms" rows="10" data-sample-short>Return Policy Conditions</textarea>
          				<?php echo form_error('return_terms', '<div style="color:red">', '</div>');?>
        			</div>
					<div class="form-group col-md-12">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>