<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<main class="content_wrapper">
    <!--page title start-->

    <!--page title end-->
    <div class="container-fluid">
        <!-- state start-->
        <div class="row">
            <div class="col-12 mt-1 mb-2">
                <a class="btn-primary btn-sm" href="<?php echo base_url('executive/wallet'); ?>">Back</a>
            </div>
            <div class="col-12">
                <div class="card card-shadow bg-info text-white mb-4">
                    <div class="card-body">
                        <form action="<?php echo base_url('executive/transactions/submit'); ?>" method="post">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">All</option>
                                        <option value="vendor" <?php echo set_select('role', 'vendor'); ?>>Vendors
                                        </option>
                                        <option value="delivery_boy" <?php echo set_select('role', 'delivery_boy'); ?>>
                                            Delivery Boys</option>
                                        <option value="user" <?php echo set_select('role', 'user'); ?>>Users</option>
                                    </select>

                                </div>

                                <div class="col-6 mb-2">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">All</option>
                                        <option value="Credit" <?php echo set_select('type', 'Credit'); ?>>Credit
                                        </option>
                                        <option value="Debit" <?php echo set_select('type', 'Debit'); ?>>Debit</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label for="from_date">From Date</label>
                                    <input type="date" id="from_date" name="from_date" class="form-control"
                                        value="<?php echo set_value('from_date'); ?>">
                                </div>

                                <div class="col-6">
                                    <label for="to_date">To Date</label>
                                    <input type="date" id="to_date" name="to_date" class="form-control"
                                        value="<?php echo set_value('to_date'); ?>">
                                    <?php echo form_error('to_date', '<div class="text-danger">', '</div>'); ?>
                                </div>
                                <div class="col-12 mt-2 text-right">
                                    <button type="submit" id="submitLoaderButton"
                                        class="btn btn-warning">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-shadow mb-4">

                    <div class="card-body">
                        <h1 class="text-center">Transactions</h1>
                        <div class="table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                    <th scope="col">Payment Type</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Amount</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaction_details as $key => $transaction): ?>
                                        <tr>
                                            <td><small class="text-success"><?php echo $transaction->payment_type ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo date('d-m-Y h:i A', strtotime($transaction->date_time)); ?></small><br>
                                                <small class="text-default">for:
                                                    <?php echo ($transaction->user_type == 'vendor') ? $transaction->vendor_business_name : $transaction->user_name; ?></small>
                                            </td>
                                            <td><?php echo $transaction->user_type ?>
                                            </td>
                                            <td>
                                                <strong
                                                    class="<?php echo ($transaction->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo ($transaction->payment_type == 'Credit') ? '+' : '-'; ?>
                                                    <?php echo $transaction->executive_referral_amount ?>
                                                </strong>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>