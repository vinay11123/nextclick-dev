<div class="container">
    <div class="row">
        <div class="col-md-12" style="">
            <form id="form_site_settings" action="<?php echo base_url('settings/site'); ?>" method="post"
                class="needs-validation reset" novalidate="" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">System Settings</h2>
                    </header>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">System Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_name" class="form-control" placeholder="System Name"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'system_name')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                            <?php echo form_error('system_name', '<div style="color:red">', '</div>'); ?>
                            <input type="hidden" name="id" value="">
                            <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">System Title <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_title" class="form-control" placeholder="System Title "
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'system_title')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                            <?php echo form_error('system_title', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Mobile Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="mobile" class="form-control" placeholder="Mobile Number"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'mobile')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Mobile Number?</div>
                            <?php echo form_error('mobile', '<div style="color:red" "margin_left=100px">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Address<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" style=" height: 70px " name="address"
                                    value=" <?php echo $this->setting_model->where('key', 'address')->get()['value']; ?>">

                            </div>
                            <div class="invalid-feedback">Address?</div>
                            <?php echo form_error('address', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Facebook Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="facebook" class="form-control" placeholder="Facebook Link"
                                    value="<?php echo $this->setting_model->where('key', 'facebook')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Facebook Link?</div>
                            <?php echo form_error('facebook', '<div style="color:red ">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Twiter Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="twiter" class="form-control" placeholder="Twiter Link"
                                    value="<?php echo $this->setting_model->where('key', 'twiter')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Twiter Link?</div>
                            <?php echo form_error('twiter', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Youtube Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="youtube" class="form-control" placeholder="Youtube Link"
                                    value="<?php echo $this->setting_model->where('key', 'youtube')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Youtube Link?</div>
                            <?php echo form_error('youtube', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Skype Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="skype" class="form-control" placeholder="Skype Link"
                                    value="<?php echo $this->setting_model->where('key', 'skype')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Skype Link?</div>
                            <?php echo form_error('skype', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pinterest Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="pinterest" class="form-control" placeholder="Pinterest Link"
                                    value="<?php echo $this->setting_model->where('key', 'pinterest')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Pinterest Link</div>
                            <?php echo form_error('mobile', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 ">Lead Allocation Time(In Minutes)</label>
                            <div class="col-sm-9">
                                <input type="text" name="lead_allocation_time" class="form-control"
                                    placeholder="Lead Allocation Time(In Minutes)"
                                    value="<?php echo $this->setting_model->where('key', 'lead_allocation_time')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Pinterest Link</div>
                            <?php echo form_error('lead_allocation_time', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 ">Ecom Deliver Partner Earning GST %</label>
                            <div class="col-sm-9">
                                <input type="text" name="ecom_delivery_partner_earning_gst_percentage"
                                    class="form-control" placeholder="Ecom Deliver Partner Earning GST %"
                                    value="<?php echo $this->setting_model->where('key', 'ecom_delivery_partner_earning_gst_percentage')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Pinterest Link</div>
                            <?php echo form_error('ecom_delivery_partner_earning_gst_percentage', '<div style="color:red">', '</div>'); ?>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3">Next Click Digital Signature</label>
                            <div class="col-sm-2">
                                <input type="file" name="digital_signature" id="digital_signature"
                                    accept=".jpg, .jpeg, .png, .gif">
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3">
                                <?php if (isset($user_signature) && !empty($user_signature)):
                                    $signatureFilePath = base_url('uploads/admin/' . $user_signature);
                                    ?>
                                    <img src="<?php echo $signatureFilePath; ?>" alt="User Signature"
                                        style="height: 50px;width: 100px !important;">
                                <?php endif; ?>
                            </div>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_site_settings')"
                                    value="Reset" />
                            </div>
                        </div>

                    </div>

                </section>
            </form>
        </div>
        <div class="col-md-6">
            <form id="form_sms" action="<?php echo base_url('settings/'); ?>" class="needs-validation" novalidate=""
                method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMS Settings</h2>
                    </header>
                    <br>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4">Username <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="sms_username" class="form-control" placeholder="Username"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'sms_username')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">sms_username?</div>
                            <?php echo form_error('sms_username', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-4">Sender <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="sms_sender" class="form-control" placeholder="Sender"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'sms_sender')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">sms_sender?</div>
                            <?php echo form_error('sms_sender', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-4">Hash Key <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="sms_hash" class="form-control" placeholder="Hash Key"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'sms_hash')->get()['value']; ?>">
                            </div>
                            <div class="invalid-feedback">Hash Key?</div>
                            <?php echo form_error('sms_hash', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_sms')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/smtp'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMTP Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4">SMTP Port <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="smtp_port" class="form-control" placeholder="SMTP Port"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'smtp_port')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">smtp_port?</div>
                            <?php echo form_error('smtp_port', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">SMTP Host<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="smtp_host" class="form-control" placeholder="SMTP Host"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'smtp_host')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">smtp_host?</div>
                            <?php echo form_error('smtp_host', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">SMTP Username<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="smtp_username" class="form-control" placeholder="SMTP Username"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'smtp_username')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">smtp_username?</div>
                            <?php echo form_error('smtp_username', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">SMTP Password<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="smtp_password" class="form-control" placeholder="SMTP Password"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'smtp_password')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">smtp_password?</div>
                            <?php echo form_error('smtp_password', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('site_logo/logo'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Logo</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Logo</label>
                            <div class="col-sm-9">
                                <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                                <?php echo form_error('file', '<div style="color:red">', '</div>'); ?>
                                <br><br />
                                <img style="width:30%;" id="blah" src="<?php echo base_url(); ?>assets/img/logo.png"
                                    alt="Logo" />
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('site_logo/favicon'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Favicon</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Favicon</label>
                            <div class="col-sm-9">
                                <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                                <?php echo form_error('file', '<div style="color:red">', '</div>'); ?>
                                <br><br />
                                <img id="blah" src="<?php echo base_url(); ?>assets/img/favicon.png"
                                    style="height: 30px;width: 30px !important;" alt="Favicon" />
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')"
                                    value="Reset" />
                            </div>
                        </div>

                    </div>

                </section>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/payment'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Pay per vendor<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="pay_per_vendor" class="form-control"
                                    placeholder="Pay per vendor" required=""
                                    value="<?php echo $this->setting_model->where('key', 'pay_per_vendor')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay per vendor?</div>
                            <?php echo form_error('pay_per_vendor', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Vendor validation count<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="vendor_validation" class="form-control"
                                    placeholder="Vendor validation count" required=""
                                    value="<?php echo $this->setting_model->where('key', 'vendor_validation')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Vendor validation count?</div>
                            <?php echo form_error('vendor_validation', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/order_payment'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Orders Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Pay per Order<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="pay_per_order" class="form-control" placeholder="Pay Per Order"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'pay_per_order')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_order', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Order Validation Count<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="order_validation" class="form-control"
                                    placeholder="Order Validation Count" required=""
                                    value="<?php echo $this->setting_model->where('key', 'order_validation')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Order Validation Count?</div>
                            <?php echo form_error('order_validation', '<div style="color:red">', '</div>'); ?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/news'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">News Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Pay per News<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="pay_per_news" class="form-control" placeholder="Pay Per News"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'pay_per_news')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_news', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>


        <!--MAX AMOUNT FOR CASH ON DELIVERY ORDERS-->

        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/cod'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">COD Max Amount Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Max Amount Per Order<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="max_amount" class="form-control"
                                    placeholder="Max Amount Per Order" required=""
                                    value="<?php echo $this->setting_model->where('key', 'max_amount')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Amount Per Order?</div>
                            <?php echo form_error('max_amount', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>


        <!--start ramkrishna-->
        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/orders'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Order Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Automatic confirmation time<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="order_confirmation_time" class="form-control"
                                    placeholder="Automatic confirmation time" required=""
                                    value="<?php echo $this->setting_model->where('key', 'order_confirmation_time')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_news', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Automatic cancellation time<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="order_cancellation_time" class="form-control"
                                    placeholder="Automatic cancellation time" required=""
                                    value="<?php echo $this->setting_model->where('key', 'order_cancellation_time')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_news', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Customer penalty(in %)<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="customer_penalty_in_percentage" class="form-control"
                                    placeholder="Customer penalty" required=""
                                    value="<?php echo $this->setting_model->where('key', 'customer_penalty_in_percentage')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('customer_penalty_in_percentage', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Wide area search</label>
                            <div class="col-sm-7">
                                <input type="checkbox" name="wide_area_search" <?php echo $this->setting_model->where('key', 'wide_area_search')->get()['value'] == 1 ? 'checked' : ''; ?> data-toggle="toggle" data-style="ios" data-on="ON" data-off="OFF"
                                    data-onstyle="success" data-offstyle="danger">
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
        <!--end ramakrishna -->


        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/bank'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Bank Details</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4">UPI ID <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_upi_id" class="form-control" placeholder="UPI ID"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'bank_upi_id')->get()['value'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Bank<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_name" class="form-control" placeholder="Bank" required=""
                                    value="<?php echo $this->setting_model->where('key', 'bank_name')->get()['value'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Account No.<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_account_no" class="form-control" placeholder="Account No."
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'bank_account_no')->get()['value'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">IFSC Code<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_ifsc_code" class="form-control" placeholder="IFSC Code"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'bank_ifsc_code')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>


        <!-- start ramakrishna start 11/11/2021 -->
        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/delivery_partner_security_deposit'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Delivery Boy Fixed Deposit Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group">
                            <?php foreach ($vechile as $item): ?>
                                <div class="form-group row">
                                    <label class="col-sm-4">
                                        <?php echo $item['name'] ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="vehicle_output[]" type="number"
                                            placeholder="Security Deposit Amount" required=""
                                            value="<?php echo $item['security_deposited_amount'] ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <!--MAX AMOUNT FOR CASH ON DELIVERY ORDERS-->

        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/maxTotalWeight'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Max Total Weight Of Order</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Max Total Weight Of Order (in gms)<span
                                    class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="max_order_weight" class="form-control"
                                    placeholder="Max Total Weight Per Order" required=""
                                    value="<?php echo $this->setting_model->where('key', 'max_order_weight')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Weight Of Order?</div>
                            <?php echo form_error('max_order_weight', '<div style="color:red">', '</div>'); ?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
        <!-- end ramakrishna 11/11/2021 -->

        <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/maxTotalDistance'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Max Total Distance Of Order</h2>
                    </header>
                    <div class="card-body">
                        <!-- <div class="form-group row"> -->
                        <!-- <label class="col-sm-5">Max Total Distance Of Order (in km)<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="max_order_distance" class="form-control" placeholder="Max Total Weight Per Order" required="" value="<?php echo $this->setting_model->where('key', 'max_order_distance')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Weight Of Order?</div> -->
                        <?php
                        // echo form_error('max_order_distance','<div style="color:red">','</div>');
                        ?>

                        <!-- <label class="col-sm-5">Vendor To User (in km)<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="vendor_to_user_max_distance" class="form-control"
                                    placeholder="Max Total Distance from vendor to user" required=""
                                    value="<?php echo $this->setting_model->where('key', 'vendor_to_user_max_distance')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Distance from vendor to user?</div>
                            <?php
                            // echo form_error('vendor_to_user_max_distance', '<div style="color:red">', '</div>');
                            ?> -->
                        <!-- </div> -->

                        <!-- <div class="form-group row">
                            <label class="col-sm-5">Vendor To Delivery Captain (in km)<span
                                    class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="vendor_to_delivery_captain_max_distance" class="form-control"
                                    placeholder="Max Total Distance from vendor to captain" required=""
                                    value="<?php echo $this->setting_model->where('key', 'vendor_to_delivery_captain_max_distance')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Distance from vendor to captain?</div>
                            <?php
                            echo form_error('vendor_to_delivery_captain_max_distance', '<div style="color:red">', '</div>');
                            ?>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-sm-5">Pickup Address To Delivery Address (in km)<span
                                    class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="pickup_address_to_delivery_address_max_distance"
                                    class="form-control"
                                    placeholder="Max Total Distance from pickup address to delivery address" required=""
                                    value="<?php echo $this->setting_model->where('key', 'pickup_address_to_delivery_address_max_distance')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Distance from pickup address to delivery address?
                            </div>
                            <?php
                            echo form_error('pickup_address_to_delivery_address_max_distance', '<div style="color:red">', '</div>');
                            ?>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5">Pickup Address To Delivery Captain (in km)<span
                                    class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="number" name="pickup_address_to_delivery_captain_max_distance"
                                    class="form-control" placeholder="Max Total Distance from pickup address to captain"
                                    required=""
                                    value="<?php echo $this->setting_model->where('key', 'pickup_address_to_delivery_captain_max_distance')->get()['value'] ?>">
                            </div>
                            <div class="invalid-feedback">Max Total Distance from pickup address to captain?</div>
                            <?php
                            echo form_error('pickup_address_to_delivery_captain_max_distance', '<div style="color:red">', '</div>');
                            ?>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')"
                                    value="Reset" />
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/free_delivery_min_amount_update'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Free Delivery Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Min Order Amount <span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="fd_minimum_amount" class="form-control" placeholder="UPI ID"
                                    required="" value="<?php echo $free_delivery_settings['minimum_amount']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Upload Image<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="file" accept="image/jpeg,image/png" name="image" class="form-control"
                                    onchange="readURL(this);"
                                    value="<?php echo base_url(); ?>uploads/free_delivery_image/<?php echo $free_delivery_settings['id']; ?>.jpg">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <img class="textimgmotion"
                                    src="<?php echo base_url(); ?>uploads/free_delivery_image/<?php echo $free_delivery_settings['image']; ?>">
                                <div class="invalid-feedback">Upload Image?</div>
                            </div>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/referral_amount'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Referral Amount</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">User Referral Amount<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="user_referral_amount" class="form-control"
                                    placeholder="User Referral Amount" required=""
                                    value="<?php echo $this->setting_model->where('key', 'user_referral_amount')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5">Vendor Referral Amount<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="vendor_referral_amount" class="form-control"
                                    placeholder="Vendor Referral Amount" required=""
                                    value="<?php echo $this->setting_model->where('key', 'vendor_referral_amount')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5">Delivery Boy Target Order Count<span
                                    class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="delivery_boy_target_order_count" class="form-control"
                                    placeholder="Target Count" required=""
                                    value="<?php echo $this->setting_model->where('key', 'delivery_boy_target_order_count')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5">Delivery Boy Referral Amount<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="delivery_boy_referral_amount" class="form-control"
                                    placeholder="Delivery Boy Referral Amount" required=""
                                    value="<?php echo $this->setting_model->where('key', 'delivery_boy_referral_amount')->get()['value'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5">Vendor to User Referral Amount<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="vendor_touser_referral_amount" class="form-control"
                                    placeholder="Vendor to User Referral Amount" required=""
                                    value="<?php echo $this->setting_model->where('key', 'vendor_touser_referral_amount')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/cashfree'); ?>" class="needs-validation form"
                novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Cash free</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-5">Client ID<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="cashfree_client_id" class="form-control"
                                    placeholder="Enter Client ID" required=""
                                    value="<?php echo $this->setting_model->where('key', 'cashfree_client_id')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5">Client Secret<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="cashfree_client_secret" class="form-control"
                                    placeholder="Enter Client Secret" required=""
                                    value="<?php echo $this->setting_model->where('key', 'cashfree_client_secret')->get()['value'] ?>">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/executive_referral_video'); ?>"
                class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Executive Demo Video</h2>
                    </header>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-5">Referral Video ID<span class="required">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="executive_referral_video_id" class="form-control"
                                    placeholder="Enter Url" required=""
                                    value="<?php echo $this->setting_model->where('key', 'executive_referral_video_id')->get()['value'] ?>">
                                <br>
                                <?php
                                $video = $this->setting_model->where('key', 'executive_referral_video_id')->get();
                                if ($video && !empty($video['value'])):
                                    ?>
                                    <?php if ($video && !empty($video['value'])): ?>
                                        <iframe width="250" height="240"
                                            src="https://www.youtube.com/embed/<?= $video['value']; ?>" frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>

    </div>

</div>




<style>
    #editor {
        padding: 0.4em 0.4em 0.4em 0;

    }
</style>