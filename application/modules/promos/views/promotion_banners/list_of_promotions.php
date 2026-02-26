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
.or{
    text-align: center;
}
</style>

<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-4 ven1">List of Promotion Banners</h4>
					<a href="<?php echo base_url()?>promotion_banners/c/0" class="col-3 btn btn-primary widfldtd">Add Promotion banner</a>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('promotion_banners/promotion_bulk_upload/0')?>"><i class="fa fa-plus" aria-hidden="true"></i>Offer Promotions Bulk Upload</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" style="width: 100%;">
							<thead>
                                <tr>
                                    <th>S.No</th>
									<th>Category</th>
									<th>Shop by Category</th>
									<th>Banner Image</th>
									<th>Banner Position</th>
                                    <th>Discount Type</th>
                                    <th>Publish Date</th>
                                    <th>Expiry Date</th>
                                    <th>Approve</th>
									<th>Action</th>
                                </tr>
                                </thead>
							<tbody>
							<?php if(!empty($banners)):?>
	                            <?php  $sno = 1; foreach ($banners as $pro): ?>
									<tr>
                                    <td><?php echo $sno++;?></td>
									<td><?php echo $pro['category']['name'];?></td>
									<td class="scrollitem">
										<ul class="scrollitemlist">
										<?php if(isset($pro['promotion_banners_shop_by_categories']))
										{
											 foreach ($pro['promotion_banners_shop_by_categories'] as $shop):?>
											<li><?php echo $shop['name'];?></li>
										<?php endforeach;}?>
										</ul>
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

									<td><?php switch($pro['promotion_banner_discount_type_id']) { 
                                            case "1": echo "Percentage of products price"; break; 
                                            case "2" : echo "Fixed amount discount"; break;
                                            case "3" : echo "Buy 1 get 1 free"; break;
                                            case "4" : echo "Buy X get Y free"; break;
                                            case "5" : echo "Buy X get Y or Z free"; break;
                                        };?> </td>
                                   <!-- <td><?php if($pro['owner']==1){echo 'Nextclick';} else{echo 'Not Available';} ;?></td>-->
                                    <td><?=date('d M,Y',strtotime($pro['published_on']))?></td>
                                    <td><?=date('d M,Y',strtotime($pro['expired_on']))?></td>
									
									<td> <input type="checkbox" class="approve_banners"
									id="<?php echo $pro['id'];?>"
									<?php echo ($pro['status'] == 1) ? 'checked':'' ;?>
									data-toggle="toggle" data-style="ios" data-on="Approved"
									data-off="Dispprove" data-onstyle="success"
									data-offstyle="danger">
									</td>
									
									<td><a
										href="<?php echo base_url()?>promotion_banners/edit/0?id=<?php echo $pro['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $pro['id'] ?>, 'promotion_banners')">
											<i class="far fa-trash-alt"></i>
									</a>
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
									<!-- Paginate -->
    <div class="row  justify-content-center">
    <div class=" col-12" style='margin-top: 10px;'>
     <?= $pagination; ?>
	
    </div>
    </div>
				</div>
			</div>


		</div>

	</div>
	