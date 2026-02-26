<div class="row">
  <div class="col-12">
    <h4 class="ven">Add Master Package Settings</h4>
    <form role="form" method="post" action="<?php echo site_url() ?>/add-master_package_settings-post">
      <div class="form-group co-md-6">
        <label for="setting_key">Key:</label>
        <input type="text" class="form-control" id="setting_key" name="setting_key">
      </div>
      <div class="form-group">
        <label for="description col-md-6">Title:</label>
        <input type="text" class="form-control" id="description" name="description">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>