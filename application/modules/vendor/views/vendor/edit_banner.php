<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven"> Banners</h2>
                    </header>
                    <div class="card-body">
                         <form id="form_cover" action="<?php echo base_url('vendor_profile/update_banner');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-md-6 form-group">
                            <label>Upload Image</label> 
                            <input type="file" name="banner" required=""  class="form-control" onchange="readURL(this);">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                            <input type="hidden" name="list_id" value="<?php echo $_GET['list_id']?>">
                            <img id="blah" src="<?php echo base_url();?>uploads/list_banner_image/list_banner_<?php echo $_GET['id'];?>.jpg" alt=""> </div>
                        </div>
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                      </form>
                    <hr/>
                    </div>
            
                </section>
        </div>

    </div>
</div>