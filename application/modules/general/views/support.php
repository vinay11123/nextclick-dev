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
             <form class="" novalidate="" action="<?php echo base_url('general/support/support_queries/r/0');?>" method="post" enctype="multipart/form-data">
              <div class="row">
 
 
             <div class="form-group col-3">
                  <label for="q">App Name</label>
                <input type="text" name="app_name" id="app_name" placeholder="app name" value="" class="form-control">
              </div> 

            <div class="form-group col-3">
                  <label for="q">Request Contant</label>
                <input type="text" name="content" id="content" placeholder="content" value="" class="form-control">
              </div> 

              <div class="form-group col-3">
                <label for="noofrows">rows</label>
                <input type="text" id="noofrows" name="noofrows" placeholder="rows" value="<?php echo $noofrows;?>" class="form-control">
              </div>
              <div class="form-group col-2" style="margin-top: 43px;">
          <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
          </div>
          </div>
          
           </form>
            <form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php echo base_url('general/support/support_queries/r/0');?>" method="post">
        
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
                <h4 class="col-9 ven1">List of Request's</h4>

                <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('general/support/support_queries/c/0')?>"><i class="fa fa-plus" aria-hidden="true"></i>Add QUERIES</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Token no</th>
                                <th>App Name</th>
                                <th>App ID</th>
                                <th>Request Content</th>
                                <th>Mobile</th>
                                <th>EmailID</th>
                                <th>subject</th>
                                <th>Message</th>
                                <th>user_id</th>
                                <th>created_at</th>
                                <th>staus</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($support_requests)):?>
                            <?php $sno = 1; foreach ($support_requests as $req):?>
                            <tr>
                                <td><?php echo $sno++;?></td>
                                <td><?php echo $req['token_no'] ;?></td>
                                <td><?php echo $req['app_name'];?></td>
                                <td><?php echo $req['app_id'];?></td>
                                <td><?php echo $req['title'];?></td>
                                <td><?php echo $req['mobile'];?></td>
                                <td><?php echo $req['email'];?></td>
                                 <td><?php echo $req['subject'];?></td>
                                  <td><?php echo $req['message'];?></td>
                                   <td><?php echo $req['created_user_id'];?></td>
                                   <td><?php echo $req['created_at'];?></td>
                                    <td><?php 
                                    if($req['staus'] == 1)
                                    {
                                      echo "Open";
                                    }
                                    if($req['staus'] == 2)
                                    {
                                      echo "Working";
                                    }
                                    if($req['staus'] == 3)
                                    {
                                      echo "Closed";
                                    }

                                   ?></td>

                                <td>
                                    <a href="<?php echo base_url()?>general/support/support_queries/edit?id=<?php echo base64_encode(base64_encode($req['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="<?php echo base_url()?>general/support/support_queries/delete?id=<?php echo base64_encode(base64_encode($req['id']));?>" class="mr-2  text-danger "  > <i
                                                class="far fa-trash-alt"></i>
                                    </a>
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
