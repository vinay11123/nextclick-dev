<div class="card-body">
    <div class="card">
        <div class="card-header">
            <h4 class="col-9 ven1">Delivery Insentives</h4>
            <a class="btn btn-outline-dark btn-lg col-3" href="<?php echo base_url('delivery_insentive/add') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if (!empty($delivery_insentive_configs)) { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>SL No</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Constituency</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($delivery_insentive_configs as $delivery_insentive_config) { ?>
                                <tr>
                                    <td> <?php echo $i; ?> </td>
                                    <td> <?php echo $delivery_insentive_config['state_object']['name'] ?> </a> </td>
                                    <td> <?php echo $delivery_insentive_config['district_object']['name'] ?> </a> </td>
                                    <td> <?php echo $delivery_insentive_config['constituency_object']['name'] ?> </a> </td>

                                    <td>
                                        <a href="<?php echo site_url() ?>delivery_insentive/mutate_status/<?php echo $delivery_insentive_config['id'] ?>">
                                            <?php if ($delivery_insentive_config['status'] == 0) {
                                                echo "Activate";
                                            } else {
                                                echo "Deactivate";
                                            } ?>
                                        </a>
                                        <a href="<?php echo site_url() ?>delivery_insentive/edit/<?php echo $delivery_insentive_config['id'] ?>">Edit</a>
                                    </td>

                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <strong>No Delivery Insentives!</strong>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>