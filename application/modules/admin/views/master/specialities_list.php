<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Specialities</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('specialities/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Speciality</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Id</th>
									<th>Name</th>
								    <th>Description</th>
									<th>Image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($specialities)):?>
    							<?php  $sno = 1; foreach ($specialities as $speciality): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
								        <td><?php echo $speciality['id'];?></td>
    									<td><?php echo $speciality['name'];?></td>
										<td><?php echo $speciality['desc'];?></td>
										<td class="timg"><img
										src="<?php echo base_url();?>uploads/speciality_image/speciality_<?php echo $speciality['id'];?>.jpg?<?php echo time();?>"
										class="img-thumb resp"></td>
									
									<td><a
										href="<?php echo base_url()?>specialities/edit?id=<?php echo $speciality['id']; ?>"
										class=" mr-2  " type="specialities"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $speciality['id'] ?>, 'specialities')">
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