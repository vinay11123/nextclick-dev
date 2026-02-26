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
        		 <form class="" novalidate="" action="<?php echo base_url('sections/0');?>" method="post" enctype="multipart/form-data">
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
					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
        		</form>
        		<form class="needs-validation h-100 justify-content-center align-items-center ptar" novalidate="" action="<?php echo base_url('sections/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control">
                    <input type="hidden" id="noofrows" name="noofrows" placeholder="rows" value="10" class="form-control">
    				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3">Clear</button>
    			</form>
			</div>
		</div>
	</div>
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Sections</h4>
					<a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('food_section/r')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Section</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Section Name</th>
									<th>Item</th>
									<th>Menu</th>
									<?php if($this->ion_auth->is_admin() || $required == 2  || $required == 0){ ?>
									<th>Required (Yes/No)</th>
									<?php }?>
									<?php if($this->ion_auth->is_admin() || $section_field == 3){ ?>
									<th>Selection type</th>
									<?php }?>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($sections)):?>
    							<?php  $sno = 1; foreach ($sections as $section): 
    							?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $section['name'];?></td>
									<td><?php echo $section['item']['name'];?></td>
									<td><?php echo $section['menu']['name'];?></td>
									<?php if($this->ion_auth->is_admin() || $required == 2  || $required == 0){ ?>
									<td><?=($section['required']==1)? 'Yes' : 'No';?></td>
									<?php }?>
									<?php if($this->ion_auth->is_admin() || $section_field == 3){ ?>
									<td><?=($section['item_field']==1)? 'Signle select' : 'Multiple select';?></td>
									<?php }?>
									<td><a
										href="<?php echo base_url()?>food_section/edit?id=<?php echo base64_encode(base64_encode($section['id'])); ?>"
										class=" mr-2  " type="ecom_brands"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $section['id'] ?>, 'food_section')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='7'><h3>
											<center>No Data Found</center>
										</h3></th>
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

<script type="text/javascript">
    function ch_sec_price(promo_type) {
        if(promo_type == 'radio'){
        	$('#all_sec_price').show();
        	//$('#check_sec_price').hide();
        }else if(promo_type == 'check'){
        	$('#all_sec_price').hide();
        	//$('#check_sec_price').show();
        }
    }
</script>