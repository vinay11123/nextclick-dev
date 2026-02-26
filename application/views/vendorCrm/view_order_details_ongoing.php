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
                            <a href="orders.php"><i class="feather icon-arrow-left"></i> Back</a>
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


                                    <div class="card">
                                        <div class="card-header text-dark">
                                            <h5 class="text-dark"> <?= $vendorOrders[0]['order_status']; ?></h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="col-md-12">
                                                <?php

                                                $created_at = strtotime($vendorOrders[0]['created_at']);

                                                $current_time = time();

                                                $time_difference = $current_time - $created_at;

                                                function format_time_difference($time_difference)
                                                {
                                                    $minutes = floor($time_difference / 60);
                                                    $hours = floor($minutes / 60);
                                                    $days = floor($hours / 24);

                                                    $minutes = $minutes % 60;
                                                    $hours = $hours % 24;

                                                    if ($days > 0) {
                                                        return $days . " days " . $hours . " hr " . $minutes . " mins";
                                                    } elseif ($hours > 0) {
                                                        return $hours . " hr " . $minutes . " mins";
                                                    } else {
                                                        return $minutes . " mins";
                                                    }
                                                }

                                                ?>
                                                <input type="hidden" id="order_id"
                                                value="<?= $vendorOrders[0]['id']; ?>">
                                                <p>Order ID: <?= $vendorOrders[0]['track_id']; ?><span
                                                        class="label-info ml-2 text-white"><?= format_time_difference($time_difference) . ' ago'; ?></span>
                                                </p>
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
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="mb-0"><?= $order['food_name']; ?></h6>
                                                                    </td>
                                                                    <td><img src="<?= base_url(); ?>uploads/food_item_image/food_item_<?= $order['image_id']; ?>.jpg"
                                                                            width="200" style="width: 200px !important;"></td>
                                                                    <td><?= $order['section_name']; ?><?php
                                                                      if (!empty($order['product_weight'])) {
                                                                          $product_weight_g = $order['product_weight'];
                                                                          $product_weight_kg = $product_weight_g / 1000;
                                                                          ?>
                                                                            <p>(<?= $product_weight_kg; ?> kgs)</p>

                                                                        <?php } ?>
                                                                    </td>
                                                                    <td><?= $order['product_quantity']; ?></td>
                                                                    <td>Quantity: <?= $order['qty']; ?></td>
                                                                    <td align="right">
                                                                        Sub Total: Rs.<?= $order['price']; ?><br>
                                                                        Tax: Rs.<?= $order['tax']; ?><br>
                                                                        Total: Rs.<?= $order['total']; ?><br>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $dis += (float) $order['promocode_discount'] + (float) $order['promotion_banner_discount'] + (float) ($vendorOrders[0]['cupon_discount'] ?? 0);
                                                                $tx += (float) $order['tax'];
                                                                $subtot += (float) $order['price'];
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
                                                                    <strong>Rs.<?= $vendorOrders[0]['delivery_fee'] ?? '0.00'; ?></strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" align="right">Total Bill</td>
                                                                <td align="right" class="bg-success">
                                                                    <strong>Rs.<?= $vendorOrders[0]['grand_total'] ?? '0.00'; ?></strong>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <?php
                                        if (trim($vendorOrders[0]['current_order_status_id']) == 2) {
                                            ?>
                                            <div class="col-md-12 mt-3 text-right">
                                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#default-Modal">Out for delivery</a>
                                            </div><?php } ?>
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
                    $('#response').html(response);
                     window.location.reload();
                },
                error: function (xhr, error) {
                    console.error("AJAX request failed.");
                    console.error(xhr, status, error);
                    $('#response').html('Something went wrong.');
                }
            });
        });
    });

</script>

<?php $this->load->view('vendorCrm/footer'); ?>