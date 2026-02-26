<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Stock</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('admin/admin/stock_settings/c/0');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
					 <div class="form-group col-md-12">
						<label>Stock Details </label> <input type="number"
							class="form-control" name="min_stock" placeholder="min stock" required="" value="<?php echo set_value('min_stock')?>">
						 <div class="invalid-feedback">Enter stock number</div>
						<?php echo form_error('min_stock','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

