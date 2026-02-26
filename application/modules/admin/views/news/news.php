<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-10 ven1">List of News</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Title</th>
									<th>Image</th>
									<th>Category</th>
									<th>Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($news)):?>
    							<?php  $sno = 1; foreach ($news as $n): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $n['title'];?></td>
									<td><img
										src="<?php echo base_url();?>uploads/news_image/news_<?php echo $n['id'];?>.jpg?<?php echo time();?>"
										class="img-thumb"></td>
									<td><?php foreach ($news_categories as $category):?>
    									<?php echo ($category['id'] == $n['category'])? $category['name']:'';?>
    									<?php endforeach;?></td>
    									<td><?php echo $n['news_date'];?></td>
									<td><a
										href="<?php echo base_url()?>news/edit?id=<?php echo $n['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $n['id'] ?>, 'news')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
											<center>News Not Available</center>
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