 <style>
.page-item>a {
	position: relative;
	display: block;
	padding: .5rem .75rem;
	margin-left: -1px;
	line-height: 1.25;
	color: #007bff;
	background-color: #fff;
	border: 1px solid #dee2e6;
}

a {
	color: #007bff;
	text-decoration: none;
	background-color: transparent;
}

.pagination>li.active>a {
	background-color: orange !important;
}

.dataTables_filter {
	display: none;
}
.or{
    text-align: center;
}
</style>
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
    		<div class="card-header">
    			<h4 class="ven subcategory">Vendors Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('section_items/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
        				<div class="form-group col-3">
        					<label for="q">Search</label>
    						<input type="text" name="q" id="q" placeholder="Name" value="<?php echo $q;?>" class="form-control">
    					</div>
                        <div class="form-group col-2">
    						<label for="noofrows">rows</label>
    						<input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
    					</div>
					</div>
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('section_items/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
                    <input type="hidden" id="noofrows" name="noofrows" placeholder="rows" value="10" class="form-control">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
    			</form>
			</div>
		</div>
	</div>
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of section items</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_section_item/r')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Section item</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Section Item Name</th>
									<th>Description</th>
									<th>Price</th>
									<th>Section</th>
									<th>Item</th>
									<th>Menu</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($section_items)):?> 
    							<?php $sno = 1; foreach ($section_items as $food_sec_item):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $food_sec_item['name'];?></td>
    									<td><?php echo $food_sec_item['desc'];?></td>
    									<td><?php echo $food_sec_item['price'];?></td>
    									<td><?php echo $food_sec_item['sec']['name'];?></td>
    									<td><?php echo $food_sec_item['item']['name'];?></td>
    									<td><?php echo $food_sec_item['menu']['name'];?></td>
    									<td><a href="<?php echo base_url()?>food_section_item/edit?id=<?php echo base64_encode(base64_encode($food_sec_item['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $food_sec_item['id'] ?>, 'food_section_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='8'><h3><center>No Data Found</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
					<!-- Paginate -->
    				<div class="row  justify-content-center">
    					<div class=" col-12" style='margin-top: 10px;'>
                           <?= $pagination; ?>
                        </div>
    				</div>
				</div>
			</div>


		</div>

	</div>
</div>

