<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List Banner Images</h4>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('banner_images/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banner Images</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>category</th>
									<th>image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php $sno = 1; foreach ($banners as $images): ?>
                            
    								<tr>
									<td><?php echo $sno++;?></td>
                                    <td><?php echo $images['category']['name'];?></td>
                                    <td><img
										src="<?php echo base_url();?>uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_<?php echo $images['id'];?>.jpg?<?php echo time();?>"
										class="img-thumb">
									</td>
                                    <td><a
										href="<?php echo base_url()?>banner_images/edit?id=<?php echo $images['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a> 
									<a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $images['id'] ?>, 'banner_images')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							
							
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>