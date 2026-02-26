<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of Promotion Banners</h4>
					<!-- <a href="<?php echo base_url()?>promotion_banners/c/0" class="col-3 btn btn-primary widfldtd">Add Promotion banner</a>-->
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
                                <tr>
                                    <th>S.No</th>
									<th>Category</th>
									<th>Sub Category</th>
									<th>Txn Id</th>
									<th>Amount</th>
									<th>Payment Date</th>
									<th>Offer Details</th>
									<th>Banner Image</th>
									<th>Banner Position</th>
                                    <th>Owner</th>
                                    <th>Publish Date</th>
                                    <th>Expiry Date</th>
                                    <th>Approve</th>
									<!-- <th>Action</th> -->
                                </tr>
                                </thead>
							<tbody>
							<?php if(!empty($banners)):?>
	                            <?php  $sno = 1; foreach ($banners as $pro): ?>
									<tr>
                                    <td><?php echo $sno++;?></td>
									<td><?php echo $pro['category']['name'];?></td>
									<td><?php echo $pro['sub_category']['name'];?></td>
									<td><?php echo $pro['joined_promotion_banner_payments'][0]['txn_id'];?></td>
									<td><?php echo $pro['joined_promotion_banner_payments'][0]['amount'];?></td>
									<td><?php echo $pro['joined_promotion_banner_payments'][0]['created_at'];?></td>
									<td class="scrollitem">
										<ul class="scrollitemlist"><li>
										<?php echo $pro['offer_details'];?>
								</li></ul>
									</td>
									<td>
									<?php if($pro['content_type'] == 3){?>
									<img
										src="<?php echo base_url();?>uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_<?php echo $pro['image_id'];?>.jpg?>"
										class="img-thumb" >
									<?php }else {?>
									<img
										src="<?php echo base_url();?>uploads/promotion_banner_image/promotion_banner_<?php echo $pro['id'];?>.jpg?>"
										class="img-thumb" >
									<?php }?>
									</td>
									<td><?= $pro['position']['title'];?></td>
                                    <td><?= $pro['vendor_list']['name'];?></td>
                                    <td><?=date('d M,Y',strtotime($pro['published_on']))?></td>
                                    <td><?=date('d M,Y',strtotime($pro['expired_on']))?></td>
									
									<td> <input type="checkbox" class="approve_banners"
									id="<?php echo $pro['id'];?>"
									<?php echo ($pro['status'] == 1) ? 'checked':'' ;?>
									data-toggle="toggle" data-style="ios" data-on="Approved"
									data-off="Dispprove" data-onstyle="success"
									data-offstyle="danger">
									</td>
									
									

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>Sorry!! No Promotion Banners Found!!!</center>
										</h3></th>
								</tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
	