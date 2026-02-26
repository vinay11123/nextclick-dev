<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>


<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#!">Referral - List</a></li>
                            </ul>
                            <h3 class="mt-3 ml-2">Executive Name: <?php echo $exe_name; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="pcoded-inner-content">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">

                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">

                                <div class="col-xl-12">

                                    <div class="row">

                                        <div class="col-xl-3 col-md-3">
                                            <div class="card o-hidden bg-c-blue web-num-card">
                                                <div class="card-block text-white">
                                                    <h5 class="m-t-15">Vendors</h5>
                                                    <h3 class="m-b-15"><?= $vendor ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="card o-hidden bg-c-yellow web-num-card">
                                                <div class="card-block text-white">
                                                    <h5 class="m-t-15">Delivery Boys</h5>
                                                    <h3 class="m-b-15"><?= $deliveryCaptain ?></h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="card o-hidden bg-c-red web-num-card">
                                                <div class="card-block text-white">
                                                    <h5 class="m-t-15">Users</h5>
                                                    <h3 class="m-b-15"><?= $users ?></h3>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($executive_type_id == 1) { ?>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="card o-hidden bg-c-green web-num-card">
                                                    <div class="card-block text-white">
                                                        <h5 class="m-t-15">Wallet</h5>
                                                        <h3 class="m-b-15"><?= $total_all_amount ?></h3>
                                                    </div>
                                                </div>
                                            </div><?php } ?>
                                    </div>
                                </div>


                                <div class="col-xl-12 col-md-12">

                                    <div class="card">

                                        <div class="card-block accordion-block color-accordion-block ">
                                            <div class="color-accordion" id="color-accordion">
                                                <a id="vendorScreen"
                                                    class="accordion-msg b-none waves-effect waves-light">
                                                    <h6>Vendors
                                                        <?= '(' . $vendor . ')'; ?>
                                                    </h6>
                                                </a>
                                                <div class="accordion-desc bg-light mt-0 pt-2">

                                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab"
                                                                href="#subscribedVendor"
                                                                role="tab">Subscribed(<?= $subscribed_vendor_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab"
                                                                href="#unsubscribedVendor"
                                                                role="tab">Unsubscribed(<?= $unsubscribed_vendor_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#pendingVendor"
                                                                role="tab">Pending(<?= $pending_vendor_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content card-block p-0 mt-3">
                                                        <div class="tab-pane active" id="subscribedVendor"
                                                            role="tabpanel">
                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="subscribed_vendor_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Shop Name</th>
                                                                                    <th>Vendor Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>
                                                                                    <th>Plan</th>
                                                                                    <th>Subc Date</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($subscribed_vendor_list as $key => $sub_vendor): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $sub_vendor->business_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $sub_vendor->owner_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $sub_vendor->whats_app_no; ?>
                                                                                        </td>
                                                                                        <td><?php echo $sub_vendor->location; ?>
                                                                                        </td>

                                                                                        <td><?php echo $sub_vendor->package_plan; ?>
                                                                                        </td>
                                                                                        <td><?php echo date('d-m-Y h:i A', strtotime($sub_vendor->first_paid_subscription_at)); ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="unsubscribedVendor" role="tabpanel">
                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="unsubscribed_vendor_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Shop Name</th>
                                                                                    <th>Vendor Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($unsubscribed_vendor_list as $key => $unsub_vendor): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $unsub_vendor->business_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $unsub_vendor->owner_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $unsub_vendor->whats_app_no; ?>
                                                                                        </td>
                                                                                        <td><?php echo $unsub_vendor->location; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="pendingVendor" role="tabpanel">
                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="pending_vendor_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Shop Name</th>
                                                                                    <th>Vendor Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($pending_vendor_list as $key => $vendor): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $vendor->business_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $vendor->owner_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $vendor->whats_app_no; ?>
                                                                                        </td>
                                                                                        <td><?php echo $vendor->location; ?>
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


                                                <a class="accordion-msg bg-c-yellow b-none waves-effect waves-light">
                                                    <h6>Delivery
                                                        Boys <?= '(' . $deliveryCaptain . ')'; ?></h6>
                                                </a>
                                                <div class="accordion-desc bg-light mt-0 pt-2">
                                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab"
                                                                href="#targetAchievedCaptain" role="tab">Target
                                                                Achieved(<?= $target_achieved_captain_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab"
                                                                href="#targetNotAchievedCaptain" role="tab">Target Not
                                                                Achieved(<?= $target_not_achieved_captain_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#pendingCaptain"
                                                                role="tab">Pending(<?= $pending_captain_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                    </ul>

                                                    <!-- Tab panes -->
                                                    <div class="tab-content card-block p-0 mt-4">
                                                        <div class="tab-pane active" id="targetAchievedCaptain"
                                                            role="tabpanel">

                                                            <div class="card">

                                                                <div class="card-block">


                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="target_achieved_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>
                                                                                    <th>Target</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($target_achieved_captains_list as $key => $target_achieved_captain): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $target_achieved_captain->captain_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $target_achieved_captain->captain_phone; ?>
                                                                                        </td>
                                                                                        <td><?php echo $target_achieved_captain->location; ?>
                                                                                        </td>
                                                                                        <td><?php echo $target_achieved_captain->target_achieved_count . '/' . $target_achieved_captain->target_given_count; ?>
                                                                                        </td>

                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div>

                                                        <div class="tab-pane" id="targetNotAchievedCaptain"
                                                            role="tabpanel">

                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="target_not_achieved_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>
                                                                                    <th>Target</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($target_not_achieved_captains_list as $key => $target_not_achieved_captain): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $target_not_achieved_captain->captain_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $target_not_achieved_captain->captain_phone; ?>
                                                                                        </td>
                                                                                        <td><?php echo $target_not_achieved_captain->location; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php
                                                                                            $target_achieved_count = isset($target_not_achieved_captain->target_achieved_count) && !empty($target_not_achieved_captain->target_achieved_count) ? $target_not_achieved_captain->target_achieved_count : 0;
                                                                                            echo $target_achieved_count . '/' . $target_not_achieved_captain->target_given_count;
                                                                                            ?>
                                                                                        </td>

                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="tab-pane" id="pendingCaptain" role="tabpanel">
                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="pending_captain_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Ph No</th>
                                                                                    <th>Location</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($pending_captains_list as $key => $pending_captain): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $pending_captain->captain_name; ?>
                                                                                        </td>
                                                                                        <td><?php echo $pending_captain->captain_phone; ?>
                                                                                        </td>
                                                                                        <td><?php echo $pending_captain->location; ?>
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
                                                <a class="accordion-msg bg-c-red b-none waves-effect waves-light">
                                                    <h6>Users
                                                        <?= '(' . $users . ')'; ?>
                                                    </h6>
                                                </a>
                                                <div class="accordion-desc bg-light mt-0 pt-2">
                                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab"
                                                                href="#orderedUser"
                                                                role="tab">Ordered(<?= $ordered_user_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#unorderedUser"
                                                                role="tab">Not
                                                                Ordered(<?= $not_ordered_user_count ?>)</a>
                                                            <div class="slide"></div>
                                                        </li>

                                                    </ul>

                                                    <!-- Tab panes -->
                                                    <div class="tab-content card-block p-0 mt-4">
                                                        <div class="tab-pane active" id="orderedUser" role="tabpanel">


                                                            <div class="card">
                                                                <div class="card-block">

                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="ordered_user_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Ph No</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($ordered_user_list as $key => $ordered_user): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $ordered_user->first_name; ?>
                                                                                        </td>

                                                                                        <td><?php echo $ordered_user->phone; ?>
                                                                                        </td>

                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="tab-pane" id="unorderedUser" role="tabpanel">

                                                            <div class="card">

                                                                <div class="card-block">
                                                                    <div class="dt-responsive table-responsive">
                                                                        <table id="unordered_user_table"
                                                                            class="table table-striped table-bordered nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Ph No</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($not_ordered_user_list as $key => $not_ordered_user): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $key + 1; ?></td>
                                                                                        <td><?php echo $not_ordered_user->first_name; ?>
                                                                                        </td>

                                                                                        <td><?php echo $not_ordered_user->phone; ?>
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

                                                <?php if ($executive_type_id == 1) { ?>

                                                    <a class="accordion-msg bg-c-red b-none waves-effect waves-light">
                                                        <h6>Wallet
                                                            <?= '(' . $total_all_amount . ')'; ?>
                                                        </h6>
                                                    </a>
                                                    <div class="accordion-desc bg-light mt-0 pt-2">
                                                        <ul class="nav nav-tabs md-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab"
                                                                    href="#walletVendor"
                                                                    role="tab">Vendor(<?= $total_vendor_amount ?>)</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#walletUser"
                                                                    role="tab">User(<?= $total_user_amount ?>)</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#walletCaptain"
                                                                    role="tab">Delivery
                                                                    Captain(<?= $total_delivery_boy_amount ?>)</a>
                                                                <div class="slide"></div>
                                                            </li>

                                                        </ul>

                                                        <!-- Tab panes -->
                                                        <div class="tab-content card-block p-0 mt-4">
                                                            <div class="tab-pane active" id="walletVendor" role="tabpanel">


                                                                <div class="card">
                                                                    <div class="card-block">

                                                                        <div class="dt-responsive table-responsive">
                                                                            <table id="wallet_vendor_table"
                                                                                class="table table-striped table-bordered nowrap">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Shop Name</th>
                                                                                        <th>Name</th>
                                                                                        <th>Ph No</th>
                                                                                        <th>Payment Type</th>
                                                                                        <th>Amount</th>
                                                                                        <th>Subscribed At</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($vendor_transaction_details as $key => $wallet_vendor): ?>
                                                                                        <tr>
                                                                                            <td><?php echo $key + 1; ?></td>
                                                                                            <td><?php echo $wallet_vendor->vendor_business_name; ?>
                                                                                            </td>
                                                                                            <td><?php echo $wallet_vendor->user_name; ?>
                                                                                            </td>
                                                                                            <td><?php echo $wallet_vendor->phone; ?>
                                                                                            </td>
                                                                                            <td><span
                                                                                                    class="<?php echo ($wallet_vendor->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>"><?php echo $wallet_vendor->payment_type; ?></span>
                                                                                            </td>

                                                                                            <td><strong
                                                                                                    class="<?php echo ($wallet_vendor->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>">
                                                                                                    <?php echo ($wallet_vendor->payment_type == 'Credit') ? '+' : '-'; ?>
                                                                                                    <?php echo $wallet_vendor->executive_referral_amount ?>
                                                                                                </strong>
                                                                                            </td>
                                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($wallet_vendor->date_time)); ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="tab-pane" id="walletUser" role="tabpanel">

                                                                <div class="card">

                                                                    <div class="card-block">
                                                                        <div class="dt-responsive table-responsive">
                                                                            <table id="wallet_user_table"
                                                                                class="table table-striped table-bordered nowrap">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Name</th>
                                                                                        <th>Ph No</th>
                                                                                        <th>Payment Type</th>
                                                                                        <th>Amount</th>
                                                                                        <th>Ordered Date</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($user_transaction_details as $key => $wallet_user): ?>
                                                                                        <tr>
                                                                                            <td><?php echo $key + 1; ?></td>
                                                                                            <td><?php echo $wallet_user->user_name; ?>
                                                                                            </td>
                                                                                            <td><?php echo $wallet_user->phone; ?>
                                                                                            </td>
                                                                                            <td><span
                                                                                                    class="<?php echo ($wallet_user->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>"><?php echo $wallet_user->payment_type; ?></span>

                                                                                            </td>

                                                                                            <td><strong
                                                                                                    class="<?php echo ($wallet_user->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>">
                                                                                                    <?php echo ($wallet_user->payment_type == 'Credit') ? '+' : '-'; ?>
                                                                                                    <?php echo $wallet_user->executive_referral_amount ?>
                                                                                                </strong>
                                                                                            </td>

                                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($wallet_user->date_time)); ?>
                                                                                            </td>

                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane" id="walletCaptain" role="tabpanel">

                                                                <div class="card">

                                                                    <div class="card-block">
                                                                        <div class="dt-responsive table-responsive">
                                                                            <table id="wallet_captain_table"
                                                                                class="table table-striped table-bordered nowrap">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Name</th>
                                                                                        <th>Ph No</th>
                                                                                        <th>Payment Type</th>
                                                                                        <th>Amount</th>
                                                                                        <th>Target Achieved At</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($captain_transaction_details as $key => $wallet_capain): ?>
                                                                                        <tr>
                                                                                            <td><?php echo $key + 1; ?></td>
                                                                                            <td><?php echo $wallet_capain->user_name; ?>
                                                                                            </td>
                                                                                            <td><?php echo $wallet_capain->phone; ?>
                                                                                            </td>
                                                                                            <td><span
                                                                                                    class="<?php echo ($wallet_capain->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>"><?php echo $wallet_capain->payment_type; ?></span>
                                                                                            </td>

                                                                                            <td><strong
                                                                                                    class="<?php echo ($wallet_capain->payment_type == 'Credit') ? 'text-success' : 'text-danger'; ?>">
                                                                                                    <?php echo ($wallet_capain->payment_type == 'Credit') ? '+' : '-'; ?>
                                                                                                    <?php echo $wallet_capain->executive_referral_amount ?>
                                                                                                </strong>
                                                                                            </td>
                                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($wallet_capain->date_time)); ?>
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
                                                <?php } ?>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>

</div>
</div>
</div>


<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>