<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Subscription Packages</h4>
    <form class="needs-validation"  action="<?php echo base_url('banner_images/s');?>" method="post" enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
        <div class="form-group mb-0 col-md-6">
            <label>Category</label>
           <select class="form-control" name="cat_id" required="" id="cat_id">
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($categories as $category):?>
                    <option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">Give Category</div>
            <?php echo form_error( 'cat_id', '<div style="color:red">', '</div>');?>
          </div>
          <div class="form-group col-md-6">
						<label>Upload Image</label> 
						<input type="file" name="file" accept="image/jpeg, image/jpeg, image/png"
              required value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);">
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>

          <div class="form-group col-md-12">

            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>


        </div>


      </div>
    </form>

    

  </div>
</div>
