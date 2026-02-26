<div class="card-body">
	<div class="card">
		<div class="card-header">
			<h4 class="col-9 ven1">List Admin Banners</h4>
			<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('admin_banners/c') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Banners</a>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="BannerDatatable" class="table table-striped table-hover" style="width: 100%;">
					<thead>
						<tr>
							<th>Sno</th>
							<th>Position</th>
							<th>image</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $sno = 1;
						foreach ($banners as $images) : ?>

							<tr>
								<td><?php echo $sno++; ?></td>
								<td><?php echo $images['position']['title']; ?></td>
								<td><img src="<?php echo base_url(); ?>uploads/admin_banners/<?php echo $images['banner_image']; ?>" class="img-thumb">
								</td>
								<td> 				<?php
											
											if ($images['status'] == "1")

												echo "Active";
											elseif ($images['status'] == "0")
												echo "Inactive";
											?>
								</td>
								<td><a href="<?php echo base_url() ?>admin_banners/edit?id=<?php echo $images['id']; ?>" class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a>
									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $images['id'] ?>, 'admin_banners')">
										<i class="far fa-trash-alt"></i>
									</a>
								</td>


							</tr>
						<?php endforeach; ?>


					</tbody>
				</table>
			</div>
		</div>
	</div>


</div>

</div>