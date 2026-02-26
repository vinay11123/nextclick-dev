<style>
        .mt-50 {
            margin-top: 50px
        }
        
        .mb-50 {
            margin-bottom: 50px
        }
        
        a.mailtoa {
            color: #f06a35;
        }
        
        .invoicecard {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border-style: solid;
            border-width: 6px;
            border-image: linear-gradient( 271deg, rgb(242 107 53), rgb(55 52 53)) 1;
            border-radius: .1875rem
        }
        
        .invoicecard-img-actions {
            position: relative
        }
        
        th.invoicetexth {
            vertical-align: center !important;
        }
        
        .invoicecard-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
            text-align: center
        }
        
        .invoicecard-title {
            margin-top: 10px;
            font-size: 17px
        }
        
        thead.invoicehead {
            background-color: bisque;
        }
        
        .invoice-color {
            color: #f26b35 !important;
        }
        
        a {
            text-decoration: none !important
        }
        
        .btn-light {
            color: #333;
            background-color: #fafafa;
            border-color: #ddd
        }
        
        @media (min-width: 768px) {
            .wmin-md-400 {
                min-width: 400px !important
            }
        }
        
        h5.font-weight-semibold {
            color: #f16b35!important;
        }
        
        button.btn.btn-primary {
            background-color: #3a3535;
        }
        
        .btn-labeled>b {
            position: absolute;
            top: -1px;
            background-color: #f16b35;
            display: block;
            line-height: 1;
            padding: .62503rem
        }
        
        button.btn-print {
            border: 1px solid #e4dfdf;
        }
</style>

<div id = "DivIdToPrint">
    <div class="bacgundcolor" >
        <div class="container d-flex justify-content-center mt-50 mb-50">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoicecard">
                        <div class="invoicecard-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                           <img src="../../assets/img/logo.png" style = "width: 200px !important ;" width="200">
                                            <address class="infoaddress">
                                                   <span> <b>Address:</b></span>
                                                    401, 4th Floor, New Mark House Hitech City, Patrika Nagar, Madhapur, Hyderabad, Telangana 500081
                                               </address>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-4 ">
                                        <div class="text-sm-right">
                                            <h4 class="invoice-color mb-2 mt-md-2">Invoice <span>#<?php echo $orderst[0]['id'];?></span></h4>
                                            <ul class="list list-unstyled mb-0">
                                                <li> Invoice Date: <span class="font-weight-semibold"> <?php  $InvoiceDate = $orderst[0]['created_at']; ?>
                                       <?php echo date('d-M-Y',strtotime($InvoiceDate));?></span></li>
                                                <!--<li>Due date: <span class="font-weight-semibold">March 30, 2021</span></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-flex flex-md-wrap">
                                <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Invoice To:</span>
                                    <ul class="list list-unstyled mb-0">
                                        <li>
                                            <h5 class="my-2"><?php echo $orderst[0]['first_name'];?></h5>
                                        </li>
                                        <li><span class="font-weight-semibold"> <?php echo stripslashes(nl2br($orderst[0]['address']));?></span></li>
                                        <li> Mobile: <?php echo $orderst[0]['phone'];?></li>
                                        
                                        <li> City: <?php echo $row1['City'];?> - <?php echo $row1['Zipcode']; ?></li>
                                        
                                        <li><a class="mailtoa" href="mailto:<?php echo $orderst[0]['email'];?>" data-abc="true">Email: <?php echo $orderst[0]['email'];?></a></li>
                                    </ul>
                                </div>
                             <!--   <div class="mb-2 ml-auto"> <span class="text-muted">Payment Details:</span>
                                    <div class="d-flex flex-wrap wmin-md-400">
                                        <ul class="list list-unstyled mb-0 text-left">
                                            <li>
                                                <h5 class="my-2">Total Due:</h5>
                                            </li>
                                            <li>Bank name:</li>
                                            <li>Country:</li>
                                            <li>City:</li>
                                            <li>Address:</li>
                                            <li>IBAN:</li>
                                            <li>SWIFT code:</li>
                                        </ul>
                                        <ul class="list list-unstyled text-right mb-0 ml-auto">
                                            <li>
                                                <h5 class="font-weight-semibold my-2">₹<?php //echo $orderst[0]['grand_total']; ?></h5>
                                            </li>
                                            <li><span class="font-weight-semibold">SBI  Bank</span></li>
                                            <li>Hitech sity</li>
                                            <li>street, 21</li>
                                            <li>Hyderabad</li>
                                            <li><span href="tel:98574959485" class="font-weight-semibold">98574959485</span>
                                            </li>
                                            <li><span class="font-weight-semibold">BHDHD98273BER</span></li>
                                        </ul>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-lg">
                                <thead class="invoicehead">

                                    <tr class="invoicetexthd">
                                        <th class="invoicetexth">Order Id</th>
                                        <th class="invoicetexth">Description</th>
                                        <th class="invoicetexth">Product Image</th>
                                        <th class="invoicetexth">Quantity</th>
                                        <th class="invoicetexth">Rate</th>
                                        <th class="invoicetexth">Discount</th>
                                        <th class="invoicetexth">Tax</th>
                                        <th class="invoicetexth">Promo Discount</th>
                                        <th class="invoicetexth">Payment Type</th>
                                        <th class="invoicetexth">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
 $dis = 0;
                  $tx = 0;
                  $sno = 1; 
                  $subtot = 0;
                  foreach ($custprod as $order): ?>   
                                    <tr>
                                        <td>#<?php  echo $order['track_id'];?></td>
                                        <td>
                                            <h6 class="mb-0"><?php echo $order['food_name'];?></h6> <span class="text-muted">
                                                <?php echo $order['desc'];?> </span>
                                        </td>
             <td><img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $order['image_id']; ?>.jpg" width="200" style ="width: 200px !important ;"  ></td>
                                        <td><?php echo $order['qty'];?></td>
                                        <td>₹<?php echo $order['price'];?></td>
                                        <td>₹<?php echo $order['discount'];?></td>
                                        <td>₹<?php echo $order['tax'];?></td>
                                        <td>₹<?php if($order['promocode_discount']>0) { echo $order['promocode_discount']; } else{ echo '0'; };?></td>
                                        <td><?php echo $orderst[0]['payment_method_name']; ?></td>
                                        <td><span class="font-weight-semibold">₹<?php echo $order['total'];?></span></td>
                                    </tr>
  <?php  $dis=  $dis + $order['discount'];
                                   $tx = $tx + $order['tax'];
                        $subtot = $subtot + $order['total'];
                            ?>              
                         <?php endforeach;?>
                                   <!--  <tr>
                                        <td>123456</td>
                                        <td>
                                            <h6 class="mb-0">Template for desnging the arts</h6> <span class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</span>
                                        </td>
                                        <td>2</td>
                                        <td>₹140</td>
                                        <td>card</td>
                                        <td><span class="font-weight-semibold">₹240</span></td>
                                    </tr>
                                    <tr>
                                        <td>123456</td>
                                        <td>
                                            <h6 class="mb-0">Technical support international</h6> <span class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</span>
                                        </td>
                                        <td>2</td>
                                        <td>₹250</td>
                                        <td>Card</td>
                                        <td><span class="font-weight-semibold">₹500</span></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <div class="invoicecard-body">
                            <div class="d-md-flex flex-md-wrap">
                                <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                                   <!-- <h6 class="mb-3 text-left">Total Due</h6>-->
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="text-left">Subtotal:</th>
                                                    <td class="text-right">₹<?php echo $subtot; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Discount:</th>
                                                    <td class="text-right">₹<?php echo $dis; ?></td>
                                                </tr>
                                               <!-- <tr>
                                                    <th class="text-left">Subtotal Less Discount:</th>
                                                    <td class="text-right">10%</td>
                                                </tr>-->
                                                <tr>
                                                    <th class="text-left">Tax: </th>
                                                    <td class="text-right">₹<?php echo $tx; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Total:</th>
                                                    <td class="text-right text-primary">
                                                        <h5 class="font-weight-semibold">₹<?php echo $subtot; ?></h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary"><b><i class="fa fa-paper-plane-o mr-1"></i></b> Send invoice</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="text-muted">
                             
                            <div class="header-elements">
                               
                              <button  onclick="printDiv('DivIdToPrint')" class="btn-print">Print</button>
                            </div>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 
  