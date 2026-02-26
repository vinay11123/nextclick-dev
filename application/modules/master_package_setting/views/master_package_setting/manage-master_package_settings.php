<div class="card-body">
  <div class="card">
    <div class="card-header">
      <h4 class="col-9 ven1">Master Package Settings</h4>
      <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('master_package_settings/add') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">

        <?php if (!empty($master_package_settings)) { ?>
          <table class="table table-striped table-hover" id="tableExportNoPagination" style="width: 100%;">
            <thead>
              <tr>
                <th>SL No</th>
                <th>Setting Key</th>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($master_package_settings as $master_package_setting) { ?>
                <tr>
                  <td> <?php echo $i; ?> </td>
                  <td><?php echo $master_package_setting['setting_key'] ?></td>
                  <td> <a href="<?php echo site_url() ?>view-master_package_settings/<?php echo $master_package_setting['id'] ?>"> <?php echo $master_package_setting['description'] ?> </a> </td>
                  <td><a href="<?php echo site_url() ?>change-status-master_package_settings/<?php echo $master_package_setting['id'] ?>"> 
                  <?php if ($master_package_setting['status'] == 0) {
                          echo "Activate";
                        } else {
                          echo "Deactivate";
                        } ?></a>
                  </td>
                  <td>
                    <a href="<?php echo site_url() ?>edit-master_package_settings/<?php echo $master_package_setting['id'] ?>"><i class="fas fa-pencil-alt"></i></a>
                  </td>

                </tr>
              <?php $i++;
              } ?>
            </tbody>
          </table>
        <?php } else { ?>
          <div class="alert alert-info" role="alert">
            <strong>No Master_package_settingss Found!</strong>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</div>