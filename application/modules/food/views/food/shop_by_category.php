<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add Category</h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('shop_by_categories/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Shop by category Name</label> <input type="text"
							class="form-control" name="name" placeholder="Shop by category Name" required="" value="<?php echo set_value('name')?>">
						<div class="invalid-feedback">New Sub_Category Name?</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>


					<div class="form-group mb-0 col-md-3">
						<label>Description</label> <input type="text" class="form-control"
							name="desc" required="" placeholder="Description" <?php echo set_value('desc')?>>
						<div class="invalid-feedback">Give some Description</div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group col-md-3">
						<label>Upload Image</label> 
						
						<input type="file" name="file" required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);">
<!-- 							<img id="blah" src="#" alt="" > -->
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>

					<div class="form-group col-md-12">

						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Categories</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Shop by category Name</th>
									<th>Description</th>
									<th>Status</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($sub_categories)):?>
    							<?php $sno = 1; foreach ($sub_categories as $sub_cat):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $sub_cat['name'];?></td>
    									<td><?php echo $sub_cat['desc'];?></td>
    									<td><?php echo (! empty($this->db->query("SELECT * FROM `vendor_in_active_shop_by_categories` WHERE vendor_id = ".$this->ion_auth->get_user_id()." AND sub_cat_id =".$sub_cat['id'])->result_array()))? 'In-Active': 'Active';?></td>
    									<td width="15%"><img
    										src="<?php echo base_url();?>uploads/sub_category_image/sub_category_<?php echo $sub_cat['id'];?>.jpg?<?php echo time();?>"
    										width="50px"></td>
    									<td><a href="<?php echo base_url()?>shop_by_categories/edit?id=<?php echo $sub_cat['id'];?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> </td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='6'><h3><center>No Sub_Category</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>

<script type="text/javascript">
    function Validate() {
        var ddlFruits = document.getElementById("ddlFruits");
        if (ddlFruits.value == "") {
            //If the "Please Select" option is selected display error.
            alert("Please select an option!");
            return false;
        }
        return true;
    }
</script>