<div class="card-body">
  <div class="card">
    <div class="card-header">
      <h4 class="col-9 ven1">View Master Package Settings</h4>
    </div>
    <div class="card-body">
      <div class="row m-b-10">
        <div class="col-md-6 well">
          Setting Key : <?php echo $master_package_settings['setting_key'] ?>
        </div>
        <div class="col-md-6 well">
          Description : <?php echo $master_package_settings['description'] ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-md-10 well">
          Status : <?php echo ($master_package_settings['status']==1) ? "Active" : "Inactive" ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>