<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Subscription Packages</h4>
    <form class="needs-validation"  action="<?php echo base_url('subscriptions_packages/s');?>" method="post" enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
        <div class="form-group mb-0 col-md-4">
            <label>Service ID</label>
           <select class="form-control" name="service_id" required="" id="service_id">
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($sevices as $sevice):?>
                    <option value="<?php echo $sevice['id'];?>"><?php echo $sevice['name']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">Give Service ID</div>
            <?php echo form_error( 'service_id', '<div style="color:red">', '</div>');?>
          </div>

          <div class="form-group mb-0 col-md-4">
            <label>Title</label> <input type="text" class="form-control"
              name="title" required="" placeholder="title" <?php echo set_value('title')?>>
            <div class="invalid-feedback">Give some Title</div>
             <?php echo form_error('title','<div style="color:red">','</div>');?>
          </div>

          <div class="form-group mb-0 col-md-4">
            <label>Description</label> <input type="text" class="form-control"
              name="desc" required="" placeholder="desc" <?php echo set_value('desc')?>>
            <div class="invalid-feedback">Give some Description</div>
             <?php echo form_error('desc','<div style="color:red">','</div>');?>
          </div>
          
          <div class="form-group mb-0 col-md-4">
            <label>Days</label> <input type="number" class="form-control"
              name="days" required="" placeholder="days" <?php echo set_value('days')?>>
            <div class="invalid-feedback">Give Days</div>
             <?php echo form_error('days','<div style="color:red">','</div>');?>
          </div>

          <div class="form-group mb-0 col-md-4">
            <label>Price</label> <input type="number" class="form-control"
              name="display_price" required="" placeholder="price" <?php echo set_value('price')?>>
            <div class="invalid-feedback">Give some Price</div>
             <?php echo form_error('display_price','<div style="color:red">','</div>');?>
          </div>

          <div class="form-group mb-0 col-md-4">
            <label>Discounted Price</label> <input type="number" class="form-control"
              name="price" required="" placeholder="Discount Price" <?php echo set_value('price')?>>
            <div class="invalid-feedback">Give some Discounted Price</div>
             <?php echo form_error('price','<div style="color:red">','</div>');?>
          </div>
					
          <div class="form-group col-md-4">
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
