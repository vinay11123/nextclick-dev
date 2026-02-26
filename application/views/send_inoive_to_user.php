<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>
<style>

    body {
        background: #f2f2f2;
        font-family: Arial, sans-serif;
    }

    .invoice-box {
        width: 900px;
        margin: 40px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .top-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .company-info h2 {
        margin: 0;
        font-weight: bold;
    }

    .company-info p {
        margin: 4px 0;
        color: #555;
        font-size: 14px;
    }

    .invoice-title {
        font-size: 36px;
        font-weight: bold;
        color: #7a8499;
        letter-spacing: 2px;
    }

    hr {
        margin: 30px 0;
        border: none;
        border-top: 1px solid #ddd;
    }

    .bill-section {
        display: flex;
        justify-content: space-between;
    }

    .bill-left p,
    .bill-right p {
        margin: 4px 0;
        font-size: 14px;
        color: #555;
    }

    .bill-left strong {
        font-size: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }

    thead {
        background: #e9edf2;
    }

    th {
        padding: 12px;
        text-align: left;
        font-size: 14px;
        color: #555;
    }

    td {
        padding: 12px;
        border: 1px solid #ddd;
        font-size: 14px;
        color: #555;
    }

    .text-right {
        text-align: right;
    }

    .summary-table td {
        border: none;
        padding: 8px 12px;
    }

    .total-due {
        font-weight: bold;
        font-size: 16px;
    }

    .footer {
        margin-top: 40px;
        font-size: 14px;
        color: #666;
    }
</style>
</head>

<body>

<div class="invoice-box">

    <!-- Header -->
    <div class="top-section">
        <div class="company-info">
            <h3><?php echo $orderst[0]['vandor_name']; ?></h3>
            <p>123 Business Street, City, Country</p>
            <p>Phone: <?php echo $orderst[0]['vendor_phone']; ?></p>
            <p>Email: <?php echo $orderst[0]['vendor_email']; ?></p>
        </div>

        <div class="invoice-title">
            INVOICE
        </div>
    </div>

    <hr>

    <!-- Billing -->
    <div class="bill-section">
        <div class="bill-left">
            <p><strong>Bill To:</strong></p>
            <p><strong><?php echo $orderst[0]['first_name']; ?></strong></p>
            <p><?php
            $address = stripslashes($orderst[0]['address']);
            $parts = array_map('trim', explode(',', $address));
            
            // Line 1
            echo $parts[0] . ", " . $parts[1] . ",<br>";
            
            // Line 2
            echo $parts[2] . ", " . $parts[3] . ",<br>";
            
            // Line 3
            echo $parts[4] . ", " . $parts[5] . ",<br>";
            
            // Line 4
            echo $parts[6] . ", " . $parts[7];
            ?>
            </p>
            <p>Phone: <?php echo $orderst[0]['phone']; ?></p>
            <p>Email: <?php echo $orderst[0]['email']; ?></p>
        </div>

        <div class="bill-right text-right">
            <p>Invoice #: <?php echo $orderst[0]['id']; ?></p>
            <p>Date: <?php $InvoiceDate = $orderst[0]['created_at']; ?><?php echo date('d-M-Y', strtotime($InvoiceDate)); ?></p>
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
               <th>Order Id</th>
				<th>Description</th>
				<th>Product Image</th>
				<th>Quantity</th>
				<th>Rate</th>
				<th>Discount</th>
				<th>Tax</th>
				<th>Promo Discount</th>
				<th>Payment Type</th>
				<th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
		<?php  $dis = 0;
			$tx = 0;
			$sno = 1;
			$subtot = 0;
			foreach ($custprod as $order): ?>
            <tr>
                <td># <?php echo $order['track_id']; ?></td>
                <td><h6 class="mb-0"><?php echo $order['food_name']; ?></h6> <span
				class="text-muted">
				<?php echo $order['desc']; ?> </span></td>
                <td><img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $order['image_id']; ?>.jpg"
                                                    width="200" style="width: 50px !important ;"></td>
                 <td><?php echo $order['qty']; ?></td>
                                            <td>₹<?php echo $order['price']; ?></td>
                                            <td>₹<?php echo $order['discount']; ?></td>
                                            <td>₹<?php echo $order['tax']; ?></td>
                                            <td>₹<?php if ($order['promocode_discount'] > 0) {
                                                echo $order['promocode_discount'];
                                            } else {
                                                echo '0';
                                            }
                                            ; ?>
                                            </td>
                                            <td><?php echo $orderst[0]['payment_method_name']; ?></td>
                                            <td><span class="font-weight-semibold">₹<?php echo $order['total']; ?></span>
                                            </td>
            </tr>
			<?php $dis = $dis + $order['discount'];
                                        $tx = $tx + $order['tax'];
                                        $subtot = $subtot + $order['total'];
                                        ?>
                                    <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Summary -->
    <table class="summary-table" style="margin-top:20px;">
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Subtotal</td>
            <td class="text-right">₹<?php echo $subtot; ?></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Discount</td>
            <td class="text-right">₹<?php echo $dis; ?></td>
        </tr>
        
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Tax (10%)</td>
            <td class="text-right">₹<?php echo $tx; ?></td>
        </tr>
        
                
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Shipping And Handling</td>
            <td class="text-right">₹<?php echo $orderst[0]['delivery_fee']; ?></td>
        </tr>
        
        <tr class="total-due">
            <td colspan="3"></td>
            <td class="text-right">Total Due</td>
            <td class="text-right">₹<?php echo $orderst[0]['grand_total']; ?></td>
        </tr>
    </table>

    <hr>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>

</div>

</body>
</html>

