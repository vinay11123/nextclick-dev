<section class="showcase">
    <div class="container">
        <div class="pb-2 mt-4 mb-2 border-bottom">
            <h2>Catalogue bulk upload</h2>
            <?php if (!empty($this->session->flashdata('upload_status')['success'])) { ?>
                <div class="alert alert-success">
                    <h5><?php echo $this->session->flashdata('upload_status')['success']; ?></h5>
                </div>
            <?php } elseif (!empty($this->session->flashdata('upload_status')['error'])) { ?>
                <div class="alert alert-danger">
                    <h5><?php echo $this->session->flashdata('upload_status')['error']; ?></h5>
                </div>
            <?php } ?>
        </div>

        <?php if (form_error('fileURL')) { ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php print form_error('fileURL'); ?>
            </div>
        <?php } ?>
        <div class="row padall border-bottom">
            <div class="col-lg-12">
                <div class="float-right">
                    <a href="<?php echo base_url('category/r'); ?>" class="btn btn-info btn-sm"><i class="fa fa-file-excel"></i> Catalogue Sample .XLSX</a>
                </div>
            </div>
        </div>
        <?php //print_array(validation_errors());
        ?>
        <form action="<?php echo base_url('catalogue/catalogue_upload')?>" class="excel-upl" id="excel-upl" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="row padall">
                <div class="form-group col-md-12">
                    <label>Upload files here</label>
                </div>
                <div class="col-6 mt-3">
                    <label for="vendor_id">Vendor<span class="text-danger">*</span></label>
                    <select class="form-control" required="" id="vendor_id" name="vendor_id">
                        <option value="" selected disabled>--select Vendor--</option>
                        <?php foreach ($vendor_lists as $vendor_list) : ?>
                            <option value="<?php echo $vendor_list['vendor_id']; ?>" <?php if (set_value('vendor_id') == $vendor_list['vendor_id']) echo 'selected'; ?>><?php echo $vendor_list['vendor_business_name'] . " (" . $vendor_list['vendor_name'] . "-" . $vendor_list['vendor_phone_no'] . ")" ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">select Vendor Name</div>
                    <?php echo form_error('vendor_id', '<div style="color:red>"', '</div>'); ?>
                </div>

                <div class="col-6 mt-3">
                    <label for="products_sheet">Upload Catalogue sheet</label>
                    <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls,.csv" onchange="readURL(this);">
                    <?php echo form_error('excel_file', '<div style="color:red">', '</div>'); ?>
                </div>

                <div class="col-3 mt-4">
                    <button type="submit" name="import" class="btn btn-lg btn-primary">Import</button>
                </div>
            </div>
        </form>

    </div>
</section>