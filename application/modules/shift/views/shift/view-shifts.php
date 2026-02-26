<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-10 well">
      Name : <?php echo $shift[0]->name ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-10 well">
      From : <?php
              $min = (int) $shift[0]->from;
              $hours = (int) ($min / 60);
              $minutes = (int) ($min % 60);
              if ($hours < 10) {
                $hours = "0" . $hours;
              }
              if ($minutes < 10) {
                $minutes = "0" . $minutes;
              }
              echo "$hours:$minutes"; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-10 well">
      Duration :
      <?php
      $min = (int) $shift[0]->duration;
      $hours = (float) ($min / 60);
      echo round($hours, 2) . " Hrs."; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-10 well">
      Min Duration :
      <?php
      $min = (int) $shift[0]->min_duration;
      $hours = (float) ($min / 60);
      echo round($hours, 2) . " Hrs."; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-10 well">
      Status : <?php echo ($shift[0]->status == 1) ? 'Active' : 'Inactive' ?>
    </div>
  </div>

</div>