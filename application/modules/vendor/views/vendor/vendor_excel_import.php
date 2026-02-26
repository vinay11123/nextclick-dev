<section class="showcase">
    <div class="container">
      <div class="pb-2 mt-4 mb-2 border-bottom">
        <h2>Import Vendors</h2>
        <?php if(! empty($this->session->flashdata('upload_status')['success'])){?>
        	<div class="alert alert-success"><h5><?php echo $this->session->flashdata('upload_status')['success'];?></h5></div>
        <?php } elseif (! empty($this->session->flashdata('upload_status')['error'])) {?>
        	<div class="alert alert-danger"><h5><?php echo $this->session->flashdata('upload_status')['error'];?></h5></div>
        <?php }?>
      </div>

      <?php if(form_error('fileURL')) {?>    
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php print form_error('fileURL'); ?>
        </div>       
    <?php } ?>
    <div class="row padall border-bottom">  
      <div class="col-lg-12">
      <div class="float-right">  
          <a href="<?php print site_url();?>dump/Vendor_bulk_latest_new_fields.xls" class="btn btn-info btn-sm"><i class="fa fa-file-excel"></i> Sample Sheet</a>
        </div> 
      </div>
      </div>

    <form action="<?php print site_url();?>vendor_excel_import" class="excel-upl" id="excel-upl" enctype="multipart/form-data" method="post" accept-charset="utf-8">
      <div class="row padall">
        <div class="col-6 mt-3">
          <input type="file" class="custom-file-input" id="validatedCustomFile" name="fileURL">
          <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
        </div>
        <div class="col-2 mt-4">
          <button type="submit" name="import" class="btn btn-lg btn-primary">Import</button>
        </div>
      </div>
    </form>
    </div>
  </section>