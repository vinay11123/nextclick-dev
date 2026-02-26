<!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
    <h4 class="ven">Add On Demand Category</h4>
    <form class="needs-validation" novalidate="" action="<?php echo base_url('od_categories/c');?>" method="post" enctype="multipart/form-data">
      <div class="card-header">
        <div class="form-row">
          <div class="form-group mb-0 col-md-3">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required="" placeholder="Title" <?php echo set_value( 'name')?>>
            <div class="invalid-feedback">Give Title</div>
            <?php echo form_error( 'name', '<div style="color:red">', '</div>');?>
          </div>
           <div class="form-group mb-0 col-md-4">
            <label>Category</label>
           <select class="form-control" name="cat_id" required="" id="cat_id">
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($categories as $category):?>
                    <option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">Give Title</div>
            <?php echo form_error( 'name', '<div style="color:red">', '</div>');?>
          </div>
          <div class="form-group col-md-4">
            <label>Upload Image</label>
            <input type="file" name="file" required="" value="<?php echo set_value('file')?>" class="form-control" onchange="readURL(this);">
            
            <div class="invalid-feedback">Upload Image?</div>
            <?php echo form_error( 'file', '<div style="color:red">', '</div>');?></div>
            <div class="form-group col-md-1">
            
            <img id="blah" src="#" alt="">
            </div>
          <div class="col col-sm col-md-12">
            <label>Description</label>
            <textarea id="speciality_desc" name="desc" class="ckeditor" rows="10" data-sample-short></textarea>
            <?php echo form_error( 'desc', '<div style="color:red">', '</div>');?></div>
          <div class="form-group col-md-12">
            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>