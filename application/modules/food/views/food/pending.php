 <style>
.page-item>a {
  position: relative;
  display: block;
  padding: .5rem .75rem;
  margin-left: -1px;
  line-height: 1.25;
  color: #007bff;
  background-color: #fff;
  border: 1px solid #dee2e6;
}

a {
  color: #007bff;
  text-decoration: none;
  background-color: transparent;
}

.pagination>li.active>a {
  background-color: orange !important;
}

.dataTables_filter {
  display: none;
}
.or{
    text-align: center;
}
</style>
<!--Add Sub_Category And its list-->


<div class="row h-100 justify-content-center align-items-center">
  <div class="col-12">
        <div class="card-header">
          <h4 class="ven subcategory">Order List</h4>
             <form class="" novalidate="" action="<?php echo base_url('pending_orders/r/0');?>" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="form-group col-3">
                  <label for="q">Customer Name</label>
            <input type="text" name="cname" id="cname" placeholder="Customer Name" value="" class="form-control">
              </div>
<?php if($user->primary_intent!='vendor'){ ?>
              <div class="form-group col-3">
                  <label for="q">Vendor Name</label>
                <input type="text" name="vname" id="vname" placeholder="Vendor Name" value="" class="form-control">
              </div>
<?php } ?>
              <div class="form-group col-3">
                  <label for="q">Track ID</label>
                <input type="text" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
              </div>



          </div>
          <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
            </form>
            <form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('pending_orders/r/0');?>" method="post">
        <input type="hidden" name="cname" id="cname" placeholder="Customer Name" value="" class="form-control">
        <input type="hidden" name="vname" id="vname" placeholder="Vendor Name" value="" class="form-control">
        <input type="hidden" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
            <button type="submit" name="submit" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
          </form>
      </div>
    </div>
  </div>


<div class="row">
  <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="ven">List of Orders</h4>
       <!--   <a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add food product</a>
				&nbsp;&nbsp;	<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/l')?>"><i class="fa fa-plus" aria-hidden="true"></i> excel</a>
     -->
<span id="order-notification-alert"></span>

        </div>
        <div class="card-body">
          <?php
          $url_date='?start_date='.$start_date.'&end_date='.$end_date;
          ?>
          

          <div class="table-responsive">
            <table class="table table-striped table-hover"
              style="width: 100%;">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Customer Name</th>
                  <th>Vendor Name</th>
                  <th>Track ID</th>
                  <th>Address</th>
                  <th>Payment Track ID</th>
                  <th>Delivery Mode</th>
                  <th>Delivery Fee</th>
                  <th>Vendor Status</th>
                  <th>Delivery Status</th>
                  <th>Total Amount</th>
                  <th>Mode of Payment</th>
                  <th>Created At</th>
                  <th>Actions</th>
                  <!-- <th>Delivery Boy Assign</th> -->
                </tr>
              </thead>
              <tbody>
              <?php if($this->ion_auth_acl->has_permission('order_veiw')):?>
                <?php if(!empty($orders)):?> 
                  <?php 
                  $sno = 1; foreach ($orders as $order): 
                      $orstatus = $this->ecom_order_status_model->where('id', $order['order_status_id'])->get();
                      if($order['order_status_id'] == 12){
                        $delivery_status = $this->delivery_job_model->fields('status')->where('ecom_order_id',$order['id'])->get();
                      }
                      ?>
                     <tr>
                      <td><?php echo $sno++;?></td>
                      <td><?php echo $order['first_name'];?></td>
                      <td>
                        <a href="<?php echo base_url()?>vendor/vendor_profile/edit?id=<?php echo $order['vandorpreid'];?>" class=" mr-2">
                        <?php echo $order['vandor_name'];?>
                        </a>    
                      </td> 
                      <td><?php echo $order['track_id'];?></td>
                      <td><?php echo $order['address'];?></td>
                      <td><?php echo $order['payment_txn_id'];?></td>
                      <td><?php echo $order['delivery_mode_name'];?></td>
                      <td><?php echo $order['delivery_fee'];?></td>
                      <td><?php echo $orstatus['status'];?></td>
                      <td><?php switch($delivery_status['status']) { 
                                  case "500": echo "Rejected"; break; 
                                  case "501" : echo "Received"; break;
                                  case "502" : echo "Payment Page"; break;
                                  case "503": echo "Cancelled"; break; 
                                  case "504" : echo "Reached to pickup point"; break;
                                  case "505" : echo "Picked the order"; break;
                                  case "506": echo " Reached to delivery point"; break; 
                                  case "507" : echo "Delivery is on hold"; break;
                                  case "508" : echo "Delivered"; break;
                          };?></td>
                      <td><?php echo $order['grand_total'];?></td>
                      <td><?php switch($order['payment_method_id']) { 
                                  case "1": echo "COD"; break; 
                                  case "2" : echo "Online"; break;
                                  case "3" : echo "Wallet"; break;
                                  default : echo "NA"; break;
                          };?></td>
                      <td><?php echo date('d-M-Y',strtotime($order['created_at'])); ?></td>
                        <td>
                        <?php if($this->ion_auth_acl->has_permission('order_details')):?>
                           <a href="<?php echo base_url()?>food_orders/edit?id=<?php echo base64_encode(base64_encode($order['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
                               </a>
                        <?php endif;?>
                   
                <!--    <a href="<?php //echo base_url()?>payment/wallet_transactions/refund?id=<?php //echo base64_encode(base64_encode($order['id']));?>/0" class=" mr-2"> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>-->

                        </td>
                    </tr>
                  <?php endforeach;?>
              <?php else :?>
              <tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
              <?php endif;?>
               <?php else :?>
              <tr ><th colspan='11'><h3><center>No Access!</center></h3></th></tr>
              <?php endif;?>
              </tbody>
            </table>
          </div>
                      <div class="row  justify-content-center">
              <div class=" col-12" style='margin-top: 10px;'>
                           <?= $pagination; ?>
                        </div>
            </div>
        </div>
      </div>
  </div>
</div>
 