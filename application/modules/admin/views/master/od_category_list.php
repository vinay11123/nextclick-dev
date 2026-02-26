<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of On Demand Categories</h4>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('od_categories/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add On Demand Categories</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
							<thead> 
								<tr>
									<th>Sno</th>
									<th>Name</th>
									<th>Category Name</th>
									<th>Description</th>
									<th>Image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($od_categories)):?>
    							<?php  $sno = 1; foreach ($od_categories as $od_category): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									
    									<td><?php echo $od_category['name'];?></td>
    									<td><?php echo (! empty($od_category['category']['name']))? $od_category['category']['name'] : 'NA' ;?></td>
    									<td><?php echo $od_category['desc'];?></td>
    									<td><img
										src="<?php echo base_url();?>uploads/od_category_image/od_category_<?php echo $od_category['id'];?>.jpg?<?php echo time();?>" class="img-thumb"></td>
									
									<td><a
										href="<?php echo base_url()?>od_categories/edit?id=<?php echo $od_category['id']; ?>"
										class=" mr-2  " type="od_categories"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $od_category['id'] ?>, 'od_categories')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>Sorry!! No Specialities!!!</center>
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