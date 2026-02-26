<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of FAQ's</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('faq/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add FAQ's</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Related To</th>
									<th>FAQ's</th>
									<th>Answer</th>
									
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($faq)):?>
    							<?php  $sno = 1; foreach ($faq as $faq_obj): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php foreach ($app_details as $category):?>
    									<?php echo ($category['id'] == $faq_obj['app_id'])? $category['app_name']:'';?>
    									<?php endforeach;?></td>
    									<td><?php echo $faq_obj['question'];?></td>
    									<td><?php echo $faq_obj['answer'];?></td>
									<td><a
										href="<?php echo base_url()?>faq/edit?id=<?php echo $faq_obj['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $faq_obj['id'] ?>, 'faq')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>Sorry!! No FAQ's!!!</center>
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