<div class="row">
  <div class="col-12">
    <h4 class="ven">Manage Subscription Features</h4>
    <form class="needs-validation" novalidate="" action="<?php echo base_url('subscriptions_packages/update_features'); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $subscriptions_package_id; ?>">
      <table class="table table-bordered table-light">
        <thead>
          <tr>
            <th>Feature</th>
            <th>Enable / Disable</th>
          </tr>
        </thead>
        <tbody>

          <?php
          foreach ($features as $key => $feature) {
          ?>
            <tr>
              <td><?php echo $feature['description']; ?></td>
              <td><input type="checkbox" name="<?php echo $feature['setting_key']; ?>" <?php in_array($feature['setting_key'], $enabled_features) ? print "checked='checked'" : "" ?> value=1></td>
            </tr>
          <?php }
          ?>
        </tbody>
      </table>
  </div>
  <div class="form-group col-md-12 float-right m-r-10"><button class="btn btn-primary mt-27 ">Update</button>
    </form>
  </div>