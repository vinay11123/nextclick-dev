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
                                <li class="breadcrumb-item"><a href="#!">Orders</a></li>
                            </ul>
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

                                <div class="col-xl-12 col-md-12">

                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?php if (isset($allStatus) && $allStatus) {
                                                echo 'active';
                                            } ?>" data-toggle="tab" href="#home3" role="tab">All Orders</a>

                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php if (isset($ongoingStatus) && $ongoingStatus) {
                                                echo 'active';
                                            } ?>" data-toggle="tab" href="#profile3" role="tab">Ongoing
                                                Orders</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php if (isset($outStatus) && $outStatus) {
                                                echo 'active';
                                            } ?>" data-toggle="tab" href="#messages3" role="tab">Out for
                                                Delivery</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php if (isset($rejectedStatus) && $rejectedStatus) {
                                                echo 'active';
                                            } ?>" data-toggle="tab" href="#settings3" role="tab">Rejected
                                                Orders</a>
                                            <div class="slide"></div>
                                        </li>

                                        <!-- <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#completed" role="tab">Cancelled
                                                Orders</a>
                                            <div class="slide"></div>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#cancelled" role="tab">Rejected
                                                by Delivery Partner</a>
                                            <div class="slide"></div>
                                        </li> -->


                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content card-block mt-4">
                                        <div class="tab-pane <?php if (isset($allStatus) && $allStatus) {
                                            echo 'show active';
                                        } ?>" id="home3" role="tabpanel">

                                            <div class="card">
                                                <form method="POST" action="">
                                                   <div class="row p-3 align-items-end">

                                                    <div class="col-md-2">
                                                        <label><h6>Select Status</h6></label>
                                                    </div>
                                                
                                                    <div class="col-md-2">
                                                        <select name="status" class="form-control">
                                                            <option value="all">All</option>
                                                            <?php foreach ($orderStatus as $orderSt): ?>
                                                                <option value="<?php echo $orderSt['id']; ?>">
                                                                    <?php echo $orderSt['name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                
                                                    <div class="col-md-2">
                                                        <label><h6>From Date</h6></label>
                                                        <input type="date" name="from_date" 
                                                               value="<?php echo isset($from_date) ? $from_date : ''; ?>" 
                                                               class="form-control">
                                                    </div>
                                                
                                                    <div class="col-md-2">
                                                        <label><h6>To Date</h6></label>
                                                        <input type="date" name="to_date" 
                                                               value="<?php echo isset($to_date) ? $to_date : ''; ?>" 
                                                               class="form-control">
                                                    </div>
                                                
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            Submit
                                                        </button>
                                                    </div>
                                                
                                                </div>


                                                </form>
                                            </div>
                                          
                                            <div class="card">
                                                <div class="card-block">
                                                    <div class="row">
                                                    <div class="mb-3">
                                                            <button class="btn btn-primary print-btn">Print</button>
                                                            <button class="btn btn-success excel-btn">Excel</button>
                                                            <button class="btn btn-danger pdf-btn">PDF</button>
                                                        </div>

                                                        <div class="dt-responsive table-responsive">
                                                            <table id="vendor_all_orders"
                                                                class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <input type="checkbox" class="select-all">
                                                                            </th>
                                                                        <th>No</th>
                                                                        <th>Order No</th>
                                                                        <th>Date/Time</th>
                                                                        <th>Payment Mode</th>
                                                                        <th>Status</th>
                                                                        <th>Price</th>
                                                                        <th class="not-export-column">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    <?php foreach ($vendorOrders as $key => $sub_vendor): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" class="row-check">
                                                                            </td>
                                                                            <td><?php echo $key + 1; ?></td>
                                                                            <td>#<?php echo $sub_vendor->track_id; ?></td>
                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($sub_vendor->created_at)); ?>
                                                                            <td><?php echo $sub_vendor->payment_method_name; ?>
                                                                            </td>
                                                                            <td class="<?php
                                                                            switch ($sub_vendor->current_order_status_id) {
                                                                                case 1:
                                                                                    echo 'badge-gray';
                                                                                    break;
                                                                                case 2:
                                                                                    echo 'badge-violet';
                                                                                    break;
                                                                                case 3:
                                                                                    echo 'badge-pink';
                                                                                    break;
                                                                                case 4:
                                                                                    echo 'badge-warning';
                                                                                    break;
                                                                                case 5:
                                                                                    echo 'badge-ttl';
                                                                                    break;
                                                                                case 6:
                                                                                    echo 'badge-orange';
                                                                                    break;
                                                                                case 7:
                                                                                    echo 'badge-indigo';
                                                                                    break;
                                                                                case 8:
                                                                                    echo 'badge-success';
                                                                                    break;
                                                                                case 9:
                                                                                    echo 'badge-yellow';
                                                                                    break;
                                                                                case 10:
                                                                                    echo 'badge-danger';
                                                                                    break;
                                                                                case 11:
                                                                                    echo 'badge-red';
                                                                                    break;
                                                                                default:
                                                                                    echo 'badge-light';
                                                                                    break;
                                                                            }
                                                                            ?>">
                                                                                <?php echo $sub_vendor->order_status; ?>
                                                                            </td>

                                                                            <td><?php echo $sub_vendor->grand_total; ?></td>
                                                                            <td><a
                                                                                    href="<?php echo base_url('vendor/order_details?id=' . base64_encode(base64_encode($sub_vendor->id))); ?>"><i
                                                                                        class="feather icon-eye text-success "></i></a>
                                                                            </td>

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

                                        <div class="tab-pane <?php if (isset($ongoingStatus) && $ongoingStatus) {
                                            echo 'show active';
                                        } ?>" id="profile3" role="tabpanel">

                                            <div class="card">
                                                <form method="POST" action="">
                                                    <div class="row p-3 align-items-center">
                                                        <div class="col-md-2">
                                                            <label for="onGoingStatus">
                                                                <h6>Select Status</h6>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="onGoingStatus" id="onGoingStatus"
                                                                class="form-control">
                                                                <option value="all" <?php echo (isset($onGoingStatus) && $onGoingStatus === 'all') ? 'selected' : ''; ?>>All
                                                                </option>
                                                                <?php foreach ($orderStatus as $orderSt): ?>
                                                                    <?php
                                                                    $selected = ((isset($onGoingStatus) && $orderSt['id'] == $onGoingStatus) || ($orderSt['id'] == set_value('onGoingStatus'))) ? 'selected' : '';

                                                                    if (in_array($orderSt['id'], [2, 3, 4, 5])) {
                                                                        ?>
                                                                        <option value="<?php echo $orderSt['id']; ?>" <?php echo $selected; ?>>
                                                                            <?php echo $orderSt['name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php endforeach; ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-2 mt-md-0">Submit</button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>

                                            <div class="card">

                                                <div class="card-block">
                                                    <div class="row">
                                                     <div class="mb-3">
                                                            <button class="btn btn-primary print-btn">Print</button>
                                                            <button class="btn btn-success excel-btn">Excel</button>
                                                            <button class="btn btn-danger pdf-btn">PDF</button>
                                                        </div>

                                                        <div class="dt-responsive table-responsive">
                                                            <table id="vendor_ongoing_orders"
                                                                class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <input type="checkbox" class="select-all">
                                                                            </th>
                                                                        <th>No</th>
                                                                        <th>Order No</th>
                                                                        <th>Date/Time</th>
                                                                        <th>Payment Mode</th>
                                                                        <th>Status</th>
                                                                        <th>Price</th>
                                                                        <th class="not-export-column">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($vendorOngoingOrders as $key => $ongoing): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" class="row-check">
                                                                            </td>
                                                                            <td><?php echo $key + 1; ?></td>
                                                                            <td>#<?php echo $ongoing->track_id; ?></td>
                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($ongoing->created_at)); ?>
                                                                            <td><?php echo $ongoing->payment_method_name; ?>
                                                                            </td>
                                                                            <td class="<?php
                                                                            switch ($ongoing->current_order_status_id) {
                                                                                case 1:
                                                                                    echo 'badge-gray';
                                                                                    break;
                                                                                case 2:
                                                                                    echo 'badge-violet';
                                                                                    break;
                                                                                case 3:
                                                                                    echo 'badge-pink';
                                                                                    break;
                                                                                case 4:
                                                                                    echo 'badge-warning';
                                                                                    break;
                                                                                case 5:
                                                                                    echo 'badge-ttl';
                                                                                    break;
                                                                                case 6:
                                                                                    echo 'badge-orange';
                                                                                    break;
                                                                                case 7:
                                                                                    echo 'badge-indigo';
                                                                                    break;
                                                                                case 8:
                                                                                    echo 'badge-success';
                                                                                    break;
                                                                                case 9:
                                                                                    echo 'badge-yellow';
                                                                                    break;
                                                                                case 10:
                                                                                    echo 'badge-danger';
                                                                                    break;
                                                                                case 11:
                                                                                    echo 'badge-red';
                                                                                    break;
                                                                                default:
                                                                                    echo 'badge-light';
                                                                                    break;
                                                                            }
                                                                            ?>">
                                                                                <?php echo $ongoing->order_status; ?>
                                                                            </td>
                                                                            <td><?php echo $ongoing->grand_total; ?></td>
                                                                            <td><a
                                                                                    href="<?php echo base_url('vendor/order_details?id=' . base64_encode(base64_encode($ongoing->id))); ?>"><i
                                                                                        class="feather icon-eye text-success "></i></a>
                                                                            </td>

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

                                        <div class="tab-pane <?php if (isset($outStatus) && $outStatus) {
                                            echo 'show active';
                                        } ?>" id="messages3" role="tabpanel">

                                            <div class="card">
                                                <form method="POST" action="">
                                                    <div class="row p-3 align-items-center">
                                                        <div class="col-md-2">
                                                            <label for="outForDeliveryStatus">
                                                                <h6>Select Status</h6>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="outForDeliveryStatus"
                                                                id="outForDeliveryStatus" class="form-control">
                                                                <option value="all" <?php echo (isset($outForDeliveryStatus) && $outForDeliveryStatus === 'all') ? 'selected' : ''; ?>>All
                                                                </option>
                                                                <?php foreach ($orderStatus as $orderSt): ?>
                                                                    <?php
                                                                    $selected = ((isset($outForDeliveryStatus) && $orderSt['id'] == $outForDeliveryStatus) || ($orderSt['id'] == set_value('outForDeliveryStatus'))) ? 'selected' : '';

                                                                    if (in_array($orderSt['id'], [6, 7, 8])) {
                                                                        ?>
                                                                        <option value="<?php echo $orderSt['id']; ?>" <?php echo $selected; ?>>
                                                                            <?php echo $orderSt['name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php endforeach; ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-2 mt-md-0">Submit</button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>

                                            <div class="card">

                                                <div class="card-block">
                                                    <div class="row">

 <div class="mb-3">
                                                            <button class="btn btn-primary print-btn">Print</button>
                                                            <button class="btn btn-success excel-btn">Excel</button>
                                                            <button class="btn btn-danger pdf-btn">PDF</button>
                                                        </div>
                                                        <div class="dt-responsive table-responsive">
                                                            <table id="vendor_out_for_delivery_orders"
                                                                class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <input type="checkbox" class="select-all">
                                                                            </th>
                                                                        <th>No</th>
                                                                        <th>Order No</th>
                                                                        <th>Date/Time</th>
                                                                        <th>Payment Mode</th>
                                                                        <th>Status</th>
                                                                        <th>Price</th>
                                                                        <th class="not-export-column">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($vendorOutfordeliveryOrders as $key => $outfordelivery): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" class="row-check">
                                                                            </td>
                                                                            <td><?php echo $key + 1; ?></td>
                                                                            <td>#<?php echo $outfordelivery->track_id; ?>
                                                                            </td>
                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($outfordelivery->created_at)); ?>
                                                                            <td><?php echo $outfordelivery->payment_method_name; ?>
                                                                            </td>
                                                                            <td class="<?php
                                                                            switch ($outfordelivery->current_order_status_id) {
                                                                                case 1:
                                                                                    echo 'badge-gray';
                                                                                    break;
                                                                                case 2:
                                                                                    echo 'badge-violet';
                                                                                    break;
                                                                                case 3:
                                                                                    echo 'badge-pink';
                                                                                    break;
                                                                                case 4:
                                                                                    echo 'badge-warning';
                                                                                    break;
                                                                                case 5:
                                                                                    echo 'badge-ttl';
                                                                                    break;
                                                                                case 6:
                                                                                    echo 'badge-orange';
                                                                                    break;
                                                                                case 7:
                                                                                    echo 'badge-indigo';
                                                                                    break;
                                                                                case 8:
                                                                                    echo 'badge-success';
                                                                                    break;
                                                                                case 9:
                                                                                    echo 'badge-yellow';
                                                                                    break;
                                                                                case 10:
                                                                                    echo 'badge-danger';
                                                                                    break;
                                                                                case 11:
                                                                                    echo 'badge-red';
                                                                                    break;
                                                                                default:
                                                                                    echo 'badge-light';
                                                                                    break;
                                                                            }
                                                                            ?>">
                                                                                <?php echo $outfordelivery->order_status; ?>
                                                                            </td>
                                                                            <td><?php echo $outfordelivery->grand_total; ?>
                                                                            </td>
                                                                            <td><a
                                                                                    href="<?php echo base_url('vendor/order_details?id=' . base64_encode(base64_encode($outfordelivery->id))); ?>"><i
                                                                                        class="feather icon-eye text-success "></i></a>
                                                                            </td>

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

                                        <div class="tab-pane <?php if (isset($rejectedStatus) && $rejectedStatus) {
                                            echo 'show active';
                                        } ?>" id="settings3" role="tabpanel">

                                            <div class="card">
                                                <form method="POST" action="">
                                                    <div class="row p-3 align-items-center">
                                                        <div class="col-md-2">
                                                            <label for="rejectFilterStatus">
                                                                <h6>Select Status</h6>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4">

                                                            <select name="rejectFilterStatus" id="rejectFilterStatus"
                                                                class="form-control">
                                                                <option value="all" <?php echo (isset($rejectFilterStatus) && $rejectFilterStatus === 'all') ? 'selected' : ''; ?>>
                                                                    All</option>
                                                                <?php
                                                                foreach ($orderStatus as $orderSt) {

                                                                    if (isset($rejectFilterStatus) || !empty($rejectFilterStatus)) {
                                                                        $selected = ($orderSt['id'] == $rejectFilterStatus) ? 'selected' : '';
                                                                    } else {
                                                                        $selected = ($orderSt['id'] == 10) ? 'selected' : '';
                                                                    }

                                                                    if (in_array($orderSt['id'], [9, 10, 11])) {
                                                                        echo '<option value="' . $orderSt['id'] . '" ' . $selected . '>' . $orderSt['name'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-2 mt-md-0">Submit</button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>

                                            <div class="card">

                                                <div class="card-block">
                                                    <div class="row">
 <div class="mb-3">
                                                            <button class="btn btn-primary print-btn">Print</button>
                                                            <button class="btn btn-success excel-btn">Excel</button>
                                                            <button class="btn btn-danger pdf-btn">PDF</button>
                                                        </div>

                                                        <div class="dt-responsive table-responsive">
                                                            <table id="vendor_rejected_orders"
                                                                class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <input type="checkbox" class="select-all">
                                                                            </th>
                                                                        <th>No</th>
                                                                        <th>Order No</th>
                                                                        <th>Date/Time</th>
                                                                        <th>Payment Mode</th>
                                                                        <th>Status</th>
                                                                        <th>Price</th>
                                                                        <th class="not-export-column">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($vendorRejectedOrders as $key => $rejected): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" class="row-check">
                                                                            </td>
                                                                            <td><?php echo $key + 1; ?></td>
                                                                            <td>#<?php echo $rejected->track_id; ?></td>
                                                                            <td><?php echo date('d-m-Y h:i A', strtotime($rejected->created_at)); ?>
                                                                            <td><?php echo $rejected->payment_method_name; ?>
                                                                            </td>
                                                                            <td class="<?php
                                                                            switch ($rejected->current_order_status_id) {
                                                                                case 1:
                                                                                    echo 'badge-gray';
                                                                                    break;
                                                                                case 2:
                                                                                    echo 'badge-violet';
                                                                                    break;
                                                                                case 3:
                                                                                    echo 'badge-pink';
                                                                                    break;
                                                                                case 4:
                                                                                    echo 'badge-warning';
                                                                                    break;
                                                                                case 5:
                                                                                    echo 'badge-ttl';
                                                                                    break;
                                                                                case 6:
                                                                                    echo 'badge-orange';
                                                                                    break;
                                                                                case 7:
                                                                                    echo 'badge-indigo';
                                                                                    break;
                                                                                case 8:
                                                                                    echo 'badge-success';
                                                                                    break;
                                                                                case 9:
                                                                                    echo 'badge-yellow';
                                                                                    break;
                                                                                case 10:
                                                                                    echo 'badge-danger';
                                                                                    break;
                                                                                case 11:
                                                                                    echo 'badge-red';
                                                                                    break;
                                                                                default:
                                                                                    echo 'badge-light';
                                                                                    break;
                                                                            }
                                                                            ?>">
                                                                                <?php echo $rejected->order_status; ?>
                                                                            </td>
                                                                            <td><?php echo $rejected->grand_total; ?></td>
                                                                            <td><a
                                                                                    href="<?php echo base_url('vendor/order_details?id=' . base64_encode(base64_encode($rejected->id))); ?>"><i
                                                                                        class="feather icon-eye text-success "></i></a>
                                                                            </td>

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
<script>
document.addEventListener('DOMContentLoaded', function () {

    // SELECT ALL (ONLY CURRENT PAGE)
    document.querySelectorAll('.select-all').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {

            let tableElement = this.closest('table');
            let tableId = tableElement.id;
            let dataTable = $('#' + tableId).DataTable();

            // Get rows on current page
            dataTable.rows({ page: 'current' }).nodes().to$()
                .find('.row-check')
                .prop('checked', this.checked);
        });
    });

    // COMMON FUNCTION TO GET DATATABLE
    function getDataTable(btn){
    let tableElement = btn.closest('.tab-pane').querySelector('table');
    let tableId = '#' + tableElement.id;

    if ($.fn.dataTable.isDataTable(tableId)) {
        return $(tableId).DataTable();
    } else {
        return $(tableId).DataTable();
    }
}

    // GET ALL SELECTED ROWS FROM ALL PAGES
    function getSelectedRows(dataTable){
        return dataTable.rows().nodes().to$().find('.row-check:checked');
    }

    // PRINT
    document.querySelectorAll('.print-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            let dataTable = getDataTable(this);
            let selected = getSelectedRows(dataTable);

            if (selected.length === 0) {
                alert('Select at least one order');
                return;
            }

            let content = `
                <h3>Selected Orders</h3>
                <table border="1" style="width:100%;border-collapse:collapse">
                <tr>
                    <th>No</th>
                    <th>Order No</th>
                    <th>Date/Time</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Price</th>
                </tr>`;

            selected.each(function () {
                let cells = $(this).closest('tr').find('td');

                content += "<tr>";
                for (let i = 1; i < cells.length - 1; i++) {
                    content += "<td>" + $(cells[i]).text().trim() + "</td>";
                }
                content += "</tr>";
            });

            content += "</table>";

            let win = window.open('', '', 'width=900,height=600');
            win.document.write(content);
            win.document.close();
            win.print();
        });
    });

    // EXCEL
    document.querySelectorAll('.excel-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            let dataTable = getDataTable(this);
            let selected = getSelectedRows(dataTable);

            if (selected.length === 0) {
                alert('Select at least one order');
                return;
            }

            let csv = "No,Order No,Date/Time,Payment,Status,Price\n";

            selected.each(function () {
                let cells = $(this).closest('tr').find('td');
                let rowData = [];

                for (let i = 1; i < cells.length - 1; i++) {
                    rowData.push('"' + $(cells[i]).text().trim() + '"');
                }

                csv += rowData.join(",") + "\n";
            });

            let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "orders.csv";
            link.click();
        });
    });

    // PDF (Same as Print)
    document.querySelectorAll('.pdf-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            let dataTable = getDataTable(this);
            let selected = getSelectedRows(dataTable);

            if (selected.length === 0) {
                alert('Select at least one order');
                return;
            }

            let content = `
                <h3>Selected Orders</h3>
                <table border="1" style="width:100%;border-collapse:collapse">
                <tr>
                    <th>No</th>
                    <th>Order No</th>
                    <th>Date/Time</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Price</th>
                </tr>`;

            selected.each(function () {
                let cells = $(this).closest('tr').find('td');

                content += "<tr>";
                for (let i = 1; i < cells.length - 1; i++) {
                    content += "<td>" + $(cells[i]).text().trim() + "</td>";
                }
                content += "</tr>";
            });

            content += "</table>";

            let win = window.open('', '', 'width=900,height=600');
            win.document.write(content);
            win.document.close();
            win.print();
        });
    });

});
$(document).ready(function () {
    setTimeout(function () {
        $('.dt-buttons').remove();
    }, 500);
});
</script>

