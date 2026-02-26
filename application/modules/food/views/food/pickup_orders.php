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

   .or {
     text-align: center;
   }
 </style>
 <!--Add Sub_Category And its list-->


 <!-- <div class="row h-100 justify-content-center align-items-center">
   <div class="col-12">
     <div class="card-header">
       <h4 class="ven subcategory">Order List</h4>
       <form class="" novalidate="" action="<?php echo base_url('food_orders/r/0'); ?>" method="post" enctype="multipart/form-data">
         <div class="row">
           <div class="form-group col-3">
             <label for="q">Customer Name</label>
             <input type="text" name="cname" id="cname" placeholder="Customer Name" value="<?= set_value('cname'); ?>" class="form-control">
           </div>
           <?php if ($user->primary_intent != 'vendor') { ?>
             <div class="form-group col-3">
               <label for="q">Vendor Name</label>
               <input type="text" name="vname" id="vname" placeholder="Vendor Name" value="<?= set_value('vname'); ?>" class="form-control">
             </div>
           <?php } ?>
           <div class="form-group col-3">
             <label for="q">Track ID</label>
             <input type="text" name="tid" id="tid" placeholder="Track ID" value="<?= set_value('tid'); ?>" class="form-control">
           </div>


           <div class="form-group col-3">
             <label for="q">Status</label>
             <select class="form-control" name="statusname" id="statusname">
               <option value="">--Select--</option>
               <?php
                foreach ($sts as $a) { ?>
                 <option value="<?php echo $a['id']; ?>" <?= set_value('statusname') == $a['id'] ? 'selected' : ''; ?>><?php echo $a['status']; ?></option>
               <?php } ?>
             </select>
           </div>
           <div class="form-group col-3">
             <label for="q">Payment Mode</label>
             <select class="form-control" name="payment_method_name" id="payment_method_name">
               <option value="">--Select--</option>
               <?php
                foreach ($payment_modes as $a) { ?>
                 <option value="<?php echo $a['id']; ?>" <?= set_value('payment_method_name') == $a['id'] ? 'selected' : ''; ?>><?php echo $a['name']; ?></option>
               <?php } ?>
             </select>
           </div>
           <div class="form-group col-3">
             <label for="q">Delivery Boy Name</label>

             <select class="form-control" name="delivery_boy_name" id="delivery_boy_name">
               <option value="">--Select--</option>
               <?php
                foreach ($delivery_boy_names as $a) { ?>
                 <option value="<?php echo $a['id']; ?>" <?= set_value('delivery_boy_name') == $a['id'] ? 'selected' : ''; ?>><?php echo $a['first_name'];
                                                                                                                              echo ' - (' . $a['phone'] . ')'; ?></option>
               <?php } ?>
             </select>
           </div>

         </div>
         <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
       </form>
       <form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('food_orders/r/0'); ?>" method="post">
         <input type="hidden" name="cname" id="cname" placeholder="Customer Name" value="" class="form-control">
         <input type="hidden" name="vname" id="vname" placeholder="Vendor Name" value="" class="form-control">
         <input type="hidden" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
         <button type="submit" name="submit" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
       </form>
     </div>
   </div>
 </div> -->


 <div class="row">
   <div class="col-12">
     <div class="card">
       <div class="card-header">
         <h4 class="ven">List of Pickup Orders</h4>
         <!--   <a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add food product</a>
				&nbsp;&nbsp;	<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_product/0/l') ?>"><i class="fa fa-plus" aria-hidden="true"></i> excel</a>
     -->
         <span id="order-notification-alert"></span>

       </div>
       <div class="card-body">
         <?php
          $url_date = '?start_date=' . $start_date . '&end_date=' . $end_date;
          ?>


         <div class="table-responsive">
           <table id="PickupOrderDatatable" class="table table-striped table-hover" style="width: 100%;">
             <thead>
               <tr>
                 <th>S.no</th>
                 <th>Category Name</th>
                 <th>Track ID</th>
                 <th>Customer Name</th>
                 <th>Delivery Boy Name</th>
                 <th>Pickup Address</th>
                 <th>Delivery Address</th>
                 <th>Address Coords (Lat, Long)</th>
                 <th>Delivery Info</th>
                 <th>Distance & Charges Info</th>
                 <th>Payment Id</th>
                 <th>Vehicle Type</th>
                 <th>Order Status</th>
                 <th>Created At</th>
                 <!-- <th>Delivery Boy Assign</th> -->
               </tr>
             </thead>
             <tbody>
               <?php if ($this->ion_auth_acl->has_permission('order_veiw')) : ?>
                 <?php if (!empty($orders)) : ?>
                   <?php
                    $sno = 1;
                    foreach ($orders as $order) :
                    ?>
                     <tr>
                       <td><?php echo $sno++; ?></td>
                       <td><?php echo $order['category_name']; ?></td>
                       <td><?php echo $order['track_id']; ?></td>
                       <td><?php echo $order['customer_name']; ?></td>
                       <td><?php echo $order['delivery_boy_name']; ?>
                       <td><?php echo $order['pickup_address']; ?></td>
                       <td><?php echo $order['delivery_address']; ?></td>
                       <td><strong>Pickup Addr Coords : </strong> <?php echo $order['pl_latitude'].", ".$order['pl_longitude']; ?><br />
                       <strong>Delivery Addr Coords : </strong> <?php echo $order['dl_latitude'].", ".$order['dl_longitude']; ?></td>
                       <td>
                        <strong>Total Fee: </strong><?php echo $order['delivery_fee']; ?><br />
                        <strong>GST(%): </strong><?php echo $order['delivery_gst_percentage']; ?><br />
                        <strong>Delivery Fee Without GST: </strong><?php echo $order['delivery_fee_without_gst']; ?><br />
                        <strong>Delivery Fee GST Value: </strong><?php echo $order['delivery_fee_gst_value']; ?><br />

                        <strong>Delivery Boy Earnings: </strong><?php echo $order['delivery_boy_delivery_fee']; ?><br />                        
                        <strong>Delivery Boy Earnings Without GST: </strong><?php echo $order['delivery_boy_delivery_fee_without_gst']; ?><br />
                        <strong>Delivery Boy Earnings GST Value: </strong><?php echo $order['delivery_boy_delivery_fee_gst_value']; ?><br />

                        <strong>NC Earnings: </strong><?php echo $order['nc_delivery_fee']; ?><br />                        
                        <strong>NC Earnings Without GST: </strong><?php echo $order['nc_delivery_fee_without_gst']; ?><br />
                        <strong>NC Earnings GST Value: </strong><?php echo $order['nc_delivery_fee_gst_value']; ?><br />
                        
                      </td>
                      <td>
                        <strong>Real Distance: </strong><?php echo $order['actual_distance']; ?><br />
                        <strong>Gmap Distance: </strong><?php echo $order['gmap_distance']; ?><br />

                        <strong>Flat Distance in km: </strong><?php echo $order['flat_distance']; ?><br />
                        <strong>DB Flat Rate: </strong><?php echo $order['flat_rate']; ?><br />
                        <strong>NC Flat Rate: </strong><?php echo $order['nc_flat_rate']; ?><br />
                        <strong>DB Per km Rate after flat distance: </strong><?php echo $order['per_km']; ?><br />                        
                        <strong>NC Per km Rate after flat distance: </strong><?php echo $order['nc_per_km']; ?><br />
                      </td>
                       <td><?php echo $order['payment_id']; ?></td>                       
                       <td><?php echo $order['vehicle_name']; ?></td>
                       <td><?php echo $order['order_status']; ?></td>
                       <td><?php echo date('d-M-Y', strtotime($order['created_at'])); ?></td>

                     </tr>
                   <?php endforeach; ?>
                 <?php else : ?>
                   <tr>
                     <th colspan='11'>
                       <h3>
                         <center>No Data Found</center>
                       </h3>
                     </th>
                   </tr>
                 <?php endif; ?>
               <?php else : ?>
                 <tr>
                   <th colspan='11'>
                     <h3>
                       <center>No Access!</center>
                     </h3>
                   </th>
                 </tr>
               <?php endif; ?>
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