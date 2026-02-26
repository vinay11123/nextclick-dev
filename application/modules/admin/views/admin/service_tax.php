
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="card">
                <div class="card-header">
                    <h4 class="col-9 ven1">List of Service Charges</h4>
                        <a class="btn btn-outline-dark btn-lg" href="<?php echo base_url('service_tax/c')?>"><i class="fa fa-plus" aria-hidden="true"></i> Add Service Charge</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Category</th>
                                    <th>SubCategory</th>
                                    <th>Menu</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Constituency</th>
                                    <th>Service Charge in %</th>
                                    <!-- <th>Rate</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($service_tax)):?>
                                <?php $sno = 1; foreach ($service_tax as $st): ?>
                                                            
                                    <tr>
                                    <td><?php echo $sno++;?></td>
                                    <td><?php echo $st['category']['name'];?></td>
                                    <td><?php echo empty($st['subcategory']) ? "All" :$st['subcategory']['name'];?></td>
                                    <td><?php echo empty($st['menu']) ? "All" :$st['menu']['name'];?></td>
                                    <td><?php echo empty($st['state']) ? "All" : $st['state']['name'];?></td>
                                    <td><?php echo empty($st['district']) ? "All" : $st['district']['name'];?></td>
                                    <td><?php echo empty($st['constituency']) ? "All" : $st['constituency']['name'];?></td>
                                    <td><?php echo $st['service_tax'];?></td>
                                    <!-- <td><?php echo $st['rate'];?></td> -->
                                    <td>
                                    <a href="<?php echo base_url()?>service_tax/edit?id=<?php echo $st['id'] ?>" class=" mr-2  " type="service_tax" > <i class="fas fa-pencil-alt"></i></a>
                                    <a href="#" class="mr-2  text-danger "
                                    onClick="delete_record(<?php echo $st['id'] ?>, 'service_tax')">
                                    <i class="far fa-trash-alt"></i>
                                    </a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            <?php else :?>
                            <tr>
                            <th colspan="5">
                            <h3><center>Sorry!! No Service Taxes Found!!!</center></h3>
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

