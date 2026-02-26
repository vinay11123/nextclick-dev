<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>
<?php
function priceToWords($price)
{
	$words = array(
		0 => 'ZERO',
		1 => 'ONE',
		2 => 'TWO',
		3 => 'THREE',
		4 => 'FOUR',
		5 => 'FIVE',
		6 => 'SIX',
		7 => 'SEVEN',
		8 => 'EIGHT',
		9 => 'NINE',
		10 => 'TEN',
		11 => 'ELEVEN',
		12 => 'TWELVE',
		13 => 'THIRTEEN',
		14 => 'FOURTEEN',
		15 => 'FIFTEEN',
		16 => 'SIXTEEN',
		17 => 'SEVENTEEN',
		18 => 'EIGHTEEN',
		19 => 'NINETEEN',
		20 => 'TWENTY',
		30 => 'THIRTY',
		40 => 'FORTY',
		50 => 'FIFTY',
		60 => 'SIXTY',
		70 => 'SEVENTY',
		80 => 'EIGHTY',
		90 => 'NINETY'
	);

	if ($price == 0) {
		return 'ZERO';
	}

	$scale = array('', 'THOUSAND', 'MILLION', 'BILLION', 'TRILLION', 'QUADRILLION');
	$unit = 'RUPEES';
	$paisa_unit = 'PAISE';

	$result = '';

	// Separate rupees and paisa parts
	$rupees = floor($price);
	$paisa = round(($price - $rupees) * 100);

	// Convert rupees to words
	for ($i = 0; $rupees > 0; $i++) {
		$chunk = $rupees % 1000;
		$rupees = floor($rupees / 1000);

		$chunkResult = '';

		if ($chunk >= 100) {
			$chunkResult .= $words[floor($chunk / 100)] . ' HUNDRED ';
			$chunk %= 100;
		}

		if ($chunk >= 20) {
			$chunkResult .= $words[floor($chunk / 10) * 10] . ' ';
			$chunk %= 10;
		}

		if ($chunk > 0) {
			$chunkResult .= $words[$chunk] . ' ';
		}

		if ($chunkResult != '') {
			$result = $chunkResult . $scale[$i] . ' ' . $result;
		}
	}

	$result .= $unit;

	if ($paisa > 0) {
		$result .= ' AND ';

		if ($paisa < 20) {
			$result .= $words[$paisa];
		} else {
			$result .= $words[floor($paisa / 10) * 10];
			$paisa %= 10;
			if ($paisa > 0) {
				$result .= ' ' . $words[$paisa];
			}
		}

		$result .= ' ' . $paisa_unit;
	}

	return trim($result);
}
?>
<style>
p {
    font-size: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

th, td {
    border: 1px solid #ccc;
    padding: 6px;
    font-size: 13px;
    word-wrap: break-word;
}

th {
    background: #f2f2f2;
}

.col-id { width: 8%; }
.col-desc { width: 32%; }
.col-qty { width: 8%; }
.col-rate { width: 10%; }
.col-disc { width: 10%; }
.col-tax { width: 10%; }
.col-total { width: 12%; }

.text-right { text-align: right; }

.summary-table {
    margin-top: 20px;
    width: 100%;
}

.summary-table td {
    border: none;
    padding: 5px;
}

</style>

</head>

<body>

<div class="invoice-box">
    <!-- Header -->
<table width="100%" style="border:none;">
    <tr>
        <!-- LEFT SIDE -->
        <td width="70%" valign="top" style="border:none; padding:0;">
            <img src="<?php echo base_url(); ?>uploads/list_cover_image/list_cover_<?php echo $vendor_id; ?>.jpg"
         style="width:50px; vertical-align:middle; display:inline-block;">

    <h3 style="margin:0 0 0 10px; 
               color:#7a8499; 
               display:inline-block; 
               vertical-align:middle;">
        <?php echo $vendor_business_name; ?>
    </h3>
            <p style="margin:5px 0;">
                <?php  $address = stripslashes($vendor_address);
            $parts = array_map('trim', explode(',', $address));
            
            // Line 1
            echo $parts[0] . ", " . $parts[1] . ",<br>";
            
            // Line 2
            echo $parts[2] . ", " . $parts[3] . ",<br>";
            
            // Line 3
            echo $parts[4] . ", " . $parts[5] . ",<br>";
            
            // Line 4
            echo $parts[6] . ", " . $parts[7]; ?>
            </p>
            <p style="margin:5px 0;">Phone: <?php echo $vendor_phone; ?></p>
            <p style="margin:5px 0;">Email: <?php echo $vendor_email; ?></p>
        </td>

        <!-- RIGHT SIDE -->
        <td width="30%" valign="top" align="right" style="border:none; padding:0;">
            <h1 style="margin:0; font-size:32px; letter-spacing:2px;    font-weight: bold; color: #7a8499;">INVOICE</h1>
        </td>
    </tr>
</table>


    <hr>

<!-- Billing -->
<table width="100%" style="margin-top:20px; border:none;">
    <tr>
        <!-- LEFT SIDE -->
        <td width="60%" valign="top" style="border:none; padding:0;">
            <p><strong>Bill To:</strong></p>
            <p><strong><?php echo $user_name; ?></strong></p>
            <p><?php
            $address = stripslashes($user_address);
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
            <p>Phone: <?php echo $user_mobile; ?></p>
            <p>Email: <?php echo $user_mail; ?></p>
        </td>

        <!-- RIGHT SIDE -->
        <td width="40%" valign="top" align="right" style="border:none; padding:0;">
            <p>Invoice Number #: <?php echo $invoice_id; ?></p>
            <p>Invoice #: <?php echo $track_id; ?></p>
            <p>Date: <?php $InvoiceDate = $invoice_date; ?><?php echo date('d-M-Y', strtotime($InvoiceDate)); ?></p>
            <p>Payment Type : COD</p>
        </td>
    </tr>
</table>

    <!-- Items Table -->
   <table>
<thead>
<tr>
    <th class="col-id">ID</th>
    <th class="col-desc">Description</th>
    <th class="col-img">Image</th>
    <th class="col-qty">Qty</th>
    <th class="col-rate">Rate</th>
    <th class="col-disc">Discount</th>
    <th class="col-tax">Tax</th>
    <th class="col-total">Total</th>
</tr>
</thead>
<tbody>

<?php 
$dis = 0;
$tx = 0;
$subtot = 0;
$i =1;

foreach ($product_data as $product): ?>
<tr>
    <td>#<?php echo $i++; ?></td>

    <td>
        <strong><?php echo $product['food_name']; ?></strong>
    </td>
    <td><img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $product['image_id']; ?>.jpg"
                                                    width="200" style="width: 50px !important ;"></td>
    <td class="text-right"><?php echo $product['qty']; ?></td>
    <td class="text-right">₹<?php echo number_format($product['price'],2); ?></td>
    <td class="text-right">₹<?php echo number_format($product['discount'],2); ?></td>
    <td class="text-right">₹<?php echo number_format($product['tax'],2); ?></td>
    <td class="text-right">₹<?php echo number_format($product['qty'] * $product['price'], 2); ?></td>
</tr>

<?php
$dis += $product['promocode_discount'] + $product['promotion_banner_discount'];
$tx += $product['tax'];
$subtot += $product['total'];
endforeach;
?>

</tbody>
</table>


    <!-- Summary -->
    <table class="summary-table" style="margin-top:20px;">
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Subtotal</td>
            <td class="text-right">₹<?php echo number_format($subtot, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Discount</td>
            <td class="text-right">₹<?php echo number_format($dis, 2); ?></td>
        </tr>
        
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Tax (10%)</td>
            <td class="text-right">₹<?php echo $tx; ?></td>
        </tr>
        
                
        <tr>
            <td colspan="3"></td>
            <td class="text-right">Shipping And Handling</td>
            <td class="text-right">₹<?php echo $ecom_data['delivery_fee']; ?></td>
        </tr>
        
        <tr class="total-due">
            <td colspan="3"></td>
            <td class="text-right">Total Due</td>
            <td class="text-right">₹<?php echo $ecom_data['grand_total']; ?></td>
        </tr>
		
		<tr>
		<td colspan="8">
			<h6 style="display: inline;">Amount in Words:</h6>
			<?= priceToWords($ecom_data['grand_total']) ?>
		</td>
		</tr>
    </table>

    <hr>

    <!-- Footer -->
    <!---<div class="footer"><p>Thank you for your business!</p>
    </div>--->

</div>

</body>
</html>

