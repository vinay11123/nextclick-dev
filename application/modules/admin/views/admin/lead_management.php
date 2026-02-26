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
    			<h4 class="ven">Lead Filter</h4>
        		 <form class="" novalidate="" action="<?php echo base_url('employee/r/0');?>" method="post" enctype="multipart/form-data">
        		 	<div class="row">
    					
					</div>
<!-- 					<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
        		</form>
         		<form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php //echo base_url('vendors_filter/0');?>" method="post" enctype="multipart/form-data">
    				<input type="hidden" name="q" placeholder="Search" value="" class="form-control"> -->
<!--     				<button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt-3">Clear</button> -->
<!--     			</form> -->
			</div>
		</div>
	</div>
	
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">Leads</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>User Name</th>
									<th>Mobile</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
                        <?php $i = 1; foreach ($vendor_leads as $lead):?>
                          		<tr>
									<td><?php echo $i++;?></td>
									<td><?php echo $lead['lead']['user']['first_name'].' '.$lead['lead']['user']['last_name'];?></td>
									<td><?php echo $lead['lead']['user']['phone'];?></td>
									<td><?php echo $lead['lead']['user']['email'];?></td>
								</tr>
                          <?php endforeach;?>
                        </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
