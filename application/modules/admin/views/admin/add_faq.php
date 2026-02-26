<!--Add Sub_Category And its list-->
<div class="row"id="description_hide">
  <div class="col-12">
    <h4 class="ven">Add FAQ</h4>
    <form class="needs-validation" novalidate=""
      action="<?php echo base_url('faq/c');?>" method="post"
      enctype="multipart/form-data">
      <div class="card-header">

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Related To</label>
            <!-- <input type="file" class="form-control" required="">-->
            <select required class="form-control" name="app_id"  >
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($app_details as $category):?>
                    <option value="<?php echo $category['id'];?>"><?php echo $category['app_name']?></option>
                  <?php endforeach;?>
            </select>
            <div class="invalid-feedback">New Category Name?</div>
            <?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
          </div>


          <div class="form-group mb-0 col-md-6">
            <label>Question</label> <input type="text" class="form-control"
              name="question" required="" placeholder="Question" <?php echo set_value('question')?>>
            <div class="invalid-feedback">Give some Description</div>
             <?php echo form_error('question','<div style="color:red">','</div>');?>
          </div>

          <div class="col col-sm col-md" >
<label>Answer</label>
            <textarea id="product_desc" name="answer" class="ckeditor" rows="10" data-sample-short></textarea>
           <?php echo form_error('answer', '<div style="color:red">', '</div>');?>
         </div>
          <div class="form-group col-md-12">

            <button class="btn btn-primary mt-27 ">Submit</button>
          </div>


        </div>


      </div>
    </form>

    

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
<style>
#description_hide #cke_1_top {
	display:none;
}
</style>