<section class="showcase">
    <div class="container">
      <div class="pb-2 mt-4 mb-2 border-bottom">
        <h2>Sub Categories bulk upload</h2>
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
          <a href="<?php print site_url();?>dump/sub_category_bulk.xlsx" class="btn btn-info btn-sm"><i class="fa fa-file-excel"></i> Sub Categories Sample .XLSX</a>
        </div> 
      </div>
      </div>
	<?php //print_array(validation_errors());?>
    <form action="<?php echo base_url('admin/bulk_upload/sub_category_upload')?>" class="excel-upl" id="excel-upl" enctype="multipart/form-data" method="post" accept-charset="utf-8">
      <div class="row padall">
        <div class="form-group col-md-12">
			<label>Upload files here</label>
        </div>
        <div class="col-6 mt-3">
        	<label for="validatedCustomFile">Upload images ZIP</label>
           	<input type="file" name="images_zip" id="validatedCustomFile" class="form-control" onchange="readURL(this);">
           	<?php echo form_error('images_zip','<div style="color:red">','</div>');?>
		</div>
		
		<div class="col-6 mt-3">
        	<label for="products_sheet">Upload Sub Categories sheet</label>
           	<input type="file" name="excel_file" id="excel_file" class="form-control" onchange="readURL(this);">
           	<?php echo form_error('excel_file','<div style="color:red">','</div>');?>
		</div>
		
        <div class="col-3 mt-4">
          <button type="submit" name="import" class="btn btn-lg btn-primary">Import</button>
        </div>
      </div>
    </form>
    
    </div>
  </section>