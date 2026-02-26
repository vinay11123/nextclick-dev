<?php
$this->load->view('food_scripts');
$this->session->set_userdata('last_page',current_url());
?>

<style type="text/css">
  a.btn.btn-sm.float-right.order-b {
    background-color: #d4d5d6;
    margin-left: 5px;
        color: #000;
}
a.btn.btn-sm.float-right.order-b:hover {
  background-color: #e9edf1; 
  }
</style>
<!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="ven">List of Leads</h4>

<span id="order-notification-alert"></span>

        </div>
        <div class="card-body">
          
          
          <!-- <button type="button" class="btn btn-secondary float-right" onClick="window.location.reload()">Assigned Orders</button> -->
          
          <!-- <a href="<?=base_url('vendor_leads/r/Canceled');?>" class="btn btn-sm float-right order-b <?=($lead_type == 'Canceled')? 'btn-success':'';?>">Canceled</a> -->
          <a href="<?=base_url('vendor_leads/r/Completed');?>" class="btn btn-sm float-right order-b <?=($lead_type == 'Completed')? 'btn-success':'';?>">Completed Leads</a>
          <a href="<?=base_url('vendor_leads/r/Processing');?>" class="btn btn-sm float-right order-b <?=($lead_type == 'Processing')? 'btn-success':'';?>">Processing</a>
          <a href="<?=base_url('vendor_leads/r/');?>" class="btn btn-sm float-right order-b <?=($lead_type == 'Received')? 'btn-success':'';?>">Received</a>
<!-- <a href="<?=base_url('vendor_leads/r/all');?>" class="btn btn-sm float-right order-b <?=($order_type == 'all')? 'btn-success':'';?>">All Orders </a> -->

          <div class="table-responsive">
            <table class="table table-striped table-hover" id="tableExport"
              style="width: 100%;">
              <thead>
                <tr>
                  <th>Id</th>
                  <!-- <th>Order Number</th> -->
                  <th>Customer</th>
                  <th>Customer Email</th>
                  <th>Customer Mobile</th>
                  <!-- <th>Vendor</th> -->
                  <!-- <th>Order Receipt</th> -->
                  <!-- <th>Price</th> -->
                  <th>Status</th>
                  <!-- <th>Payment</th> -->
                  <th>Created Time</th>
                  <th>Actions</th>
                  <!-- <th>Delivery Boy Assign</th> -->
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($leads)):?> 
                  <?php /*echo "<pre>";print_r($orders);*/
                  $sno = 1; foreach ($leads as $order):
                    $ord_stay='';
                  $ord=$order['lead_status'];
                  if($ord==1){
                    $ord_stay ='<a href="'.base_url('vendor_lead_status/').$order['id'].'/2" class="btn btn-sm btn-success">Accept</a><br/>';
                    $ord_sta='Received';
                    $ord_sta_id=2;
                  }elseif($ord==2){
                    $ord_stay='<a href="'.base_url('vendor_lead_status/').$order['id'].'/3" class="btn btn-sm btn-success">Completed</a>';
                    $ord_sta='Processing';
                    $ord_sta_id=3;
                  }elseif($ord==3){
                    $ord_sta='Completed';
                    $ord_sta_id=3;
                  }
                  ?>
                    <tr>
                      <td><?php echo $sno++;?></td>
                      <!-- <td><?php echo $order['order_track'];?></td> -->
                      <td><?php echo $order['user']['first_name'];?></td>
                      <td><?php echo $order['user']['email'];?></td>
                      <td><?php echo $order['user']['phone'];?></td>
                      <!-- <td><?php echo $order['vendor']['name'];?></td> -->
                      <td>
                        <?php
                        if(!empty($ord_sta)){
                          echo $ord_sta;
                        }
                        //echo 'd';
                        ?>
                      </td>
                      <td><?php echo $order['created_at'];?></td>
                      <td>
                        <?php
                        if(!empty($ord_stay)){
                          echo $ord_stay;
                        }
                        ?>
                      </td>
                     <!--  <td>
                        <?php
                        if(!empty($order['deal_id'])){
                          echo "Assigned To: ".$order['deal_name'].'<br/>';
                        }else{
                          echo "Assign To: ";
                        ?>
                        <select id="del_boy" value="0" onchange="manual_assign(this.value,<?=$order['id'];?>);">
                          <option value="">Select Delivery Boy</option>
                          <?php foreach ($users as $user):?>
                                  <option value="<?=$user['id'];?>"><?=$user['first_name']?></option>
                                <?php endforeach;?>
                        </select>
                        <?php
                        }
                        ?>
                      </td> -->
                    </tr>
                  <?php endforeach;?>
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

<script type="text/javascript">
  setInterval(function(){ get_order_alert(); }, 5000);
  function get_order_alert(){
    $.ajax({
            url: '<?php echo base_url();?>food/get_orders_count/<?=$orders_count;?>',
            type: 'get',
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
              $('#order-notification-alert').html(response.message);
              order_bell();
              }
              
            }
        });
  }

  function reject_order(order_id){
    var reason = prompt("Enter Reason for Rejecting Order:", "");
        if(reason != null){
    if(reason == ''){
      alert('Please Enter Reason');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/reject_food_order',
            type: 'post',
            data: {reason : reason, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Rejected Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Rejected');
              }
            }
          });
    }
        }
  }

    function out_for_delivery(ord_deal_id){
        var otp = prompt("Enter Delivery Boy OTP :", "");
        
        if(otp != null){
        if(otp == ''){
            alert('Please Enter OTP');
        } else {
            $.ajax({
            url: '<?=base_url();?>food/food_out_for_delivery',
            type: 'post',
            data: {otp : otp, ord_deal_id : ord_deal_id},
            dataType: 'json',
            success: function(response)
            {
                if(response.status==1){
                    alert('Order Out For Delivery');
                    location.reload();
                }else if(response.status==0){
                    alert('In-Correct OTP');
                }
            }
            });
        }
        }
    }



     function manual_assign(del_id,order_id){
      var ac = confirm("Are You Sure Want To Assign");
           if(ac == true){
    if(ac == ''){
      alert('Please Select Any One');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/manual_assign_order',
            type: 'post',
            data: {del_id : del_id, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Assigned Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Assigned');
              }
            }
          });
        }
        }
  /*  var reason = prompt("Enter Reason for Rejecting Order:", "<input type='radio' name='g' value='m' /><input type='radio' name='g' value='f' />");
        if(reason != null){
    if(reason == ''){
      alert('Please Enter Reason');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/reject_food_order',
            type: 'post',
            data: {reason : reason, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Rejected Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Rejected');
              }
            }
          });
    }
        }*/
  }
</script>



