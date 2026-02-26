<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Bulk Upload</li>
                                <li class="breadcrumb-item">Catalogue</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="pcoded-inner-content">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">

                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">

                                <div class="col-xl-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header text-dark">
                                            <h5 class="text-dark" style="margin-top: 20px;"><i class="feather icon-home"></i> Bulk Upload Catalogue</h5>
                                            <span class="fa-pull-right"><a href="<?php echo base_url('vendor_crm/catalogue/export') ?>" class="btn btn-info"><i class="feather icon-file-plus"></i> Catalogue Sample .XLSX</a></span>
                                        </div>
                                        <?php if (!empty($this->session->flashdata('upload_status')['success'])) { ?>
                                            <div class="alert alert-success">
                                                <h5><?php echo $this->session->flashdata('upload_status')['success']; ?></h5>
                                            </div>
                                        <?php }
                                        if (!empty($this->session->flashdata('upload_status')['error'])) { ?>
                                            <div class="alert alert-danger">
                                                <h5><?php echo $this->session->flashdata('upload_status')['error']; ?></h5>
                                            </div>
                                        <?php } ?>
                                        <div class="card-block">
                                            <form action="<?php echo base_url('vendor_crm/catalogue/catalogue_upload') ?>" class="excel-upl" id="excel-upl" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Bulk Upload Catalogue<span class="text-danger">*</span></label>
                                                        <input type="file" name="excel_file" id="excel_file" class="form-control" onchange="readURL(this);">
                                                        <?php echo form_error('excel_file', '<div style="color:red">', '</div>'); ?>
                                                    </div>
                                                    <div class="col-md-6 mt-3">
                                                        <button type="submit" name="import" class="btn btn-primary" style="margin-top: 15px;">Import</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>

</div>
</div>
</div>
<?php $this->load->view('vendorCrm/footer'); ?>