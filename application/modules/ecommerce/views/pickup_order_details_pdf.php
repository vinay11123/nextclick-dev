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

<!DOCTYPE html>
<html lang="en">

<head>
	<title>NEXT CLICK CRM</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #fff;
			color: #333;
			margin: 0;
			padding: 0;
			font-size: 12px;
		}

		.page-wrapper {
			padding: 20px;
		}

		.invoicefooter {
			bottom: 10px;
			margin: 0 auto;
			font-size: 10px;
			position: absolute;
		}

		.card {
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 30px;
			background-color: #fff;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		/* .row {
			display: flex;
			flex-wrap: wrap;
			margin-right: -15px;
			margin-left: -15px;
		} */

		/* .col-md-6 {
			flex: 0 0 50%;
			max-width: 50%;
			padding-right: 15px;
			padding-left: 15px;
		}

		.col-md-12 {
			flex: 0 0 100%;
			max-width: 100%;
			padding-right: 15px;
			padding-left: 15px;
		} */

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}

		.invoice-contact {
			margin-bottom: 5px;
		}

		.invoice-client-info {
			margin-bottom: 20px;
		}

		.table {
			width: 100%;
			margin-bottom: 1rem;
			border-color: #ccc !important;
			border: 1px solid #ccc !important;

		}


		table th,
		td {
			padding: .75rem;
			vertical-align: top;
			border: 1px solid #ccc !important;

		}

		table,
		th,
		td {
			border-collapse: collapse;
		}


		.bg-default {
			background-color: #f8f9fa !important;
		}

		.text-muted {
			color: #6c757d !important;
		}

		.mb-5 {
			margin-bottom: 3rem !important;
		}

		.mb-4 {
			margin-bottom: 1.5rem !important;
		}

		.mb-0 {
			margin-bottom: 0 !important;
		}

		.mt-0 {
			margin-top: 0px;
		}

		.p-30 {
			padding: 30px !important;
		}

		.p-t-15 {
			padding-top: 15px !important;
		}

		.p-b-15 {
			padding-bottom: 15px !important;
		}

		h6 {
			font-size: 12px;
			margin-top: 0;
			margin-bottom: .5rem;

		}

		h5 {
			font-size: 15px;
			margin-top: 0;
			margin-bottom: .5rem;

		}

		h3 {
			font-size: 14px;
			margin-top: 0;
			margin-bottom: -5px;
		}

		img {
			max-width: 200px;
			height: auto;
			vertical-align: middle;
			border-style: none;
		}

		.billingaddress {
			width: 300px !important;
			max-width: 300px;
		}

		.bold-label {
			font-weight: bold;
		}

		.table-container {
			page-break-inside: avoid;
			display: table;
			width: 100%;
		}

		.thead-container {
			display: table-header-group;
		}

		.tbody-container {
			display: table-row-group;
		}
	</style>
</head>

<body>

	<div class="card p-30">
		<div class="row invoice-contact mb-5">
			<div class="col-md-6">
				<img src="<?= $imageSrc3 ?>" width="150px" height="auto" />
			</div>
			<div class="col-md-5 text-right" style="margin-top:-35px;">
				<h6>Tax Invoice/Bill of Supply/Cash Memo</h6>
				<p>(Original for Recipient)</p>
			</div>
		</div>

		<div class="row" style="margin-top: -30px; width:100%;">
			<div style="width:50%; float: left;">
				<h5>Billing Address</h5>
				<p class="m-0"><b class="bold-label">Name: <?= $user_name ?></b></p>
				<p style="margin-bottom: 2px; width: 300px; word-wrap: break-word;">
					<b class="bold-label">Address: </b><?= $user_address ?>
				</p>
				<p class="m-0"><b class="bold-label">Phone: </b><?= $user_mobile ?></p>
				<p class="mt-0"><b class="bold-label">Email: </b><?= $user_mail ?></p>
			</div>
			<div style="width:50%; float: right; text-align:right">
				<h5>Shipping Address</h5>
				<p class="m-0"><b class="bold-label">Name: <?= $user_name ?></b></p>
				<p style="margin-bottom: 2px; word-wrap: break-word;">
					<b class="bold-label">Address: </b><?= $user_address ?>
				</p>
				<p class="m-0"><b class="bold-label">Phone: </b><?= $user_mobile ?></p>
				<p><b class="bold-label">Email: </b><?= $user_mail ?></p>
			</div>
		</div>
		<div style="width:100% height:1px"></div>


		<div class="row">
			<h6>Invoice No: <?= '#' . $invoice_id ?></h6>
			<h6>Invoice Details: <?= $track_id ?></h6>
			<h6>Invoice Date: <?= date('d-m-Y H:i:s', strtotime($invoice_date)) ?></h6>
		</div>




		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					<div class="table-container">
						<div class="thead-container">
							<table class="table">
								<thead>
									<tr class="bg-default text-dark">
										<th>Sl No</th>
										<th>Description</th>
										<th>Delivery Fee</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$subtot = 0.00;
									if (!empty($product_data)): ?>
										<?php foreach ($product_data as $index => $product): ?>

											<tr>
												<td><?= $index + 1 ?></td>
												<td>
													<h6 class="mb-0">
														<?php echo $product['category_name']; ?>
													</h6>
												</td>

												<td>₹<?= $product['delivery_fee'] ?></td>
											</tr>
											<?php

											$subtot = $subtot + $product['delivery_fee'];
											?>
										<?php endforeach; ?>

									<?php else: ?>
										<tr>
											<td colspan="3">No products found.</td>
										</tr>
									<?php endif; ?>


									<tr>
										<td colspan="2" class="text-right">Total Delivery Fee</td>

										<td class="bg-default text-dark">
											₹<?= number_format($subtot, 2) ?></td>
									</tr>

									<tr>
										<td colspan="2" class="text-right"><strong>Grand
												Total</strong>
										</td>
										<td class="bg-default text-dark text-right">
											₹<?= number_format($subtot, 2) ?>
										</td>
									</tr>

									<tr>
										<td colspan="3">
											<h6 style="display: inline;">Amount in Words:</h6>
											<?= priceToWords($subtot); ?>
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>





		<div class="row ">
			<div class="col-sm-12 text-center invoicefooter">
				<p>Customers desirous of availing input GST credit are requested to create a
					Business account and purchase on nextclick.in from Business eligible offers.<br>
					Please note that this invoice is not a demand for payment.
				</p>
				<p style="text-align:right">E & O.E</p>
				<!-- <p style="text-align:left; margin-top:-30px;">Page No: 1</p> -->
			</div>

		</div>
	</div>

</body>

</html>