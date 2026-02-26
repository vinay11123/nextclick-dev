<div class="container">

  <h2>Update Shifts</h2>  
<form role="form" method="post" action="<?php echo site_url()?>shift/edit-shift" enctype="multipart/form-data">

 <input type="hidden" value="<?php echo $shift[0]->id ?>"   name="shift_id">


      <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" value="<?php echo $shift[0]->name ?>" class="form-control" id="name" name="name">
  </div>
    <div class="form-group">
    <label for="from">From:</label>
    <select class="form-control" name="from" value="<?php echo $shift[0]->from ?>">
          <?php for ($min = 0; $min <= 1440; $min += 30) { 
            $hours = (int) ($min/60);
            $minutes = (int) ($min%60);
            if($hours<10){
              $hours = "0".$hours;
            }
            if($minutes<10){
              $minutes = "0".$minutes;
            }
            $selected="";
            if($shift[0]->from == $min){
              $selected = 'selected="selected"';
            }
            echo "<option value=$min $selected>$hours:$minutes</option>";
            } ?>
        </select>
    <!-- <input type="number" value="<?php echo $shift[0]->from ?>" class="form-control" id="from" name="from"> -->
  </div>
  <div class="form-group">
<label for="duration">Duration:</label>
<select class="form-control" id="duration" name="duration">
<option value="290" <?php if($shift[0]->duration == "290"){ echo "selected"; } ?> >4.5 Hrs.</option>
<option value="480" <?php if($shift[0]->duration == "480"){ echo "selected"; } ?> >8 Hrs.</option>
</select>
</div>
    <div class="form-group">
    <label for="min_duration">Min Duration(In Hrs.):</label>
    <input type="number" value="<?php echo $shift[0]->min_duration ?>" class="form-control" id="min_duration" name="min_duration">
  </div>
  <div class="form-group">
<label for="status">Status:</label>
<select class="form-control" id="status" name="status">
<option value="0" <?php if($shift[0]->status == "0"){ echo "selected"; } ?> >In Active</option>
<option value="1" <?php if($shift[0]->status == "1"){ echo "selected"; } ?> >Active</option>
</select>
</div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>