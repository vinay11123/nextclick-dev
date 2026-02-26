<div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('master_package_setting');?>">Subscriptions
<i class="fa fa-angle-double-left"></i> 
Master Package Settings</a> 
   
    </div>
    </div>


<div class="row">
  <div class="col-12">
    <h4 class="ven">Update Master Package Settings</h4>
    <form role="form" method="post" action="<?php echo site_url() ?>edit-master_package_settings-post" enctype="multipart/form-data">
      <input type="hidden" value="<?php echo $master_package_settings['id'] ?>" name="master_package_settings_id">
      <div class="form-group">
        <label for="setting_key">Key:</label>
        <input type="text" value="<?php echo $master_package_settings['setting_key'] ?>" class="form-control" id="setting_key" name="setting_key">
      </div>
      <div class="form-group">
        <label for="description">Title:</label>
        <input type="text" value="<?php echo $master_package_settings['description'] ?>" class="form-control" id="description" name="description">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>