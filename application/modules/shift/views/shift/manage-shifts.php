<div class="card-body">
  <div class="card">
    <div class="card-header">
      <h4 class="col-10 ven1">Manage Shifts</h4>
      <?php if ($this->ion_auth_acl->has_permission('vendor_bulk_upload') || $this->ion_auth->is_admin()) : ?>
        <a class="btn btn-outline-dark btn-lg col-2 pull-right" href="<?php echo base_url('shift/add') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Shift</a>
      <?php endif; ?>
    </div>
    <?php if ($this->session->flashdata('success')) { ?>
      <div class="alert alert-success">
        <strong><span class="glyphicon glyphicon-ok"></span> <?php echo $this->session->flashdata('success'); ?></strong>
      </div>
    <?php } ?>

    <?php if (!empty($shifts)) { ?>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>S. No</th>
            <th>Name</th>
            <th>From</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          foreach ($shifts as $shift) { ?>
            <tr>
              <td> <?php echo $i; ?> </td>
              <td> <a href="<?php echo site_url() ?>shift/view/<?php echo $shift->id ?>"> <?php echo $shift->name ?> </a> </td>
              <td>
                <?php
                $min = (int) $shift->from;
                $hours = (int) ($min / 60);
                $minutes = (int) ($min % 60);
                if ($hours < 10) {
                  $hours = "0" . $hours;
                }
                if ($minutes < 10) {
                  $minutes = "0" . $minutes;
                }
                echo "$hours:$minutes"; ?>
              </td>
              <td>
              <a href="<?php echo site_url() ?>shift/change-status/<?php echo $shift->id ?>">
                <?php if ($shift->status == 0) {
                  echo "Activate";
                } else {
                  echo "Deactivate";
                } ?>
              </a>
              <a href="<?php echo site_url() ?>shift/edit/<?php echo $shift->id ?>">Edit</a>
              <a href="<?php echo site_url() ?>delete-shifts/<?php echo $shift->id ?>" onclick="return confirm('are you sure to delete')">Delete</a>
              </td>

            </tr>
          <?php $i++;
          } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <div class="alert alert-info" role="alert">
        <strong>No Shifts Found!</strong>
      </div>
    <?php } ?>
  </div>
</div>