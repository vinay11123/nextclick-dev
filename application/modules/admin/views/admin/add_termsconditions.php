<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<!--Add Sub_Category And its list-->
<div class="pcoded-main-container">
  <div class="pcoded-wrapper">
    <div class="pcoded-content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4 class="ven">Add T&C</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('terms_conditions/c'); ?>"
              method="post" enctype="multipart/form-data">
              <div class="card-header">

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Related To</label>
                    <!-- <input type="file" class="form-control" required="">-->
                    <select required class="form-control" name="app_id">
                      <option value="0" selected disabled>--select--</option>
                      <?php foreach ($app_details as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                          <?php echo $category['app_name'] ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Category Name?</div>
                    <?php echo form_error('app_id', '<div style="color:red>"', '</div>'); ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label>T&C Page</label>
                    <select required class="form-control" name="page_id">
                      <option value="0" selected disabled>--Select Page--</option>
                      <option value="1">Registration Page</option>
                      <option value="2">Login Page</option>
                      <option value="3">Payment Page</option>
                      <option value="4">App Terms&Condtions</option>
                      <option value="5">Security Deposit Page</option>
                    </select>
                    <div class="invalid-feedback">T&C Page?</div>
                    <?php echo form_error('page_id', '<div style="color:red>"', '</div>'); ?>
                  </div>


                  <div class="form-group mb-0 col-md-12">
                    <label>Title</label> <input type="text" class="form-control" name="title" required=""
                      placeholder="title" <?php echo set_value('title') ?>>
                    <div class="invalid-feedback">Give some Title</div>
                    <?php echo form_error('title', '<div style="color:red">', '</div>'); ?>
                  </div>

                  <div class="col col-sm col-md">
                    <label>T&C Description</label>
                    <textarea id="desc" name="desc" class="ckeditor" rows="10" data-sample-short></textarea>
                    <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                  </div>
                  <div class="form-group col-md-12">

                    <button class="btn btn-primary mt-27 ">Submit</button>
                  </div>


                </div>


              </div>
            </form>

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
<?php $this->load->view('vendorCrm/footer'); ?>