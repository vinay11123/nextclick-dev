<!--Add Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven">Add News Category</h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('news_categories/c');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>Category Name</label> <input type="text" name="name"
							required="" value="<?php echo set_value('name')?>"
							class="form-control">
						<div class="invalid-feedback">New Category Name?</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label>Description</label> <input type="text" name="desc"
							required="" value="<?php echo set_value('desc')?>"
							class="form-control">
						<div class="invalid-feedback">Give some Description</div>
						<?php echo form_error('desc', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group  col-md-4">
						<label>Upload Image</label> <input class="form-control" type="file" name="file"
							required="" value="<?php echo set_value('file')?>"
							 onchange="readURL(this);">
							<!-- <img id="blah"
							src="#" alt="" class="img-thumbnail"> -->
						<div class="invalid-feedback">Upload Image?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group col-md-1">
					<img id="blah"
							src="#" alt="" class="img-thumbnail">
					</div>
					<div class="form-group col-md-2">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>
				</div>
			</div>
		</form>

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of News Categories</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Category Name</th>
									<th>Description</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
							<?php if(!empty($news_categories)):?>
    							<?php  $sno = 1; foreach ($news_categories as $news_category): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $news_category['name'];?></td>
									<td><?php echo $news_category['desc'];?></td>
									<td><img
										src="<?php echo base_url();?>uploads/news_category_image/news_category_<?php echo $news_category['id'];?>.jpg?<?php echo time();?>"
										class="img-thumb"></td>
									<td><a
										href="<?php echo base_url()?>news_categories/edit?id=<?php echo $news_category['id']; ?>"
										class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $news_category['id'] ?>, 'news_categories')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='5'><h3>
									<center>No Categories</center>
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
</div>
