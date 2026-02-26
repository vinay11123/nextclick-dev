<div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('subscriptions_packages/r');?>">Subscriptions
<i class="fa fa-angle-double-left"></i> 
Subscription Packages</a> 
   
    </div>
    </div>
<div class="row">
<div class="col-12">
<h4 class="ven">Edit Subscriptions</h4>
<form class="needs-validation" novalidate=""
  action="<?php echo base_url('subscriptions_packages/u');?>" method="post"
  enctype="multipart/form-data">
  <div class="card-header">

    <div class="form-row">
    
    <input type="hidden" name="id" value="<?php echo $subscriptions_packages['id'] ; ?>">

    <div class="form-group mb-0 col-md-4">
        <label>Service ID</label> 
        <input type="number" name="service_id" class="form-control"  value="<?php echo $subscriptions_packages['service_id'];?>">
        <div class="invalid-feedback">Give some Title</div>
    </div>

    <div class="form-group mb-0 col-md-4">
        <label>Title</label> 
        <input type="text" name="title" class="form-control"  value="<?php echo $subscriptions_packages['title'];?>">
        <div class="invalid-feedback">Give some Title</div>
    </div>

    <div class="form-group mb-0 col-md-4">
        <label>Description</label> 
        <input type="text" name="desc" class="form-control"  value="<?php echo $subscriptions_packages['desc'];?>">
        <div class="invalid-feedback">Give some Title</div>
    </div>

    <div class="form-group mb-0 col-md-4">
        <label>Days</label> 
        <input type="number" name="days" class="form-control"  value="<?php echo $subscriptions_packages['days'];?>">
        <div class="invalid-feedback">Give some Title</div>
    </div>

    <div class="form-group mb-0 col-md-3">
        <label>Price</label> 
        <input type="number" name="display_price" class="form-control"  value="<?php echo $subscriptions_packages['display_price'];?>">
        <div class="invalid-feedback">Give some Price</div>
    </div>

    <div class="form-group mb-0 col-md-3">
        <label>Discounted Price</label> 
        <input type="number" name="price" class="form-control"  value="<?php echo $subscriptions_packages['price'];?>">
        <div class="invalid-feedback">Give some Discounted Price</div>
    </div>
    <div class="form-group mb-0 col-md-4">
        <label>Upload Image</label>
        <input type="file" id='input1' name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/subscriptions_image/subscriptions_<?php echo $subscriptions_packages['id']; ?>.jpg">
        
						<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
      <div class="invalid-feedback">Upload Image?</div>
    </div>
    <div class="form-group mb-0 col-md-1">
    <img id="imagepreview1" src="<?php echo base_url(); ?>uploads/subscriptions_image/subscriptions_<?php echo $_GET['id']; ?>.jpg" >
          <img id="blah" src="#" alt="" >
    </div>

      <div class="form-group col-md-12"><button class="btn btn-primary mt-27 ">Update</button>
      </div>


    </div>


  </div>
</form>



</div>
</div>