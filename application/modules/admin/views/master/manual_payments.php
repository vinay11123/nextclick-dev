
<div class="card-body">
    <div class="card">
        <div class="card-header">
            <h4 class="col-9 ven1">Manage Manual Payments</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if (!empty($manual_payments)) { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Transaction Ref. #</th>
                                <th>Payment For</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; 
                            foreach ($manual_payments as $manual_payment) { ?>
                                <tr>
                                    <td> <?php echo $i; ?> </td>
                                    <td> <?php echo $manual_payment['payment_txn_id'] ?> </td>
                                    <td> <?php echo $manual_payment['payment_intent'] ?> </td>
									<?php
									$user_id = $manual_payment['created_user_id'];
									$user_details = $this->db->query("SELECT *  FROM users WHERE id = '$user_id'")->result_array();
									?>
                                    <td> <?php echo $user_details[0]['first_name']." ".$user_details[0]['last_name'] ?> </td>
                                    <td> <?php echo $user_details[0]['email'] ?> </td>
                                    <td> <?php echo $user_details[0]['phone'] ?> </td>
                                    <td> <?php echo number_format((float)$manual_payment['amount'], 2, '.', '') ?> </td>
                                    <td> <?php echo date("d-m-Y",strtotime($manual_payment['created_at'])); ?> </td>
                                    <?php if($manual_payment['status'] == 1){ ?>
                                    <td>
                                        <button class="btn btn-primary approve_manual_payment" payment_ref="<?php echo $manual_payment['id']?>">Approve</button>
                                        <button class="btn btn-primary reject_manual_payment" payment_ref="<?php echo $manual_payment['id']?>">Reject</button>
                                    </td>
                                    <?php }else if($manual_payment['status'] == 2){ ?>
                                        <td> <button class="btn btn-primary">Approved</button></td>
                                    <?php } ?>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        <strong>No Manual Payments!</strong>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>