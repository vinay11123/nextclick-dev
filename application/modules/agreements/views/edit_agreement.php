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
                                <li class="breadcrumb-item">Edit Agreement</li>
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
                                                action="<?php echo base_url('agreements/u'); ?>" method="post"
                                                enctype="multipart/form-data">

                                                <input type="hidden" name="id" value="<?php if (isset($_POST['id']))
                                                    echo $_POST['id'];
                                                else
                                                    echo $_GET['id']; ?>">

                                                <div class="form-group">
                                                    <label for="app_id">App Type</label>
                                                    <select class="form-control border" name="app_id" required>
                                                        <option value="0" selected disabled>select</option>
                                                        <?php foreach ($app_details as $app_detail): ?>
                                                            <option value="<?php echo $app_detail['id']; ?>" <?php echo ($app_detail['id'] == $aggrementDetails['app_details_id']) ? 'selected' : ''; ?>>
                                                                <?php echo $app_detail['app_name'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a category.</div>
                                                    <?php echo form_error('app_id', '<div style="color:red;">', '</div>'); ?>
                                                </div>

                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" class="form-control" value="<?php if (isset($_POST['title']))
                                                        echo $_POST['title'];
                                                    else
                                                        echo $aggrementDetails['title']; ?>">
                                                    <div class="invalid-feedback">Please provide a title.</div>
                                                    <?php echo form_error('title', '<div style="color:red;">', '</div>'); ?>
                                                </div>

                                                <div class="form-group border-left border-right">
                                                    <label for="description">Description</label>
                                                    <textarea id="description" name="description"
                                                        class="ckeditor form-control cke_wysiwyg" rows="10" required><?php if (isset($_POST['description']))
                                                            echo $_POST['description'];
                                                        else
                                                            echo $aggrementDetails['description']; ?></textarea>
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