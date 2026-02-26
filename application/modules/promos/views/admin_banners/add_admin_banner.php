<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Admin Banners</h4>
    <form class="needs-validation" action="<?php echo base_url('admin_banners/s'); ?>" method="post" enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
          <div class="form-group mb-0 col-md-6">
            <label>Position</label>
            <select class="form-control" name="promotion_banner_position_id" required="" id="promotion_banner_position_id">
              <option value="0" selected disabled>--select--</option>
              <?php foreach ($positions as $position) : ?>
                <option value="<?php echo $position['id']; ?>"<?php if ($position['id'] == set_value('promotion_banner_position_id')) echo "selected = 'selected'"?>><?php echo $position['title'] ?></option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Give Position</div>
            <?php echo form_error('promotion_banner_position_id', '<div style="color:red">', '</div>'); ?>
          </div>
          <div class="form-group col-md-6">
            <label>Upload Image</label>
            <input type="file" name="image" id="image" accept="image/jpeg, image/jpeg, image/png"  value="<?php echo set_value('image') ?>" class="form-control" onchange="readURL(this);">
            <div class="invalid-feedback">Upload Image?</div>
            <?php echo form_error('image', '<div style="color:red">', '</div>'); ?>
          </div>
          <div class="form-group col-md-12">
            <label>Status</label>
            <select class="form-control" name="status" required id="status">

              <option value="1">Active</option>
              <option value="0">Inactive</option>

            </select>
            <div class="invalid-feedback">Give Category</div>
            <?php echo form_error('status', '<div style="color:red">', '</div>'); ?>
          </div>

          <div class="form-group col-md-12">

            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>


        </div>


      </div>
    </form>



  </div>
</div>