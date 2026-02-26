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

<!--<div class="row h-100 justify-content-center align-items-center">
  <div class="col-12">
        <div class="card-header">
          <h4 class="ven">Request List</h4>
             <form class="" novalidate="" action="<?php //echo base_url('admin/master/support/r');?>" method="post" enctype="multipart/form-data">
              <div class="row">
 
<div class="form-group col-3">
   <label for="q">Track ID</label>
                <select required class="form-control" name="req_id"  >
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($request_type as $category):?>
                    <option value="<?php //echo $category['id'];?>"><?php //echo $category['title']?></option>
                  <?php endforeach;?>
            </select>
</div>
               <div class="form-group col-3">
                  <label for="q">Track ID</label>
                <input type="text" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
              </div> 
          </div>
          <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
            </form>
            <form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php //echo base_url('admin/master/support/r');?>" method="post">
        <input type="hidden" name="req_id" id="req_id" value="" class="form-control">
         
            <button type="submit" name="submit" class="btn btn-danger mt-3">Clear</button>
          </form>
      </div>
    </div>
  </div>-->
<div class="row">
     
    <div class="card-body listflow">
        <div class="card">
            <div class="card-header">
                <h4 class="ven">Stock Setting</h4>

                <!-- <a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('admin/admin/stock_settings/c/0')?>"><i class="fa fa-plus" aria-hidden="true"></i>Add Stock</a> -->
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover"  style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Minimum Stock</th>
                                <!-- <th>Created Userid</th>
                                <th>created_at</th>
                                <th>updated_at</th>
                                <th>deleted_at</th>
                                <th>status</th>
                               -->
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($stock_setting)):?>
                            <?php $sno = 1; foreach ($stock_setting as $req):?>
                            <tr>
                                <td><?php echo $sno++;?></td>
                                <td><?php echo $req['min_stock'] ;?></td>
                                <!-- <td><?php 

$dt = $this->user_model->where('id', $req['created_user_id'])->get();
  
                                echo $dt['username'];?></td>
                                <td><?php echo $req['created_at'];?></td>
                                <td><?php echo $req['updated_at'];?></td>
                                <td><?php echo $req['deleted_at'];?></td>
                                 
                                 
                                    <td><?php 
                                    if($req['status'] == 1)
                                    {
                                      echo "Active";
                                    }
                                    if($req['status'] == 2)
                                    {
                                      echo "In-active";
                                    }
                                     

                                   ?></td>
-->
                                <td>
                                    <a href="<?php echo base_url()?>admin/admin/stock_settings/edit?id=<?php echo base64_encode(base64_encode($req['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- <a href="<?php echo base_url()?>admin/admin/stock_settings/delete?id=<?php echo base64_encode(base64_encode($req['id']));?>" class="mr-2  text-danger "  > <i
                                                class="far fa-trash-alt"></i>
                                    </a> --->
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

                        <div class="row  justify-content-center">
                         <div class=" col-12" style='margin-top: 10px;'>
                          <?= $pagination; ?>
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
