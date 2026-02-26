<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<main class="content_wrapper">
    <!--page title start-->

    <!--page title end-->
    <div class="container-fluid">
        <!-- state start-->
        <div class="row">
            <div class="col-12 mt-1 mb-2">
                <a class="btn-primary btn-sm" href="<?php echo base_url('executive/wallet'); ?>">Back</a>
            </div>
            <div class="col-12">
                <?php if (empty($executive_bank_details)) { ?>
                    <div class="card card-shadow mb-4">
                        <div class="card-body">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">

                                    <h3 class="text-primary mb-15">Add Bank Account</h3>
                                    <?php $form_data = $this->session->flashdata('form_data'); ?>
                                    <form action="<?php echo base_url('executive/bank_account/submit'); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="ac_holder_name" class="form-control" value="<?php echo set_value('ac_holder_name') ? set_value('ac_holder_name') : (isset($form_data['ac_holder_name']) ? $form_data['ac_holder_name'] : ''); ?>">
                                            <?php echo form_error('ac_holder_name', '<div class="text-danger">', '</div>'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">A/C Number <span class="text-danger">*</span></label>
                                            <input type="text" name="ac_number" class="form-control" value="<?php echo set_value('ac_number') ? set_value('ac_number') : (isset($form_data['ac_number']) ? $form_data['ac_number'] : ''); ?>">
                                            <?php echo form_error('ac_number', '<div class="text-danger">', '</div>'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Bank Name <span class="text-danger">*</span></label>
                                            <select name="bank_id" id="bank_id" class="form-control">
                                                <option value="" selected disabled>Select Bank</option>
                                                <?php foreach ($banks as $bank) : ?>
                                                    <option value="<?php echo $bank['id']; ?>" <?php if ($bank['id'] == $_POST['bank_id']) {
                                                                                                    echo "selected";
                                                                                                } else if ($bank['id'] == $form_data['bank_id']) {
                                                                                                    echo "selected";
                                                                                                } ?>>
                                                        <?php echo $bank['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php echo form_error('bank_name', '<div class="text-danger">', '</div>'); ?>

                                            <?php if ($error_bank_account = $this->session->flashdata('error_bank_account')) : ?>
                                                <div class="text-danger"><?php echo $error_bank_account; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">IFSC Code <span class="text-danger">*</span></label>
                                            <input type="text" name="ifsc" class="form-control" value="<?php echo set_value('ifsc') ? set_value('ifsc') : (isset($form_data['ifsc']) ? $form_data['ifsc'] : ''); ?>">
                                            <?php echo form_error('ifsc', '<div class="text-danger">', '</div>'); ?>

                                            <?php if ($error_ifsc = $this->session->flashdata('error_ifsc')) : ?>
                                                <div class="text-danger"><?php echo $error_ifsc; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <?php if ($error_message = $this->session->flashdata('error_message')) : ?>
                                                <div class="text-danger"><?php echo $error_message; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <button type="submit" id="submitLoaderButton" class="btn btn-primary btn-flat m-b-30 m-t-30">Submit</button>
                                    </form>
                                </div>


                            </div>
                        </div>

                    </div>
            </div>
        <?php } ?>
        <div class="col-12">
            <?php if (!empty($executive_bank_details)) : ?>
                <?php foreach ($executive_bank_details as $bank_details) : ?>
                    <div class="card card-shadow mb-4">
                        <div class="card-body">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">

                                    <h3 class="text-primary mb-15">Bank Account <span class="float-right">
                                            <!-- <input type="radio" name="primary_account" class="primary-account-radio" data-account-number="<?php echo $bank_details->ac_number; ?>" data-ifsc="<?php echo $bank_details->ifsc; ?>" <?php if ($bank_details->is_primary) echo 'checked="checked"'; ?>> -->
                                        </span></h3>
                                    <p>
                                        <?php echo $bank_details->bank_name; ?>
                                        <?php if ($bank_details->is_primary) { ?><span class="small badge-success">Primary</span> <?php } ?><br>
                                        <?php echo $bank_details->ac_holder_name; ?><br>
                                        <?php echo $bank_details->ac_number; ?><br>
                                        <?php echo $bank_details->ifsc; ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


        </div>
        </div>
        <!-- state end-->
    </div>
</main>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>