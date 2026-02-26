

    <!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Doctor</h4>
    <form class="needs-validation" novalidate="" action="<?php echo base_url('doctors/c');?>" method="post" enctype = "multipart/form-data">
      <div class="card-header">

        <div class="form-row">
         <div class="form-group mb-0 col-md-4">
            <label>Name</label> <input type="text" class="form-control"
              name="name" required="" placeholder="Title" >
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('title','<div style="color:red">','</div>');?>
          </div>
           <div class="form-group col-md-4">
            <label>Speciality List</label>
            <select required class="form-control" name="hosp_specialty_id"  >
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($specialities as $speciality):?>
                    <option value="<?php echo $speciality['id'];?>"><?php echo $speciality['name']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">New Category Name?</div>
            <?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
          </div>
           
           <div class="form-group mb-0 col-md-4">
            <label>Qualification</label> <input type="text" class="form-control"
              name="qualification" required="" placeholder="Qualification" <?php echo set_value('qualification')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('qualification','<div style="color:red">','</div>');?>
          </div>
           <div class="form-group mb-0 col-md-4">
            <label>Experience</label> <input type="number" class="form-control"
              name="experience" required="" placeholder="Experience" <?php echo set_value('experience')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('experience','<div style="color:red">','</div>');?>
          </div>
           <div class="form-group mb-0 col-md-4">
            <label>Languages</label> <input type="text" class="form-control"
              name="languages" required="" placeholder="Languages" <?php echo set_value('languages')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('languages','<div style="color:red">','</div>');?>
          </div>
          <div class="form-group mb-0 col-md-4">
            <label>Fee of Doctor</label> <input type="number" class="form-control"
              name="fee" required="" placeholder="Fee of Doctor" <?php echo set_value('fee')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('fee','<div style="color:red">','</div>');?>
          </div>
            <div class="form-group mb-0 col-md-4">
            <label>Discount</label> <input type="number" class="form-control"
              name="discount" required="" placeholder="Discount" <?php echo set_value('discount')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php echo form_error('discount','<div style="color:red">','</div>');?>
          </div>
           <!-- <div class="form-group mb-0 col-md-4">
            <label>Holidays</label> <input type="text" class="form-control"
              name="holidays" required="" placeholder="Discount" <?php// echo set_value('holidays')?>>
            <div class="invalid-feedback">Give Title</div>
             <?php// echo form_error('holidays','<div style="color:red">','</div>');?>
          </div> -->
           <div class="form-group col-md-4">
            <label>Upload Image</label>
            <input type="file" name="file" required="" value="<?php echo set_value('file')?>" class="form-control" onchange="readURL(this);">
            
            <div class="invalid-feedback">Upload Image?</div>
            <?php echo form_error( 'file', '<div style="color:red">', '</div>');?></div>
            <div class="form-group col-md-1">

            <img id="blah" src="#" alt="">
            </div>
          <div class="col col-sm col-md-12" >
          <label>Description</label>
            <textarea id="doctors_desc" name="desc" class="ckeditor" rows="10" data-sample-short></textarea>
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
