
    <!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Request</h4>
    <form class="needs-validation" novalidate=""
      action="<?php echo base_url('request/c');?>" method="post"
      enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
         <div class="form-group mb-0 col-md-12">
            <label>Title</label> <input type="text" class="form-control"
              name="title" required="" placeholder="Title" <?php echo set_value('title')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('title','<div style="color:red">','</div>');?>
          </div>

          <div class="col col-sm col-md-12" >
          <label>Description</label>
            <textarea id="request_desc" name="desc" class="ckeditor" rows="10" data-sample-short></textarea>
           <?php echo form_error('desc', '<div style="color:red">', '</div>');?>
         </div>
          <div class="form-group col-md-12">

            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>
</div>
</div>
    </form>
</div>
</div>
