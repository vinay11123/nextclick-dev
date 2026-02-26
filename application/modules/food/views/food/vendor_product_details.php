
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style type="text/css">
    body {
        overflow-x: hidden;
    }
    .main-content {
        padding-top: 0px !important;
    }
</style>


<div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('food/food/inventory/r/0');?>">Ecommerce
<i class="fa fa-angle-double-left"></i> 
Vendor Inventory</a> 
   
    </div>
    </div>
<div class="card-body">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="container">
                <div class="col-md-4">
                    <h6 style="color: #f26b35;">Image of products</h6>
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                        <?php if(! empty($product_details['item_images'])){foreach($product_details['item_images'] as $k => $item_image){?>
                            <li data-target="#myCarousel" data-slide-to="<?php echo $k;?>" class="<?php echo ($k == 0)? 'active' : ''?>"></li>
                        <?php }}?>
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <?php if(! empty($product_details['item_images'])){foreach($product_details['item_images'] as $key => $item_image){?>
                            <div class="item <?php echo ($key == 0)? 'active' : ''?>">
                                <img src="<?php echo $item_image['image']; ?>" style="width: 100%;" />
                            </div>
                            <?php }}else{ ?>
                            <div class="item active">
                                <img src="<?php echo base_url(); ?>assets/img/no.png" style="width: 100%;" />
                            </div>
                            <?php }?>
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
                    <h6 style="color: #f26b35;">Details of products</h6>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-5">
                                    <?php echo $product_details['name']; ?>
                                    <br />
                                    <?php echo $product_details['product_code']; ?>
                                    <?php if ($product_details['status'] == 1) { ?>
                                    <span class="badge badge-info">Catalogue</span>
                                    <?php } ?>
                                    <?php if ($product_details['status'] == 2) { ?>
                                    <span class="badge badge-success">Approved</span>
                                    <?php } ?>
                                    <?php if ($product_details['status'] == 3) { ?>
                                    <span class="badge badge-danger">Pending</span>
                                    <?php } ?>
                                    <br />
                                    <br />
                                    <br />
                                    <br />

                                    <?php if ($this->ion_auth_acl->has_permission('product_add_to_catalogue')): ?>
                                    <a href="<?php echo base_url(); ?>products_approve/0/changecat?id=<?php echo $product_details['id']; ?>" class="btn btn-secondary">Add To Catlogue</a>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-5" style="border-left-style: dotted; border-color: lightgrey;">
                                    <span>Uploaded By :</span>
                                    <?php echo $product_details['created_by']['unique_id']; ?>
                                    <br />
                                    <br />
                                    <a href="<?php echo base_url(); ?>employee/eye/0?id=<?php echo $product_details['created_by']['id']?>" class="btn btn-warning">User Info</a><br />
                                    <br />

                                    <span>Uploaded At :</span>
                                    <?php echo $product_details['created_at']; ?>

                                    <?php if ($this->ion_auth_acl->has_permission('product_approval')): ?>
                                    <!--<a href="<?php echo base_url(); ?>products_approve/0/foodapprovestatus?id=<?php echo $product_details['id']; ?>" class="btn btn-info">Approved</a> -->
									 <?php  if($this->ion_auth_acl->has_permission('product_approval')):?>
   <!--<a href = "<?php echo base_url()?>food_product/0/foodapprovestatus?id=<?php echo $product_details['id'];?>"   class="btn btn-info">Approve</a>-->
 <?php endif;?>
                                    <?php endif; ?>
									<?php print_r($product_details['status']);
if($product_details['status']=='2')
									{ ?>
									<span class="badge badge-success">Approved</span>
									<?php } else{ ?><span class="badge badge-danger">Not Approved</span>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-5">
                                    <span class="badge badge-success">Menus </span>
                                    <br />
                                    <br />
                                    <?php echo $product_details['menu']['name']; ?>
                                </div>

                                <div class="col-md-5" style="border-left-style: dotted; border-color: lightgrey;">
                                    <span class="badge badge-success">Category :</span>

                                    <br />
                                    <br />
                                    <?php echo $product_details['sub_category']['name']; ?>
                                </div>
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
                    <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Vendor</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Discount</th>
                                <th>Tax</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php if (!empty($product_details['vendor_product_varinats'])):
                            foreach ($product_details['vendor_product_varinats'] as $a) { ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $user_details['business_name'].'['.$user_details['id'].']'; ?></td>
                                <td><?php echo $a['sku']; ?></td>
                                <td><?php echo $a['price']; ?></td>
                                <td><?php echo $a['stock']; ?></td>
                                <td><?php echo $a['discount']; ?></td>
                                <td><?php echo $a['tax']['tax']; ?></td>
                                <td><?php echo date('dM(Y)', strtotime($a['created_at'])); ?></td>
                                <td><?php echo date('dM(Y)', strtotime($a['updated_at'])); ?></td>
                                <td><?php echo ($a['status'] == 1)? 'Active': 'In-Active'; ?></td>
                            </tr>
                            <?php } ?>
                            <?php
                            else:
                                 ?>
                            <tr>
                                <th colspan="11">
                                    <h3><center>No Data Found</center></h3>
                                </th>
                            </tr>
                            <?php
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
