<!--Add Sub_Category And its list-->

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


/*.card-body.listflow>.card-body{
  font-size: smaller;
}*/
.card-body.listflow {
    font-size: smaller;
}
@media (max-width:360px) {

    .card-body.listflow {
    font-size: smaller;
    width: 300px !important;
}
}
</style>

<div class="row h-100 justify-content-center align-items-center">
  <div class="col-12">
        <div class="card-header">
          <h4 class="ven">Request Lists</h4>
             <form class="" novalidate="" action="<?php echo base_url('general/support/customer/r/0');?>" method="post" enctype="multipart/form-data">
              <div class="row">
 
 
             <div class="form-group col-3">
                  <label for="q">App Name</label>
				<select name="app_name" id="app_name" class="form-control"> 
					<option value="1">User Application</option>
					<option value="2">Vendor Application</option>
					<option value="4">Devlivery Partner</option>
				</select>
    			
              </div>


			  

            <div class="form-group col-3">
                  <label for="q">Request Type</label>
                <input type="text" name="content" id="content" placeholder="content" value="" class="form-control">
              </div> 

              <div class="form-group col-3">
                <label for="noofrows">rows</label>
                <input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
              </div>
			  
			  <div class="form-group col-3">
                  <label for="q">Severity</label>
				<select name="severity" id="severity" class="form-control"> 
					<option value="0">Low</option>
					<option value="1">Medium</option>
					<option value="2">High</option>
					<option value="3">Critical</option>
				</select>
    			
              </div>
			  
			  <div class="form-group col-3">
                  <label for="q">Status</label>
				<select name="status" id="status" class="form-control"> 
					<option value="1">Open</option>
					<option value="2">Working</option>
					<option value="3">Closed</option>
				</select>
    			
              </div>
              <div class="form-group col-2" style="margin-top: 43px;">
          <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
          </div>
          </div>
          
           </form>
            <form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php echo base_url('general/support/customer/r/0');?>" method="post">
        
        <input type="hidden" name="app_name" id="app_name" placeholder="app name" value="" class="form-control">
          <input type="hidden" name="content" id="content" placeholder="content" value="" class="form-control">
         
            <button type="submit" name="submit" class="btn btn-danger mt7">Clear</button>
          </form>
      </div>
    </div>
  </div>
<div class="row">
     <div class="col-md-12">
    <div class="card-body listflow">
      <div class="table-responsive">
        <div class="card">
            <div class="card-header">
                <h4 class="col-9 ven1">List of Customer Support</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>App Name</th>
                                <th>UserName</th>
								<th>Phone Number</th>
								<th>Email</th>
                                <th>Request Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Severity</th>
                                <th>created_at</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Assigned By</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
							
                            <?php if(!empty($support_requests)):?>
                            <?php $sno = 1; foreach ($support_requests as $req):?>
                            <tr>
                                <td><?php echo $req['id'];?></td>
                                <td><?php echo $req['app_name'];?></td>
								 <td><p><?php echo $req['first_name'] . ' '. $req['last_name'];?></p>
									<p><b>Business Name</b>:  <?php echo $req['bussiness_name'];?></p>
								 </td>
								<td><?php echo $req['phone'];?></td>

                                   <td><?php echo $req['email'];?></td>
                                <td><?php echo $req['request_type'];?></td>
                                <td><?php echo $req['title'];?></td>
                                <td><?php echo $req['description'];?></td>
                                <td><?php //echo $req['severity'];
								
								$favcolor = $req['severity'];

switch ($favcolor) {
  case "0":
    echo "Low";
    break;
  case "1":
    echo "Medium";
    break;
  case "2":
    echo "High";
    break;
  default:
    echo "Critical";
	break;
}
								?></td>

                                   <td><?php echo $req['created_at'];?></td>
                                    <td><?php 
                                    if($req['status'] == 1)
                                    {
                                      echo "Open";
                                    }
                                    if($req['status'] == 2)
                                    {
                                      echo "Working";
                                    }
                                    if($req['status'] == 3)
                                    {
                                      echo "Closed";
                                    }

                                   ?></td>
								<td><?php 
									$assigned_to = $req['assigned_to'];
									if($assigned_to!=0){
									$assigned_to_name=$this->db->query("select first_name,last_name from users where id= '$assigned_to'")->result_array();
									echo $assigned_to_name[0]['first_name']. " ".$assigned_to_name[0]['last_name'];
									}
									else{
										echo "";
									}
									?></td>
								<td><?php 
									$assigned_by = $req['assigned_by'];
									if($assigned_by!=0){
									$assigned_name=$this->db->query("select first_name,last_name from users where id= '$assigned_by'")->result_array();
									echo $assigned_name[0]['first_name']. " ".$assigned_name[0]['last_name'];
									}
									else{
										echo "";
									}
									?></td>
                                <td>
                                  <a href="<?php echo base_url()?>general/support/customer/edit?id=<?php echo base64_encode(base64_encode($req['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!--<a href="<?php echo base_url()?>general/support/customer/delete?id=<?php echo base64_encode(base64_encode($req['id']));?>" class="mr-2  text-danger "  > <i
                                                class="far fa-trash-alt"></i>
                                    </a> -->
                                </td>


                               
                            </tr>
                            <?php endforeach;?>
                            <?php else :?>
                            <tr>
                                <th colspan="5">
                                    <h3><center>No Request's</center></h3>
                                </th>
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
</div>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script type="text/javascript">
//     $(document).ready(function(){
// $('#btnFilter').click(function(){
// alert('clicked');
// });

//     });
$(document).ready(function(){
$('#tableExport').DataTable({
  dom: 'Bfrtip',
  buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
  ],
})
});
</script>
