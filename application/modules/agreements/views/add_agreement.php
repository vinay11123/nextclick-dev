<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
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
                                <li class="breadcrumb-item">Add Agreement</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- Main-body start -->
            <div class="main-body">
                <div class="page-wrapper">

                    <!-- Page-body start -->
                    <div class="page-body">

                        <div class="row">
                            <div class="col-12">

                                <div class="card-body">
                                    <div class="card">

                                        <div class="card-body">

                                            <form class="needs-validation" novalidate=""
                                                action="<?php echo base_url('agreements/c'); ?>" method="post"
                                                enctype="multipart/form-data">


                                                <div class="form-group">
                                                    <label for="app_id">App Type</label>
                                                    <select class="form-control border" name="app_id" required>
                                                        <option value="" disabled <?php echo set_select('app_id', '', TRUE); ?>>-- Select --</option>
                                                        <?php foreach ($app_details as $category): ?>
                                                            <option value="<?php echo $category['id']; ?>" <?php echo set_select('app_id', $category['id']); ?>>
                                                                <?php echo $category['app_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a category.</div>
                                                    <?php echo form_error('app_id', '<div style="color:red;">', '</div>'); ?>
                                                </div>






                                                <!-- <div class="form-group">
                                                    <label for="app_id">App Type</label>
                                                    <select class="form-control border" name="app_id" required>
                                                        <option value="" disabled selected>-- Select --</option>
                                                        <?php foreach ($app_details as $category): ?>
                                                            <option value="<?php echo $category['id']; ?>">
                                                                <?php echo $category['app_name'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a category.</div>
                                                    <?php echo form_error('app_id', '<div style="color:red;">', '</div>'); ?>
                                                </div> -->

                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" class="form-control" name="title" required
                                                        placeholder="Title" value="<?php echo set_value('title'); ?>">
                                                    <div class="invalid-feedback">Please provide a title.</div>
                                                    <?php echo form_error('title', '<div style="color:red;">', '</div>'); ?>
                                                </div>

                                                <div class="form-group border-left border-right">
                                                    <label for="description">Description</label>
                                                    <textarea id="description" name="description"
                                                        class="ckeditor form-control cke_wysiwyg" rows="10"
                                                        required><?php echo set_value('description'); ?></textarea>
                                                    <div class="invalid-feedback">Please provide a description.</div>
                                                    <?php echo form_error('description', '<div style="color:red;">', '</div>'); ?>
                                                </div>


                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('vendorCrm/footer'); ?>