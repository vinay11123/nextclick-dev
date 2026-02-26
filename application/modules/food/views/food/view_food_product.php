  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style type="text/css">
  body{
     overflow-x: hidden;
  }
 .main-content {
    padding-top:0px !important;
 }
</style>

    <div class="card-body">
      <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
    <div class="container">
    <div class = "col-md-4">
   <h6  style = "  color: #f26b35;">Image of products</h6>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
<?php   $id =    $vendourproduct[0]['image_id'];?>
<?php   $id1 =   $vendourproduct[1]['image_id']; ?>
<?php   $id2 =   $vendourproduct[2]['image_id']; ?>
      <div class="item active">
        <img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $id; ?>.jpg" style="width:100%;">
      </div>
    <?php if($id1){ ?>
      <div class="item">
        <img src=" <?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $id1; ?>.jpg" style="width:100%;">
      </div>
    <?php } ?>

    <?php if($id2){ ?>
       <div class="item">
        <img src=" <?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $id2; ?>.jpg" style="width:100%;">
      </div>
      <?php } ?>
    </div>
    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<div class="col-md-6">

<h6  style = " color: #f26b35;">Details of products</h6>
        <div class="card-body">
            <div class="card">     
                <div class="card-body">
               <div class="col-md-5">     
                 <?php echo $vendourproduct[0]['food_name']; ?>
                 <br>
                  <?php echo $vendourproduct[0]['product_code'];?>  

 <?php echo $vendourproduct[0]['food_name'];
                  if($vendourproduct[0]['status'] == '1'){ echo '<span class="badge badge-success">Catalogue</span>'; } 

if($vendourproduct[0]['status'] == '2'){ echo '<span class="badge badge-success"> Approved </span>'; } 
if($vendourproduct[0]['status'] == '3'){ echo '<span class="badge badge-success">Pending</span>'; } 
                  ?>
     <br><br><br>   <br>
                <?php  if($this->ion_auth_acl->has_permission('product_add_to_catalogue')):?>
           		<a href = "<?php echo base_url()?>food_product/0/changecat?id=<?php echo $vendourproduct[0]['id']; ?>" class="btn btn-secondary">Add To Catlogue</a>      
				<?php endif;?>
                </div>
                 
                 <div class="col-md-5" style = " border-left-style: dotted; border-color: lightgrey;">     
 <span>Uploaded By :</span>
<?php   echo $userinfo[0]['username']; ?><br><br>
<a href = "<?php echo base_url()?>employee/r/0" class="btn btn-warning">User Info</a><br><br>

  <span>Uploaded At :</span>
  <?php echo $vendourproduct[0]['created_at']; ?>

 <?php  if($this->ion_auth_acl->has_permission('product_approval')):?>
   <a href = "<?php echo base_url()?>food_product/0/foodapprovestatus?id=<?php echo $vendourproduct[0]['id'];?>"   class="btn btn-info">Approve</a>
 <?php endif;?> 
                </div>
            </div>
          </div>
        </div>



</div>
<div class="col-md-10">
   
  <div class="card-body">
            <div class="card">     
                <div class="card-body">

<div class="col-md-2"> <span class="badge badge-success">Product name: </span> </div>
<div class="col-md-2"><span class="badge badge-success">Shop by Category : </span> </div>

<div class="col-md-2">  <span class="badge badge-success">Menus: </span>
                <br>
                <br>
               <?php echo  $vendourproduct[0]['menu_name']; ?>
  </div>
<div class="col-md-2"><span class="badge badge-success">Category :</span>
 
 <br>
                <br>
               <?php echo  $vendourproduct[0]['sub_name']; ?>
  </div>
  <div class="col-md-2">
  <span class="badge badge-success">Brands:</span>

</div>
               <!-- <div class="col-md-5">     
                 <span class="badge badge-success">Menus </span>
                <br>
                <br>
               <?php echo  $vendourproduct[0]['menu_name']; ?>
 
                 
                </div> -->
<!--                  
                 <div class="col-md-5" style = " border-left-style: dotted; border-color: lightgrey;">     
 <span class="badge badge-success">Category :</span>
 
 <br>
                <br>
               <?php echo  $vendourproduct[0]['sub_name']; ?>
 
                </div> -->
            </div>
          </div>
        </div>

</div>
</div>
      </div>
        </div>

     <div class="card-body">
      <div class="card">
        <div class="card-header">
          <h4 class="ven">Vendor Product List</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="tableExport"
              style="width: 100%;">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Vendor Name</th>
                  <th>Variant Name</th>
                  <!-- <th>Section name</th> -->
                  <th>Price In Rupees</th>
                  <th>Weight In Grams</th>
                  <th>Created</th>
                  <th>Updated</th>

                </tr>
              </thead>
              <tbody>
        <?php $i=1 ;?>
        <?php if(!empty($se_food_itm)):
        foreach($se_food_itm as $a) {  ?>
          <tr>       
              <td><?php echo $i++; ?></td>  
              <td><?php echo $a['vendor_name']; ?></td>   
              <td><?php echo $a['variant_name']; ?></td> 
              <!-- <td><?php echo $a['section_name']; ?></td>    -->
              <td><?php echo $a['price']; ?></td>   
              <td><?php echo $a['weight']; ?></td> 
              <td><?php echo $a['created_at']; ?></td> 
              <td><?php echo $a['updated_at']; ?></td>   
         </tr>
        <?php } ?>
        <?php else :?>
              <tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
          <?php endif;?>    
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

 
  
   
