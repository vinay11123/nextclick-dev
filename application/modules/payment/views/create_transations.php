 <style>
  .clear{
    clear:both;
    margin-top: 20px;
}

#searchResult{
    list-style: none;
    padding: 0px;
    width: 250px;
    position: absolute;
    margin: 0;
}

#searchResult li{
    background: lavender;
    padding: 4px;
    margin-bottom: 1px;
}

#searchResult li:nth-child(even){
    background: cadetblue;
    color: white;
}

#searchResult li:hover{
    cursor: pointer;
}
ul#searchResult {
    position: inherit;
    width: 286px;
    margin-left: 153px;
}
</style>
        <div class="row">
            <div class="col-12">
                <h4 class="ven">Wallet Transaction</h4>
                <form class="needs-validation" novalidate="" action="<?php echo base_url('payment/wallet_transactions/e/0');?>" method="post" onsubmit="return valEarnings()">
                    <div class="card-header">
 <div class = "row">
 <div class = "col-sm-6">
  <div class="form-group row">
    <label for="staticEmail" class="col-sm-3 col-form-label">User</label>
    <div class="col-sm-6">

   <input type="text" class="form-control" id="user" name ="user" placeholder="mobile number" value = "<?php if($refnd == 'refunds'){ echo $user[0]['phone']; } ?>" required>
    <!--  <select class="form-control" aria-label="Default select example" name = "userid" id = "userid">
          <?php
          foreach($user as $u) { ?>
               <option value="<?php echo $u['id']; ?>"><?php echo $u['phone']; ?></option>
           <?php } ?>
             
</select>-->

    </div>
  </div>
<div>
  <ul id="searchResult"></ul>
</div>
  <div class="form-group row">
    <label for="earning_type" class="col-sm-3 col-form-label">Earning Type</label>
    <div class="col-sm-6">
      <select class="form-control"  name = "earning_type" id = "earning_type" required>
        <option value="">--select--</option>
        <option>User Earnings</option>
        <option>Vendor Earnings</option>
        <option>Delivery Boy Earnings</option>
      </select>
    </div> 
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label">Amount</label>
    <div class="col-sm-6">
      <input type="number" step="0.01" class="form-control" id="amount" name ="amount" placeholder="Amount" required>
    </div>
   </div>
    
      <!--  <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Type</label>
  <div class="col-sm-6">
      <select class="form-control"  name = "modetype" id = "modetype">
         <option value="">--select--</option>
        <option value="CREDIT">CREDIT</option>
         <option value="DEBIT">DEBIT</option>
     </select>

    </div> 
   </div>-->

     <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label">Message</label>
    <div class="col-sm-6">
       <textarea class="form-control" name = "message" id="message" rows="3"></textarea>

    </div>
   </div>
 
 </div>
 <div class = "col-sm-6">

 	<div class="form-group row">
    <label for="staticEmail" class="col-sm-3 col-form-label">Name</label>
    <div class="col-sm-6">
      <input type="text"   class="form-control" id="name" name = "name" value="" placeholder="name" readonly value = "<?php if($refnd == 'refunds'){ echo $user[0]['first_name']; } ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label">Email</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="email" name ="email" placeholder="email" readonly value = "<?php if($refnd == 'refunds'){ echo $user[0]['email']; } ?>">
    </div>
   </div>

     <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label">User Earnings</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="walletamount" name = "walletamount" placeholder="User Earning Amount" readonly value = "">

    </div>
   </div>

  <div class="form-group row">
      <label for="inputPassword" class="col-sm-3 col-form-label">Vendor Earnings</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="vendor_earnings" name = "vendor_earnings" placeholder="Vendors Earning Amount" readonly value = "">
      </div>
  </div>

  <div class="form-group row">
      <label for="inputPassword" class="col-sm-3 col-form-label">Delivery Boy Earnings</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="delivery_boy_earnings" name = "delivery_boy_earnings" placeholder="Delivery Boy Earning Amount" readonly value = "">
      </div>
  </div>

 </div>
<input type = "hidden" name = "id" id ="id">
<input type = "hidden" name = "type" id = "type">
                            <div class="form-group col-md-12 mt-4 pt-2">
                                <button class="btn btn-primary mt-27 " type = "submit" name = "submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

   $(document).ready(function(){
            $("#user").keyup(function(){
                var search = $(this).val();

                if(search != ""){
 
                    $.ajax({
                        url: '<?= site_url() ?>/payment/wallet_transactions/srh/0',
                        type: 'post',
                        data: {search:search},
                        dataType: 'json',
                        success:function(response){
                            var len = response.length;
                            $("#searchResult").empty();
                            for( var i = 0; i<len; i++){
                                 var phone = response[i]['phone'];
                                 var id = response[i]['id'];
                                $("#searchResult").append("<li value='"+id+"'>"+phone+"</li>");
                            }
                            // binding click event to li
                          $("#searchResult li").bind("click",function(){
                               setText(this);
                            });
                        }
                    });
                }
            });
        });


        function setText(element){
            var userid = $(element).val();
            $("#searchResult").empty();

            var data = {
                userid: userid
            }
          $.ajax({
            type: "POST",
            url: '<?= site_url() ?>/payment/wallet_transactions/st/0',
            data: data,
            cache: false,
            success: function (data)
            { 
					console.log(data);
                    data1 = JSON.parse(data);
                    $("#user").val(data1.phone);
                    $("#name").val(data1.first_name);
                    $("#email").val(data1.email);
                    $("#walletamount").val(data1.wallet);
                    $("#vendor_earnings").val(data1.vendor_earning_wallet);
                    $("#delivery_boy_earnings").val(data1.delivery_boy_earning_wallet);
                    $("#id").val(data1.id);
                    $("#type").val(data1.type);  
            }
          });
        }

        function valEarnings() {
          if($("#earning_type").val() != '' && $("#amount").val()) {

            let amount = parseFloat($("#amount").val());
            let walletamount = parseFloat($("#walletamount").val());
            let vendor_earnings = parseFloat($("#vendor_earnings").val());
            let delivery_boy_earnings = parseFloat($("#delivery_boy_earnings").val());

            if(parseFloat($("#amount").val()) <= 0) {
              alert("amount should be greater than 0");
              return false;
            }

            if($("#earning_type").val() == "User Earnings") {
              if(amount > walletamount) {
                alert("Amount should be lessthan or equal to user earnings");
                return false;
              }              
            }
            else if($("#earning_type").val() == "Vendor Earnings") {
              if(amount > vendor_earnings) {
                alert("Amount should be lessthan or equal to vender earnings");
                return false;
              }
            }
            else if($("#earning_type").val() == "Delivery Boy Earnings") {
              if(amount > delivery_boy_earnings) {
                alert("Amount should be lessthan or equal to delivery boy earnings");
                return false;
              }
            }
              
          }
        }
</script>  

 