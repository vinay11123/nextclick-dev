<!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Request</h4>
    <form class="needs-validation" novalidate=""
      action="<?php echo base_url('support/u');?>" method="post"
      enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
          
          <div class="form-group col-md-12">

      <input type = "hidden" name = "id" value ="<?php echo $requests['id']; ?>">
            <label>Relate To:</label>
            <!-- <input type="file" class="form-control" required="">-->
            <select required class="form-control" name="req_id"  >
                <option value="0" >--select--</option>
                  <?php foreach ($request_type as $category):?>
            <option value="<?php echo $category['id'];?>"<?php if($category['id']==$requests['req_id'])  echo 'selected';?>><?php echo $category['title']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">New Category Name?</div>
            <?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
          </div>

                    <div class="form-group col-md-12">
            <label>Contact Mail</label> <input type="text"
              class="form-control" name="contact_mail" placeholder="Contact Mail" required="" value="<?php echo $requests['contact_mail']; ?>">
            <div class="invalid-feedback">Enter Contact MailID</div>
            <?php echo form_error('contact_mail','<div style="color:red">','</div>')?>
          </div>

           <div class="form-group col-md-12">
            <label>Full Name</label> <input type="text"
              class="form-control" name="fullname" placeholder="full name" required="" value="<?php echo $requests['name']; ?>">
            <div class="invalid-feedback">Enter Name</div>
            <?php echo form_error('fullname','<div style="color:red">','</div>')?>
          </div>

          
           <div class="col col-sm col-md-12" >
          <label>Description</label>
            <textarea id="request_desc" name="req_content" class="ckeditor" rows="10" data-sample-short><?php echo $requests['req_content']; ?></textarea>
           <?php echo form_error('req_content', '<div style="color:red">', '</div>');?>
         </div>
          <div class="form-group col-md-12">

            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>


        </div>


      </div>
    </form>

    

  </div>
</div>

