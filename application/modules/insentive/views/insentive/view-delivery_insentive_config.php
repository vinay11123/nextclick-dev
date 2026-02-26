<!DOCTYPE html>
<html lang="en">
<head>
  <title>Codeigniter Crud By PHP Code Builder</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="http://crudegenerator.in">Codeigniter Crud By PHP Code Builder</a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="<?php echo site_url(); ?>manage-delivery_insentive_config">Manage Delivery_insentive_config</a></li>
        <li><a href="<?php echo site_url(); ?>add-delivery_insentive_config">Add Delivery_insentive_config</a></li>
      </ul>
  </div>
</nav>

<div class="container">

 <div class="row">
  <div class="col-xs-12 col-md-10 well">
   state_id  :  <?php echo $delivery_insentive_config[0]->state_id ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   district  :  <?php echo $delivery_insentive_config[0]->district ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   constituency  :  <?php echo $delivery_insentive_config[0]->constituency ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   shift_id  :  <?php echo $delivery_insentive_config[0]->shift_id ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   allowed_delivery_boys_count  :  <?php echo $delivery_insentive_config[0]->allowed_delivery_boys_count ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   min_touch_points  :  <?php echo $delivery_insentive_config[0]->min_touch_points ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   req_ontime_delivery_percentage  :  <?php echo $delivery_insentive_config[0]->req_ontime_delivery_percentage ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   amount_for_addtional_touch_point  :  <?php echo $delivery_insentive_config[0]->amount_for_addtional_touch_point ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   max_limit  :  <?php echo $delivery_insentive_config[0]->max_limit ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   created_at  :  <?php echo $delivery_insentive_config[0]->created_at ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   updated_at  :  <?php echo $delivery_insentive_config[0]->updated_at ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   deleted_at  :  <?php echo $delivery_insentive_config[0]->deleted_at ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   created_user_id  :  <?php echo $delivery_insentive_config[0]->created_user_id ?>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-10 well">
   updated_user_id  :  <?php echo $delivery_insentive_config[0]->updated_user_id ?>
  </div>
</div>

</div>

</body>
</html>