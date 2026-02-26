<style>
.elementToFadeInAndOut {
    display:block;
    -webkit-animation: fadeinout 10s linear forwards;
    animation: fadeinout 10s linear forwards;
}
@-webkit-keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
@keyframes fadeinout {
  0%,100% { opacity: 0; }
  50% { opacity: 1; }
}
</style>
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="card">
                <?php if (!empty($this->session->flashdata('upload_status'))) {
                ?>
                    <div class="alert alert-success elementToFadeInAndOut">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('upload_status'); ?>
                    </div>
                <?php
                } ?>
				<?php if (!empty($this->session->flashdata('delete_status'))) {
                ?>
                    <div class="alert alert-danger elementToFadeInAndOut">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('delete_status'); ?>
                    </div>
                <?php
                } ?>
                <div class="card-header">
                    <h4 class="col-9 ven1">List of Return Policies</h4>
                        <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('return_policies/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Return Policies</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Sub Category</th>
                                    <th>Menu</th>
                                    <th>Days to Return</th>
                                    <th>Return Policies</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($return_policies)):?>
                                <?php $sno = 1; foreach ($return_policies as $rp): ?>
                                                            
                                    <tr>
                                    <td><?php echo $sno++;?></td>
                                    <td><?php echo $rp['sub_category']['name'];?></td>
                                    <td><?php echo $rp['menu']['name'];?></td>
                                    <td><?php echo $rp['return_days'];?></td>
                                    <td><?php echo $rp['terms_conditions'];?></td>
                                    <td>
                                        <a href="#" class="mr-2  text-danger "
                                    onClick="delete_record(<?php echo $rp['id'] ?>, 'return_policies')">
                                    <i class="far fa-trash-alt"></i>
                                    </a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            <?php else :?>
                            <tr>
                            <th colspan="5">
                            <h3><center>Sorry!! No Return Policies Found!!!</center></h3>
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

