<div class="row">
  <div class="col-12">
    <h4 class="ven subcategory">Add Shift</h4>
    <form role="form" method="post" action="<?php echo site_url() ?>shift/add-shift">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="from">From:</label>
        <select class="form-control" name="from" id="exampleFormControlSelect1" required>
          <?php for ($min = 0; $min <= 1440; $min += 30) { 
            $hours = (int) ($min/60);
            $minutes = (int) ($min%60);
            if($hours<10){
              $hours = "0".$hours;
            }
            if($minutes<10){
              $minutes = "0".$minutes;
            }
            echo "<option value=$min>$hours:$minutes</option>";
            } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="duration">Duration:</label>
        <select class="form-control" id="duration" name="duration" required>
          <option value="270">4.5 Hrs.</option>
          <option value="480">8 Hrs.</option>
        </select>
      </div>
      <div class="form-group">
        <label for="min_duration">Min Duration (In Hrs.):</label>
        <input type="number" class="form-control" id="min_duration" name="min_duration" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>