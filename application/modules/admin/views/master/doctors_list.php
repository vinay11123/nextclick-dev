<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of Doctors</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('doctors/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Doctor</a>
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
									<th>Qualificaion</th>
									<th>Experience</th>
									<th>Languages</th>
									<th>Fee</th>
									<th>Discount</th>
									<th>Holiday</th>
									<th class="tdimgul">Image</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($doctors)):?>
    							<?php  $sno = 1; foreach ($doctors as $doctor): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									   
									    <td><?php echo $doctor['doctor_details'][0]['id'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['name'];?></td>
										<td><?php echo $doctor['doctor_details'][0]['desc'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['qualification'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['experience'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['languages'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['fee'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['discount'];?></td>
    									<td><?php echo $doctor['doctor_details'][0]['holidays'];?></td>
    									
    									<td><img class="tdimguld"
										src="<?php echo base_url();?>uploads/doctors_image/doctors_<?php echo $doctor['id'];?>.jpg?<?php echo time();?>" class="img-thumb"></td>
									
									<td><a
										href="<?php echo base_url()?>doctors/edit?id=<?php echo $doctor['id'];?>"
										class=" mr-2" type="doctors"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $doctor['id'] ?>, 'doctors')">
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