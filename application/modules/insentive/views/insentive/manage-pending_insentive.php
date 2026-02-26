<div class="card-body">
    <div class="card">
        <div class="card-header">
            <h4 class="col-9 ven1">Delivery Insentive Review</h4>
            <label class="right">Est. Total: Rs. <?php ((float) $total_insentive >0) ? print(number_format((float)$total_insentive, 2, '.', '')) : print '0' ; ?></label>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if (!empty($deivery_boy_performances)) { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Total Touch Points</th>
                                <th>Incentive Amt.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($deivery_boy_performances as $deivery_boy_performance) { ?>
                                <tr>
                                    <td> <?php echo $i; ?> </td>
                                    <td> <?php echo $deivery_boy_performance['id'] ?> </td>
                                    <td> <?php echo $deivery_boy_performance['first_name'] ?> </td>
                                    <td> <?php echo $deivery_boy_performance['last_name'] ?> </td>
                                    <td> <?php echo $deivery_boy_performance['touch_points'] ?> </td>
                                    <td> <?php echo number_format((float)$deivery_boy_performance['amount'], 2, '.', '') ?> </td>

                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                    <form role="form" method="post" action="<?php echo site_url() ?>delivery_insentive/process">
                        <button class="btn btn-primary right">Process</button>
                    </form>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <strong>No Pending Delivery Insentives!</strong>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>