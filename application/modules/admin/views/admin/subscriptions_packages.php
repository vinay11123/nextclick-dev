<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of Subscriptions</h4>
					<a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('subscriptions_packages/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Subscriptions</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Service Id</th>
									<th>Title</th>
									<th>Description</th>
									<th>Days</th>
									<th>Price</th>
									<th>Discounted Price</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($subscriptions_packages)):?>
    							<?php $sno = 1; foreach ($subscriptions_packages as $packages): ?>
                            

    								<tr>
									<td><?php echo $sno++;?></td>
                                    <td><?php echo $packages['service_id'];?></td>
                                    <td><?php echo $packages['title'];?></td>
    								<td><?php echo $packages['desc'];?></td>
    								<td><?php echo $packages['days'];?></td>
                                    <td><?php echo $packages['display_price'];?></td>
									<td><?php echo $packages['price'];?></td>
									<td><a
										href="<?php echo base_url()?>subscriptions_packages/edit?id=<?php echo $packages['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a>
									<a href="<?php echo base_url()?>subscriptions_packages/manage_features?id=<?php echo $packages['id']; ?>"
										class=" mr-2  " type="settings"> Settings
									</a>
									<a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $packages['id'] ?>, 'subscriptions_packages')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>Sorry!! No Packages Found!!!</center>
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