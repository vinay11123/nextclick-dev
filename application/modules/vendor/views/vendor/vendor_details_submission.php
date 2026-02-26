
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

.or {
	text-align: center;
}
</style>
<!--Add Category And its list-->
<!--Add Category And its list-->
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-12">
		<div class="card-header">
			<h4 class="ven subcategory">Vendors Filter</h4>
			<form class="" novalidate=""
				action="<?php echo base_url('details_by_vendor/r/0');?>"
				method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="form-group col-3">
						<label for="q">Name</label> <input type="text" name="q" id="q"
							placeholder="Name" value="<?php echo $q;?>" class="form-control">
					</div>
					<div class="form-group col-2">
						<label for="exe">Till date</label> <input type="text" id="start_date"
							name="till_date" placeholder="Till Date"
							value="<?php echo $till_date;?>" class="form-control">
					</div>
					<div class="form-group col-3">
						<label for="mobile">Mobile</label> <input type="text" id="mobile"
							name="mobile" placeholder="Mobile" value="<?php echo $mobile;?>"
							class="form-control">
					</div>
					<div class="form-group col-2">
						<label for="noofrows">rows</label> <input type="text"
							id="noofrows" name="noofrows" placeholder="rows"
							value="<?php echo $noofrows;?>" class="form-control">
					</div>
				</div>
				<button type="submit" name="submit" id="upload" value="Apply"
					class="btn btn-primary mt-27 "><i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp; Search</button>
			</form>
			<form
				class="needs-validation h-100 justify-content-center align-items-center ptar"
				novalidate=""
				action="<?php echo base_url('details_by_vendor/r/0');?>"
				method="post" enctype="multipart/form-data">
				<input type="hidden" name="q" placeholder="Search" value=""
					class="form-control"> <input type="hidden" id="noofrows"
					name="noofrows" placeholder="rows" value="10" class="form-control">
				<button type="submit" name="submit" id="upload" value="Apply"
					class="btn btn-danger mt-3"><i class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
			</form>
		</div>
	</div>
</div>

<div class="card-body">
	<div class="card">
		<div class="card-header">
			<h4 class="ven subcategory">List of Vendors</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover"
					id="tableExportNoPagination" style="width: 100%;">
					<thead>
						<tr>
							<th>Sno</th>
							<th>Unique Id</th>
							<th>Customer</th>
							<th>Shop</th>
							<th>Landmark</th>
							<th>Address</th>
							<th>Contact</th>
							<th>Category</th>
							<th>Submitted at</th>
						</tr>
					</thead> 
					<tbody>
    					<?php if(!empty($vendors)): $sno = 1; foreach ($vendors as $vendor):?>
        				<tr>
							<td><?php echo $sno++;?></td>
							<td><?php echo $vendor['unique_id'];?></td>
							<td><?php echo $vendor['customer_name'];?></td>
							<td><?php echo $vendor['shop_name'];?></td>
							<td><?php echo $vendor['landmark'];?></td>
							<td><?php echo $vendor['address'];?></td>
							<td><?php echo (array_search($vendor['list_id'], array_column($contacts, 'list_id')) !== FALSE)? $contacts[array_search($vendor['list_id'], array_column($contacts, 'list_id'))]['number']: ""."<br />".$vendor['email'];?></td>
							<td><?php foreach ($categories as $category): if($vendor['category_id'] == $category['id']): echo $category['name']; endif;endforeach;?></td>
							<td><?php echo date('M d, Y (H:i)', strtotime($vendor['created_at']));?></td>
						</tr>
            			<?php endforeach; else :?>
        				<tr>
							<th colspan='9'><h3><center>No Vendor</center></h3></th>
						</tr>
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
