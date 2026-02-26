<?php if ($type == 'user_services') { ?>

  <!--Edit State -->
  <div class="row">
    <div class="col-12">
      <h4 class="ven">Edit Service</h4>
      <form class="needs-validation" novalidate="" action="<?php echo base_url('user_services/u'); ?> " method="post"
        enctype="multipart/form-data">
        <div class="card-header">
          <div class="form-row">
            <div class="form-group col-md-6">

              <label>Service Name</label>
              <input type="text" name="name" class="form-control" required="" value="<?php echo $services['name']; ?>">

              <div class="invalid-feedback">Enter Valid Service Name?</div>
              <input type="hidden" name="id" value="<?php echo $services['id']; ?>">

            </div>

            <div class="form-group col-md-6">
              <button class="btn btn-primary mt-27 ">Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

<?php } elseif ($type == 'category_banner') { ?>
  <div class="row">
    <div class="col-12">
      <h4 class="ven">Edit Banner</h4>
      <form class="needs-validation" novalidate="" action="<?php echo base_url('category_banner/u'); ?> " method="post"
        enctype="multipart/form-data">
        <div class="card-header">

          <div class="form-row">
            <div class="form-group col-md-6">

              <div class="form-group col-md-6">
                <label>Upload Image</label>
                <input type="file" id='input1' name="file" class="form-control" onchange="readURL(this);"
                  value="<?php echo base_url(); ?>uploads/cat_banners_image/cat_banners_<?php echo $category['id']; ?>.jpg">
                <br><img id="imagepreview1"
                  src="<?php echo base_url(); ?>uploads/cat_banners_image/cat_banners_<?php echo $_GET['cat_id']; ?>_<?php echo $_GET['id']; ?>.jpg"
                  style="width: 200px;" />

                <img id="blah" src="#" alt="" style="width: 200px;" />
                <input type="hidden" name="banner_id" value="<?php echo $_GET['id']; ?>" />
                <input type="hidden" name="cat_id" value="<?php echo $_GET['cat_id']; ?>" />
                <div class="invalid-feedback">Upload Image?</div>
              </div>
              <div class="form-group col-md-6">
                <button class="btn btn-primary mt-27 ">Update</button>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div>
<?php } elseif ($type == 'faq') { ?>
  <style>
    #description_hide #cke_1_top {
      display: none;
    }
  </style>
  <!--Add Sub_Category And its list-->
  <div class="row" id="description_hide">
    <div class="col-12">
      <h4 class="ven">Edit FAQ</h4>
      <form class="needs-validation" novalidate="" action="<?php echo base_url('faq/u'); ?>" method="post"
        enctype="multipart/form-data">
        <div class="card-header">

          <div class="form-row">

            <div class="form-group col-md-6">
              <label>Related To</label>
              <select class="form-control" name="app_id" required="">
                <option value="0" selected disabled>select</option>
                <?php foreach ($app_details as $app_detail): ?>
                  <option value="<?php echo $app_detail['id']; ?>" <?php echo ($app_detail['id'] == $faq['app_id']) ? 'selected' : ''; ?>>
                    <?php echo $app_detail['app_name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">


            <div class="form-group mb-0 col-md-6">
              <label>Question</label>
              <input type="text" name="question" class="form-control" value="<?php echo $faq['question']; ?>">
              <div class="invalid-feedback">Give some Question</div>
            </div>

            <div class="col col-sm col-md">
              <label>Answer</label>
              <textarea id="product_desc" name="answer" class="ckeditor" rows="10"
                data-sample-short><?php echo $faq['answer'] ?></textarea>
              <?php echo form_error('answer', '<div style="color:red">', '</div>'); ?>
            </div>
            <div class="form-group col-md-12"><button class="btn btn-primary mt-27 ">Update</button>
            </div>


          </div>


        </div>
      </form>



    </div>
  </div>

<?php } elseif ($type == 'termsconditions') { ?>
  <?php $this->load->view('vendorCrm/header'); ?>
  <?php $this->load->view('vendorCrm/sidebar'); ?>
  <!--Add Sub_Category And its list-->
  <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
      <div class="pcoded-content">
        <div class="container">
          <div class="row pb-4">
            <div class="col-md-12">
              <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;"
                href="<?php echo base_url('terms_conditions/r'); ?>">Terms&Conditions
                <i class="feather icon-chevron-left"></i>
                Edit T&C</a>

            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <h4 class="ven">Edit T&C</h4>
              <form class="needs-validation" novalidate="" action="<?php echo base_url('terms_conditions/u'); ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">

                  <div class="form-row">

                    <div class="form-group col-md-6">
                      <label>Related To</label>
                      <select class="form-control" id="app_id" name="app_id" required="">
                        <option value="0" selected disabled>select</option>
                        <?php foreach ($app_details as $app_detail): ?>
                          <option value="<?php echo $app_detail['id']; ?>" <?php echo ($app_detail['id'] == $termsconditions['app_details_id']) ? 'selected' : ''; ?>>
                            <?php echo $app_detail['app_name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label>T&c Page</label>
                      <select class="form-control" id="page_id" name="page_id" required="">
                        <option value="1" <?php if ($termsconditions['page_id'] == 1) {
                          echo 'selected';
                        } ?>>Registration
                          Page</option>
                        <option value="2" <?php if ($termsconditions['page_id'] == 2) {
                          echo 'selected';
                        } ?>>Login Page
                        </option>
                        <option value="3" <?php if ($termsconditions['page_id'] == 3) {
                          echo 'selected';
                        } ?>>Payment Page
                        </option>
                        <option value="4" <?php if ($termsconditions['page_id'] == 4) {
                          echo 'selected';
                        } ?>>App
                          Terms&Condtions</option>
                        <option value="5" <?php if ($termsconditions['page_id'] == 5) {
                          echo 'selected';
                        } ?>>Security Deposit
                          Page</option>
                      </select>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $termsconditions['id']; ?>">


                    <div class="form-group mb-0 col-md-12">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control"
                        value="<?php echo $termsconditions['title']; ?>">
                      <div class="invalid-feedback">Give some Title</div>
                    </div>

                    <div class="col col-sm col-md">
                      <label>Description</label>
                      <textarea id="desc" name="desc" class="ckeditor" rows="10"
                        data-sample-short><?php echo $termsconditions['desc'] ?></textarea>
                      <?php echo form_error('desc', '<div style="color:red">', '</div>'); ?>
                    </div>
                    <div class="form-group col-md-12"><button class="btn btn-primary mt-27 ">Update</button>
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
  <?php $this->load->view('vendorCrm/footer'); ?>
<?php } ?>