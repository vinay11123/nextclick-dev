<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<style>
    .centered-alert {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        max-width: 80%;
        border-radius: 8px;
    }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-11">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Order Details</a></li>

                            </ul>

                        </div>
                        <div class="col-md-1">
                            <a href="<?php echo base_url('vendorOrders'); ?>"><i class="feather icon-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <div class="pcoded-inner-content" id="page_inner">
                <!-- Main-body start -->
                <div class="main-body">
                    <div class="page-wrapper">

                        <!-- Page-body start -->
                        <div class="page-body">
                            <div class="row">

                                <div class="col-xl-12 col-md-12">


                                    <div class="card">
                                        <div class="card-header text-dark">
                                            <h5 class="text-dark"> <?= $vendorOrders[0]['order_status']; ?></h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="col-md-12">
                                                <input type="hidden" id="order_id"
                                                    value="<?= $vendorOrders[0]['id']; ?>">
                                                <p>Delivery Mode: <?= $vendorOrders[0]['delivery_mode_name']; ?></p>
                                                <p>Payment Type: <?= $vendorOrders[0]['payment_method_name']; ?></p>
                                            </div>

                                            <hr>
                                            <h5>Products Information</h5>
                                            <div class="dt-responsive table-responsive">
                                                <table class="table table-bordered nowrap">

                                                    <tbody>
                                                        <?php
                                                        $dis = 0.00;
                                                        $tx = 0.00;
                                                        $sno = 1;
                                                        $subtot = 0.00;
                                                        if (!empty($custprod)):
                                                            foreach ($custprod as $order): ?>

                                                                <?php if ($order['status'] == 4): ?>
                                                                    <tr style="background-color: #f8d7da; opacity: 0.6;">
                                                                        <td>
                                                                            <h6 class="mb-0"><?= $order['food_name']; ?></h6>
                                                                        </td>
                                                                        <td><img src="<?= base_url(); ?>uploads/food_item_image/food_item_<?= $order['image_id']; ?>.jpg"
                                                                                width="200" style="width: 200px !important;"></td>
                                                                        <td><?= $order['section_name']; ?></td>
                                                                        <td><?= $order['product_quantity']; ?></td>
                                                                        <td>Quantity: <?= $order['qty']; ?></td>
                                                                        <td align="right">
                                                                            Sub Total: Rs.<?= $order['price']; ?><br>
                                                                            Tax: Rs.<?= $order['tax']; ?><br>
                                                                            Total: Rs.<?= $order['total']; ?><br>
                                                                            <strong class="text-danger">Rejected</strong>
                                                                        </td>
                                                                    </tr>
                                                                <?php else: ?>
                                                                    <tr>
                                                                        <td>
                                                                            <h6 class="mb-0"><?= $order['food_name']; ?></h6>
                                                                        </td>
                                                                        <td><img src="<?= base_url(); ?>uploads/food_item_image/food_item_<?= $order['image_id']; ?>.jpg"
                                                                                width="200" style="width: 200px !important;"></td>
                                                                        <td><?= $order['section_name']; ?></td>
                                                                        <td><?= $order['product_quantity']; ?></td>
                                                                        <td>Quantity: <?= $order['qty']; ?></td>
                                                                        <td align="right">
                                                                            Sub Total: Rs.<?= $order['price']; ?><br>
                                                                            Tax: Rs.<?= $order['tax']; ?><br>
                                                                            Total: Rs.<?= $order['total']; ?><br>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                <?php
                                                                if ($order['status'] != 4):
                                                                    $dis += (float) $order['promocode_discount'] + (float) $order['promotion_banner_discount'] + (float) ($vendorOrders[0]['cupon_discount'] ?? 0);
                                                                    $tx += (float) $order['tax'];
                                                                    $subtot += (float) $order['price'];
                                                                endif;
                                                                ?>
                                                            <?php endforeach; ?>
                                                            <tr>
                                                                <td colspan="4"><strong>Bill Details</strong></td>
                                                                <td align="right">Sub Total</td>
                                                                <td align="right"><strong>Rs.<?= $subtot; ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" align="right">Discount</td>
                                                                <td align="right"><strong>Rs.<?= $dis; ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" align="right">Taxes</td>
                                                                <td align="right"><strong>Rs.<?= $tx; ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" align="right">Service Charge</td>
                                                                <td align="right">
                                                                    <strong>Rs. 0.00</strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" align="right">Total Bill</td>

                                                                <td align="right" class="bg-success">
                                                                    <strong>Rs.<?= $subtot + $dis + $tx; ?></strong>
                                                                </td>

                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                        
                                if (trim($vendorOrders[0]['current_order_status_id']) == 1 && empty($vendorOrders[0]['reject_request_status'])) {
                                    ?>
                                    <div class="col-md-12 mt-3 text-right">
                                        <a href="#" class="btn btn-primary" data-toggle="modal"
                                            data-target="#default-Modal1">Accept</a> <a href="#" class="btn btn-danger"
                                            data-toggle="modal" data-target="#reject-Modal">Reject</a>
                                    </div><?php } else if (trim($vendorOrders[0]['current_order_status_id']) == 1 && $vendorOrders[0]['reject_request_status'] == 2) {
                                    ?>
                                        <div class="col-md-12 mt-3 text-right">
                                            <a href="#" class="btn btn-primary" data-toggle="modal"
                                                data-target="#default-Modal1">Accept</a>
                                        </div><?php } else if (trim($vendorOrders[0]['current_order_status_id']) == 2 || trim($vendorOrders[0]['current_order_status_id']) == 3) {
                                    ?>
                                            <div class="col-md-12 mt-3 text-right">
                                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#default-Modal">Out for delivery</a>
                                            <?php
                                            if (!empty(trim($vendorOrders[0]['preparation_time']))): ?>
                                                    <a href="#" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#default-Modal3">Update Preparation Time</a>
                                        <?php endif; ?>
                                            </div><?php } ?>
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


<div class="modal fade" id="reject-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject</h5>
                <div class="ml-auto">
                    <input type="checkbox" id="select-all"> Select All
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($custprod)):
                    foreach ($custprod as $order): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="<?= base_url(); ?>uploads/food_item_image/food_item_<?= $order['image_id']; ?>.jpg"
                                            class="img-fluid" alt="Food Image">
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-0"><?= $order['food_name']; ?></h6>
                                        <p>Product Quantity: <?= $order['product_quantity']; ?></p>
                                        <?php if (!empty($order['product_weight'])) {
                                            $product_weight_g = $order['product_weight'];
                                            $product_weight_kg = $product_weight_g / 1000;
                                            ?>
                                            <p>Weight: <?= $product_weight_kg; ?> kgs</p>
                                        <?php } ?>
                                        <p>Quantity: <?= $order['qty']; ?></p>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <input type="checkbox" class="form-control mt-1 order-checkbox"
                                            value="<?= $order['id']; ?>" data-product-id="<?= $order['item_id']; ?>"
                                            data-product-variant-id="<?= $order['vendor_product_variant_id']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                endif ?>
                <div class="selectionError"></div>
                <div class="form-group">
                    <label for="rejectreason">Tell us why?</label>
                    <input type="text" id="rejectreason" name="rejectreason" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" id="submit-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="default-Modal1" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Preparation Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="m-b-0">Tell us preparation time</p>
                <input type="text" id="preparation_time" class="form-control mt-1" placeholder="in mins">
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary waves-effect waves-light" id="submitPreparationTime"
                    href="#">Submit</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="default-Modal3" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Preparation Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="m-b-0">Tell us preparation time</p>
                <input type="text" id="extend_preparation_time" class="form-control mt-1" placeholder="in mins">
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary waves-effect waves-light" id="extendPreparationTime"
                    href="#">Submit</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter OTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="m-b-0">Enter Delivery Partner OTP</p>
                <input type="text" class="form-control mt-1" id="delivery_otp" placeholder="OTP goes here">
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary waves-effect waves-light" id="submitOTP" href="#">Submit</a>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#select-all').change(function () {
            $('.order-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('#submit-btn').click(function () {
            let selectedOrders = [];
            let rejectedProducts = [];
            let allOrders = $('.order-checkbox');
            let allOrdersCount = allOrders.length;
            let checkedOrdersCount = $('.order-checkbox:checked').length;
            let order_id = $('#order_id').val();

            if (allOrdersCount === checkedOrdersCount) {
                $('.order-checkbox:checked').each(function () {
                    selectedOrders.push($(this).val());
                });
            } else {
                $('.order-checkbox:checked').each(function () {
                    let orderData = {
                        product_id: $(this).data('product-id'),
                        product_variant_id: $(this).data('product-variant-id')
                    };
                    selectedOrders.push($(this).val());
                    rejectedProducts.push(orderData);
                });
            }

            let rejectReason = $('#rejectreason').val();

            if (selectedOrders.length === 0) {
                alert('Please select at least one order to reject.');
                return;
            }

            let data = {
                reason: rejectReason,
                order_id: order_id,
                is_total_order_rejected: checkedOrdersCount === allOrdersCount ? 1 : 0
            };

            if (checkedOrdersCount !== allOrdersCount) {
                data.rejected_products = rejectedProducts;
            }

            $.ajax({
                url: '<?php echo base_url("vendorOrders/reject"); ?>',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function (response) {

                    if (response.job_status === 'Rejected') {

                        $('#reject-Modal').modal('hide').on('hidden.bs.modal', function () {

                            var alertDiv2 = $('<div class="centered-alert">' +
                                '<h5 class="text-success">' + response.message + '</h5>' +
                                '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                                '</div>');
                            $('body').append(alertDiv2);


                            alertDiv2.find('.ok-btn').on('click', function () {
                                alertDiv2.remove();
                            });


                            $('#page_inner').load(window.location.href + ' #page_inner');
                        });
                    } else {

                        var errorMessage_reject = $('<div class="error-message" style="color: red;">' + response + '</div>');
                        $('#selectionError').after(errorMessage_reject);

                        $(document).on('click', function () {
                            errorMessage_reject.remove();
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr, status, error);

                    $('#reject-Modal').modal('hide').on('hidden.bs.modal', function () {

                        var alertDiv2 = $('<div class="centered-alert">' +
                            '<h5 class="text-danger">Something went wrong.</h5>' +
                            '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                            '</div>');
                        $('body').append(alertDiv2);

                        alertDiv2.find('.ok-btn').on('click', function () {
                            alertDiv2.remove();
                        });
                    });
                }
            });
        });


        $('#submitPreparationTime').on('click', function (e) {
            e.preventDefault();

            var order_id = $('#order_id').val();
            var preparation_time = $('#preparation_time').val();

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url("vendorOrders/accept"); ?>',
                data: {
                    type: 'accept',
                    order_id: order_id,
                    preparation_time: preparation_time
                },
                dataType: 'json',
                success: function (response) {

                    if (response.job_status === 'Accepted') {

                        $('#default-Modal1').modal('hide').on('hidden.bs.modal', function () {

                            var alertDiv1 = $('<div class="centered-alert">' +
                                '<h5 class="text-success">' + response.message + '</h5>' +
                                '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                                '</div>');
                            $('body').append(alertDiv1);


                            alertDiv1.find('.ok-btn').on('click', function () {
                                alertDiv1.remove();
                            });


                            $('#page_inner').load(window.location.href + ' #page_inner');
                        });
                    } else {

                        var errorMessage_time = $('<div class="error-message" style="color: red;">' + response.message + '</div>');
                        $('#preparation_time').after(errorMessage_time);

                        $(document).on('click', function () {
                            errorMessage_time.remove();
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr, status, error);

                    $('#default-Modal1').modal('hide').on('hidden.bs.modal', function () {

                        var alertDiv1 = $('<div class="centered-alert">' +
                            '<h5 class="text-danger">Something went wrong.</h5>' +
                            '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                            '</div>');
                        $('body').append(alertDiv1);

                        alertDiv1.find('.ok-btn').on('click', function () {
                            alertDiv1.remove();
                        });
                    });
                }
            });
        });

        $('#submitOTP').on('click', function (e) {
            e.preventDefault();

            var order_id = $('#order_id').val();
            var delivery_otp = $('#delivery_otp').val();

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url("vendorOrders/verify_out_for_delivery"); ?>',
                data: {
                    order_id: order_id,
                    otp: delivery_otp
                },
                success: function (response) {

                    if (response.job_status === 'Valid') {

                        $('#default-Modal').modal('hide').on('hidden.bs.modal', function () {

                            var alertDiv = $('<div class="centered-alert">' +
                                '<h5 class="text-success">' + response.message + '</h5>' +
                                '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                                '</div>');
                            $('body').append(alertDiv);


                            alertDiv.find('.ok-btn').on('click', function () {
                                alertDiv.remove();
                            });


                            $('#page_inner').load(window.location.href + ' #page_inner');
                        });
                    } else {

                        var errorMessage = $('<div class="error-message" style="color: red;">' + response + '</div>');
                        $('#delivery_otp').after(errorMessage);

                        $(document).on('click', function () {
                            errorMessage.remove();
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr, status, error);

                    $('#default-Modal').modal('hide').on('hidden.bs.modal', function () {

                        var alertDiv = $('<div class="centered-alert">' +
                            '<h5 class="text-danger">Something went wrong.</h5>' +
                            '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                            '</div>');
                        $('body').append(alertDiv);

                        alertDiv.find('.ok-btn').on('click', function () {
                            alertDiv.remove();
                        });
                    });
                }
            });
        });





        $('#extendPreparationTime').on('click', function (e) {
            e.preventDefault();

            var order_id = $('#order_id').val();
            var preparation_time = $('#extend_preparation_time').val();

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url("vendorOrders/extend_preparation_time"); ?>',
                data: {
                    type: 'accept',
                    order_id: order_id,
                    preparation_time: preparation_time
                },
                dataType: 'json',
                success: function (response) {

                    if (response.job_status === 'Modified') {

                        $('#default-Modal3').modal('hide').on('hidden.bs.modal', function () {

                            var alertDiv3 = $('<div class="centered-alert">' +
                                '<h5 class="text-success">' + response.message + '</h5>' +
                                '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                                '</div>');
                            $('body').append(alertDiv3);


                            alertDiv3.find('.ok-btn').on('click', function () {
                                alertDiv3.remove();
                            });


                            $('#page_inner').load(window.location.href + ' #page_inner');
                        });
                    } else {

                        var errorMessage_extend_time = $('<div class="error-message" style="color: red;">' + response.message + '</div>');
                        $('#extend_preparation_time').after(errorMessage_extend_time);

                        $(document).on('click', function () {
                            errorMessage_extend_time.remove();
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr, status, error);

                    $('#default-Modal3').modal('hide').on('hidden.bs.modal', function () {

                        var alertDiv3 = $('<div class="centered-alert">' +
                            '<h5 class="text-danger">Something went wrong.</h5>' +
                            '<button class="btn btn-primary ok-btn float-right">OK</button>' +
                            '</div>');
                        $('body').append(alertDiv3);

                        alertDiv3.find('.ok-btn').on('click', function () {
                            alertDiv3.remove();
                        });
                    });
                }
            });
        });






    });
</script>

<?php $this->load->view('vendorCrm/footer'); ?>