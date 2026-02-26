<!--Add Sub_Category And its list-->


<div class="row h-100 justify-content-center align-items-center">
  <div class="col-12">
        <div class="card-header">
          <h4 class="ven">Request List</h4>
             <form class="" novalidate="" action="<?php echo base_url('admin/master/support/r');?>" method="post" enctype="multipart/form-data">
              <div class="row">
 
<div class="form-group col-3">
   <label for="q">Track ID</label>
                <select required class="form-control" name="req_id"  >
                <option value="0" selected disabled>--select--</option>
                  <?php foreach ($request_type as $category):?>
                    <option value="<?php echo $category['id'];?>"><?php echo $category['title']?></option>
                  <?php endforeach;?>
            </select>
</div>
              <!--<div class="form-group col-3">
                  <label for="q">Track ID</label>
                <input type="text" name="tid" id="tid" placeholder="Track ID" value="" class="form-control">
              </div>-->
          </div>
          <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt-27 ">Search</button>
            </form>
            <form class="needs-validation h-100 justify-content-center align-items-center" novalidate="" action="<?php echo base_url('admin/master/support/r');?>" method="post">
        <input type="hidden" name="req_id" id="req_id" value="" class="form-control">
         
            <button type="submit" name="submit" class="btn btn-danger mt-3">Clear</button>
          </form>
      </div>
    </div>
  </div>
<div class="row">
     
    <div class="card-body">
        <div class="card">
            <div class="card-header">
                <h4 class="ven">List of Request's</h4>

                <a class="btn btn-outline-dark btn-lg col-2" href="<?php echo base_url('admin/master/support/c')?>"><i class="fa fa-plus" aria-hidden="true"></i>Add QUERIES</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Unique Id</th>
                                <th>Request's Related To</th>
                                <th>Vendors mail</th>
                                <th>Contact Mail</th>
                                <th>Request Content</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Deleted At</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($requests)):?>
                            <?php $sno = 1; foreach ($requests as $req):?>
                            <tr>
                                <td><?php echo $sno++;?></td>
                                <td><?php echo (! empty($req['users'][0]['unique_id']))? $req['users'][0]['unique_id'] :'NA' ;?></td>

                                <td><?php foreach ($request_type as $category):?>
                                        <?php echo ($category['id'] == $req['req_id'])? $category['title']:'';?>
                                <?php endforeach;?></td>

                                <td><?php echo (! empty($req['users']))? $req['users'][0]['email']:'NA';?></td>
                                <td><?php echo $req['contact_mail'];?></td>
                                <td><?php echo $req['req_content'];?></td>
                                <td><?php echo $req['created_at'];?></td>
                                <td><?php echo $req['updated_at'];?></td>
                                <td><?php echo $req['deleted_at'];?></td>
                                <td>
                                    <a href="<?php echo base_url()?>admin/master/support/edit?id=<?php echo base64_encode(base64_encode($req['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="<?php echo base_url()?>admin/master/support/delete?id=<?php echo base64_encode(base64_encode($req['id']));?>" class="mr-2  text-danger "  > <i
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
