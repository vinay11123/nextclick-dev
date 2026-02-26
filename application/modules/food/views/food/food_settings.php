
<div class="row">
	<div class="col-12">
		<form class="needs-validation" novalidate="" action="<?=base_url('food_settings/u');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<h4 class="ven">Settings</h4>
				<div class="form-row">
					<div class="form-group col-md-6">
						 <label for="field-1" class="control-label">Preparation Time (in Minutes)</label>
                    <input type="number" class="form-control" name="preparation_time" placeholder="Preparation Time (in Minutes)" required="" min="20" value="<?=$food_settings['preparation_time'];?>">
					</div>
<!-- 					<div class="form-group col-md-6"> -->
<!-- 						<label>Restaurant Status</label>  -->
<!-- 						<div  class="form-control">  
                        <label><input type="radio" name="restaurant_status" required="" value="1"  <?=($food_settings['restaurant_status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="restaurant_status" required="" value="2" <?=($food_settings['restaurant_status'] == 2)? 'checked' : '';?>> Not-Available</label>
                         </div> -->
<!-- 					</div> -->
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Update</button>
					</div>
				</div>
			
		</form>
	</div>
</div>
<br/><br/>

