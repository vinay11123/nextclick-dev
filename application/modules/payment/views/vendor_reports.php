<!-- <div class="modal fade" tabindex="-1" id="myModal" role="dialog"> -->

<div class="container">


<div class="cord-body">
	<div class="card-header">
		<h4 class="ven1">GST Report of <?php echo $reports[0]['first_name']; ?></h4><br>
		<h5><span class="text-danger">Business Name:</span> <?php echo $reports[0]['business_name']; ?></h5>
		<h5><span class="text-danger">GST Number:</span> <?php echo $reports[0]['gst_number']; ?></h5>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="text-center">Si.No</th>
					<th class="text-center">Order id</th>
					<th class="text-center">Item</th>
					<!-- <th class="text-center">GST Number</th> -->
					<th class="text-center">Tax</th>
					<th class="text-center">At</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1;
				if (isset($reports) && is_array($reports) && count($reports)) : $i = 1;
					foreach ($reports as $key => $data) {
				?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="text-center">
							<a href="<?php echo base_url()?>food_orders/edit?id=<?php echo base64_encode(base64_encode($data['o_id']));?>" target="_blank" class=" mr-2  "  > <?php echo $data['track_id']; ?>
                               </a>
						</td>
							<td class="text-center"><?php echo $data['name']; ?></td>
							<!-- <td class="text-center"><?php echo $data['gst_number']; ?></td> -->
							<td class="text-center"><?php echo $data['total_tax']; ?></td>
							<td class="text-center"><?php echo date('d-M-Y H:i', strtotime($data['created_at']));?></td>
						</tr>


				<?php
					}
				endif;
				?>
			</tbody>
		</table>
	</div>
</div>
</div>