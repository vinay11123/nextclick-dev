<!-- <div class="card">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div> -->
<!-- <?php
           // print_r($order);
?> -->
<div class="row">
        <div class="col-xl-12" id="printThis">
            <div class="sidebar-category mt-4" style="box-shadow: 0 1px 6px 1px rgba(0, 0, 0, 0.05);background-color: #fff;">
                <div class="category-content">
                    <div href="#" class="btn btn-block content-group" style="text-align: left; background-color: #8360c3; color: #fff; border-radius: 0;"><strong style="font-size: 1.3rem;"><?=$order['order_track']?></strong>
                        <a onclick="printDiv('printThis')" class="btn btn-primary mt-27 pull-right">Print</a>
                    </div>
                    <div class="p-3">
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Order Placed: </strong></label>
                            <?=$order['created_at']?> ( <?=$this->food_orders_model->time_elapsed_string($order['created_at']);?> )
                        </div>
                        <hr>

                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Customer Details: </strong></label>
                            <br>
                            <p><b>Name: </b> <?=$order['user']['first_name'];?></p>
                            <p><b>Email: </b> <?=$order['user']['email'];?></p>
                            <p><b>Contact Number: </b> <?=$order['user']['phone'];?></p>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Vendor Name: </strong></label>
                            <?=$order['vendor']['name'];?>
                        </div>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Status:</strong></label>
                            <span class="badge badge-flat border-grey-800 text-default text-capitalize">
                            	   
                            	<?=$order['order_stat'];?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Address: </strong></label>
                            <p><?=$order['vendor']['address'];?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Payment Mode: </strong></label>
                            <span class="badge badge-flat border-grey-800 text-default text-capitalize">
                            <?=$order['payment_method_id'];?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Comment/Suggestion: </strong></label>
                            <span>
                            <?=$order['instructions'];?>
                            </span>
                        </div>
                        <hr>
<?php if($order['ord_rating']==1){?>
                        <div class="form-group">
                            <label class="control-label no-margin text-semibold mr-2"><strong>Order Review & Rating from user: </strong></label>
                            <br>
                            <p><b>Review & Rating: </b> <?=$order['review'];?><b>(<?=$order['rating'];?>/5)</b></p>
                            <p><b>Delivery boy Review & Rating: </b>  <?=$order['review'];?><b>(<?=$order['rating'];?>/5)</b></p>
                        </div>

                        <hr>
                      <?php }?>
                                                <div class="text-right">
                            <div class="form-group">
                                <div class="clearfix"></div>
                                <div class="row">
                                <div class="col-md-12 p-2 mb-3" style="background-color: #f7f8fb; float: right; text-align: left;">
                                                                        <div>
                                    <!-- <div class="d-flex mb-1 align-items-start" style="font-size: 1.2rem;">
                                        <span class="badge badge-flat border-grey-800 text-default mr-2">x1</span>
                                        <strong class="mr-2" style="width: 100%;">MUTTON DUM BIRIYANI</strong>
                                        
                                        <span class="badge badge-flat border-grey-800 text-default">र 200</span>
                                    </div> -->
                                      
                          <?php
                          foreach ($order['order_items'] as $ord_it) {
                          ?>
                          <div class="d-flex mb-1 align-items-start" style="font-size: 1.2rem;">
                          	<span class="badge badge-flat border-grey-800 text-default mr-2"><?=' x'.$ord_it['quantity'];?></span>
                          	
                          	<strong class="mr-2" style="width: 100%;">
                          		<?=$this->db->get_where('food_item',array('id'=>$ord_it['item_id']))->row()->name;?><?=(!empty($ord_it['sec_item_id']))? '<br/>'.$this->db->get_where('food_sec_item',array('id'=>$ord_it['sec_item_id']))->row()->name : '';?></strong>
                          	<span class="badge badge-flat border-grey-800 text-default">
                          		र <?=$ord_it['price'];?>
                          	</span>

                         	
                          </div>
                             <?php
                        if(!empty($order['sub_order_items'])){
                          ?>
                          <ul style="margin-left: 35px;">
                          <?php
                          foreach ($order['sub_order_items'] as $sub_ord_it) {
                          if($sub_ord_it['item_id'] == $ord_it['item_id']){
                          ?>
                          <li>
                          <div class="d-flex mb-1 align-items-start" style="font-size: 1rem;">
                          <strong class="mr-2" style="width: 100%;">
                          		<?=$this->db->get_where('food_sec_item',array('id'=>$sub_ord_it['sec_item_id']))->row()->name;?>
                          	</strong>
                           </div>
                       </li>
                        <?php 
                        }
                        }
                        ?>
                       </ul>
                        <?php
                        }
                        ?>

                        <?php }?>
                         </div>
                                                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label class="control-label no-margin text-semibold mr-2"><strong>Coupon: </strong></label>
                                <?=(($order['promo_code']=='')? 'None' : $order['promo_code'])?>
                            </div>
                            <div class="form-group">
                                <label class="control-label no-margin text-semibold mr-2"><strong>Delivery Charge: </strong></label>
                                र<?=(($order['delivery_fee']=='')? '0' : $order['delivery_fee'])?>
                            </div>
                                                        <div class="form-group">
                                <label class="control-label no-margin text-semibold mr-2"><strong>Tax: </strong></label>
                                <?=(($order['tax']=='')? '0' : $order['tax'])?>%
                                                            </div>
                                                            <?php
                                                            if($order['used_walet'] == 1){
                                                            ?>
                                                            <div class="form-group">
                                <label class="control-label no-margin text-semibold mr-2"><strong>Tax: </strong></label>
                                र <?=(($order['used_walet_amount']=='')? '0' : $order['used_walet_amount'])?>
                                                            </div>
                                                          <?php }?>
                            <hr>
                            <div class="form-group">
                                <h3>
                                    <label class="control-label no-margin text-semibold mr-2"><strong>TOTAL</strong></label>
                                    <strong> र <?php if($order['total']==''){echo '0';}elseif($order['total'] != '' && $order['used_walet'] == 1){ echo $order['total']-$order['used_walet_amount'];}elseif($order['total'] != '' && $order['used_walet'] == 0){ echo $order['total'];}?> </strong>
                                </h3>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
