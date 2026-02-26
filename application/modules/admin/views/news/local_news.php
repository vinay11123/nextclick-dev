<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-8 ven1">List of Local News</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Title</th>
									<th>Content</th>
									<th>Category</th>
									<th>Video Link</th>
									<th>Image</th>
									<th>Date</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($local_news)):?>
    							<?php  $sno = 1; foreach ($local_news as $n): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $n['title'];?></td>
									<td><?php echo $n['news'];?></td>
									<td><?php foreach ($news_categories as $category):?>
    										<?php echo ($category['id'] == $n['category'])? $category['name']:'';?>
    									<?php endforeach;?></td>
									<td><?php echo $n['video_link'];?></td>
									
									<td><img src="<?php echo base_url();?>uploads/local_news_image/local_news_<?php echo $n['id']?>.jpg?<?php echo time();?>" class="img-thumb"></td>
    								<td><?php echo $n['created_at'];?></td>
    								<td><input type="checkbox" class="approve_news"  user_id="<?php echo $n['user_id'];?>" <?php echo ($n['status'] == 2) ? 'checked':'' ;?>  data-toggle="toggle" data-style="ios" data-on="Published" data-off="Posted" data-onstyle="success" data-offstyle="danger"></td>
									
									<td><a href="<?php echo base_url()?>local_news/edit?id=<?php echo $n['id']; ?>" class="mr-2" type="local_news"><i class="fas fa-pencil-alt"></i></a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $n['id'] ?>, 'local_news')">
											<i class="far fa-trash-alt"></i>
									</a>
									

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='8'><h3>
											<center>Local News Not Available</center>
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