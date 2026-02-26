
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="col-9 ven1">List of Vendor Packages</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExportNoPagination"
							style="width: 100%;">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Service</th>
									<th>Package</th>
									<th>Vendor Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Package start date</th>
									<th>Package End date</th>
									<th>Days</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
                        <?php 
						$i = 1; foreach ($vendor_packages as $package):
						$vuser_id=$package['vendors']['vendor_user_id'];
						$users = $this->db->query("SELECT *  FROM users WHERE id = '$vuser_id'")->result_array();
						?>
						
                                <tr>
									<td><?php echo $i++;?></td>
									<td><?php echo $package['services']['name'];?></td>
									<td><?php echo $package['packages']['title'];?></td>
									<td><?php echo $package['vendors']['name'];?></td>
									<td><?php echo $users[0]['email'];?></td>
									<td><?php echo $users[0]['phone'];?></td>
									<td><?php echo $package['created_at'];?></td>
									<td><?php  $aday=$package['packages']['days'];
											 // echo $aday;
											echo date('Y-m-d H:i:s', strtotime($package['created_at']. ' +'.$aday.'  days'));

									?></td>
									<td><?php echo $package['packages']['days'];?></td>
									<td><?php $validity = date('Y-m-d H:i:s', strtotime($package['created_at']. ' +'.$aday.'  days'));
            echo (strtotime($validity) >= now()) && $package['packages']['status']==1 ? 'Active' : 'Inactive';?></td>
									
						        </tr>
                          <?php endforeach;?>
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
