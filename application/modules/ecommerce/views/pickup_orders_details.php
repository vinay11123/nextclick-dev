<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                <i class="feather icon-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#!">Invoice</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">

        <div class="main-body">
            <div class="page-wrapper">

                <!-- Page-body start -->
                <div class="page-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="invoice-contact">
                                    <div class="col-md-12 pt-3 pl-4">
                                        <div class="invoice-box">
                                            <img src="<?php echo base_url() ?>assets/img/logo.png" width="200"
                                                height="auto" alt="" />
                                            <p>Address: 401, 4th Floor, New Mark House Hitech City, Patrika Nagar.</p>
                                            <h3>Invoice <span>#
                                                    <?php echo $orderDetails[0]['id']; ?>
                                                </span></h4>
                                            </h3>
                                            <p>Invoice Date: <?php echo DATE($orderDetails['created_at']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="row invoive-info mb-3">
                                        <div class="col-md-6 col-xs-12 invoice-client-info">
                                            <h6>Client Information :</h6>
                                            <h6 class="m-0">
                                                <?php echo $orderDetails[0]['customer_name']; ?>
                                            </h6>
                                            <p class="m-0 m-t-10">
                                                <?php echo $orderDetails[0]['delivery_address']; ?>,
                                            </p>
                                            <p class="m-0">
                                                <?php echo $orderDetails[0]['delivery_phone']; ?>
                                            </p>
                                            <p>
                                                <?php echo $orderDetails[0]['delivery_email']; ?>
                                            </p>
                                            <p>Status:
                                                <span class="label label-success">
                                                    <?php echo $orderDetails[0]['order_status']; ?>
                                                </span>
                                            </p>
                                            <p class="text-danger">TXN ID:
                                                <?php echo $orderDetails[0]['track_id']; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <p>Product Pickup Image:</p>

                                            <img src="<?php echo base_url() . 'uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $orderDetails[0]['delivery_id'] . '.jpg?' . time(); ?>"
                                                class="img-fluid" alt="" />
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <p>Product Delivery Image:</p>
                                            <img src="<?php echo base_url() . 'uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $orderDetails[0]['delivery_id'] . '.jpg?' . time(); ?>"
                                                class="img-fluid" alt="" />
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table  invoice-detail-table">
                                        <thead>
                                            <tr class="bg-c-blue">
                                                <th>Order ID</th>
                                                <th width="500">Description</th>
                                                <th>Delivery Fee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dis = 0;
                                            $tx = 0;
                                            $sno = 1;
                                            $subtot = 0.00;
                                            if (!empty($orderDetails)):
                                                foreach ($orderDetails as $order): ?>
                                                    <tr>
                                                        <td>#<?php echo $order['track_id']; ?></td>
                                                        <td>
                                                            <h6 class="mb-0"><?php echo $order['category_name']; ?></h6> <span
                                                                class="text-muted">
                                                                <?php echo $order['product_desc']; ?> </span>
                                                        </td>
                                                        <td><span
                                                                class="font-weight-semibold"><?php echo $order['delivery_fee']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php

                                                    $subtot = $subtot + $order['delivery_fee'];
                                                    ?>
                                                <?php endforeach;
                                            endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">

                                <div class="card-block">
                                    <div class="row invoive-info mb-3">
                                        <div class="col-md-8 col-xs-12 invoice-client-info">
                                            <h6>Bill Details:</h6>
                                            <div class="table-responsive">
                                                <table class="table table-stripped">
                                                    <tr>
                                                        <td>
                                                            Delivery Fee:
                                                        </td>
                                                        <td>Rs.
                                                            <?php echo number_format($subtot, 2); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Total Amount:
                                                        </td>
                                                        <td>Rs.
                                                            <?php echo number_format($subtot, 2); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>

                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row invoive-info mb-3">
                                        <div class="col-md-8 col-xs-12 invoice-client-info">
                                            <h6>Order Status</h6>
                                            <div class="table-responsive">
                                                <p><span class="label label-success"><i
                                                            class="feather icon-check  text-white"></i></span> Order
                                                    Placed </p>
                                                <div class="table-responsive">
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check  text-white"></i></span> Order
                                                        has been Preparing</p>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check  text-white"></i></span> Out
                                                        for Delivery</p>

                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check  text-white"></i></span>
                                                        Reached to Delivery Point</p>

                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle  text-white"></i></span>
                                                        Delivered</p>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <?php
                        $orderPlacedStatus = false;
                        $outDeliveryStatus = false;
                        $reachedDeliveryStatus = false;
                        $orderDeliveredStatus = false;
                        $orderPreparingStatus = false;
                        ?>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row invoive-info mb-3">
                                        <div class="col-md-8 col-xs-12 invoice-client-info">
                                            <h6>Order Status</h6>
                                            <div class="table-responsive">
                                                <?php foreach ($statusDetails as $statusObj): ?>
                                                    <?php $status = $statusObj->status; ?>
                                                    <?php if ($status == 504): ?>
                                                        <?php $orderPlacedStatus = true; ?>
                                                    <?php endif; ?>

                                                    <?php if ($status == 502): ?>
                                                        <?php $orderPreparingStatus = true; ?>
                                                    <?php endif; ?>

                                                    <?php if ($status == 505): ?>
                                                        <?php $outDeliveryStatus = true; ?>
                                                    <?php endif; ?>

                                                    <?php if ($status == 506): ?>
                                                        <?php $reachedDeliveryStatus = true; ?>
                                                    <?php endif; ?>

                                                    <?php if ($status == 508): ?>
                                                        <?php $orderDeliveredStatus = true; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>

                                                <?php if ($orderPlacedStatus): ?>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check text-white"></i></span> Order
                                                        Placed</p>
                                                <?php else: ?>
                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle text-white"></i></span>
                                                        Order Placed</p>
                                                <?php endif; ?>

                                                <?php if ($orderPreparingStatus): ?>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check text-white"></i></span> Order has
                                                        been Preparing</p>
                                                <?php else: ?>
                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle text-white"></i></span>
                                                        Order has been Preparing</p>
                                                <?php endif; ?>

                                                <?php if ($outDeliveryStatus): ?>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check text-white"></i></span> Out for
                                                        Delivery</p>
                                                <?php else: ?>
                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle text-white"></i></span> Out
                                                        for Delivery</p>
                                                <?php endif; ?>

                                                <?php if ($reachedDeliveryStatus): ?>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check text-white"></i></span> Reached to
                                                        Delivery Point</p>
                                                <?php else: ?>
                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle text-white"></i></span>
                                                        Reached to Delivery Point</p>
                                                <?php endif; ?>

                                                <?php if ($orderDeliveredStatus): ?>
                                                    <p><span class="label label-success"><i
                                                                class="feather icon-check text-white"></i></span> Delivered
                                                    </p>
                                                <?php else: ?>
                                                    <p><span class="label label-danger"><i
                                                                class="feather icon-minus-circle text-white"></i></span>
                                                        Delivered</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" text-center">
                    <div class="col-sm-12 invoice-btn-group text-center">
                        <?php
                        // if ($orderst[0]['order_status'] == 'Received'):
                        $pickupFilename = $_SERVER["DOCUMENT_ROOT"] . '/uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $orderDetails[0]['delivery_id'] . '.jpg';

                        $deliveryFilename = $_SERVER["DOCUMENT_ROOT"] . '/uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $orderDetails[0]['delivery_id'] . '.jpg';

                        if (file_exists($pickupFilename) && file_exists($deliveryFilename)):
                            ?>

                            <a href="#" data-type="user_download" data-orderid="<?php echo $orderDetails[0]['id']; ?>"
                                class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">
                                Download User Invoice<i class='feather icon-download'></i>
                            </a>

                            <a href="#" data-type="user" data-orderid="<?php echo $orderDetails[0]['id']; ?>"
                                class="btn btn-success btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20 send-invoice">
                                Send Invoice to User<i class='feather icon-mail'></i>
                            </a>
                            <?php
                        endif;
                        ?>

                        <a href="<?= base_url('ecom_pickup_orders') ?>"
                            class="btn btn-danger waves-effect m-b-10 btn-sm waves-light">Back</a>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.send-invoice').click(function (e) {
            e.preventDefault();
            var type = $(this).data('type');
            var orderId = $(this).data('orderid');

            var url = '<?php echo base_url("epickup_orders/pdf"); ?>?id=' + encodeURIComponent(btoa(btoa(orderId))) + '&ctype=' + type;

            var $button = $(this);
            $button.attr('disabled', true).addClass('disabled');
            $button.find('.feather').removeClass('icon-mail').addClass('icon-loader');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (type == 'vendor_download' || type == 'user_download') {
                        var filePath = JSON.parse(response);
                        var link = document.createElement('a');
                        link.href = filePath;
                        console.log(filePath);
                        link.download = filePath.split('/').pop();
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // $.ajax({
                        //     url: '<?php echo base_url("ecom/delete_file"); ?>',
                        //     method: 'POST',
                        //     data: { filePath: filePath },
                        //     success: function (response) {
                        //         console.log(response);
                        //     },
                        //     error: function (xhr, status, error) {
                        //         console.error('Error deleting file:', error);
                        //     }
                        // });
                    } else if (type == 'user' || type == 'vendor') {
                        console.log("Email sent successfully");
                    }
                    $button.attr('disabled', false).removeClass('disabled');
                    $button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
                },
                error: function (xhr, status, error) {
                    console.error('Error sending email:', error);
                    $button.attr('disabled', false).removeClass('disabled');
                    $button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
                }
            });

        });
    });
</script>
<?php $this->load->view('vendorCrm/footer'); ?>